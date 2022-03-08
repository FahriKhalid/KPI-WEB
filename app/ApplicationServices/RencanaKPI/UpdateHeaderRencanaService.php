<?php
namespace App\ApplicationServices\RencanaKPI;

use Carbon\Carbon;
use App\ApplicationServices\ApplicationService;
use App\Domain\User\Entities\User;
use App\Infrastructures\Repositories\KPI\RencanaKPIRepository;

class UpdateHeaderRencanaService extends ApplicationService
{
    /**
     * @var RencanaKPIRepository
     */
    protected $rencanaKPIRepository;

    /**
     * UpdateHeaderRencanaService constructor.
     *
     * @param RencanaKPIRepository $rencanaKPIRepository
     */
    public function __construct(RencanaKPIRepository $rencanaKPIRepository)
    {
        $this->rencanaKPIRepository = $rencanaKPIRepository;
    }

    /**
     * @param $id
     * @param array $data
     * @param User $user
     * @return array
     */
    public function call($id, array $data, User $user)
    {
        try {
            $rencanaKPIRepository = $this->rencanaKPIRepository->findById($id);
            $rencanaKPIRepository->Tahun = $data['Tahun'];
            $rencanaKPIRepository->NPK = $data['NPK'];
            $rencanaKPIRepository->IDMasterPosition = $data['IDMasterPosition'];
            $rencanaKPIRepository->Grade = $data['Grade'];
            $rencanaKPIRepository->NPKAtasanLangsung = $data['NPKAtasanLangsung'];
            $rencanaKPIRepository->JabatanAtasanLangsung = $data['JabatanAtasanLangsung'];
            $rencanaKPIRepository->NPKAtasanBerikutnya = $data['NPKAtasanBerikutnya'];
            $rencanaKPIRepository->JabatanAtasanBerikutnya = $data['JabatanAtasanBerikutnya'];
            $rencanaKPIRepository->IDStatusDokumen = 1;
            $rencanaKPIRepository->Keterangan = (! empty($data['Keterangan'])) ? $data['Keterangan'] : null;
            $rencanaKPIRepository->CreatedBy = $user->NPK;
            $rencanaKPIRepository->CreatedOn = Carbon::now('Asia/Jakarta');
            $rencanaKPIRepository->save();
            return $this->successResponse();
        } catch (\Exception $e) {
            return $this->errorResponse($e->getMessage());
        }
    }
}
