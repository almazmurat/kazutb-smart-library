<?php

namespace App\Services\Library;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class InternalTriageService
{
    private const COPY_TABLE = 'app.book_copies';
    private const DOCUMENT_TABLE = 'app.documents';
    private const READER_TABLE = 'app.readers';
    private const QUALITY_ISSUES_TABLE = 'review.quality_issues';

    /**
     * Aggregated triage summary across copies, documents, and readers.
     *
     * @return array{data: array<string, mixed>, source: string}
     */
    public function triageSummary(int $topReasonCodesLimit = 5): array
    {
        $topReasonCodesLimit = min(max($topReasonCodesLimit, 1), 20);

        $copyCounts = $this->entityCounts(self::COPY_TABLE);
        $documentCounts = $this->entityCounts(self::DOCUMENT_TABLE);
        $readerCounts = $this->entityCounts(self::READER_TABLE);

        $qualityIssuesCounts = $this->qualityIssueCounts();

        $totalUnresolved = $copyCounts['needsReview'] + $documentCounts['needsReview'] + $readerCounts['needsReview'];
        $totalEntities = $copyCounts['total'] + $documentCounts['total'] + $readerCounts['total'];

        $topReasonCodes = $this->aggregatedTopReasonCodes($topReasonCodesLimit);

        return [
            'data' => [
                'totalUnresolved' => $totalUnresolved,
                'totalEntities' => $totalEntities,
                'byEntity' => [
                    'copies' => [
                        'total' => $copyCounts['total'],
                        'needsReviewCount' => $copyCounts['needsReview'],
                        'resolvedCount' => max(0, $copyCounts['total'] - $copyCounts['needsReview']),
                    ],
                    'documents' => [
                        'total' => $documentCounts['total'],
                        'needsReviewCount' => $documentCounts['needsReview'],
                        'resolvedCount' => max(0, $documentCounts['total'] - $documentCounts['needsReview']),
                    ],
                    'readers' => [
                        'total' => $readerCounts['total'],
                        'needsReviewCount' => $readerCounts['needsReview'],
                        'resolvedCount' => max(0, $readerCounts['total'] - $readerCounts['needsReview']),
                    ],
                ],
                'qualityIssues' => $qualityIssuesCounts,
                'topReasonCodes' => $topReasonCodes,
            ],
            'source' => 'internal_triage_aggregation',
        ];
    }

    /**
     * Aggregated reason codes across all three entity types.
     *
     * @return array{data: array<string, mixed>, source: string}
     */
    public function triageReasonCodes(int $topLimit = 10, bool $includePerEntity = false): array
    {
        $topLimit = min(max($topLimit, 1), 50);

        $aggregated = $this->aggregatedTopReasonCodes($topLimit);

        $result = [
            'data' => [
                'topReasonCodes' => $aggregated,
            ],
            'source' => 'internal_triage_aggregation',
        ];

        if ($includePerEntity) {
            $result['data']['perEntity'] = [
                'copies' => $this->entityTopReasonCodes(self::COPY_TABLE, 'review_reason_codes', $topLimit),
                'documents' => $this->entityTopReasonCodes(self::DOCUMENT_TABLE, 'review_reason_codes', $topLimit),
                'readers' => $this->entityTopReasonCodes(self::READER_TABLE, 'review_reason_codes', $topLimit),
            ];
        }

        return $result;
    }

    /**
     * @return array{total: int, needsReview: int}
     */
    private function entityCounts(string $table): array
    {
        $total = DB::connection('pgsql')->table($table)->count();
        $needsReview = DB::connection('pgsql')->table($table)->where('needs_review', true)->count();

        return ['total' => $total, 'needsReview' => $needsReview];
    }

    /**
     * @return array{total: int, openCount: int, criticalCount: int, highCount: int}
     */
    private function qualityIssueCounts(): array
    {
        $hasTable = $this->hasQualityIssuesTable();
        if (! $hasTable) {
            return ['total' => 0, 'openCount' => 0, 'criticalCount' => 0, 'highCount' => 0];
        }

        $total = DB::connection('pgsql')->table(self::QUALITY_ISSUES_TABLE)->count();

        $openCount = DB::connection('pgsql')
            ->table(self::QUALITY_ISSUES_TABLE)
            ->whereRaw("LOWER(status) = 'open'")
            ->count();

        $criticalCount = DB::connection('pgsql')
            ->table(self::QUALITY_ISSUES_TABLE)
            ->whereRaw("LOWER(severity) = 'critical'")
            ->count();

        $highCount = DB::connection('pgsql')
            ->table(self::QUALITY_ISSUES_TABLE)
            ->whereRaw("LOWER(severity) = 'high'")
            ->count();

        return [
            'total' => $total,
            'openCount' => $openCount,
            'criticalCount' => $criticalCount,
            'highCount' => $highCount,
        ];
    }

    /**
     * @return list<array{reasonCode: string, count: int, entities: list<string>}>
     */
    private function aggregatedTopReasonCodes(int $limit): array
    {
        $unionParts = [];
        $bindings = [];

        if ($this->hasReviewReasonColumn(self::COPY_TABLE)) {
            $unionParts[] = "SELECT UNNEST(review_reason_codes) AS reason_code, 'copies' AS entity_type
                FROM {$this->escapeTable(self::COPY_TABLE)}
                WHERE needs_review IS TRUE AND review_reason_codes IS NOT NULL";
        }

        if ($this->hasReviewReasonColumn(self::DOCUMENT_TABLE)) {
            $unionParts[] = "SELECT UNNEST(review_reason_codes) AS reason_code, 'documents' AS entity_type
                FROM {$this->escapeTable(self::DOCUMENT_TABLE)}
                WHERE needs_review IS TRUE AND review_reason_codes IS NOT NULL";
        }

        if ($this->hasReviewReasonColumn(self::READER_TABLE)) {
            $unionParts[] = "SELECT UNNEST(review_reason_codes) AS reason_code, 'readers' AS entity_type
                FROM {$this->escapeTable(self::READER_TABLE)}
                WHERE needs_review IS TRUE AND review_reason_codes IS NOT NULL";
        }

        if (empty($unionParts)) {
            return [];
        }

        $union = implode(' UNION ALL ', $unionParts);

        $sql = "
            SELECT reason_code,
                   COUNT(*)::int AS aggregate_count,
                   ARRAY_AGG(DISTINCT entity_type ORDER BY entity_type) AS entity_types
            FROM ({$union}) AS all_reasons
            GROUP BY reason_code
            ORDER BY aggregate_count DESC, reason_code ASC
            LIMIT ?
        ";
        $bindings[] = $limit;

        $rows = DB::connection('pgsql')->select($sql, $bindings);

        return array_map(fn (object $row): array => [
            'reasonCode' => (string) ($row->reason_code ?? ''),
            'count' => (int) ($row->aggregate_count ?? 0),
            'entities' => $this->normalizePgArray($row->entity_types ?? null),
        ], $rows);
    }

    /**
     * @return list<array{reasonCode: string, count: int}>
     */
    private function entityTopReasonCodes(string $table, string $column, int $limit): array
    {
        if (! $this->hasReviewReasonColumn($table)) {
            return [];
        }

        $escapedTable = $this->escapeTable($table);

        $rows = DB::connection('pgsql')->select(
            "SELECT reason_code, COUNT(*)::int AS aggregate_count
             FROM (
                 SELECT UNNEST({$column}) AS reason_code
                 FROM {$escapedTable}
                 WHERE needs_review IS TRUE AND {$column} IS NOT NULL
             ) reason_rows
             GROUP BY reason_code
             ORDER BY aggregate_count DESC, reason_code ASC
             LIMIT ?",
            [$limit]
        );

        return array_map(static fn (object $row): array => [
            'reasonCode' => (string) ($row->reason_code ?? ''),
            'count' => (int) ($row->aggregate_count ?? 0),
        ], $rows);
    }

    private function hasReviewReasonColumn(string $table): bool
    {
        return Schema::connection('pgsql')->hasColumn($table, 'review_reason_codes');
    }

    private function hasQualityIssuesTable(): bool
    {
        try {
            DB::connection('pgsql')->table(self::QUALITY_ISSUES_TABLE)->limit(1)->count();

            return true;
        } catch (\Throwable) {
            return false;
        }
    }

    private function escapeTable(string $table): string
    {
        // Tables are hardcoded constants (app.book_copies etc), safe for direct use.
        return $table;
    }

    /**
     * @return list<string>
     */
    private function normalizePgArray(mixed $value): array
    {
        if (is_array($value)) {
            return array_values(array_map('strval', $value));
        }

        if (! is_string($value)) {
            return [];
        }

        $trimmed = trim($value);

        if ($trimmed === '' || $trimmed === '{}') {
            return [];
        }

        $trimmed = trim($trimmed, '{}');

        if ($trimmed === '') {
            return [];
        }

        return array_values(array_filter(array_map(
            static fn (string $item): string => trim($item, "\" \t\n\r\0\x0B"),
            explode(',', $trimmed)
        ), static fn (string $item): bool => $item !== ''));
    }
}
