<?php

namespace App\View\Components;

use Illuminate\View\Component;
use Illuminate\View\View;

/**
 * Tata letak halaman masuk dan halaman akun tamu lainnya (lupa password, verifikasi).
 */
class GuestLayout extends Component
{
    public function __construct(public string $title = 'Masuk') {}

    public function render(): View
    {
        return view('layouts.guest');
    }
}
