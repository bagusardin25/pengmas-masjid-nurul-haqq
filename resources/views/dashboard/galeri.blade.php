@extends('layouts.dashboard')

@section('title', 'Galeri & Media')
@section('breadcrumb', 'Galeri & Media')

@php
    use App\Enums\PublicationStatus;

    $statusOptions = collect($statuses)->mapWithKeys(fn ($status) => [$status->value => $status->label()])->all();
    $categoryOptions = ['' => 'Tanpa kategori'] + $categories->pluck('name', 'id')->all();

    $perPage = 5;

    // Item yang gagal divalidasi harus terlihat walaupun berada di luar 5 item pertama.
    $minShown = fn ($items, string $prefix) => $items->values()
        ->filter(fn ($item) => $errors->getBag($prefix.'-'.$item->id)->any())
        ->keys()
        ->map(fn ($index) => $index + 1)
        ->max() ?? 0;

    $sections = [
        'album' => 'Album foto',
        'video' => 'Video kajian',
        'artikel' => 'Artikel',
        'kategori' => 'Kategori',
    ];
@endphp

@section('content')
    <div class="dash-page">
        <header class="dash-page-header">
            <div>
                <p class="dash-eyebrow">Kelola situs</p>
                <h1>Galeri &amp; Media</h1>
                <p>Album foto kegiatan, video kajian dari YouTube, dan artikel jamaah di halaman Galeri. Hanya konten berstatus Terbit yang tampil di situs.</p>
            </div>
            <x-dashboard.page-link :href="route('galeri')" />
        </header>

        <x-dashboard.status-toast />

        <nav class="dash-section-nav" aria-label="Bagian halaman Galeri">
            @foreach ($sections as $anchor => $sectionLabel)
                <a href="#{{ $anchor }}">{{ $sectionLabel }}</a>
            @endforeach
        </nav>

        {{-- Album foto --}}
        <section id="album" class="dash-panel" aria-labelledby="album-title">
            <header class="dash-panel-header">
                <h2 id="album-title">Album foto</h2>
                <p>Satu album untuk satu kegiatan. Buat albumnya dulu, lalu unggah foto-fotonya di halaman album. Album tanpa foto tidak tampil di situs.</p>
            </header>

            @if ($albums->isEmpty())
                <p class="dash-empty">Belum ada album. Buat album pertama lewat form di bawah.</p>
            @else
                <div x-data="loadMore({ key: 'galeri-album', total: {{ $albums->count() }}, step: {{ $perPage }}, min: {{ $minShown($albums, 'album') }} })">
                <ul class="dash-album-grid">
                    @foreach ($albums as $album)
                        @php $cover = $album->cover(); @endphp
                        <li @class(['dash-album-card', 'is-hidden' => $album->status === PublicationStatus::Draft])
                            x-show="isShown({{ $loop->index }})" @if ($loop->index >= $perPage) x-cloak @endif>
                            <a class="dash-album-link" href="{{ route('dashboard.galeri.albums.edit', $album) }}">
                                <span class="dash-album-cover">
                                    @if ($cover)
                                        <img src="{{ $cover->url() }}" alt="" loading="lazy">
                                    @else
                                        <span>Belum ada foto</span>
                                    @endif
                                    <span class="dash-album-count">{{ $album->photos_count }} foto</span>
                                </span>
                                <span class="dash-album-copy">
                                    <span @class(['dash-status', 'dash-status-draft' => $album->status === PublicationStatus::Draft])>{{ $album->status->label() }}</span>
                                    <strong>{{ $album->title }}</strong>
                                    <small>
                                        {{ $album->category?->name ?? 'Tanpa kategori' }}
                                        @if ($album->date) · {{ $album->date->locale('id')->translatedFormat('j M Y') }} @endif
                                    </small>
                                    <span class="dash-album-action">Kelola album &amp; foto →</span>
                                </span>
                            </a>
                        </li>
                    @endforeach
                </ul>
                <x-dashboard.load-more-button label="album" />
                </div>
            @endif

            <form method="POST" action="{{ route('dashboard.galeri.albums.store') }}" class="dash-add">
                @csrf
                <h3>Buat album baru</h3>
                <div class="dash-item-form dash-grid-six">
                    <x-dashboard.field bag="album-new" name="title" label="Judul album" placeholder="Peringatan Maulid Nabi 1448 H" required class="dash-span-3" />
                    <x-dashboard.field bag="album-new" name="category_id" label="Kategori" type="select" :options="$categoryOptions" />
                    <x-dashboard.field bag="album-new" name="date" label="Tanggal kegiatan" type="date" />
                    <x-dashboard.field bag="album-new" name="status" label="Status" type="select" :options="$statusOptions" value="published" required />
                    <x-dashboard.field bag="album-new" name="location" label="Lokasi" placeholder="Ruang utama masjid" class="dash-span-2" />
                    <x-dashboard.field bag="album-new" name="description" label="Keterangan" type="textarea" rows="1" class="dash-span-4" />
                    <x-dashboard.item-actions submit-label="Buat album lalu unggah foto" class="dash-actions-row" />
                </div>
            </form>
        </section>

        {{-- Video kajian --}}
        <section id="video" class="dash-panel" aria-labelledby="video-title">
            <header class="dash-panel-header">
                <h2 id="video-title">Video kajian</h2>
                <p>Video diambil dari YouTube: cukup tempel tautannya, gambar pratinjau dibuat otomatis. Video terbaru tampil paling atas.</p>
            </header>

            <div class="dash-list" x-data="loadMore({ key: 'galeri-video', total: {{ $videos->count() }}, step: {{ $perPage }}, min: {{ $minShown($videos, 'video') }} })">
                @forelse ($videos as $video)
                    @php
                        $bag = 'video-'.$video->id;
                        $label = 'video “'.$video->title.'”';
                    @endphp
                    <div @class(['dash-item', 'is-hidden' => $video->status === PublicationStatus::Draft])
                        x-show="isShown({{ $loop->index }})" @if ($loop->index >= $perPage) x-cloak @endif>
                        <form method="POST" action="{{ route('dashboard.galeri.videos.update', $video) }}" class="dash-item-form dash-grid-video"
                            x-data="youtubeAutofill(@js(route('dashboard.galeri.videos.lookup')))" @change="lookup($event)">
                            @csrf
                            @method('PUT')
                            <a class="dash-photo-thumb dash-video-thumb" href="{{ $video->watchUrl() }}" target="_blank" rel="noopener" aria-label="Tonton {{ $video->title }} di YouTube">
                                <img src="{{ $video->thumbnailUrl() }}" alt="" loading="lazy">
                            </a>
                            <div class="dash-item-form dash-grid-six">
                                <x-dashboard.field :bag="$bag" name="title" label="Judul video" :value="$video->title" class="dash-span-3"
                                    hint="Kosongkan untuk memakai judul dari YouTube." />
                                <x-dashboard.field :bag="$bag" name="youtube_url" label="Tautan YouTube" type="url" :value="$video->youtube_url" required class="dash-span-3" />
                                <x-dashboard.field :bag="$bag" name="category_id" label="Kategori" type="select" :options="$categoryOptions" :value="$video->category_id" />
                                <x-dashboard.field :bag="$bag" name="speaker" label="Pemateri" :value="$video->speaker" class="dash-span-2" />
                                <x-dashboard.field :bag="$bag" name="date" label="Tanggal" type="date" :value="$video->date?->toDateString()" />
                                <x-dashboard.field :bag="$bag" name="duration" label="Durasi" :value="$video->duration" placeholder="24:18" />
                                <x-dashboard.field :bag="$bag" name="status" label="Status" type="select" :options="$statusOptions" :value="$video->status->value" required />
                                <x-dashboard.field :bag="$bag" name="description" label="Keterangan" type="textarea" rows="1" :value="$video->description" class="dash-span-full" />
                                <x-dashboard.item-actions :delete-form="'hapus-'.$bag" :label="$label" class="dash-actions-row" />
                            </div>
                        </form>
                        <x-dashboard.delete-form :id="'hapus-'.$bag" :action="route('dashboard.galeri.videos.destroy', $video)" :label="$label" />
                    </div>
                @empty
                    <p class="dash-empty">Belum ada video. Tambahkan lewat form di bawah.</p>
                @endforelse
                <x-dashboard.load-more-button label="video" />
            </div>

            <form method="POST" action="{{ route('dashboard.galeri.videos.store') }}" class="dash-add"
                x-data="youtubeAutofill(@js(route('dashboard.galeri.videos.lookup')))" @change="lookup($event)">
                @csrf
                <h3>Tambah video</h3>
                <div class="dash-item-form dash-grid-six">
                    <x-dashboard.field bag="video-new" name="youtube_url" label="Tautan YouTube" type="url" placeholder="https://youtu.be/..." required class="dash-span-3"
                        hint="Di YouTube pilih Bagikan → Salin. Judul dan durasi terisi otomatis." />
                    <x-dashboard.field bag="video-new" name="title" label="Judul video" placeholder="Otomatis dari YouTube" class="dash-span-3" />
                    <x-dashboard.field bag="video-new" name="category_id" label="Kategori" type="select" :options="$categoryOptions" />
                    <x-dashboard.field bag="video-new" name="speaker" label="Pemateri" class="dash-span-2" />
                    <x-dashboard.field bag="video-new" name="date" label="Tanggal" type="date" />
                    <x-dashboard.field bag="video-new" name="duration" label="Durasi" placeholder="Otomatis" />
                    <x-dashboard.field bag="video-new" name="status" label="Status" type="select" :options="$statusOptions" value="published" required />
                    <x-dashboard.field bag="video-new" name="description" label="Keterangan" type="textarea" rows="1" class="dash-span-full" />
                    <div class="dash-item-actions dash-actions-row">
                        <span class="dash-hint dash-autofill-status" x-show="status" x-text="status" role="status"></span>
                        <button type="submit" class="dash-button dash-button-secondary" :disabled="busy">Tambah video</button>
                    </div>
                </div>
            </form>
        </section>

        {{-- Artikel --}}
        <section id="artikel" class="dash-panel" aria-labelledby="artikel-title">
            <header class="dash-panel-header">
                <h2 id="artikel-title">Artikel</h2>
                <p>Tulisan kegiatan atau kajian dari pengurus dan jamaah. Pisahkan paragraf dengan satu baris kosong. Tanggal terbit yang dikosongkan diisi otomatis saat artikel diterbitkan.</p>
            </header>

            <div class="dash-list" x-data="loadMore({ key: 'galeri-artikel', total: {{ $articles->count() }}, step: {{ $perPage }}, min: {{ $minShown($articles, 'article') }} })">
                @forelse ($articles as $article)
                    @php
                        $bag = 'article-'.$article->id;
                        $label = 'artikel “'.$article->title.'”';
                        $removeCoverChecked = $errors->getBag($bag)->any() && old('remove_cover_image');
                    @endphp
                    <div @class(['dash-item', 'is-hidden' => $article->status === PublicationStatus::Draft])
                        x-show="isShown({{ $loop->index }})" @if ($loop->index >= $perPage) x-cloak @endif>
                        <form method="POST" action="{{ route('dashboard.galeri.articles.update', $article) }}" enctype="multipart/form-data"
                            class="dash-item-form dash-grid-six" x-data="photoUpload()">
                            @csrf
                            @method('PUT')
                            <x-dashboard.field :bag="$bag" name="title" label="Judul artikel" :value="$article->title" required class="dash-span-3" />
                            <x-dashboard.field :bag="$bag" name="author" label="Penulis" :value="$article->author" class="dash-span-2" />
                            <x-dashboard.field :bag="$bag" name="category" label="Kategori" :value="$article->category" placeholder="Kajian" />
                            <div class="dash-field">
                                <span class="dash-check-label">Sampul saat ini</span>
                                @if ($article->coverImage)
                                    <a class="dash-photo-thumb" href="{{ $article->coverImage->url() }}" target="_blank" rel="noopener">
                                        <img src="{{ $article->coverImage->url() }}" alt="Sampul {{ $article->title }}" loading="lazy">
                                    </a>
                                    <label class="dash-inline-check">
                                        <input type="hidden" name="remove_cover_image" value="0">
                                        <input type="checkbox" name="remove_cover_image" value="1" @checked($removeCoverChecked)>
                                        Hapus sampul
                                    </label>
                                @else
                                    <span class="dash-hint">Belum ada sampul.</span>
                                @endif
                            </div>
                            <x-dashboard.photo-field :bag="$bag" name="cover_image" :label="$article->coverImage ? 'Ganti sampul' : 'Foto sampul'" class="dash-span-3" />
                            <x-dashboard.field :bag="$bag" name="published_at" label="Tanggal terbit" type="date" :value="$article->published_at?->toDateString()" />
                            <x-dashboard.field :bag="$bag" name="status" label="Status" type="select" :options="$statusOptions" :value="$article->status->value" required />
                            <x-dashboard.field :bag="$bag" name="excerpt" label="Ringkasan" type="textarea" rows="2" :value="$article->excerpt" class="dash-span-full"
                                hint="Tampil di kartu artikel. Bila kosong, diambil dari awal isi artikel." />
                            <x-dashboard.field :bag="$bag" name="body" label="Isi artikel" type="textarea" rows="8" :value="$article->body" required class="dash-span-full" />
                            <div class="dash-item-actions dash-actions-row">
                                @if ($article->status === PublicationStatus::Published)
                                    <a class="dash-button dash-button-ghost" href="{{ route('galeri.artikel', $article) }}" target="_blank" rel="noopener">Lihat artikel</a>
                                @endif
                                <button type="submit" class="dash-button dash-button-primary" :disabled="busy">Simpan</button>
                                <button type="submit" form="hapus-{{ $bag }}" class="dash-button dash-button-danger" aria-label="Hapus {{ $label }}">Hapus</button>
                            </div>
                        </form>
                        <x-dashboard.delete-form :id="'hapus-'.$bag" :action="route('dashboard.galeri.articles.destroy', $article)" :label="$label" />
                    </div>
                @empty
                    <p class="dash-empty">Belum ada artikel. Tulis artikel pertama lewat form di bawah.</p>
                @endforelse
                <x-dashboard.load-more-button label="artikel" />
            </div>

            <form method="POST" action="{{ route('dashboard.galeri.articles.store') }}" enctype="multipart/form-data" class="dash-add" x-data="photoUpload()">
                @csrf
                <h3>Tulis artikel</h3>
                <div class="dash-item-form dash-grid-six">
                    <x-dashboard.field bag="article-new" name="title" label="Judul artikel" required class="dash-span-3" />
                    <x-dashboard.field bag="article-new" name="author" label="Penulis" placeholder="Takmir Masjid Nurul Haqq" class="dash-span-2" />
                    <x-dashboard.field bag="article-new" name="category" label="Kategori" placeholder="Kegiatan" />
                    <x-dashboard.photo-field bag="article-new" name="cover_image" label="Foto sampul" class="dash-span-4" />
                    <x-dashboard.field bag="article-new" name="published_at" label="Tanggal terbit" type="date" />
                    <x-dashboard.field bag="article-new" name="status" label="Status" type="select" :options="$statusOptions" value="draft" required />
                    <x-dashboard.field bag="article-new" name="excerpt" label="Ringkasan" type="textarea" rows="2" class="dash-span-full" />
                    <x-dashboard.field bag="article-new" name="body" label="Isi artikel" type="textarea" rows="8" required class="dash-span-full" />
                    <div class="dash-item-actions dash-actions-row">
                        <button type="submit" class="dash-button dash-button-secondary" :disabled="busy">Simpan artikel</button>
                    </div>
                </div>
            </form>
        </section>

        {{-- Kategori --}}
        <section id="kategori" class="dash-panel" aria-labelledby="kategori-title">
            <header class="dash-panel-header">
                <h2 id="kategori-title">Kategori</h2>
                <p>Kategori album dan video, tampil sebagai pilihan filter di halaman Galeri. Kategori yang belum punya konten terbit tidak ditampilkan.</p>
            </header>

            <div class="dash-list">
                @forelse ($categories as $category)
                    @php $bag = 'category-'.$category->id; @endphp
                    <div class="dash-item">
                        <form method="POST" action="{{ route('dashboard.galeri.categories.update', $category) }}" class="dash-item-form dash-grid-faq">
                            @csrf
                            @method('PUT')
                            <x-dashboard.field :bag="$bag" name="name" label="Nama kategori" :value="$category->name" required
                                :hint="$category->albums_count.' album · '.$category->videos_count.' video'" />
                            <x-dashboard.field :bag="$bag" name="sort_order" label="Urutan" type="number" :value="$category->sort_order" :show-optional="false" />
                            <x-dashboard.item-actions :delete-form="'hapus-'.$bag" :label="'kategori “'.$category->name.'”'" />
                        </form>
                        <x-dashboard.delete-form :id="'hapus-'.$bag" :action="route('dashboard.galeri.categories.destroy', $category)"
                            :label="'kategori “'.$category->name.'” (album dan videonya tetap ada, menjadi tanpa kategori)'" />
                    </div>
                @empty
                    <p class="dash-empty">Belum ada kategori. Tambahkan lewat form di bawah.</p>
                @endforelse
            </div>

            <form method="POST" action="{{ route('dashboard.galeri.categories.store') }}" class="dash-add">
                @csrf
                <h3>Tambah kategori</h3>
                <div class="dash-item-form dash-grid-faq">
                    <x-dashboard.field bag="category-new" name="name" label="Nama kategori" placeholder="Hari besar Islam" required />
                    <x-dashboard.field bag="category-new" name="sort_order" label="Urutan" type="number" placeholder="Otomatis" :show-optional="false" />
                    <x-dashboard.item-actions submit-label="Tambah" />
                </div>
            </form>
        </section>
    </div>
@endsection
