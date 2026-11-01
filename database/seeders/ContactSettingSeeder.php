<?php

namespace Database\Seeders;

use App\Models\ContactPhone;
use App\Models\ContactSetting;
use Illuminate\Database\Seeder;

class ContactSettingSeeder extends Seeder
{
    public function run(): void
    {
        (ContactSetting::current() ?? new ContactSetting)->fill([
            'email' => 'masjidnurulhaqq@gmail.com',
            'instagram' => 'masjidnurulhaqq',
            'address' => 'Griya Taman Asri Blok FF 08-09, Tawangsari, Kec. Taman, Kabupaten Sidoarjo, Jawa Timur.',
            'maps_url' => 'https://maps.app.goo.gl/gHrzRV5846VvbFmH6',
            'maps_embed_url' => 'https://www.google.com/maps/embed?pb=!1m14!1m8!1m3!1d63317.13888036278!2d112.7141765!3d-7.3177718!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x2dd7e30053c2643f%3A0x4d32a9da57f07b9e!2sMasjid%20Nurul%20Haqq!5e0!3m2!1sid!2sid!4v1793539089201!5m2!1sid!2sid',
            'service_hours' => 'Senin - Jumat, 09.00 - 15.00 WIB',
            'youtube_url' => 'https://youtube.com',
        ])->save();

        $phones = [
            ['label' => 'Kontak takmir', 'number' => '0812-3456-7890', 'is_whatsapp' => false],
            ['label' => 'Sekretariat / WhatsApp', 'number' => '0812-3956-7390', 'is_whatsapp' => true],
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
