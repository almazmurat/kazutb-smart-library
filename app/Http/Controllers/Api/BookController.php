<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class BookController extends Controller
{
    /**
     * Get book details from external API by ISBN or ID
     */
    public function show(Request $request): JsonResponse
    {
        $isbn = $request->route('isbn');

        try {
            // Try to fetch from external API with search
            $response = Http::get('http://10.0.1.8:5173/api/v1/catalog', [
                'q' => $isbn,
                'limit' => 100,
            ]);

            if ($response->successful()) {
                $data = $response->json();
                $books = $data['data'] ?? [];

                // Find book by ISBN
                $book = collect($books)->first(function ($item) use ($isbn) {
                    return ($item['isbn']['raw'] ?? '') === $isbn 
                        || ($item['id'] ?? '') === $isbn;
                });

                if ($book) {
                    return response()->json([
                        'data' => $book,
                        'success' => true,
                    ]);
                }
            }

            return response()->json([
                'error' => 'Book not found',
                'success' => false,
            ], 404);
        } catch (\Exception $e) {
            return response()->json([
                'error' => 'Error fetching book details',
                'message' => $e->getMessage(),
                'success' => false,
            ], 503);
        }
    }
}
