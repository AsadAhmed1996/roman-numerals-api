<?php

namespace App\Services;

class RomanNumeralConverter implements IntegerConverterInterface
{
    private const array MAPPING = [
        1000 => 'M',
        900 => 'CM',
        500 => 'D',
        400 => 'CD',
        100 => 'C',
        90 => 'XC',
        50 => 'L',
        40 => 'XL',
        10 => 'X',
        9 => 'IX',
        5 => 'V',
        4 => 'IV',
        1 => 'I'
    ];

    public function convertInteger(int $integer): string
    {
        $roman = '';
        foreach (self::MAPPING as $value => $symbol) {
            while ($integer >= $value) {
                $roman .= $symbol;
                $integer -= $value;
            }
        }

        return $roman;
    }
}
