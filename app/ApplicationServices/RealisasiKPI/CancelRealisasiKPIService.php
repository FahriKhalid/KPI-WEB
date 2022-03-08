<?php
namespace App\ApplicationServices\RealisasiKPI;

use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use App\ApplicationServices\ApplicationService;
use App\Infrastructures\Repositories\KPI\RealisasiKPIRepository;

class CancelRealisasiKPIService extends ApplicationService
{
    /**
     * @var RealisasiKPIRepository
     */
    protected $realisasiKPIRepository;

    /**
     * CancelRealisasiKPIService constructor.
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
                $header->isAllowedToCancel();
                $header->IDStatusDokumen = 5;
                $header->CanceledBy = $user->NPK;
                $header->CanceledOn = Carbon::now(config('app.timezone'));
                $header->AlasanCancel = (! empty($data['AlasanCancel'])) ? $data['AlasanCancel'] : null;
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
