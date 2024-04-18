<?php

namespace App\Utils;

class CNPJUtil
{
    /**
     * Gera um número de CNPJ válido.
     *
     * @return string
     */
    public static function generate(): string
    {
        $cnpj = '';

        for ($i = 0; $i < 12; $i++) {
            $cnpj .= rand(0, 9);
        }

        $cnpj .= self::generateVerifyingDigits($cnpj);

        return $cnpj;
    }

    /**
     * Gera os dois dígitos verificadores do CNPJ.
     *
     * @param string $cnpjOs 12 primeiros dígitos do CNPJ
     * @return string
     */
    private static function generateVerifyingDigits(string $cnpjOs): string
    {
        $sum = 0;
        $weights = [5, 4, 3, 2, 9, 8, 7, 6, 5, 4, 3, 2];

        for ($i = 0; $i < 12; $i++) {
            $sum += $cnpjOs[$i] * $weights[$i];
        }
        $firstDigit = ($sum % 11 < 2) ? 0 : (11 - $sum % 11);

        $cnpjOs .= $firstDigit;

        $sum = 0;
        $weights = [6, 5, 4, 3, 2, 9, 8, 7, 6, 5, 4, 3, 2];

        for ($i = 0; $i < 13; $i++) {
            $sum += $cnpjOs[$i] * $weights[$i];
        }
        $secondDigit = ($sum % 11 < 2) ? 0 : (11 - $sum % 11);

        return $firstDigit . $secondDigit;
    }
}
