<?php
namespace App\Domain\Rencana\Services;

/**
 * Class TargetParserService
 * Parsing count of target to month count association
 *
 * @package App\Domain\Rencana\Services
 */
class TargetParserService
{
    /**
     * @param $count
     * @return array
     */
    public function targetCount($count)
    {
        if ($count === 1) {
            return [12];
        } elseif ($count === 2) {
            return [6, 12];
        } elseif ($count === 4) {
            return [3, 6, 9, 12];
        } else {
            return [1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12];
        }
    }
}
