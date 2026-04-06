<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Services\BibliographyFormatter;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ShortlistController extends Controller
{
    /**
     * List all shortlisted items.
     */
    public function index(Request $request): JsonResponse
    {
        $items = $this->getShortlist($request);

        return response()->json([
            'data' => array_values($items),
            'meta' => [
                'total' => count($items),
            ],
        ]);
    }

    /**
     * Add a book/resource to the shortlist.
     */
    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'identifier' => ['required', 'string', 'max:255'],
            'title' => ['required', 'string', 'max:1000'],
            'type' => ['nullable', 'string', 'in:book,external_resource'],
            'author' => ['nullable', 'string', 'max:500'],
            'publisher' => ['nullable', 'string', 'max:500'],
            'year' => ['nullable', 'string', 'max:10'],
            'language' => ['nullable', 'string', 'max:50'],
            'isbn' => ['nullable', 'string', 'max:30'],
            'available' => ['nullable', 'integer', 'min:0'],
            'total' => ['nullable', 'integer', 'min:0'],
            'url' => ['nullable', 'string', 'url', 'max:2048'],
            'provider' => ['nullable', 'string', 'max:500'],
            'access_type' => ['nullable', 'string', 'max:50'],
        ]);

        $items = $this->getShortlist($request);
        $identifier = $validated['identifier'];

        if (isset($items[$identifier])) {
            return response()->json([
                'message' => 'Ресурс уже в подборке.',
                'duplicate' => true,
                'data' => $items[$identifier],
            ], 409);
        }

        $item = [
            'identifier' => $identifier,
            'title' => $validated['title'],
            'type' => $validated['type'] ?? 'book',
            'author' => $validated['author'] ?? null,
            'publisher' => $validated['publisher'] ?? null,
            'year' => $validated['year'] ?? null,
            'language' => $validated['language'] ?? null,
            'isbn' => $validated['isbn'] ?? null,
            'available' => $validated['available'] ?? null,
            'total' => $validated['total'] ?? null,
            'url' => $validated['url'] ?? null,
            'provider' => $validated['provider'] ?? null,
            'access_type' => $validated['access_type'] ?? null,
            'addedAt' => now()->toIso8601String(),
        ];

        $items[$identifier] = $item;
        $this->saveShortlist($request, $items);

        return response()->json([
            'message' => 'Ресурс добавлен в подборку.',
            'data' => $item,
            'meta' => ['total' => count($items)],
        ], 201);
    }

    /**
     * Remove a book/resource from the shortlist.
     */
    public function destroy(Request $request, string $identifier): JsonResponse
    {
        $items = $this->getShortlist($request);

        if (! isset($items[$identifier])) {
            return response()->json([
                'message' => 'Книга не найдена в подборке.',
            ], 404);
        }

        unset($items[$identifier]);
        $this->saveShortlist($request, $items);

        return response()->json([
            'message' => 'Книга удалена из подборки.',
            'meta' => ['total' => count($items)],
        ]);
    }

    /**
     * Clear the entire shortlist.
     */
    public function clear(Request $request): JsonResponse
    {
        $this->saveShortlist($request, []);

        return response()->json([
            'message' => 'Подборка очищена.',
            'meta' => ['total' => 0],
        ]);
    }

    /**
     * Export shortlist as formatted bibliography text.
     */
    public function export(Request $request): JsonResponse
    {
        $format = $request->query('format', BibliographyFormatter::FORMAT_NUMBERED);
        $items = $this->getShortlist($request);
        $formatter = new BibliographyFormatter();

        $result = $formatter->format(array_values($items), $format);

        return response()->json([
            'data' => $result,
            'meta' => ['total' => count($items)],
        ]);
    }

    /**
     * Check shortlist status for given identifiers.
     */
    public function check(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'identifiers' => ['required', 'array', 'max:50'],
            'identifiers.*' => ['required', 'string', 'max:255'],
        ]);

        $items = $this->getShortlist($request);
        $result = [];

        foreach ($validated['identifiers'] as $id) {
            $result[$id] = isset($items[$id]);
        }

        return response()->json([
            'data' => $result,
            'meta' => ['total' => count($items)],
        ]);
    }

    private function getShortlist(Request $request): array
    {
        $list = $request->session()->get('library.shortlist', []);

        return is_array($list) ? $list : [];
    }

    private function saveShortlist(Request $request, array $items): void
    {
        $request->session()->put('library.shortlist', $items);
    }
}
