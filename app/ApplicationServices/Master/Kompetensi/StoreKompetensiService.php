<?php
namespace App\ApplicationServices\Master\Kompetensi;

use Carbon\Carbon;
use App\ApplicationServices\ApplicationService;
use App\Domain\KPI\Entities\Kompetensi;
use App\Domain\User\Entities\User;
use Ramsey\Uuid\Uuid;

class StoreKompetensiService extends ApplicationService
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
            $kompetensi = new Kompetensi();
            $kompetensi->PositionID = $data['PositionID'];
            $kompetensi->Keterangan = (! empty($data['Keterangan'])) ? $data['Keterangan'] : null;
            $kompetensi->CreatedBy = $user->ID;
            $kompetensi->CreatedOn = Carbon::now();
            $kompetensi->save();
            return $this->successResponse();
        } catch (\Exception $e) {
            return $this->errorResponse($e->getMessage());
        }
    }
}
