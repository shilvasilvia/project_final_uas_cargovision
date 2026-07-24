<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\News;
use App\Models\Country;
use App\Services\NewsService;

class RealtimeNewsComponent extends Component
{
    public $search = '';
    public $sentiment = '';
    public $countryId = '';

    public function syncNews(NewsService $newsService)
    {
        $count = $newsService->fetchLatestSupplyChainNews();
        session()->flash('news_success', "Berita realtime berhasil disinkronkan. ($count berita baru ditambahkan)");
    }

    public function render()
    {
        $query = News::with('country');

        if (!empty($this->search)) {
            $query->where(function ($q) {
                $q->where('title', 'like', '%' . $this->search . '%')
                  ->orWhere('content', 'like', '%' . $this->search . '%');
            });
        }

        if (!empty($this->sentiment)) {
            $query->where('sentiment', $this->sentiment);
        }

        if (!empty($this->countryId)) {
            $query->where('country_id', $this->countryId);
        }

        $newsList = $query->latest()->take(12)->get();
        $countries = Country::all();

        return view('livewire.realtime-news-component', [
            'newsList' => $newsList,
            'countries' => $countries,
        ]);
    }
}
