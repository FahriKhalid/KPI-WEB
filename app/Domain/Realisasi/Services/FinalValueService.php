<?php
namespace App\Domain\Realisasi\Services;

class FinalValueService
{
    /**
     * @param $convertion
     * @param $bobot
     * @return float
     */
    public static function calculate($convertion, $bobot)
    {
        $result = ($convertion * $bobot) / 100;
        return round($result, 2, PHP_ROUND_HALF_DOWN);
    }

    /**
     * @param array $values
     * @return float
     */
    public static function calculateAverage(array $values)
    {
        $sums = array_sum($values);
        $average = $sums / count($values);
        return round($average, 2, PHP_ROUND_HALF_DOWN);
    }

    /**
     * @param array $values
     * @return float|int
     */
    public static function calculateTotalValue(array $values)
    {
        return array_sum($values);
    }

    /**
     * @param array $valuesTotal
     * @param array $valuesTaskForce
     * @return float|int
     */
    public static function calculateTotalValueNonTaskForce(array $valuesTotal, array $valuesTaskForce)
    {
        return self::calculateTotalValue($valuesTotal) - array_sum($valuesTaskForce);
    }
}
