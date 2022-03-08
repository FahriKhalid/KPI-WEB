<?php
namespace App\ApplicationServices\Master\Kompetensi;

use App\ApplicationServices\ApplicationService;
use App\Infrastructures\Repositories\KPI\KompetensiRepository;

class DeleteKompetensiService extends ApplicationService
{
    /**
     * @var KompetensiRepository
     */
    protected $kompetensiRepository;

    /**
     * DeleteKompetensiService constructor.
     *
     * @param KompetensiRepository $kompetensiRepository
     */
    public function __construct(KompetensiRepository $kompetensiRepository)
    {
        $this->kompetensiRepository = $kompetensiRepository;
    }

    /**
     * @param $id
     * @return array
     */
    public function call($id)
    {
        try {
            $kompetensi = $this->kompetensiRepository->findById($id);
            $kompetensi->delete();
            return $this->successResponse();
        } catch (\Exception $e) {
            return $this->errorResponse($e->getMessage());
        }
    }
}
