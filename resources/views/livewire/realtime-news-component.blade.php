<div wire:poll.60s class="card bg-dark text-white border-0 shadow-lg p-4">
    @if(session('news_success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <i class="fa-solid fa-circle-check me-2"></i>{{ session('news_success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <div class="d-flex flex-wrap justify-content-between align-items-center mb-4 gap-3">
        <div>
            <h4 class="fw-bold mb-1 text-info">
                <i class="fa-solid fa-newspaper me-2"></i>Real-time News Intelligence & Sentiment Analysis
            </h4>
            <p class="text-muted small mb-0">Berita geopolitik & logistik maritim global secara real-time (Livewire Poll: 60 detik)</p>
        </div>
        <div>
            <button wire:click="syncNews" class="btn btn-outline-info btn-sm rounded-pill px-3">
                <i class="fa-solid fa-rotate me-1"></i> Sync Realtime News Feed
            </button>
        </div>
    </div>

    <!-- Filters -->
    <div class="row g-3 mb-4">
        <div class="col-md-5">
            <div class="input-group">
                <span class="input-group-text bg-secondary bg-opacity-20 border-secondary text-light">
                    <i class="fa-solid fa-magnifying-glass"></i>
                </span>
                <input type="text" wire:model.live.debounce.300ms="search" class="form-control bg-secondary bg-opacity-10 text-white border-secondary" placeholder="Cari judul berita atau topik rantai pasok...">
            </div>
        </div>
        <div class="col-md-3">
            <select wire:model.live="sentiment" class="form-select bg-secondary bg-opacity-10 text-white border-secondary">
                <option value="" class="bg-dark">Semua Sentimen</option>
                <option value="Positive" class="bg-dark">Sentimen Positif (Stabilitas)</option>
                <option value="Neutral" class="bg-dark">Sentimen Netral</option>
                <option value="Negative" class="bg-dark">Sentimen Negatif (Risiko Delay/Disrupsi)</option>
            </select>
        </div>
        <div class="col-md-4">
            <select wire:model.live="countryId" class="form-select bg-secondary bg-opacity-10 text-white border-secondary">
                <option value="" class="bg-dark">Semua Negara (15 Negara Global)</option>
                @foreach($countries as $c)
                    <option value="{{ $c->id }}" class="bg-dark">{{ $c->name }} ({{ $c->code }})</option>
                @endforeach
            </select>
        </div>
    </div>

    <!-- News Grid -->
    <div class="row g-3">
        @forelse($newsList as $item)
            <div class="col-md-6 col-lg-4">
                <div class="card h-100 bg-secondary bg-opacity-10 border-secondary border-opacity-25 rounded-3 p-3">
                    <div class="d-flex justify-content-between align-items-center mb-2">
                        <span class="badge bg-primary bg-opacity-30 text-info border border-info border-opacity-25 rounded-pill px-2 py-1 small">
                            <i class="fa-solid fa-tag me-1"></i>{{ $item->category }}
                        </span>
                        @if($item->sentiment === 'Positive')
                            <span class="badge bg-success bg-opacity-20 text-success border border-success border-opacity-25 rounded-pill px-2 py-1 small">
                                <i class="fa-solid fa-circle-check me-1"></i>Positive
                            </span>
                        @elseif($item->sentiment === 'Negative')
                            <span class="badge bg-danger bg-opacity-20 text-danger border border-danger border-opacity-25 rounded-pill px-2 py-1 small">
                                <i class="fa-solid fa-triangle-exclamation me-1"></i>Negative (Risk)
                            </span>
                        @else
                            <span class="badge bg-secondary bg-opacity-30 text-light border border-secondary border-opacity-25 rounded-pill px-2 py-1 small">
                                <i class="fa-solid fa-minus me-1"></i>Neutral
                            </span>
                        @endif
                    </div>

                    <h6 class="fw-bold text-white mb-2" style="font-size: 0.95rem;">{{ $item->title }}</h6>
                    <p class="text-muted small flex-grow-1 mb-3" style="line-height: 1.4;">{{ Str::limit($item->content, 120) }}</p>

                    <div class="d-flex justify-content-between align-items-center pt-2 border-top border-secondary border-opacity-25 text-muted small">
                        <span><i class="fa-solid fa-globe me-1 text-info"></i>{{ $item->country->name ?? 'Global' }}</span>
                        <span><i class="fa-solid fa-clock me-1"></i>{{ $item->published_date ? \Carbon\Carbon::parse($item->published_date)->diffForHumans() : 'Realtime' }}</span>
                    </div>
                </div>
            </div>
        @empty
            <div class="col-12 text-center py-5 text-muted">
                <i class="fa-solid fa-newspaper fa-3x mb-3 text-secondary opacity-50"></i>
                <p class="mb-0">Tidak ada berita yang sesuai dengan filter pencarian.</p>
            </div>
        @endforelse
    </div>
</div>
