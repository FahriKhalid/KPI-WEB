<?php
namespace App\Domain\KPI\Services;

use App\Domain\KPI\Entities\PeriodeAktif;

class PeriodeAktifFactoryService
{
    /**
     * @param PeriodeAktif|null $periodeAktif
     * @return false|mixed|string
     */
    public static function getTahunRencana(PeriodeAktif $periodeAktif = null)
    {
        if ($periodeAktif !== null) {
            return $periodeAktif->Tahun;
        }
        return date('Y');
    }
}
