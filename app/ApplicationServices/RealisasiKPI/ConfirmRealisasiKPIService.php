<?php
namespace App\ApplicationServices\RealisasiKPI;

use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use App\ApplicationServices\ApplicationService;
use App\Exceptions\DomainException;
use App\Infrastructures\Repositories\KPI\RealisasiKPIRepository;

class ConfirmRealisasiKPIService extends ApplicationService
{
    /**
     * @var RealisasiKPIRepository
     */
    protected $realisasiKPIRepository;

    /**
     * ConfirmRealisasiKPIService constructor.
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
            $array = (array) $data['id'];
            DB::beginTransaction();
            foreach ($array as $id) {
                $header = $this->realisasiKPIRepository->findById($id);
                $header->isAllowedToConfirm();
                $header->IDStatusDokumen = 3;
                $header->ConfirmedBy = $user->NPK;
                $header->ConfirmedOn = Carbon::now(config('app.timezone'));
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
