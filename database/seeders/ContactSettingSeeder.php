<?php

namespace Database\Seeders;

use App\Models\ContactPhone;
use App\Models\ContactSetting;
use Illuminate\Database\Seeder;

/**
 * Kontak resmi masjid.
 *
 * Sumber: kop surat takmir ("KOP SURAT.pdf": alamat dan email), "Notulensi Rapat Jum'at, 30 Oktober 2026.pdf"
 * (nomor Pak Gatot, Pak Iswanto, dan koordinator ambulans), serta susunan pengurus 2025–2030 (jabatan).
 * Instagram, YouTube, dan jam layanan belum ada di dokumen, jadi dikosongkan dan bisa diisi dari dashboard.
 */
class ContactSettingSeeder extends Seeder
{
    /** Nomor contoh dari tahap desain yang diganti nomor asli. */
    private const DEMO_NUMBERS = ['0812-3456-7890', '0812-3956-7390'];

    /** Isian contoh dari tahap desain yang belum terkonfirmasi, dikosongkan bila belum diubah pengurus. */
    private const DEMO_VALUES = [
        'instagram' => 'masjidnurulhaqq',
        'service_hours' => 'Senin - Jumat, 09.00 - 15.00 WIB',
        'youtube_url' => 'https://youtube.com',
    ];

    public function run(): void
    {
        $contact = ContactSetting::current() ?? new ContactSetting;

        $contact->fill([
            'email' => 'takmirmnh22@gmail.com',
            'address' => 'Griya Taman Asri Blok FF 08-09 RT 32 RW 08, Tawangsari, Kec. Taman, Kabupaten Sidoarjo, Jawa Timur.',
            'maps_url' => 'https://maps.app.goo.gl/gHrzRV5846VvbFmH6',
            'maps_embed_url' => 'https://www.google.com/maps/embed?pb=!1m14!1m8!1m3!1d63317.13888036278!2d112.7141765!3d-7.3177718!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x2dd7e30053c2643f%3A0x4d32a9da57f07b9e!2sMasjid%20Nurul%20Haqq!5e0!3m2!1sid!2sid!4v1793539089201!5m2!1sid!2sid',
        ]);

        foreach (self::DEMO_VALUES as $field => $demoValue) {
            if ($contact->getAttribute($field) === $demoValue) {
                $contact->setAttribute($field, null);
            }
        }

        $contact->save();

        ContactPhone::query()->whereIn('number', self::DEMO_NUMBERS)->delete();

        $phones = [
            ['label' => 'Sekretaris Takmir (Pak Gatot Bambang)', 'number' => '0812-3028-185', 'is_whatsapp' => true],
            ['label' => 'Wakil Ketua Bidang Idaroh (Pak H. Iswanto)', 'number' => '0812-3058-294', 'is_whatsapp' => true],
            ['label' => 'Koordinator Ambulans (Mas Mukhlis Yulianto)', 'number' => '0822-3061-5186', 'is_whatsapp' => false],
            ['label' => 'Koordinator Ambulans (Mas Yopi Yacob Isaac)', 'number' => '0812-3588-189', 'is_whatsapp' => false],
        ];

        $sortOrder = 1;

        foreach ($phones as $phone) {
            ContactPhone::query()->updateOrCreate(
                ['number' => $phone['number']],
                [
                    'label' => $phone['label'],
                    'is_whatsapp' => $phone['is_whatsapp'],
                    'sort_order' => $sortOrder++,
                ],
            );
        }
    }
}
