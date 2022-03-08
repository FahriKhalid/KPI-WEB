<?php
namespace App\Infrastructures\Repositories\Reports;

use App\Domain\KPI\Entities\RencanaPengembangan;
use App\Domain\Realisasi\Entities\DetilRealisasiKPI;
use App\Domain\Realisasi\Entities\HeaderRealisasiKPI;
use App\Infrastructures\Repositories\Karyawan\KaryawanRepository;
use App\Infrastructures\Repositories\KPI\RencanaPengembanganRepository;

class ReportRencanaPengembanganRepository extends RencanaPengembanganRepository
{
    /**
     * @var DetilRealisasiKPI
     */
    protected $detilRealisasiKPI;

    /**
     * ReportRencanaPengembanganRepository constructor.
     *
     * @param RencanaPengembangan $model
     * @param HeaderRealisasiKPI $header
     * @param KaryawanRepository $karyawan
     * @param DetilRealisasiKPI $detilRealisasiKPI
     */
    public function __construct(
        RencanaPengembangan $model,
        HeaderRealisasiKPI $header,
        KaryawanRepository $karyawan,
        DetilRealisasiKPI $detilRealisasiKPI
    ) {
        parent::__construct($model, $header, $karyawan);
        $this->detilRealisasiKPI = $detilRealisasiKPI;
    }

    /**
     * @param int $paginate
     * @param $defaultTahunPeriode
     * @param array $filters
     * @return \Illuminate\Contracts\Pagination\LengthAwarePaginator
     */
    public function getReport($paginate = 25, $defaultTahunPeriode, array $filters = [])
    {
        return $this->detilRealisasiKPI->with([
            'headerrealisasikpi.karyawan.organization.position.unitkerja'
        ])->whereHas('headerrealisasikpi', function ($query) use ($defaultTahunPeriode, $filters) {
            $query->where('Tahun', $defaultTahunPeriode);
            if (array_key_exists('npk', $filters) && !empty($filters['npk'])) {
                $query->where('NPK', $filters['npk']);
            }
        })->where('IDRencanaPengembangan', 1)->paginate($paginate);
    }
}
