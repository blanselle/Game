<?php

namespace App\Enum\Equipment;

enum ArmorType: string
{
    case heavy = 'heavy';
    case lightweight = 'lightweight';

    /** @return string[] */
    public static function values(): array
    {
        return array_column(static::cases(), 'value');
    }
}
