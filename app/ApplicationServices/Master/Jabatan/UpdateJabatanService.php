<?php
namespace App\ApplicationServices\Master\Jabatan;

use App\ApplicationServices\ApplicationService;
use App\Domain\Karyawan\Entities\MasterPosition as Jabatan;
use App\Domain\User\Entities\User;
use Carbon\Carbon;

class UpdateJabatanService extends ApplicationService
{
    public function call($id, array $data, User $user)
    {
        $jabatan = Jabatan::find($id);
        try {
            $jabatan->PositionID = $data['PositionID'];
            $jabatan->PositionTitle = $data['PositionTitle'];
            $jabatan->PositionAbbreviation = $data['PositionAbbreviation'];
            $jabatan->KodeUnitKerja = $data['KodeUnitKerja'];
            $jabatan->StatusAktif =  $data['StatusAktif'];
            $jabatan->Keterangan = (! empty($data['Keterangan'])) ? $data['Keterangan'] : null;
            $jabatan->UpdatedBy = $user->ID;
            $jabatan->UpdatedOn = Carbon::now();

            // if ($role == 'Administrator') {
            //     $jabatan->StatusAktif = 'approved';
            //   }
            $jabatan->save();
            return $this->successResponse();
        } catch (Exception $e) {
            return $this->errorResponse($e->getMessage());
        }
    }
}
