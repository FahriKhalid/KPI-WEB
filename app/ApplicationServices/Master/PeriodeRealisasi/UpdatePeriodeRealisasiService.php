<?php
namespace App\ApplicationServices\Master\PeriodeRealisasi;

use Carbon\Carbon;
use App\ApplicationServices\ApplicationService;
use App\Domain\KPI\Entities\PeriodeRealisasi;
use App\Domain\KPI\Entities\JenisPeriode;
use App\Domain\User\Entities\User;
use Ramsey\Uuid\Uuid;

class UpdatePeriodeRealisasiService extends ApplicationService
{
    /**
     * @param array $data
     * @param User $user
     * @return array
     */
    public function call(array $data)
    {
        try {
            foreach ($data['Status'] as $value) {
                $perioderealisasi = new JenisPeriode();
                $perioderealisasi->ID = $value;
                $perioderealisasi->save();
            }
            
            return $this->successResponse();
        } catch (\Exception $e) {
            return $this->errorResponse($e->getMessage());
        }
    }
}
