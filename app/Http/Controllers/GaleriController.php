<?php

namespace App\Http\Controllers;

use App\Enums\PublicationStatus;
use App\Models\Article;
use App\Models\GalleryAlbum;
use App\Models\GalleryCategory;
use App\Models\GalleryVideo;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Illuminate\View\View;

class GaleriController extends Controller
{
    /** Jumlah album/video/artikel yang tampil pertama kali dan yang ditambahkan tiap klik "Muat lebih banyak". */
    private const ALBUMS_STEP = 4;

    private const VIDEOS_STEP = 6;

    private const ARTICLES_STEP = 4;

    /** Batas atas agar ?video=999999 tidak memuat semua data sekaligus. */
    private const MAX_SHOWN = 300;

    public function index(Request $request): View|JsonResponse
    {
        // Hanya kategori yang punya album atau video terbit yang tampil sebagai filter.
        $categories = GalleryCategory::query()
            ->where(fn (Builder $query) => $query
                ->whereHas('albums', fn (Builder $albums) => $albums->published()->has('photos'))
                ->orWhereHas('videos', fn (Builder $videos) => $videos->published()))
            ->ordered()
            ->get();

        $activeCategory = $categories->firstWhere('slug', self::stringQuery($request, 'kategori'));
        $inCategory = fn (Builder $query) => $query->when(
            $activeCategory,
            fn (Builder $query) => $query->where('category_id', $activeCategory->id),
        );

        // Album tanpa foto tidak ditampilkan agar tidak ada kartu kosong.
        $albumQuery = GalleryAlbum::query()->published()->has('photos')->tap($inCategory)->withCardData()->latestFirst();
        $videoQuery = GalleryVideo::query()->published()->tap($inCategory)->with('category')->latestFirst();
        $articleQuery = Article::query()->published()->with('coverImage')->latestFirst();

        // Tombol "Muat lebih banyak" meminta potongan berikutnya saja (lihat loadMore di app.js).
        $partial = self::stringQuery($request, 'partial');
        if ($partial === 'album') {
            return $this->moreItems($request, $albumQuery, 'album', self::ALBUMS_STEP, 'pages.partials.album-card', 'album');
        }
        if ($partial === 'video') {
            return $this->moreItems($request, $videoQuery, 'video', self::VIDEOS_STEP, 'pages.partials.video-card', 'video');
        }
        if ($partial === 'artikel') {
            return $this->moreItems($request, $articleQuery, 'artikel', self::ARTICLES_STEP, 'pages.partials.article-card', 'article');
        }

        [$albums, $moreAlbumsUrl] = $this->firstItems($request, $albumQuery, 'album', self::ALBUMS_STEP);
        [$videos, $moreVideosUrl] = $this->firstItems($request, $videoQuery, 'video', self::VIDEOS_STEP);
        [$articles, $moreArticlesUrl] = $this->firstItems($request, $articleQuery, 'artikel', self::ARTICLES_STEP);

        return view('pages.galeri', compact(
            'categories', 'activeCategory', 'albums', 'videos', 'articles', 'moreAlbumsUrl', 'moreVideosUrl', 'moreArticlesUrl',
        ));
    }

    /**
     * Item yang tampil saat halaman dibuka: sebanyak ?album=N / ?video=N / ?artikel=N (bawaan satu langkah).
     * Tautan "Muat lebih banyak" menambah satu langkah, jadi tetap berfungsi tanpa JavaScript.
     *
     * @return array{0: Collection, 1: ?string}
     */
    private function firstItems(Request $request, Builder $query, string $param, int $step): array
    {
        $shown = min(self::MAX_SHOWN, max($step, (int) self::stringQuery($request, $param)));
        $items = $query->limit($shown + 1)->get();
        $hasMore = $items->count() > $shown;

        return [$items->take($shown), $hasMore ? $this->moreUrl($request, $param, $shown + $step) : null];
    }

    /**
     * Potongan berikutnya untuk tombol "Muat lebih banyak": HTML kartu baru dan tautan untuk klik berikutnya.
     */
    private function moreItems(Request $request, Builder $query, string $param, int $step, string $view, string $variable): JsonResponse
    {
        $offset = min(self::MAX_SHOWN, max(0, (int) self::stringQuery($request, 'dari')));
        $items = $query->offset($offset)->limit($step + 1)->get();
        $hasMore = $items->count() > $step && $offset + $step < self::MAX_SHOWN;

        $html = $items->take($step)->map(fn ($item) => view($view, [$variable => $item])->render())->implode('');
        $shown = $offset + min($step, $items->count());

        return response()->json([
            'html' => $html,
            'count' => min($step, $items->count()),
            'next' => $hasMore ? $this->moreUrl($request, $param, $shown + $step) : null,
            'url' => $this->moreUrl($request, $param, $shown),
        ])->header('Cache-Control', 'no-store');
    }

    private function moreUrl(Request $request, string $param, int $shown): string
    {
        $query = collect($request->query())
            ->except(['partial', 'dari'])
            ->filter(fn ($value) => is_string($value))
            ->put($param, (string) $shown)
            ->all();

        return route('galeri').'?'.http_build_query($query).'#'.$param;
    }

    /**
     * Nilai parameter URL sebagai teks. Parameter yang dikirim sebagai array (?kategori[]=x) diabaikan,
     * agar tidak memicu error "Array to string conversion".
     */
    private static function stringQuery(Request $request, string $key): string
    {
        $value = $request->query($key);

        return is_string($value) ? $value : '';
    }

    public function album(GalleryAlbum $album): View
    {
        abort_unless($album->status === PublicationStatus::Published, 404);

        $album->load(['category', 'photos.image']);

        $otherAlbums = GalleryAlbum::query()
            ->published()
            ->whereKeyNot($album->id)
            ->has('photos')
            ->withCardData()
            ->latestFirst()
            ->limit(3)
            ->get();

        return view('pages.galeri-album', compact('album', 'otherAlbums'));
    }

    public function article(Article $article): View
    {
        abort_unless($article->status === PublicationStatus::Published, 404);

        $article->load('coverImage');

        $otherArticles = Article::query()
            ->published()
            ->whereKeyNot($article->id)
            ->latestFirst()
            ->limit(3)
            ->get();

        return view('pages.galeri-artikel', compact('article', 'otherArticles'));
    }
}
