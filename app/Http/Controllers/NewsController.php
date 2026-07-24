<?php

namespace App\Http\Controllers;

use App\Models\News;
use App\Models\Country;
use App\Services\SentimentAnalysisService;
use Illuminate\Http\Request;

class NewsController extends Controller
{
    public function index(Request $request)
    {
        $query = News::with('country');

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where('title', 'like', "%{$search}%")
                  ->orWhere('content', 'like', "%{$search}%");
        }

        if ($request->filled('sentiment')) {
            $query->where('sentiment', $request->sentiment);
        }

        $news = $query->latest()->paginate(10);
        $countries = Country::all();

        return view('news.index', compact('news', 'countries'));
    }

    public function create()
    {
        $countries = Country::all();
        return view('news.create', compact('countries'));
    }

    public function store(Request $request, SentimentAnalysisService $sentimentService)
    {
        $validated = $request->validate([
            'country_id' => 'required|exists:countries,id',
            'title' => 'required|string|max:255',
            'category' => 'required|string|max:255',
            'published_date' => 'required|date',
            'content' => 'required|string',
        ]);

        $sentimentResult = $sentimentService->analyze($validated['title'] . ' ' . $validated['content']);
        $validated['sentiment'] = $sentimentResult['sentiment'];

        News::create($validated);

        return redirect()->route('news.index')->with('success', 'Berita berhasil ditambahkan dengan analisis sentimen otomatis (' . $sentimentResult['sentiment'] . ').');
    }

    public function show(News $news)
    {
        $news->load('country');
        return view('news.show', compact('news'));
    }

    public function destroy(News $news)
    {
        $news->delete();
        return redirect()->route('news.index')->with('success', 'Berita berhasil dihapus.');
    }
}
