<?php
namespace App\Domain\Rencana\Services;

/**
 * DEPRECATED
 * Class PercentageCascadeService
 *
 * @package App\Domain\Rencana\Services
 */
class PercentageCascadeService
{
    /**
     * @param $target
     * @param $percentage
     * @return float|int
     */
    public function countPercentage($target, $percentage)
    {
        return ($percentage / 100) * $target;
    }
}
