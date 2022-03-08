<?php
/**
 * Created by PhpStorm.
 * User: SENA
 * Date: 09/30/2017
 * Time: 10:33 PM
 */

namespace App\Domain\Realisasi\Services;

use App\Domain\Realisasi\Entities\HeaderRealisasiKPI;
use App\Domain\Rencana\Services\TargetParserService;
use App\Domain\Rencana\Services\TargetPeriodeService;

class GapValueCalculationService
{
    /**
     * @param $target
     * @param $realization
     * @param string $persentaseRealisasi
     * @return int
     */
    public static function calculate($target, $realization, $persentaseRealisasi = 'HIB')
    {
        $result = (int)($target - $realization);
        if ($persentaseRealisasi == 'LOB') {
            $result *= (-1);
        }
        return $result;
    }

    /**
     * @param $idheaderrealisasi
     * @return mixed
     */
    public static function calculatetotal($idheaderrealisasi)
    {
        $header = HeaderRealisasiKPI::find($idheaderrealisasi);
        $count = HeaderRealisasiKPI::where('NPK', $header->NPK)->where('Tahun', $header->Tahun)->select('ID')->whereNotIn('IDStatusDokumen', [5])->count();
        ;
        $raw = null;
        $targetPeriodeService = new TargetPeriodeService;
        $targetParserService = new TargetParserService;
        $target = $targetPeriodeService->periodeID($header->periodeaktif->IDJenisPeriode, $header->headerrencanakpi->IsKPIUnitKerja)->getTargetCount();
        $targetRealization = $targetParserService->targetCount($target)[$count -1];
        foreach ($header->detail()->get() as $detail) {
            $raw += self::calculate($detail->detilrencana->{'Target'.$targetRealization}, $detail->Realisasi);
        }
        return (int)$raw;
    }
}
