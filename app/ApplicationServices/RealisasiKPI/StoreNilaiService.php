<?php
namespace App\ApplicationServices\RealisasiKPI;

use Illuminate\Support\Facades\DB;
use App\ApplicationServices\ApplicationService;
use App\Domain\Realisasi\Services\ConvertionService;
use App\Domain\Realisasi\Services\FinalValueService;
use App\Domain\Realisasi\Services\TargetValueCalculationService;
use App\Infrastructures\Repositories\KPI\RealisasiKPIRepository;

class StoreNilaiService extends ApplicationService
{
    /**
     * @var RealisasiKPIRepository
     */
    protected $realisasiKPIRepository;

    /**
     * RegisterRealisasiKPIService constructor.
     *
     * @param RealisasiKPIRepository $realisasiKPIRepository
     */
    public function __construct(RealisasiKPIRepository $realisasiKPIRepository)
    {
        $this->realisasiKPIRepository = $realisasiKPIRepository;
    }

    /**
     * @param array $data
     * @param $user
     * @return array
     */
    public function call($headerRealisasiID, array $data, $user)
    {
        try {
            DB::beginTransaction();
            $counter = count($data['idrealisasiitem']);
            $arrKPIValues = [];
            $arrKPITaskForces = [];
            for ($i = 0; $i < $counter; $i++) {
                $item = $this->realisasiKPIRepository->findDetailById($data['idrealisasiitem'][$i]);
                $item->Realisasi = $data['realization'][$i];

                // calculate percentage
                $percentage = TargetValueCalculationService::calculate($data['target'][$i], $data['realization'][$i], $data['IDPersentaseRealisasi'][$i]);
                $convertion = ConvertionService::convert($percentage);
                $nilaiAkhir = FinalValueService::calculate($convertion, $data['bobot'][$i]);
                $arrKPIValues[] = $nilaiAkhir;
                if ($data['idaspekkpi'][$i] == 4) {
                    $arrKPITaskForces[] = $nilaiAkhir;
                }
                $item->PersentaseRealisasi = $percentage;
                $item->KonversiNilai = $convertion;
                $item->NilaiAkhir = $nilaiAkhir;
                $item->save();
            }

            $header = $this->realisasiKPIRepository->findById($headerRealisasiID);
            $header->NilaiAkhir = FinalValueService::calculateTotalValue($arrKPIValues);
            $header->NilaiAkhirNonTaskForce = FinalValueService::calculateTotalValueNonTaskForce($arrKPIValues, $arrKPITaskForces);
            $header->save();
            DB::commit();
            return $this->successResponse();
        } catch (\Exception $e) {
            DB::rollback();
            return $this->errorResponse($e->getMessage());
        }
    }
}
