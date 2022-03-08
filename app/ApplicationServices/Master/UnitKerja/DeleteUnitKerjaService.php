<?php
namespace App\ApplicationServices\Master\UnitKerja;

use App\ApplicationServices\ApplicationService;
use App\Infrastructures\Repositories\KPI\UnitKerjaRepository;

class DeleteUnitKerjaService extends ApplicationService
{
    /**
     * @var UnitKerjaRepository
     */
    protected $unitkerjaRepository;

    /**
     * DeleteUnitKerjaService constructor.
     *
     * @param UnitKerjaRepository $unitkerjaRepository
     */
    public function __construct(UnitKerjaRepository $unitkerjaRepository)
    {
        $this->unitkerjaRepository = $unitkerjaRepository;
    }

    /**
     * @param $id
     * @return array
     */
    public function call($id)
    {
        try {
            $unitkerja = $this->unitkerjaRepository->findById($id);
            $unitkerja->delete();
            return $this->successResponse();
        } catch (\Exception $e) {
            return $this->errorResponse($e->getMessage());
        }
    }
}
