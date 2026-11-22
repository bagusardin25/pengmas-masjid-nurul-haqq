@extends('layouts.dashboard')

@section('title', $album->title.' — Galeri')
@section('breadcrumb', 'Galeri & Media › Album')
@section('preview-url', $album->status === \App\Enums\PublicationStatus::Published && $album->photos()->exists() ? route('galeri.album', $album) : route('galeri'))

@php
    use App\Enums\PublicationStatus;
    use App\Http\Controllers\Dashboard\Galeri\GalleryPhotoController;

    $statusOptions = collect($statuses)->mapWithKeys(fn ($status) => [$status->value => $status->label()])->all();
    $categoryOptions = ['' => 'Tanpa kategori'] + $categories->pluck('name', 'id')->all();
    $bag = 'album-'.$album->id;
    $cover = $album->cover();
    $isPublic = $album->status === PublicationStatus::Published && $photos->isNotEmpty();
    $uploadErrors = $errors->getBag('photo-new');
    $uploadError = $uploadErrors->first('photos') ?: $uploadErrors->first('photos.*');
@endphp

@section('content')
    <div class="dash-page">
        <header class="dash-page-header">
            <div>
                <p class="dash-eyebrow"><a class="dash-back-link" href="{{ route('dashboard.galeri') }}#album">← Galeri &amp; Media</a></p>
                <h1>{{ $album->title }}</h1>
                <p>
                    {{ $photos->count() }} foto ·
                    @if ($isPublic)
                        tampil di halaman Galeri.
                    @elseif ($album->status === PublicationStatus::Draft)
                        masih draf, belum tampil di situs.
                    @else
                        belum tampil karena album belum berisi foto.
                    @endif
                </p>
            </div>
            @if ($isPublic)
                <x-dashboard.page-link :href="route('galeri.album', $album)" label="Lihat album" />
            @endif
        </header>

        <x-dashboard.status-toast />

        <nav class="dash-section-nav" aria-label="Bagian halaman album">
            <a href="#foto">Foto</a>
            <a href="#detail">Detail album</a>
        </nav>

        {{-- Foto --}}
        <section id="foto" class="dash-panel" aria-labelledby="foto-title">
            <header class="dash-panel-header">
                <h2 id="foto-title">Foto</h2>
                <p>Pilih beberapa foto sekaligus (maksimal {{ GalleryPhotoController::MAX_PER_UPLOAD }} per unggahan). Foto besar otomatis diperkecil sebelum dikirim. Pastikan orang yang tampil di foto tidak keberatan fotonya dipublikasikan.</p>
            </header>

            <form method="POST" action="{{ route('dashboard.galeri.albums.photos.store', $album) }}" enctype="multipart/form-data"
                class="dash-add dash-upload-box" x-data="multiPhotoUpload({ max: {{ GalleryPhotoController::MAX_PER_UPLOAD }} })"
                @submit="if (busy || blocked) $event.preventDefault()">
                @csrf
                <h3>Unggah foto</h3>
                <div @class(['dash-field', 'has-error' => $uploadError])>
                    <label for="photo-new-photos">Pilih foto</label>
                    <input id="photo-new-photos" name="photos[]" type="file" multiple accept="image/jpeg,image/png,image/webp"
                        @change="pick($event)" required
                        @if ($uploadError) aria-invalid="true" @endif aria-describedby="photo-new-photos-hint">
                    <p class="dash-hint" id="photo-new-photos-hint">
                        <span x-show="busy">Memproses foto…</span>
                        <span x-show="!busy && message" x-text="message" :class="{ 'dash-error': blocked }"></span>
                        <span x-show="!busy && !message">JPG, PNG, atau WebP. Foto dari iPhone (HEIC) ubah dulu ke JPG.</span>
                    </p>
                    @if ($uploadError)
                        <p class="dash-error">{{ $uploadError }}</p>
                    @endif
                </div>
                <ul class="dash-upload-previews" x-show="previews.length" x-cloak>
                    <template x-for="item in previews" :key="item.url">
                        <li><img :src="item.url" alt=""></li>
                    </template>
                </ul>
                <div class="dash-item-actions">
                    <button type="submit" class="dash-button dash-button-primary" :disabled="busy || blocked">Unggah foto</button>
                </div>
            </form>

            @if ($photos->isEmpty())
                <p class="dash-empty dash-empty-spaced">Album ini belum berisi foto.</p>
            @else
                <ul class="dash-photo-grid">
                    @foreach ($photos as $photo)
                        @php
                            $photoBag = 'photo-'.$photo->id;
                            $isCover = $cover?->id === $photo->image_id;
                            $photoLabel = 'foto '.($photo->caption ? '“'.$photo->caption.'”' : 'ke-'.$loop->iteration);
                        @endphp
                        <li @class(['dash-photo-card', 'is-cover' => $isCover])>
                            <a class="dash-photo-card-image" href="{{ $photo->image->url() }}" target="_blank" rel="noopener">
                                <img src="{{ $photo->image->url() }}" alt="{{ $photo->caption ?: 'Foto '.$album->title }}" loading="lazy">
                                @if ($isCover)
                                    <span class="dash-cover-badge">Sampul</span>
                                @endif
                            </a>
                            <form method="POST" action="{{ route('dashboard.galeri.albums.photos.update', [$album, $photo]) }}" class="dash-photo-card-form">
                                @csrf
                                @method('PUT')
                                <x-dashboard.field :bag="$photoBag" name="caption" label="Keterangan" :value="$photo->caption" />
                                <x-dashboard.field :bag="$photoBag" name="sort_order" label="Urutan" type="number" :value="$photo->sort_order" :show-optional="false" />
                                <button type="submit" class="dash-button dash-button-secondary">Simpan</button>
                            </form>
                            <div class="dash-photo-card-actions">
                                @unless ($isCover)
                                    <form method="POST" action="{{ route('dashboard.galeri.albums.photos.cover', [$album, $photo]) }}">
                                        @csrf
                                        @method('PUT')
                                        <button type="submit" class="dash-text-button">Jadikan sampul</button>
                                    </form>
                                @endunless
                                <form method="POST" action="{{ route('dashboard.galeri.albums.photos.destroy', [$album, $photo]) }}"
                                    onsubmit="return confirm(@js('Hapus '.$photoLabel.'? Foto yang dihapus tidak bisa dikembalikan.'))">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="dash-text-button dash-text-button-danger" aria-label="Hapus {{ $photoLabel }}">Hapus</button>
                                </form>
                            </div>
                        </li>
                    @endforeach
                </ul>
            @endif
        </section>

        {{-- Detail album --}}
        <section id="detail" class="dash-panel" aria-labelledby="detail-title">
            <header class="dash-panel-header">
                <h2 id="detail-title">Detail album</h2>
                <p>Judul, kategori, dan tanggal tampil di kartu album di halaman Galeri.</p>
            </header>

            <form method="POST" action="{{ route('dashboard.galeri.albums.update', $album) }}" class="dash-item-form dash-grid-six">
                @csrf
                @method('PUT')
                <x-dashboard.field :bag="$bag" name="title" label="Judul album" :value="$album->title" required class="dash-span-3" />
                <x-dashboard.field :bag="$bag" name="category_id" label="Kategori" type="select" :options="$categoryOptions" :value="$album->category_id" />
                <x-dashboard.field :bag="$bag" name="date" label="Tanggal kegiatan" type="date" :value="$album->date?->toDateString()" />
                <x-dashboard.field :bag="$bag" name="status" label="Status" type="select" :options="$statusOptions" :value="$album->status->value" required />
                <x-dashboard.field :bag="$bag" name="location" label="Lokasi" :value="$album->location" class="dash-span-2" />
                <x-dashboard.field :bag="$bag" name="description" label="Keterangan" type="textarea" rows="2" :value="$album->description" class="dash-span-4" />
                <x-dashboard.item-actions :delete-form="'hapus-'.$bag" :label="'album “'.$album->title.'”'" class="dash-actions-row" />
            </form>
            <x-dashboard.delete-form :id="'hapus-'.$bag" :action="route('dashboard.galeri.albums.destroy', $album)"
                :label="'album “'.$album->title.'” beserta '.$photos->count().' fotonya'" />
        </section>
    </div>
@endsection
