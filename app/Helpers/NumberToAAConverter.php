<?php

namespace App\Helpers;


class NumberToAAConverter
{
    public static function format($number)
    {
        $number = $number + 26;
        $number = intval($number);
        if ($number <= 0) {
            return '';
        }
        $alphabet = '';
        while ($number != 0) {
            $p = ($number - 1) % 26;
            $number = intval(($number - $p) / 26);
            $alphabet = chr(65 + $p) . $alphabet;
        }
        return $alphabet;
    }
}
