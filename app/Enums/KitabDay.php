<?php

namespace App\Enums;

enum KitabDay: string
{
    case Senin = 'senin';
    case Selasa = 'selasa';
    case Rabu = 'rabu';
    case Kamis = 'kamis';
    case Jumat = 'jumat';
    case Sabtu = 'sabtu';
    case Ahad = 'ahad';

    public function label(): string
    {
        return match ($this) {
            self::Senin => 'Senin',
            self::Selasa => 'Selasa',
            self::Rabu => 'Rabu',
            self::Kamis => 'Kamis',
            self::Jumat => 'Jumat',
            self::Sabtu => 'Sabtu',
            self::Ahad => 'Ahad',
        };
    }
}
