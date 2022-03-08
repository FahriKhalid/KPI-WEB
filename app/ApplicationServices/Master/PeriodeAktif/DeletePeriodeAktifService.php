<?php
namespace App\ApplicationServices\Master\PeriodeAktif;

use Illuminate\Support\Facades\DB;
use App\ApplicationServices\ApplicationService;
use App\Infrastructures\Repositories\KPI\PeriodeAktifRepository;

class DeletePeriodeAktifService extends ApplicationService
{
    /**
     * @var PeriodeAktifRepository
     */
    protected $periodeaktifRepository;

    /**
     * DeletePeriodeAktifService constructor.
     *
     * @param PeriodeAktifRepository $periodeaktifRepository
     */
    public function __construct(PeriodeAktifRepository $periodeaktifRepository)
    {
        $this->periodeaktifRepository = $periodeaktifRepository;
    }

    /**
     * @param $id
     * @return array
     */
    public function call($id)
    {
        try {
            DB::beginTransaction();
            $periodeaktif = $this->periodeaktifRepository->findByTahun($id);
            foreach ($periodeaktif as $_) {
                $_->delete();
            }
            DB::commit();
            return $this->successResponse();
        } catch (\Exception $e) {
            DB::rollback();
            return $this->errorResponse($e->getMessage());
        }
    }
}
