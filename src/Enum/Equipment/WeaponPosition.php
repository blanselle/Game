<?php

namespace App\Enum\Equipment;

enum WeaponPosition: string
{
    case rightHand = 'right_hand';
    case leftHand = 'left_hand';
    case twoHands = 'two_hands';

    /** @return string[] */
    public static function values(): array
    {
        return array_column(static::cases(), 'value');
    }
}
