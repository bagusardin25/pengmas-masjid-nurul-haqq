<?php

namespace App\Http\Controllers;

use App\Models\AboutPage;
use App\Models\AboutRole;
use App\Models\AboutStat;
use App\Models\Facility;
use App\Models\OrganizationMember;

class ProfilController extends Controller
{
    public function index()
    {
        return view('pages.profil', [
            'about' => AboutPage::current(),
            'stats' => AboutStat::query()->ordered()->get(),
            'roles' => AboutRole::query()->ordered()->get(),
            'facilities' => Facility::query()->where('is_active', true)->ordered()->get(),
            'members' => OrganizationMember::query()->where('is_active', true)->ordered()->get(),
        ]);
    }
}
