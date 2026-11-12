<?php

namespace App\Support;

class WhatsApp
{
    /**
     * Tautan wa.me dari nomor telepon Indonesia, mis. "0812-3956-7390" → https://wa.me/6281239567390.
     */
    public static function url(?string $phone): ?string
    {
        $digits = preg_replace('/\D+/', '', (string) $phone);

        if ($digits === '') {
            return null;
        }

        if (str_starts_with($digits, '0')) {
            $digits = '62'.substr($digits, 1);
        }

        return 'https://wa.me/'.$digits;
    }
}
