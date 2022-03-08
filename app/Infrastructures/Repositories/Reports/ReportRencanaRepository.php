<?php
namespace App\Infrastructures\Repositories\Reports;

use Illuminate\Support\Facades\DB;
use App\Domain\Karyawan\Entities\Karyawan;
use App\Domain\KPI\Entities\UnitKerja;
use App\Domain\Rencana\Entities\Attachment;
use App\Domain\Rencana\Entities\DetilRencanaKPI;
use App\Domain\Rencana\Entities\HeaderRencanaKPI;
use App\Domain\Rencana\Entities\KRAKPIDetail;
use App\Infrastructures\Repositories\KPI\RencanaKPIRepository;

class ReportRencanaRepository extends RencanaKPIRepository
{
    /**
     * @var Karyawan
     */
    protected $karyawan;

    /**
     * @var UnitKerja
     */
    protected $unitkerja;

    /**
     * ReportRencanaRepository constructor.
     *
     * @param HeaderRencanaKPI $header
     * @param DetilRencanaKPI $detail
     * @param Attachment $attachment
     * @param KRAKPIDetail $penurunanKPI
     * @param Karyawan $karyawan
     * @param UnitKerja $unitKerja
     */
    public function __construct(
        HeaderRencanaKPI $header,
        DetilRencanaKPI $detail,
        Attachment $attachment,
        KRAKPIDetail $penurunanKPI,
        Karyawan $karyawan,
        UnitKerja $unitKerja
    ) {
        parent::__construct($header, $detail, $attachment, $penurunanKPI);
        $this->karyawan = $karyawan;
        $this->unitkerja = $unitKerja;
    }

    /**
     * @param int $paginate
     * @param $periodeYear
     * @param array $filters
     * @return \Illuminate\Contracts\Pagination\LengthAwarePaginator
     */
    public function datatableReportRencanaIndividu($paginate = 25, $periodeYear, array $filters = [])
    {
        $relationships = [
            'organization.position.unitkerja',
            'rencanakpi' => function ($query) use ($periodeYear) {
                $query->where('tahun', $periodeYear)->latest('CreatedOn'); // only select one latest data based selected periode
            },
            'rencanakpi.statusdokumen'
        ];

        $builder = $this->karyawan->with($relationships)->select('Ms_Karyawan.NPK', 'NamaKaryawan');

        // build filter conditions
        if (count($filters) > 0) {
            if (array_key_exists('npk', $filters) && ! empty($filters['npk'])) {
                $builder->where('Ms_Karyawan.NPK', $filters['npk']);
            }
            if (array_key_exists('status', $filters) && (! empty($filters['status']) && $filters['status'] != 'all')) {
                if ($filters['status'] == 'empty') {
                    $builder->whereDoesntHave('rencanakpi');
                } else {
                    $builder->whereHas('rencanakpi', function ($query) use ($filters) {
                        if ($filters['status'] == 'inprogress') {
                            $query->inProgress();
                        } elseif ($filters['status'] == 'approved') {
                            $query->approved();
                        }
                    });
                }
            }
        }
        return $builder->paginate($paginate);
    }

    /**
     * @param int $paginate
     * @param $periodeYear
     * @param array $filters
     * @return \Illuminate\Contracts\Pagination\LengthAwarePaginator
     */
    public function datatableReportRencanaUnitKerja($paginate = 25, $periodeYear, array $filters = [])
    {
        $selected = [
            'Ms_UnitKerja.CostCenter',
            'Ms_UnitKerja.Deskripsi',
            DB::raw('(select count(View_OrganizationalAssignment.PositionID) from View_OrganizationalAssignment 
            LEFT JOIN Ms_MasterPosition ON Ms_MasterPosition.PositionID = View_OrganizationalAssignment.PositionID 
            WHERE Ms_MasterPosition.KodeUnitKerja = Ms_UnitKerja.CostCenter
            ) as karyawan_count'),
            DB::raw('(SELECT count(Tr_KPIRencanaHeader.ID) from Tr_KPIRencanaHeader
            LEFT JOIN View_OrganizationalAssignment ON View_OrganizationalAssignment.NPK = Tr_KPIRencanaHeader.NPK
            AND Tr_KPIRencanaHeader.IDStatusDokumen = 4 AND Tr_KPIRencanaHeader.Tahun = '.$periodeYear.'
            LEFT JOIN Ms_MasterPosition ON Ms_MasterPosition.PositionID = View_OrganizationalAssignment.PositionID 
            WHERE Ms_MasterPosition.KodeUnitKerja = Ms_UnitKerja.CostCenter) as collected_count')
        ];
        $builder = $this->unitkerja->select($selected);
        if (count($filters) > 0) {
            if (array_key_exists('unitkerja', $filters) && ! empty($filters['unitkerja'])) {
                $builder->where('Ms_UnitKerja.CostCenter', $filters['unitkerja']);
            }
        }
        return $builder->paginate($paginate);
    }
}
