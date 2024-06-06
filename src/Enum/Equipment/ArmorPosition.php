<?php

namespace App\Enum\Equipment;

enum ArmorPosition: string
{
    case head = 'head';
    case neck = 'neck';
    case chest = 'chest';
    case leftWrist = 'leftWrist';
    case rightWrist = 'rightWrist';
    case legs = 'legs';
    case feet = 'feet';
    case leftRing = 'leftRing';
    case rightRing = 'rightRing';

    /** @return string[] */
    public static function values(): array
    {
        return array_column(static::cases(), 'value');
    }
}
