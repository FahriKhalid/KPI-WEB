<?php
namespace App\ApplicationServices\Master\Jabatan;

use App\ApplicationServices\ApplicationService;
use App\Domain\Karyawan\Entities\MasterPosition;

class DeleteJabatanService extends ApplicationService
{
    protected $user;

    public function __construct(MasterPosition $jabatan)
    {
        $this->user = $jabatan;
    }

    public function call($id)
    {
        $jabatan = $this->user->find($id);
        try {
            $jabatan->delete();
            return $this->successResponse();
        } catch (\Exception $e) {
            return $this->errorResponse($e->getMessage());
        }
    }
}
