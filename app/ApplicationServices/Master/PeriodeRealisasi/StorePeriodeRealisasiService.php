<?php
namespace App\ApplicationServices\Master\PeriodeRealisasi;

use Carbon\Carbon;
use App\ApplicationServices\ApplicationService;
use App\Domain\KPI\Entities\PeriodeRealisasi;
use App\Domain\User\Entities\User;
use Ramsey\Uuid\Uuid;

class StorePeriodeRealisasiService extends ApplicationService
{
    /**
     * @param array $data
     * @param User $user
     * @return array
     */
    public function call(array $data, User $user)
    {
        try {
            // dd($data['IDJenisPeriode']);
            foreach ($data['IDJenisPeriode'] as $value) {
                $perioderealisasi = new PeriodeRealisasi();
                $perioderealisasi->Tahun = $data['Tahun'];
                $perioderealisasi->IDJenisPeriode = $value;
                $perioderealisasi->save();
            }
            return $this->successResponse();
        } catch (\Exception $e) {
            return $this->errorResponse($e->getMessage());
        }
    }
}
