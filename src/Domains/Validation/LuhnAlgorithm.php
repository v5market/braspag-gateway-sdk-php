<?php

namespace Braspag\Gateway\Domains\Validation;

class LuhnAlgorithm
{
    public static function check($cardNumber): bool
    {
        $cardNumber = preg_replace('/\D/', '', $cardNumber);
        $cardNumberSplited = array_reverse(str_split($cardNumber));
        $hash = '';

        foreach ($cardNumberSplited as $k => $v) {
            $hash .= ($k % 2 !== 0) ? $v * 2 : $v;
        }

        return array_sum(str_split($hash)) % 10 === 0;
    }
}
