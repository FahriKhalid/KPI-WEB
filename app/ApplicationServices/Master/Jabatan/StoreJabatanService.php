<?php 
namespace App\ApplicationServices\Master\Jabatan;

use Carbon\Carbon;
use App\ApplicationServices\ApplicationService;
use App\Domain\Karyawan\Entities\MasterPosition;
use App\Domain\User\Entities\User;

//use App\Infrastructures\Repositories\Karyawan\JabatanRepository;
//use Ramsey\Uuid\Uuid
/**
 * summary
 */
class StoreJabatanService extends ApplicationService
{
    /**
     * summary
     */
    public function call(array $data, User $user)
    {
        try {
            $role = $user->UserRole->Role;
            $jabatan = new MasterPosition();
            $jabatan->PositionID = $data['PositionID'];
            $jabatan->PositionTitle = $data['PositionTitle'];
            $jabatan->PositionAbbreviation = $data['PositionAbbreviation'];
            $jabatan->KodeUnitKerja = $data['KodeUnitKerja'];
            $jabatan->StatusAktif =  $data['StatusAktif'];
            $jabatan->Keterangan = (! empty($data['Keterangan'])) ? $data['Keterangan'] : null;
            $jabatan->CreatedBy = $user->ID;
            $jabatan->CreatedOn = Carbon::now();

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
