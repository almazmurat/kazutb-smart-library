<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Services\Library\CatalogReadService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Http;

class CatalogController extends Controller
{
    public function dbIndex(Request $request, CatalogReadService $service): JsonResponse
    {
        $validated = $request->validate([
            'q' => ['nullable', 'string', 'max:255'],
            'language' => ['nullable', 'string', 'max:10'],
            'page' => ['nullable', 'integer', 'min:1'],
            'limit' => ['nullable', 'integer', 'min:1', 'max:100'],
            'sort' => ['nullable', 'string', 'in:popular,newest,title,author'],
        ]);

        $result = $service->search(
            query: (string) ($validated['q'] ?? ''),
            language: isset($validated['language']) ? (string) $validated['language'] : null,
            page: (int) ($validated['page'] ?? 1),
            limit: (int) ($validated['limit'] ?? 10),
            sort: (string) ($validated['sort'] ?? 'popular'),
        );

        return response()->json($result);
    }

    public function index(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'q' => ['nullable', 'string', 'max:255'],
            'category' => ['nullable', 'string', 'max:100'],
            'page' => ['nullable', 'integer', 'min:1'],
            'per_page' => ['nullable', 'integer', 'min:1', 'max:100'],
        ]);

        $query = mb_strtolower((string) ($validated['q'] ?? ''));
        $category = mb_strtolower((string) ($validated['category'] ?? ''));
        $page = (int) ($validated['page'] ?? 1);
        $perPage = (int) ($validated['per_page'] ?? 10);

        $items = collect($this->catalogItems())
            ->when($query !== '', function (Collection $collection) use ($query): Collection {
                return $collection->filter(function (array $item) use ($query): bool {
                    $haystack = mb_strtolower(implode(' ', [
                        $item['title'],
                        $item['author'],
                        $item['category'],
                        $item['isbn'],
                    ]));

                    return str_contains($haystack, $query);
                })->values();
            })
            ->when($category !== '', function (Collection $collection) use ($category): Collection {
                return $collection
                    ->filter(fn (array $item): bool => mb_strtolower($item['category']) === $category)
                    ->values();
            });

        $total = $items->count();
        $offset = ($page - 1) * $perPage;
        $data = $items->slice($offset, $perPage)->values()->all();

        return response()->json([
            'data' => $data,
            'meta' => [
                'page' => $page,
                'per_page' => $perPage,
                'total' => $total,
                'total_pages' => (int) ceil($total / $perPage),
            ],
            'filters' => [
                'q' => $validated['q'] ?? null,
                'category' => $validated['category'] ?? null,
            ],
        ]);
    }

    /**
     * Proxy endpoint to the external catalog API.
     * Forwards requests to http://10.0.1.8:5173/api/v1/catalog
     *
     * @return JsonResponse
     */
    public function proxy(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'q' => ['nullable', 'string', 'max:255'],
            'language' => ['nullable', 'string', 'max:10'],
            'limit' => ['nullable', 'integer', 'min:1', 'max:100'],
            'page' => ['nullable', 'integer', 'min:1'],
        ]);

        try {
            $externalApiUrl = 'http://10.0.1.8:5173/api/v1/catalog';

            $response = Http::get($externalApiUrl, array_filter([
                'q' => $validated['q'] ?? null,
                'language' => $validated['language'] ?? null,
                'limit' => $validated['limit'] ?? 6,
                'page' => $validated['page'] ?? 1,
            ]));

            if ($response->successful()) {
                return response()->json($response->json());
            }

            return response()->json([
                'error' => 'Failed to fetch from external API',
                'status' => $response->status(),
            ], $response->status());
        } catch (\Exception $e) {
            return response()->json([
                'error' => 'Error connecting to external API',
                'message' => $e->getMessage(),
            ], 503);
        }
    }

    /**
     * Demo dataset for catalog endpoint.
     * Replace this with DB-backed records when books table is available.
     *
     * @return array<int, array<string, string|int>>
     */
    private function catalogItems(): array
    {
        return [
            [
                'id' => 1,
                'title' => 'Artificial Intelligence: A Modern Approach',
                'author' => 'Stuart Russell, Peter Norvig',
                'category' => 'Computer Science',
                'isbn' => '9780134610993',
                'year' => 2021,
                'format' => 'ebook',
            ],
            [
                'id' => 2,
                'title' => 'Clean Code',
                'author' => 'Robert C. Martin',
                'category' => 'Software Engineering',
                'isbn' => '9780132350884',
                'year' => 2008,
                'format' => 'print',
            ],
            [
                'id' => 3,
                'title' => 'Introduction to Algorithms',
                'author' => 'Thomas H. Cormen',
                'category' => 'Computer Science',
                'isbn' => '9780262046305',
                'year' => 2022,
                'format' => 'print',
            ],
            [
                'id' => 4,
                'title' => 'Principles of Economics',
                'author' => 'N. Gregory Mankiw',
                'category' => 'Economics',
                'isbn' => '9780357722718',
                'year' => 2023,
                'format' => 'ebook',
            ],
            [
                'id' => 5,
                'title' => 'Designing Data-Intensive Applications',
                'author' => 'Martin Kleppmann',
                'category' => 'Data Engineering',
                'isbn' => '9781449373320',
                'year' => 2017,
                'format' => 'ebook',
            ],
            [
                'id' => 6,
                'title' => 'Research Design',
                'author' => 'John W. Creswell',
                'category' => 'Research Methods',
                'isbn' => '9781506386706',
                'year' => 2018,
                'format' => 'print',
            ],
        ];
    }
}
