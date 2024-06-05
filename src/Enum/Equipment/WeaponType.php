<?php

namespace App\Enum\Equipment;

enum WeaponType: string
{
    case OneHand = 'one_hand';
    case TwoHand = 'two_hands';
    case Shield = 'shield';

    /** @return string[] */
    public static function values(): array
    {
        return array_column(static::cases(), 'value');
    }
}
