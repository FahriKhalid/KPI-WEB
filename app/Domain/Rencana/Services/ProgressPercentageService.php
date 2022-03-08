<?php
namespace App\Domain\Rencana\Services;

class ProgressPercentageService
{
    /**
     * Calculate progress & percentage rencana karyawan
     *
     * @param $totalKaryawan
     * @param $collected
     * @return array
     */
    public static function calculateProgressRencanaKaryawan($totalKaryawan, $collected)
    {
        $uncollected = $totalKaryawan - $collected;
        $progressPercentage = calculatePercentage($collected, $totalKaryawan);
        return ['uncollected' => $uncollected, 'progressPercentage' => number_format($progressPercentage, 2)];
    }
}
