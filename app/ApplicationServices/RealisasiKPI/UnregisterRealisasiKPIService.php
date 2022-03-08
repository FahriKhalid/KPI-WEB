<?php
namespace App\ApplicationServices\RealisasiKPI;

use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use App\ApplicationServices\ApplicationService;
use App\Exceptions\DomainException;
use App\Infrastructures\Repositories\KPI\RealisasiKPIRepository;

class UnregisterRealisasiKPIService extends ApplicationService
{
    /**
     * @var RealisasiKPIRepository
     */
    protected $realisasiKPIRepository;

    /**
     * UnregisterRealisasiKPIService constructor.
     *
     * @param RealisasiKPIRepository $realisasiKPIRepository
     */
    public function __construct(RealisasiKPIRepository $realisasiKPIRepository)
    {
        $this->realisasiKPIRepository = $realisasiKPIRepository;
    }

    /**
     * @param array $data
     * @param $user
     * @return array
     */
    public function call(array $data, $user)
    {
        try {
            DB::beginTransaction();
            foreach ($data['id'] as $id) {
                $header = $this->realisasiKPIRepository->findById($id);
                $header->isAllowedToUnregister();
                $header->IDStatusDokumen = 1;
                $header->RegisteredBy = null;
                $header->RegisteredOn = null;
                $header->save();
            }
            DB::commit();
            return $this->successResponse();
        } catch (\Exception $e) {
            DB::rollback();
            return $this->errorResponse($e->getMessage());
        }
    }
}
