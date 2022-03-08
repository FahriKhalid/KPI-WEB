<?php
namespace App\ApplicationServices\Master\PeriodeRealisasi;

use App\ApplicationServices\ApplicationService;
use App\Domain\KPI\Entities\PeriodeRealisasi;
use App\Infrastructures\Repositories\KPI\PeriodeRealisasiRepository;

class DeletePeriodeRealisasiService extends ApplicationService
{
    /**
     * @var PeriodeAktifRepository
     */
    protected $perioderealisasiRepository;

    /**
     * DeletePeriodeAktifService constructor.
     *
     * @param PeriodeAktifRepository $periodeaktifRepository
     */
    public function __construct(PeriodeRealisasiRepository $perioderealisasiRepository)
    {
        $this->perioderealisasiRepository = $perioderealisasiRepository;
    }

    /**
     * @param $id
     * @return array
     */
    public function call($tahun)
    {
        try {
            $perioderealisasi = PeriodeRealisasi::select('*')->where('Tahun', $tahun);
            $perioderealisasi->delete();
            return $this->successResponse();
        } catch (\Exception $e) {
            return $this->errorResponse($e->getMessage());
        }
    }
}
