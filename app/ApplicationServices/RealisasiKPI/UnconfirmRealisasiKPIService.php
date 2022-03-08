<?php
namespace App\ApplicationServices\RealisasiKPI;

use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use App\ApplicationServices\ApplicationService;
use App\Exceptions\DomainException;
use App\Infrastructures\Repositories\KPI\RealisasiKPIRepository;

class UnconfirmRealisasiKPIService extends ApplicationService
{
    /**
     * @var RealisasiKPIRepository
     */
    protected $realisasiKPIRepository;

    /**
     * UnconfirmRealisasiKPIService constructor.
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
                $header->isAllowedToUnconfirm();
                $header->IDStatusDokumen = 1;
                $header->ConfirmedBy = $user->NPK;
                $header->ConfirmedOn = Carbon::now(config('app.timezone'));
                $header->CatatanUnconfirm = (! empty($data['CatatanUnconfirm'])) ? $data['CatatanUnconfirm'] : null;
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
