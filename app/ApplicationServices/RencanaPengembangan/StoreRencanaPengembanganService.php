<?php
namespace App\ApplicationServices\RencanaPengembangan;

use Illuminate\Support\Facades\DB;
use App\ApplicationServices\ApplicationService;
use App\Domain\Realisasi\Entities\DetilRealisasiKPI;
use App\Infrastructures\Repositories\KPI\RealisasiKPIRepository;

class StoreRencanaPengembanganService extends ApplicationService
{
    /**
     * @var RealisasiKPIRepository
     */
    protected $realisasiKPIRepository;

    /**
     * StoreRencanaPengembanganService constructor.
     *
     * @param RealisasiKPIRepository $realisasiKPIRepository
     */
    public function __construct(RealisasiKPIRepository $realisasiKPIRepository)
    {
        $this->realisasiKPIRepository = $realisasiKPIRepository;
    }

    /**
     * @param array $data
     * @return array
     */
    public function call(array $data)
    {
        try {
            DB::beginTransaction();
            foreach ($data['iddetailrealisasi'] as $key => $idDetailRealisasi) {
                $detil = $this->realisasiKPIRepository->findDetailById($idDetailRealisasi);
                $detil->IDRencanaPengembangan = ($data['IDRencanaPengembangan'][$key] != 'empty') ? $data['IDRencanaPengembangan'][$key] : null;
                if ($data['IDRencanaPengembangan'][$key] == 'empty' && $detil->RencanaPengembangan != null) {
                    $detil->RencanaPengembangan = null;
                } else {
                    $detil->RencanaPengembangan = (! empty($data['RencanaPengembangan'][$key])) ? $data['RencanaPengembangan'][$key] : null;
                }
                $detil->save();
            }
            DB::commit();
            return $this->successResponse();
        } catch (\Exception $e) {
            DB::rollback();
            return $this->errorResponse($e->getMessage());
        }
    }
}
