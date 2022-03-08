<?php
namespace App\ApplicationServices\RencanaKPI;

use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use App\ApplicationServices\ApplicationService;
use App\Exceptions\DomainException;
use App\Infrastructures\Repositories\KPI\RencanaKPIRepository;

class UnconfirmRencanaKPIService extends ApplicationService
{
    /**
     * @var RencanaKPIRepository
     */
    protected $rencanaKPIRepository;

    /**
     * UnconfirmRencanaKPIService constructor.
     *
     * @param RencanaKPIRepository $rencanaKPIRepository
     */
    public function __construct(RencanaKPIRepository $rencanaKPIRepository)
    {
        $this->rencanaKPIRepository = $rencanaKPIRepository;
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
            $array = (array) $data['id'];
            foreach ($array as $id) {
                $header = $this->rencanaKPIRepository->findById($id);
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
