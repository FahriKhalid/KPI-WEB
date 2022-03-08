<?php
/**
 * Created by PhpStorm.
 * User: SENA
 * Date: 08/03/2017
 * Time: 09:34 AM
 */

namespace App\ApplicationServices\RealisasiKPI;

use Carbon\Carbon;
use App\ApplicationServices\ApplicationService;
use App\Infrastructures\Repositories\KPI\RealisasiKPIRepository;

class UpdateDetailRealisasiService extends ApplicationService
{
    /**
     * @var RealisasiKPIRepository
     */
    protected $realisasiKPIRepository;

    /**
     * UpdateDetailRealisasiService constructor.
     *
     * @param RealisasiKPIRepository $realisasiKPIRepository
     */
    public function __construct(RealisasiKPIRepository $realisasiKPIRepository)
    {
        $this->realisasiKPIRepository = $realisasiKPIRepository;
    }

    /**
     * @param $id
     * @param array $data
     * @param $user
     * @return array
     */
    public function call($id, array $data, $user)
    {
        try {
            $detailRealisasi = $this->realisasiKPIRepository->findDetailByID($id);
            $detailRealisasi->IDKPIRealisasiHeader = $data['IDKPIRealisasiHeader'];
            $detailRealisasi->IDPeriodeKPI = $data['IDPeriodeKPI'];
            $detailRealisasi->Realisasi = $data['Realisasi'];
            $detailRealisasi->KonversiNilai = $data['KonversiNilai'];
            $detailRealisasi->Keterangan = (! empty($data['Keterangan'])) ? $data['Keterangan'] : null;
            $detailRealisasi->UpdatedBy = $user->NPK;
            $detailRealisasi->UpdatedOn = Carbon::now('Asia/Jakarta');
            $detailRealisasi->save();
            return $this->successResponse();
        } catch (\Exception $e) {
            return $this->errorResponse($e->getMessage());
        }
    }
}
