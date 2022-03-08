<?php
/**
 * Created by PhpStorm.
 * User: SENA
 * Date: 10/28/2017
 * Time: 07:32 PM
 */

namespace App\Infrastructures\Repositories\Reports;

use Illuminate\Support\Facades\DB;
use App\Domain\Karyawan\Entities\Karyawan;
use App\Domain\KPI\Entities\UnitKerja;

class ReportRecapitulationRepository
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
     * ReportRecapitulationRepository constructor.
     * @param Karyawan $karyawan
     * @param UnitKerja $unitKerja
     */
    public function __construct(Karyawan $karyawan, UnitKerja $unitKerja)
    {
        $this->karyawan = $karyawan;
        $this->unitKerja = $unitKerja;
    }

    /**
     * @param $tahun
     * @param $idjenisperiode
     * @return \Illuminate\Database\Eloquent\Builder|Karyawan
     */
    public function reportKPIIndividu($tahun, $idjenisperiode)
    {
        $relationships = [
            'organization.position.unitkerja',
            'rencanakpi' => function ($query) use ($tahun) {
                $query->where('tahun', $tahun)->latest('CreatedOn'); // only select one latest data based selected periode
            },
            'realisasikpi' => function ($query) use ($tahun, $idjenisperiode) {
                $query->where('Tahun', $tahun)->where('IDJenisPeriode', $idjenisperiode)->latest('CreatedOn'); // only select one latest data based selected periode
            },
            'realisasikpi.statusdokumen',
        ];

        $builder = $this->karyawan->with($relationships)->select('Ms_Karyawan.NPK', 'NamaKaryawan');

        return $builder;
    }

    /**
     * @param $tahun
     * @param $idjenisperiode
     * @return mixed
     */
    public function reportKPIUnitKerja($tahun, $idjenisperiode)
    {
        $selected = [
            'Ms_UnitKerja.CostCenter',
            'Ms_UnitKerja.Deskripsi',
            DB::raw('(select count(View_OrganizationalAssignment.PositionID) from View_OrganizationalAssignment 
            LEFT JOIN Ms_MasterPosition ON Ms_MasterPosition.ID = View_OrganizationalAssignment.PositionID 
            WHERE Ms_MasterPosition.KodeUnitKerja = Ms_UnitKerja.CostCenter
            ) as karyawan_count'),
            DB::raw('(SELECT count(Tr_KPIRealisasiHeader.ID) from Tr_KPIRealisasiHeader
            LEFT JOIN View_OrganizationalAssignment ON View_OrganizationalAssignment.NPK = Tr_KPIRealisasiHeader.NPK
            AND Tr_KPIRealisasiHeader.IDStatusDokumen = 4 AND Tr_KPIRealisasiHeader.Tahun = '.$tahun.'
            AND Tr_KPIRealisasiHeader.IDJenisPeriode = '.$idjenisperiode.'
            LEFT JOIN Ms_MasterPosition ON Ms_MasterPosition.ID = View_OrganizationalAssignment.PositionID 
            WHERE Ms_MasterPosition.KodeUnitKerja = Ms_UnitKerja.CostCenter) as collected_count')
        ];

        $builder = $this->unitKerja->select($selected);

        return $builder;
    }

    /**
     * @param $tahun
     * @return \Illuminate\Database\Eloquent\Builder|Karyawan
     */
    public function reportKPIIndividuRencana($tahun)
    {
        $relationships = [
            'organization.position.unitkerja',
            'rencanakpi' => function ($query) use ($tahun) {
                $query->where('tahun', $tahun)->latest('CreatedOn'); // only select one latest data based selected periode
            },
            'rencanakpi.statusdokumen'
        ];

        $builder = $this->karyawan->with($relationships)->select('Ms_Karyawan.NPK', 'NamaKaryawan');

        return $builder;
    }

    /**
     * @param $tahun
     * @return mixed
     */
    public function reportKPIUnitKerjaRencana($tahun)
    {
        $selected = [
            'Ms_UnitKerja.CostCenter',
            'Ms_UnitKerja.Deskripsi',
            DB::raw('(select count(View_OrganizationalAssignment.PositionID) from View_OrganizationalAssignment 
            LEFT JOIN Ms_MasterPosition ON Ms_MasterPosition.ID = View_OrganizationalAssignment.PositionID 
            WHERE Ms_MasterPosition.KodeUnitKerja = Ms_UnitKerja.CostCenter
            ) as karyawan_count'),
            DB::raw('(SELECT count(Tr_KPIRencanaHeader.ID) from Tr_KPIRencanaHeader
            LEFT JOIN View_OrganizationalAssignment ON View_OrganizationalAssignment.NPK = Tr_KPIRencanaHeader.NPK
            AND Tr_KPIRencanaHeader.IDStatusDokumen = 4 AND Tr_KPIRencanaHeader.Tahun = '.$tahun.'
            LEFT JOIN Ms_MasterPosition ON Ms_MasterPosition.ID = View_OrganizationalAssignment.PositionID 
            WHERE Ms_MasterPosition.KodeUnitKerja = Ms_UnitKerja.CostCenter) as collected_count')
        ];

        $builder = $this->unitKerja->select($selected);

        return $builder;
    }
}
