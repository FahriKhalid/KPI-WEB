<?php
namespace App\ApplicationServices\Master\UnitKerja;

use Carbon\Carbon;
use App\ApplicationServices\ApplicationService;
use App\Domain\KPI\Entities\UnitKerja;
use App\Domain\User\Entities\User;
use Ramsey\Uuid\Uuid;

class StoreUnitKerjaService extends ApplicationService
{
    /**
     * @param array $data
     * @param User $user
     * @return array
     */
    public function call(array $data, User $user)
    {
        try {
            $role = $user->UserRole->Role;
            $unitkerja = new UnitKerja();
            $unitkerja->CostCenter = $data['CostCenter'];
            $unitkerja->Deskripsi = $data['Deskripsi'];
            $unitkerja->Keterangan = (! empty($data['Keterangan'])) ? $data['Keterangan'] : null;
            $unitkerja->Aktif  = $data['Aktif'];
            $unitkerja->Field1 = isset($data['Field1'])?$data['Field1']:null;
            $unitkerja->Field2 = isset($data['Field2'])?$data['Field2']:null;
            $unitkerja->Field3 = isset($data['Field3'])?$data['Field3']:null;
            $unitkerja->Field4 = isset($data['Field4'])?$data['Field4']:null;
            $unitkerja->Field5 = isset($data['Field5'])?$data['Field5']:null;
            $unitkerja->CreatedBy = $user->ID;
            $unitkerja->CreatedOn = Carbon::now();

            $unitkerja->save();
            return $this->successResponse();
        } catch (\Exception $e) {
            return $this->errorResponse($e->getMessage());
        }
    }
}
