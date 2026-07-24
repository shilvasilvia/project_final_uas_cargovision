<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\News;
use App\Services\SentimentAnalysisService;
use Illuminate\Http\Request;

class NewsApiController extends Controller
{
    public function index(Request $request)
    {
        $query = News::with('country');

        if ($request->has('country_id')) {
            $query->where('country_id', $request->country_id);
        }

        if ($request->has('sentiment')) {
            $query->where('sentiment', $request->sentiment);
        }

        if ($request->has('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                  ->orWhere('content', 'like', "%{$search}%");
            });
        }

        $news = $query->paginate($request->get('per_page', 15));

        return response()->json([
            'success' => true,
            'message' => 'Daftar berita berhasil diambil',
            'data' => $news
        ]);
    }

    public function store(Request $request, SentimentAnalysisService $sentimentService)
    {
        $validated = $request->validate([
            'country_id' => 'required|exists:countries,id',
            'title' => 'required|string|max:255',
            'category' => 'nullable|string|max:255',
            'published_date' => 'required|date',
            'content' => 'required|string',
        ]);

        $sentimentResult = $sentimentService->analyze($validated['title'] . ' ' . $validated['content']);
        $validated['sentiment'] = $sentimentResult['sentiment'];

        $news = News::create($validated);

        return response()->json([
            'success' => true,
            'message' => 'Berita berhasil ditambahkan dengan analisis sentimen otomatis',
            'sentiment_analysis' => $sentimentResult,
            'data' => $news->load('country')
        ], 201);
    }

    public function show($id)
    {
        $news = News::with('country')->find($id);

        if (!$news) {
            return response()->json([
                'success' => false,
                'message' => 'Berita tidak ditemukan'
            ], 404);
        }

        return response()->json([
            'success' => true,
            'message' => 'Detail berita',
            'data' => $news
        ]);
    }

    public function update(Request $request, $id, SentimentAnalysisService $sentimentService)
    {
        $news = News::find($id);

        if (!$news) {
            return response()->json([
                'success' => false,
                'message' => 'Berita tidak ditemukan'
            ], 404);
        }

        $validated = $request->validate([
            'country_id' => 'sometimes|exists:countries,id',
            'title' => 'sometimes|string|max:255',
            'category' => 'nullable|string|max:255',
            'published_date' => 'sometimes|date',
            'content' => 'sometimes|string',
        ]);

        if (isset($validated['content']) || isset($validated['title'])) {
            $title = $validated['title'] ?? $news->title;
            $content = $validated['content'] ?? $news->content;
            $sentimentResult = $sentimentService->analyze($title . ' ' . $content);
            $validated['sentiment'] = $sentimentResult['sentiment'];
        }

        $news->update($validated);

        return response()->json([
            'success' => true,
            'message' => 'Berita berhasil diperbarui',
            'data' => $news->load('country')
        ]);
    }

    public function destroy($id)
    {
        $news = News::find($id);

        if (!$news) {
            return response()->json([
                'success' => false,
                'message' => 'Berita tidak ditemukan'
            ], 404);
        }

        $news->delete();

        return response()->json([
            'success' => true,
            'message' => 'Berita berhasil dihapus'
        ]);
    }
}
