<?php
namespace App\Domain\Realisasi\Services;

class CalculateValidasiService
{
    /**
     * @param $nilaiAkhir
     * @param $avgValidasi
     * @return float
     */
    public static function calculateValidasi($nilaiAkhir, $avgValidasi)
    {
        $result = ($avgValidasi / 100) * $nilaiAkhir;
        return round($result, 2, PHP_ROUND_HALF_DOWN);
    }
}
