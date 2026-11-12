<?php

namespace App\Http\Controllers;

use App\Models\TpqDocumentation;
use App\Models\TpqFaq;
use App\Models\TpqPage;
use App\Models\TpqPillar;

class TpqController extends Controller
{
    public function index()
    {
        return view('pages.tpq', [
            'tpq' => TpqPage::current(),
            'pillars' => TpqPillar::query()->ordered()->get(),
            'faqs' => TpqFaq::query()->ordered()->get(),
            'documentations' => TpqDocumentation::query()->with('image')->ordered()->get(),
        ]);
    }
}
