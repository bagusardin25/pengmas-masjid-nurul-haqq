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
            'maps_url' => 'https://maps.google.com/?q=Masjid+Jami+Nurul+Haqq+Sidoarjo',
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
