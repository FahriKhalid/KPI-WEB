<?php

namespace App\ApplicationServices\RealisasiKPI;

use Carbon\Carbon;
use App\ApplicationServices\ApplicationService;
use App\Domain\Realisasi\Entities\DetilRealisasiKPI;
use Ramsey\Uuid\Uuid;

class StoreDetailRealisasiKPIService extends ApplicationService
{
    /**
     * @param array $data
     * @param $user
     * @return array
     */
    public function call(array $data, $user)
    { 
        try {
            $realisasiDetail = new DetilRealisasiKPI();
            $realisasiDetail->ID = Uuid::uuid4();
            $realisasiDetail->IDKPIRealisasiHeader = $data['IDKPIRealisasiHeader'];
            $realisasiDetail->IDPeriodeKPI = $data['IDPeriodeKPI'];
            $realisasiDetail->Realisasi = $data['Realisasi'];
            $realisasiDetail->KonversiNilai = $data['KonversiNilai'];
            $realisasiDetail->Keterangan = (! empty($data['Keterangan'])) ? $data['Keterangan'] : null;
            $realisasiDetail->CreatedBy = $user->NPK;
            $realisasiDetail->CreatedOn = Carbon::now('Asia/Jakarta');
            $realisasiDetail->save();
            return $this->successResponse();
        } catch (\Exception $e) {
            return $this->errorResponse($e->getMessage());
        }
    }
}
