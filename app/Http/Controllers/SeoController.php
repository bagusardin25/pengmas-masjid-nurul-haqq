<?php

namespace App\Http\Controllers;

use App\Models\Article;
use App\Models\GalleryAlbum;
use Illuminate\Http\Response;

/**
 * robots.txt dan sitemap.xml dibuat dinamis agar alamatnya selalu memakai domain tempat situs dipasang.
 */
class SeoController extends Controller
{
    public function robots(): Response
    {
        $lines = [
            'User-agent: *',
            'Disallow: /dashboard',
            'Disallow: /login',
            'Disallow: /profile',
            '',
            'Sitemap: '.route('sitemap'),
        ];

        return response(implode("\n", $lines)."\n", 200, ['Content-Type' => 'text/plain; charset=UTF-8']);
    }

    public function sitemap(): Response
    {
        $pages = collect([
            ['url' => route('home'), 'priority' => '1.0'],
            ['url' => route('profil'), 'priority' => '0.8'],
            ['url' => route('jadwal'), 'priority' => '0.9'],
            ['url' => route('tpq'), 'priority' => '0.8'],
            ['url' => route('ziswaf'), 'priority' => '0.8'],
            ['url' => route('ziswaf.laporan'), 'priority' => '0.7'],
            ['url' => route('galeri'), 'priority' => '0.7'],
            ['url' => route('kontak'), 'priority' => '0.7'],
        ]);

        $albums = GalleryAlbum::query()->published()->has('photos')->get()
            ->map(fn (GalleryAlbum $album) => [
                'url' => route('galeri.album', $album),
                'lastmod' => $album->updated_at?->toAtomString(),
                'priority' => '0.5',
            ]);

        $articles = Article::query()->published()->get()
            ->map(fn (Article $article) => [
                'url' => route('galeri.artikel', $article),
                'lastmod' => $article->updated_at?->toAtomString(),
                'priority' => '0.6',
            ]);

        return response()
            ->view('seo.sitemap', ['entries' => $pages->concat($albums)->concat($articles)])
            ->header('Content-Type', 'application/xml; charset=UTF-8');
    }
}
