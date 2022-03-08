<?php
namespace App\ApplicationServices\Master\Kompetensi;

use Carbon\Carbon;
use App\ApplicationServices\ApplicationService;
use App\Domain\User\Entities\User;
use App\Infrastructures\Repositories\KPI\KompetensiRepository;

class UpdateKompetensiService extends ApplicationService
{
    protected $kompetensiRepository;

    /**
     * UpdateKompetensiService constructor.
     *
     * @param KompetensiRepository $kompetensiRepository
     */
    public function __construct(KompetensiRepository $kompetensiRepository)
    {
        $this->kompetensiRepository = $kompetensiRepository;
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
            $kompetensi = $this->kompetensiRepository->findById($id);
            $kompetensi->PositionID = $data['PositionID'];
            $kompetensi->Keterangan = (! empty($data['Keterangan'])) ? $data['Keterangan'] : null;
            $kompetensi->UpdatedBy = $user->ID;
            $kompetensi->UpdatedOn = Carbon::now();
            $kompetensi->save();
            return $this->successResponse();
        } catch (\Exception $e) {
            return $this->errorResponse($e->getMessage());
        }
    }
}
