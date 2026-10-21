<?php

namespace Tests\Feature;

use Tests\TestCase;

class PublicPagesTest extends TestCase
{
    public function test_all_public_pages_load_with_shared_navigation(): void
    {
        $pages = [
            '/' => 'Masjid Nurul Haqq',
            '/profil' => 'Rumah ibadah warga Griya Taman Asri',
            '/jadwal-kegiatan' => 'Ibadah dan kajian setiap pekan',
            '/program-tpq' => 'Tumbuh bersama Al-Qur\'an sejak dini',
            '/ziswaf' => 'Salurkan kebaikan, kuatkan kebersamaan',
            '/galeri' => 'Momen kebersamaan jamaah',
            '/kontak' => 'Hubungi pengurus masjid',
        ];

        foreach ($pages as $url => $heading) {
            $this->get($url)
                ->assertOk()
                ->assertSee($heading)
                ->assertSee('Jadwal &amp; Kegiatan', false)
                ->assertSee('aria-current="page"', false)
                ->assertSee(route('kontak'), false);
        }
    }
}
