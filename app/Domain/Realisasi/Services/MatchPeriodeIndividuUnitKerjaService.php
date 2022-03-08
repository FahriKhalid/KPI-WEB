<?php
namespace App\Domain\Realisasi\Services;

class MatchPeriodeIndividuUnitKerjaService
{
    /**
     * @param $idJenisPeriodeIndividu
     * @param $idJenisPeriodeUnitKerja
     * @return bool
     */
    public static function match($idJenisPeriodeIndividu, $idJenisPeriodeUnitKerja)
    {
        if (
        ($idJenisPeriodeIndividu == 1 && $idJenisPeriodeUnitKerja == 7) ||
        ($idJenisPeriodeIndividu == 2 && $idJenisPeriodeUnitKerja == 5) ||
        ($idJenisPeriodeIndividu == 3 && $idJenisPeriodeUnitKerja == 7) ||
        ($idJenisPeriodeIndividu == 10 && $idJenisPeriodeUnitKerja == 4) ||
        ($idJenisPeriodeIndividu == 13 && $idJenisPeriodeUnitKerja == 5) ||
        ($idJenisPeriodeIndividu == 16 && $idJenisPeriodeUnitKerja == 6) ||
        ($idJenisPeriodeIndividu == 19 && $idJenisPeriodeUnitKerja == 7)
        ) {
            return true;
        }
        return false;
    }

    /**
     * @param $periodeAktifCollection
     * @param $triwulanPeriodeCollection
     * @return array
     */
    public static function mapping($periodeAktifCollection, $triwulanPeriodeCollection)
    {
        $arr = [];
        foreach ($triwulanPeriodeCollection as $triwulanPeriode) {
            foreach ($periodeAktifCollection as $periodeAktif) {
                if (! self::match($periodeAktif->IDJenisPeriode, $triwulanPeriode->ID)) {
                    $arr[] = $triwulanPeriode;
                }
            }
        }
        return $arr;
    }
}
