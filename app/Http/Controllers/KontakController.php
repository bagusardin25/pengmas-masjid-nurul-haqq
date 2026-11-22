<?php

namespace App\Http\Controllers;

use Illuminate\View\View;

class KontakController extends Controller
{
    /**
     * Data kontak ($siteContact) dibagikan ke semua halaman publik lewat AppServiceProvider.
     */
    public function index(): View
    {
        return view('pages.kontak');
    }
}
