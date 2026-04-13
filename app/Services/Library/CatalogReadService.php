<?php

namespace App\Services\Library;

use Illuminate\Support\Facades\DB;

class CatalogReadService
{
    /**
     * @return array{data: array<int, array<string, mixed>>, meta: array<string, int>}
     */
    public function search(
        string $query = '',
        ?string $title = null,
        ?string $author = null,
        ?string $publisher = null,
        ?string $isbn = null,
        ?string $udc = null,
        ?string $language = null,
        int $page = 1,
        int $limit = 10,
        string $sort = 'popular',
        ?int $yearFrom = null,
        ?int $yearTo = null,
        bool $availableOnly = false,
        ?string $subjectId = null,
    ): array {
        $page = max($page, 1);
        $limit = min(max($limit, 1), 100);

        $builder = DB::table('app.document_detail_v as d')
            ->select([
                'd.document_id',
                'd.legacy_doc_id',
                'd.title_display',
                'd.title_raw',
                'd.subtitle_raw',
                'd.isbn_normalized',
                'd.isbn_raw',
                'd.publication_year',
                'd.language_code',
                'd.language_raw',
                'd.publisher_name',
                'd.authors_json',
                'd.copy_summary_json',
                'd.subjects_json',
                'd.raw_marc',
            ]);

        if ($query !== '') {
            $q = '%' . mb_strtolower($query) . '%';
            $builder->where(function ($inner) use ($q): void {
                $inner
                    ->whereRaw("LOWER(COALESCE(d.title_display, d.title_raw, '')) LIKE ?", [$q])
                    ->orWhereRaw("LOWER(COALESCE(d.isbn_normalized, d.isbn_raw, '')) LIKE ?", [$q])
                    ->orWhereRaw("LOWER(COALESCE(d.publisher_name, '')) LIKE ?", [$q])
                    ->orWhereRaw("LOWER(COALESCE(d.authors_json::text, '')) LIKE ?", [$q]);
            });
        }

        if ($title !== null && trim($title) !== '') {
            $value = '%' . mb_strtolower(trim($title)) . '%';
            $builder->whereRaw("LOWER(COALESCE(d.title_display, d.title_raw, '')) LIKE ?", [$value]);
        }

        if ($author !== null && trim($author) !== '') {
            $value = '%' . mb_strtolower(trim($author)) . '%';
            $builder->whereRaw("LOWER(COALESCE(d.authors_json::text, '')) LIKE ?", [$value]);
        }

        if ($publisher !== null && trim($publisher) !== '') {
            $value = '%' . mb_strtolower(trim($publisher)) . '%';
            $builder->whereRaw("LOWER(COALESCE(d.publisher_name, '')) LIKE ?", [$value]);
        }

        if ($isbn !== null && trim($isbn) !== '') {
            $value = '%' . mb_strtolower(trim($isbn)) . '%';
            $builder->whereRaw("LOWER(COALESCE(d.isbn_normalized, d.isbn_raw, '')) LIKE ?", [$value]);
        }

        if ($udc !== null && trim($udc) !== '') {
            $value = '%' . mb_strtolower(trim($udc)) . '%';
            $builder->whereRaw("LOWER(COALESCE(d.raw_marc, '')::text) LIKE ?", [$value]);
        }

        if (!empty($language)) {
            $builder->whereRaw("LOWER(COALESCE(d.language_code, '')) = ?", [mb_strtolower($language)]);
        }

        if ($yearFrom !== null) {
            $builder->where('d.publication_year', '>=', $yearFrom);
        }

        if ($yearTo !== null) {
            $builder->where('d.publication_year', '<=', $yearTo);
        }

        if ($availableOnly) {
            $builder->whereRaw("COALESCE((d.copy_summary_json->>'availableCopies')::int, 0) > 0");
        }

        if ($subjectId !== null && $subjectId !== '') {
            $builder->whereExists(function ($sub) use ($subjectId): void {
                $sub->select(DB::raw(1))
                    ->from('app.document_subjects as ds')
                    ->whereColumn('ds.document_id', 'd.document_id')
                    ->where('ds.subject_id', $subjectId);
            });
        }

        $total = (clone $builder)->count();

        $sortLower = mb_strtolower($sort);
        if ($sortLower === 'newest') {
            $builder->orderByDesc('d.publication_year')->orderBy('d.title_display');
        } elseif ($sortLower === 'title') {
            $builder->orderBy('d.title_display');
        } elseif ($sortLower === 'author') {
            $builder->orderByRaw('COALESCE((d.authors_json->0->>\'name\'), \'\') ASC')->orderBy('d.title_display');
        } else {
            $builder->orderByRaw('COALESCE((d.copy_summary_json->>\'availableCopies\')::int, 0) DESC')
                ->orderBy('d.title_display');
        }

        $rows = $builder
            ->offset(($page - 1) * $limit)
            ->limit($limit)
            ->get();

        $documentIds = $rows
            ->map(static fn (object $row): string => (string) ($row->document_id ?? ''))
            ->filter(static fn (string $id): bool => $id !== '')
            ->values()
            ->all();

        $locationsByDocument = $this->loadLocationsByDocument($documentIds);

        $data = $rows->map(function (object $row) use ($locationsByDocument): array {
            $authors = $this->decodeJsonValue($row->authors_json);
            $copySummary = $this->decodeJsonValue($row->copy_summary_json);
            $subjects = $this->decodeJsonValue($row->subjects_json);
            $primaryAuthor = is_array($authors) && isset($authors[0]['name']) ? (string) $authors[0]['name'] : null;
            $documentId = (string) ($row->document_id ?? '');

            $available = is_array($copySummary) ? (int) ($copySummary['availableCopies'] ?? 0) : 0;
            $totalCopies = is_array($copySummary) ? (int) ($copySummary['totalCopies'] ?? 0) : 0;

            $classification = [];
            if (is_array($subjects) && count($subjects) > 0) {
                $classification = array_map(static fn (array $s): array => [
                    'id' => (string) ($s['id'] ?? ''),
                    'label' => (string) ($s['label'] ?? ''),
                    'sourceKind' => (string) ($s['sourceKind'] ?? ''),
                ], $subjects);
            }

            return [
                'id' => (string) ($row->document_id ?? $row->legacy_doc_id ?? ''),
                'title' => [
                    'display' => (string) ($row->title_display ?: $row->title_raw ?: 'Без названия'),
                    'raw' => (string) ($row->title_raw ?: $row->title_display ?: 'Без названия'),
                    'subtitle' => (string) ($row->subtitle_raw ?: ''),
                ],
                'primaryAuthor' => $primaryAuthor,
                'publisher' => [
                    'name' => (string) ($row->publisher_name ?: ''),
                ],
                'publicationYear' => $row->publication_year,
                'language' => [
                    'code' => (string) ($row->language_code ?: ''),
                    'raw' => (string) ($row->language_raw ?: $row->language_code ?: ''),
                ],
                'isbn' => [
                    'raw' => (string) ($row->isbn_normalized ?: $row->isbn_raw ?: ''),
                ],
                'copies' => [
                    'available' => $available,
                    'total' => $totalCopies,
                ],
                'availability' => [
                    'locations' => $locationsByDocument[$documentId] ?? [],
                ],
                'classification' => $classification,
                'udc' => $this->extractUdcData($row->raw_marc ?? null, $classification),
                'source' => 'app.document_detail_v',
            ];
        })->all();

        $totalPages = max(1, $limit > 0 ? (int) ceil($total / $limit) : 1);

        return [
            'data' => $data,
            'meta' => [
                'page' => $page,
                'per_page' => $limit,
                'total' => $total,
                'total_pages' => $totalPages,
                'totalPages' => $totalPages,
            ],
        ];
    }

    /**
     * @param array<int, array<string, string>> $classification
     * @return array{raw: string, source: string}
     */
    private function extractUdcData(mixed $rawMarc, array $classification = []): array
    {
        if (is_string($rawMarc) && $rawMarc !== '') {
            foreach (['080', '084'] as $tag) {
                $value = $this->extractMarcFieldValue($rawMarc, $tag);
                if ($value !== '') {
                    return ['raw' => $value, 'source' => $tag];
                }
            }
        }

        foreach ($classification as $item) {
            $kind = (string) ($item['sourceKind'] ?? '');
            $label = trim((string) ($item['label'] ?? ''));
            if ($label !== '' && in_array($kind, ['subject', 'specialization'], true)) {
                return ['raw' => $label, 'source' => $kind];
            }
        }

        return ['raw' => '', 'source' => ''];
    }

    private function extractMarcFieldValue(string $rawMarc, string $tag): string
    {
        $pattern = sprintf('/(?:^|\x1E)%s\s{0,2}([^\x1E]+)/u', preg_quote($tag, '/'));
        if (! preg_match($pattern, $rawMarc, $matches)) {
            return '';
        }

        $fieldData = (string) ($matches[1] ?? '');
        $subfields = preg_split('/\x1F/u', $fieldData) ?: [];
        $values = [];

        foreach ($subfields as $subfield) {
            $subfield = trim($subfield);
            if ($subfield === '') {
                continue;
            }

            $code = mb_substr($subfield, 0, 1);
            $value = trim(mb_substr($subfield, 1));
            if ($value === '') {
                continue;
            }

            if (in_array($code, ['a', 'x'], true)) {
                $values[] = preg_replace('/\s+/u', ' ', $value) ?: $value;
            }
        }

        if ($values !== []) {
            return trim(implode(' · ', array_unique($values)));
        }

        $normalized = preg_replace('/[\x1F\\]+/u', ' ', $fieldData);

        return trim(preg_replace('/\s+/u', ' ', $normalized ?: '') ?: '');
    }

    /**
     * @return array<string, mixed>|array<int, mixed>|null
     */
    private function decodeJsonValue(mixed $value): array|null
    {
        if (is_array($value)) {
            return $value;
        }

        if (!is_string($value) || $value === '') {
            return null;
        }

        $decoded = json_decode($value, true);

        return is_array($decoded) ? $decoded : null;
    }

    /**
     * @param array<int,string> $documentIds
     * @return array<string,array<int,array<string,mixed>>>
     */
    private function loadLocationsByDocument(array $documentIds): array
    {
        if ($documentIds === []) {
            return [];
        }

        $rows = DB::table('app.document_availability_by_location_v')
            ->select([
                'document_id',
                'institution_unit_name',
                'institution_unit_code',
                'campus_name',
                'campus_code',
                'service_point_name',
                'service_point_code',
                'total_copy_count',
                'available_copy_count',
            ])
            ->whereIn('document_id', $documentIds)
            ->orderByDesc('available_copy_count')
            ->orderByDesc('total_copy_count')
            ->get();

        $result = [];

        foreach ($rows as $row) {
            $documentId = (string) ($row->document_id ?? '');
            if ($documentId === '') {
                continue;
            }

            $result[$documentId][] = [
                'institutionUnit' => [
                    'code' => (string) ($row->institution_unit_code ?? ''),
                    'name' => (string) ($row->institution_unit_name ?? ''),
                ],
                'campus' => [
                    'code' => (string) ($row->campus_code ?? ''),
                    'name' => (string) ($row->campus_name ?? ''),
                ],
                'servicePoint' => [
                    'code' => (string) ($row->service_point_code ?? ''),
                    'name' => (string) ($row->service_point_name ?? ''),
                ],
                'copies' => [
                    'total' => (int) ($row->total_copy_count ?? 0),
                    'available' => (int) ($row->available_copy_count ?? 0),
                ],
            ];
        }

        return $result;
    }
}
