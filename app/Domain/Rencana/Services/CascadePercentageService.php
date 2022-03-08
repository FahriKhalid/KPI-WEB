<?php
namespace App\Domain\Rencana\Services;

class CascadePercentageService
{
    /**
     * @param $totalPercentage
     * @return bool
     */
    public static function isValid($totalPercentage)
    {
        return $totalPercentage % 100 === 0;
    }

    /**
     * @param $storedPercentage
     * @param $addedPercentage
     * @return bool
     */
    public static function isAllowableToStore($storedPercentage, $addedPercentage)
    {
        $result = $storedPercentage + $addedPercentage;
        return $result <= 100;
    }
}
