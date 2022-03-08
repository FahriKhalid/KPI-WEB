<?php
namespace App\Domain\Realisasi\Services;

class ConvertionService
{
    /**
     * @param $persentase
     * @return int
     */
    public static function convert($persentase)
    {
        if ($persentase < 70) {
            return 0;
        } elseif ($persentase >= 70 && $persentase < 80) {
            return 1;
        } elseif ($persentase >= 80 && $persentase < 90) {
            return 2;
        } elseif ($persentase >= 90 && $persentase <= 100) {
            return 3;
        } else {
            return 4;
        }
    }
}
