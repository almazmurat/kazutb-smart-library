<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
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
            'author' => ['nullable', 'string', 'max:500'],
            'publisher' => ['nullable', 'string', 'max:500'],
            'year' => ['nullable', 'string', 'max:10'],
            'language' => ['nullable', 'string', 'max:50'],
            'isbn' => ['nullable', 'string', 'max:30'],
            'available' => ['nullable', 'integer', 'min:0'],
            'total' => ['nullable', 'integer', 'min:0'],
        ]);

        $items = $this->getShortlist($request);
        $identifier = $validated['identifier'];

        if (isset($items[$identifier])) {
            return response()->json([
                'message' => 'Книга уже в подборке.',
                'duplicate' => true,
                'data' => $items[$identifier],
            ], 409);
        }

        $item = [
            'identifier' => $identifier,
            'title' => $validated['title'],
            'author' => $validated['author'] ?? null,
            'publisher' => $validated['publisher'] ?? null,
            'year' => $validated['year'] ?? null,
            'language' => $validated['language'] ?? null,
            'isbn' => $validated['isbn'] ?? null,
            'available' => $validated['available'] ?? null,
            'total' => $validated['total'] ?? null,
            'addedAt' => now()->toIso8601String(),
        ];

        $items[$identifier] = $item;
        $this->saveShortlist($request, $items);

        return response()->json([
            'message' => 'Книга добавлена в подборку.',
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
