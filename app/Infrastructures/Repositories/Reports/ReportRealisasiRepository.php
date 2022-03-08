<?php
namespace App\Infrastructures\Repositories\Reports;

use Illuminate\Support\Facades\DB;
use App\Domain\Karyawan\Entities\Karyawan;
use App\Domain\KPI\Entities\UnitKerja;
use App\Domain\Realisasi\Entities\Attachment;
use App\Domain\Realisasi\Entities\DetilRealisasiKPI;
use App\Domain\Realisasi\Entities\HeaderRealisasiKPI;
use App\Domain\Realisasi\Entities\ValidasiUnitKerja;
use App\Domain\Rencana\Entities\HeaderRencanaKPI;
use App\Infrastructures\Repositories\KPI\RealisasiKPIRepository;

class ReportRealisasiRepository extends RealisasiKPIRepository
{
    /**
     * @var Karyawan
     */
    protected $karyawan;

    /**
     * @var UnitKerja
     */
    protected $unitKerja;

    /**
     * ReportRealisasiRepository constructor.
     *
     * @param HeaderRealisasiKPI $header
     * @param DetilRealisasiKPI $detail
     * @param Attachment $attachment
     * @param ValidasiUnitKerja $validasiUnitKerja
     * @param Karyawan $karyawan
     * @param UnitKerja $unitKerja
     * @param HeaderRencanaKPI $headerRencanaKPI
     */
    public function __construct(
        HeaderRealisasiKPI $header,
        DetilRealisasiKPI $detail,
        Attachment $attachment,
        ValidasiUnitKerja $validasiUnitKerja,
        Karyawan $karyawan,
        UnitKerja $unitKerja,
        HeaderRencanaKPI $headerRencanaKPI
    ) {
        parent::__construct($header, $detail, $attachment, $validasiUnitKerja, $headerRencanaKPI);
        $this->karyawan = $karyawan;
        $this->unitKerja = $unitKerja;
    }

    /**
     * @param int $paginate
     * @param $periodeYear
     * @param $IDperiodeRealisasi
     * @param array $filters
     * @return \Illuminate\Contracts\Pagination\LengthAwarePaginator
     */
    public function datatableReportRealisasiIndividu($paginate = 25, $periodeYear, $IDperiodeRealisasi, array $filters = [])
    {
        $relationships = [
            'organization.position.unitkerja',
            'rencanakpi' => function ($query) use ($periodeYear) {
                $query->where('tahun', $periodeYear)->latest('CreatedOn'); // only select one latest data based selected periode
            },
            'realisasikpi' => function ($query) use ($periodeYear, $IDperiodeRealisasi) {
                $query->where('tahun', $periodeYear)
                    ->where('IDJenisPeriode', $IDperiodeRealisasi)->latest('CreatedOn'); // only select one latest data based selected periode
            },
            'realisasikpi.statusdokumen'
        ];

        $builder = $this->karyawan->with($relationships)->select('Ms_Karyawan.NPK', 'NamaKaryawan');

        // build filter conditions
        if (count($filters) > 0) {
            if (array_key_exists('npk', $filters) && ! empty($filters['npk'])) {
                $builder->where('Ms_Karyawan.NPK', $filters['npk']);
            }

            if (array_key_exists('status', $filters)) {
                if ($filters['status'] != 'all') {
                    if ($filters['status'] == 'empty') {
                        $builder->whereDoesntHave('realisasikpi', function ($query) use ($filters) {
                            $query->where('Tahun', $filters['tahunperiode'])->where('IDJenisPeriode', $filters['perioderealisasi']);
                        });
                    } else {
                        $builder->whereHas('realisasikpi', function ($query) use ($filters) {
                            if ($filters['status'] == 'inprogress') {
                                $query->inProgress();
                            } elseif ($filters['status'] == 'approved') {
                                $query->approved();
                            }
                            $query->where('Tahun', $filters['tahunperiode'])->where('IDJenisPeriode', $filters['perioderealisasi']);
                        });
                    }
                }
            }
        }
        return $builder->paginate($paginate);
    }

    /**
     * @param int $paginate
     * @param $periodeYear
     * @param $IDperiodeRealisasi
     * @param array $filters
     * @return \Illuminate\Contracts\Pagination\LengthAwarePaginator
     */
    public function datatableReportRealisasiByUnitKerja($paginate = 25, $periodeYear, $IDperiodeRealisasi, array $filters = [])
    {
        $selected = [
            'Ms_UnitKerja.CostCenter',
            'Ms_UnitKerja.Deskripsi',
            DB::raw('(SELECT count(View_OrganizationalAssignment.PositionID) from View_OrganizationalAssignment 
            LEFT JOIN Ms_MasterPosition ON Ms_MasterPosition.PositionID = View_OrganizationalAssignment.PositionID 
            WHERE Ms_MasterPosition.KodeUnitKerja = Ms_UnitKerja.CostCenter
            ) as karyawan_count'),
            DB::raw('(SELECT count(Tr_KPIRealisasiHeader.ID) from Tr_KPIRealisasiHeader
            LEFT JOIN View_OrganizationalAssignment ON View_OrganizationalAssignment.NPK = Tr_KPIRealisasiHeader.NPK
            AND Tr_KPIRealisasiHeader.IDStatusDokumen = 4 AND Tr_KPIRealisasiHeader.Tahun = '.$periodeYear.'
            AND Tr_KPIRealisasiHeader.IDJenisPeriode = '.$IDperiodeRealisasi.' 
            LEFT JOIN Ms_MasterPosition ON Ms_MasterPosition.PositionID = View_OrganizationalAssignment.PositionID 
            WHERE Ms_MasterPosition.KodeUnitKerja = Ms_UnitKerja.CostCenter) as collected_count')
        ];
        $builder = $this->unitKerja->select($selected);
        if (count($filters) > 0) {
            if (array_key_exists('unitkerja', $filters) && ! empty($filters['unitkerja'])) {
                $builder->where('Ms_UnitKerja.CostCenter', $filters['unitkerja']);
            }
        }
        return $builder->paginate($paginate);
    }
}
