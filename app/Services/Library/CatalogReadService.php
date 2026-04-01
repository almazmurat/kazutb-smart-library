<?php

namespace App\Services\Library;

use Illuminate\Support\Facades\DB;

class CatalogReadService
{
    /**
     * @return array{data: array<int, array<string, mixed>>, meta: array<string, int>}
     */
    public function search(string $query = '', ?string $language = null, int $page = 1, int $limit = 10, string $sort = 'popular'): array
    {
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

        if (!empty($language)) {
            $builder->whereRaw("LOWER(COALESCE(d.language_code, '')) = ?", [mb_strtolower($language)]);
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

        $data = $rows->map(function (object $row): array {
            $authors = $this->decodeJsonValue($row->authors_json);
            $copySummary = $this->decodeJsonValue($row->copy_summary_json);
            $primaryAuthor = is_array($authors) && isset($authors[0]['name']) ? (string) $authors[0]['name'] : null;

            $available = is_array($copySummary) ? (int) ($copySummary['availableCopies'] ?? 0) : 0;
            $totalCopies = is_array($copySummary) ? (int) ($copySummary['totalCopies'] ?? 0) : 0;

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
}
