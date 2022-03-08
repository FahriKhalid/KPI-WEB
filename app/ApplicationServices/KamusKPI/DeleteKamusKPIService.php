<?php
namespace App\ApplicationServices\KamusKPI;

use App\ApplicationServices\ApplicationService;
use App\Infrastructures\Repositories\KPI\KamusRepository;

class DeleteKamusKPIService extends ApplicationService
{
    /**
     * @var KamusRepository
     */
    protected $kamusRepository;

    /**
     * DeleteKamusKPIService constructor.
     *
     * @param KamusRepository $kamusRepository
     */
    public function __construct(KamusRepository $kamusRepository)
    {
        $this->kamusRepository = $kamusRepository;
    }

    /**
     * @param $id
     * @return array
     */
    public function call($id)
    {
        try {
            $kamusKPI = $this->kamusRepository->findById($id);
            $kamusKPI->delete();
            return $this->successResponse();
        } catch (\Exception $e) {
            return $this->errorResponse($e->getMessage());
        }
    }
}
