<?php

namespace App\Http\Controllers;

use App\Enums\PublicationStatus;
use App\Models\Article;
use App\Models\GalleryAlbum;
use App\Models\GalleryCategory;
use App\Models\GalleryVideo;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\View\View;

class GaleriController extends Controller
{
    private const ALBUMS_PER_PAGE = 9;

    private const VIDEOS_SHOWN = 6;

    private const ARTICLES_SHOWN = 4;

    public function index(Request $request): View
    {
        // Hanya kategori yang punya album atau video terbit yang tampil sebagai filter.
        $categories = GalleryCategory::query()
            ->where(fn (Builder $query) => $query
                ->whereHas('albums', fn (Builder $albums) => $albums->published()->has('photos'))
                ->orWhereHas('videos', fn (Builder $videos) => $videos->published()))
            ->ordered()
            ->get();

        $activeCategory = $categories->firstWhere('slug', (string) $request->query('kategori'));
        $inCategory = fn (Builder $query) => $query->when(
            $activeCategory,
            fn (Builder $query) => $query->where('category_id', $activeCategory->id),
        );

        // Album tanpa foto tidak ditampilkan agar tidak ada kartu kosong.
        $albums = GalleryAlbum::query()
            ->published()
            ->has('photos')
            ->tap($inCategory)
            ->withCardData()
            ->latestFirst()
            ->paginate(self::ALBUMS_PER_PAGE, pageName: 'halaman')
            ->withQueryString()
            ->fragment('album');

        $videos = GalleryVideo::query()
            ->published()
            ->tap($inCategory)
            ->with('category')
            ->latestFirst()
            ->limit(self::VIDEOS_SHOWN)
            ->get();

        $articles = Article::query()
            ->published()
            ->with('coverImage')
            ->latestFirst()
            ->limit(self::ARTICLES_SHOWN)
            ->get();

        return view('pages.galeri', compact('categories', 'activeCategory', 'albums', 'videos', 'articles'));
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
