<?php
namespace App\ApplicationServices\RencanaKPI;

use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use App\ApplicationServices\ApplicationService;
use App\Domain\Rencana\Entities\KRAKPIDetail;
use App\Infrastructures\Repositories\KPI\RencanaKPIRepository;

class CancelRencanaKPIService extends ApplicationService
{
    /**
     * @var RencanaKPIRepository
     */
    protected $rencanaKPIRepository;

    /**
     * CancelRencanaKPIService constructor.
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
            foreach ($data['id'] as $id) {
                $header = $this->rencanaKPIRepository->findById($id);
                $header->isAllowedToCancel();
                $header->IDStatusDokumen = 5;
                $header->CanceledBy = $user->NPK;
                $header->CanceledOn = Carbon::now(config('app.timezone'));
                $header->AlasanCancel = (! empty($data['AlasanCancel'])) ? $data['AlasanCancel'] : null;
                $header->save();

                $this->cancelCascadingStatus($header);
            }
            DB::commit();
            return $this->successResponse();
        } catch (\Exception $e) {
            DB::rollback();
            return $this->errorResponse($e->getMessage());
        }
    }

    /**
     * @param $headerRencana
     * @return void
     */
    protected function cancelCascadingStatus($headerRencana)
    {
        KRAKPIDetail::whereHas('cascadedkpi', function ($query) use ($headerRencana) {
            $query->where('IDKPIRencanaHeader', $headerRencana->ID);
        })->update(['IsCascaded' => 0]);
    }
}
