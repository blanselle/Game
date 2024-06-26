<?php

namespace App\Enum\Equipment;

enum WeaponType: string
{
    case oneHand = 'one_hand';
    case twoHands = 'two_hands';
    case shield = 'shield';

    /** @return string[] */
    public static function values(): array
    {
        return array_column(static::cases(), 'value');
    }
}
