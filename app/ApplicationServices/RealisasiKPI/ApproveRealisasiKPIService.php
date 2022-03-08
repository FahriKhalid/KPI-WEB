<?php
namespace App\ApplicationServices\RealisasiKPI;

use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use App\ApplicationServices\ApplicationService;
use App\Exceptions\DomainException;
use App\Infrastructures\Repositories\KPI\RealisasiKPIRepository;

class ApproveRealisasiKPIService extends ApplicationService
{
    /**
     * @var RealisasiKPIRepository
     */
    protected $realisasiKPIRepository;

    /**
     * ApproveRealisasiKPIService constructor.
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
            foreach ($array as $id) {
                DB::beginTransaction();
                $header = $this->realisasiKPIRepository->findById($id);
                $jeniskpi = ($header->IsUnitKerja == 1) ? 'unitkerja' : 'individu';
                $header->isAllowedToApprove($jeniskpi);
                $header->IDStatusDokumen = 4;
                $header->ApprovedBy = $user->NPK;
                $header->ApprovedOn = Carbon::now(config('app.timezone'));
                $header->save();
                DB::commit();
            }
            return $this->successResponse();
        } catch (\Exception $e) {
            DB::rollback();
            return $this->errorResponse($e->getMessage());
        }
    }

    /**
     * @param $headerRealization
     */
    protected function cascadeRealization($headerRealization)
    {
        // check if item is cascade from atasan
            // if yes, check if atasan is already create realization in that periode
            // if already create, update realization atasan. if no, then no action needed
        // if no then pass
    }
}
