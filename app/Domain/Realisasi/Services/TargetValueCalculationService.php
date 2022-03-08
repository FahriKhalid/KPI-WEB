<?php
namespace App\Domain\Realisasi\Services;

class TargetValueCalculationService
{
    /**
     * @param $target
     * @param $realization
     * @param int $idPersentaseRealisasi
     * @return float
     */
    public static function calculate($target, $realization, $idPersentaseRealisasi = 1)
    {
        $result = 0.00;
        if ($idPersentaseRealisasi == 1) { // higher better
            if ($target == 0) {
                if ($realization == 0) {
                    $result = 100;
                }
            } else {
                if (is_numeric($realization)) {
                    if ($realization >= 0) {
                        $result = ($realization / $target) * 100;
                    }
                }
            }
        } else { // lower better
            if ($target == 0) {
                if ($realization == 0) {
                    $result = 100.00;
                }
            } else {
                if (is_numeric($realization)) {
                    if ($realization >= 0) {
                        $result = (1 + (($target - $realization) / $target)) * 100;
                        if ($result < 0) {
                            $result = 0.00;
                        }
                    }
                }
            }
        }
        return round($result, 2, PHP_ROUND_HALF_DOWN);
    }

    /**
     * @param $target
     * @param $percentage
     * @param int $idPersentaseRealisasi
     * @return float
     */
    public static function reverseCalculate($target, $percentage, $idPersentaseRealisasi = 1)
    {
        if ($idPersentaseRealisasi == 1) {
            $result = ($percentage / 100) * $target;
        } else {
            $result = (1 + (($target - $percentage) / $target)) * 100;
        }
        return round($result, 2, PHP_ROUND_HALF_DOWN);
    }
}
