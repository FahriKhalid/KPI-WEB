<?php
namespace App\ApplicationServices\RealisasiKPI;

use App\ApplicationServices\ApplicationService;
use Illuminate\Support\Facades\DB;
use App\Exceptions\DomainException;
use App\Infrastructures\Repositories\KPI\RealisasiKPIRepository;

class UnapproveRealisasiKPIService extends ApplicationService
{
    /**
     * @var RealisasiKPIRepository
     */
    protected $realisasiKPIRepository;

    /**
     * UnapproveRealisasiKPIService constructor.
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
                $jeniskpi = ($header->IsUnitKerja == 1) ? 'unitkerja' : 'individu';
                $header->isAllowedToUnapproved($jeniskpi);
                $header->IDStatusDokumen = 1;
                $header->CatatanUnapprove = (! empty($data['CatatanUnapprove'])) ? $data['CatatanUnapprove'] : null;
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
