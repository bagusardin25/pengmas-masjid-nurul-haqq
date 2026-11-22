<?php

namespace App\Http\Controllers\Dashboard;

use App\Enums\PublicationStatus;
use App\Http\Controllers\Controller;
use App\Models\Article;
use App\Models\GalleryAlbum;
use App\Models\GalleryCategory;
use App\Models\GalleryVideo;
use Illuminate\View\View;

class GaleriController extends Controller
{
    public function edit(): View
    {
        return view('dashboard.galeri', [
            'categories' => GalleryCategory::query()->withCount(['albums', 'videos'])->ordered()->get(),
            'albums' => GalleryAlbum::query()->withCardData()->latestFirst()->get(),
            'videos' => GalleryVideo::query()->latestFirst()->get(),
            'articles' => Article::query()->with('coverImage')->latestFirst()->get(),
            'statuses' => PublicationStatus::cases(),
        ]);
    }
}
