<?php

namespace App\Utils;

class CPFUtil
{
    /**
     * Gera um número de CPF válido.
     *
     * @return string
     */
    public static function generate(): string
    {
        $cpf = '';

        for ($i = 0; $i < 9; $i++) {
            $cpf .= mt_rand(0, 9);
        }

        $sum = 0;
        for ($i = 0, $j = 10; $i < 9; $i++, $j--) {
            $sum += $cpf[$i] * $j;
        }
        $remainder = $sum % 11;
        $first_digit = ($remainder < 2) ? 0 : 11 - $remainder;

        $cpf .= $first_digit;

        $sum = 0;
        for ($i = 0, $j = 11; $i < 10; $i++, $j--) {
            $sum += $cpf[$i] * $j;
        }
        $remainder = $sum % 11;
        $second_digit = ($remainder < 2) ? 0 : 11 - $remainder;

        $cpf .= $second_digit;

        return $cpf;
    }
}
