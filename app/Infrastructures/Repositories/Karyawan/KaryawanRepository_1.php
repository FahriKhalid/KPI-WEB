<?php
namespace App\Infrastructures\Repositories\Karyawan;

use Illuminate\Support\Facades\DB;
use App\Domain\Karyawan\Entities\Karyawan;
use App\Domain\Karyawan\Services\PositionAbbreviation;

class KaryawanRepository
{
    /**
     * @var Karyawan
     */
    protected $model;

    /**
     * KaryawanRepository constructor.
     *
     * @param Karyawan $karyawan
     */
    public function __construct(Karyawan $karyawan)
    {
        $this->model = $karyawan;
    }

    /**
     * @return mixed
     */
    public function datatable()
    {
        return DB::table('Ms_Karyawan')
                ->leftJoin('Ms_OrganizationalAssignment', 'Ms_Karyawan.NPK', '=', 'Ms_OrganizationalAssignment.NPK')
                ->leftJoin('Ms_MasterPosition', 'Ms_MasterPosition.PositionID', '=', 'Ms_OrganizationalAssignment.PositionID')
                ->leftJoin('Ms_UnitKerja', 'Ms_MasterPosition.KodeUnitKerja', '=', 'Ms_UnitKerja.CostCenter')
                ->select([
                    'Ms_Karyawan.NPK',
                    'Ms_Karyawan.NamaKaryawan',
                    'Ms_Karyawan.Email',
                    'Ms_OrganizationalAssignment.Grade',
                    'Ms_OrganizationalAssignment.Shift',
                    'Ms_MasterPosition.PositionID',
                    'Ms_MasterPosition.PositionAbbreviation',
                    'Ms_MasterPosition.PositionTitle',
                    'Ms_UnitKerja.CostCenter',
                    'Ms_UnitKerja.Deskripsi'
                ]);
    }

    /**
     * @return int
     */
    public function count()
    {
        return $this->model->count('NPK');
    }

    /**
     * @param $npk
     * @return mixed
     */
    public function getByNPK($npk)
    {
        return $this->model->with(['organization.position'])->select('NPK', 'NamaKaryawan')
                    ->where('NPK', 'LIKE', '%'.$npk.'%')->get();
    }

    /**
     * @param $npk
     * @return mixed
     */
    public function findByNPK($npk)
    {
        return $this->model->where('NPK', $npk)->first();
    }

    /**
     * @param $positionAbbreviation
     * @param null $codeShift
     * @return \Illuminate\Database\Eloquent\Model|null|static
     */
    public function findByPositionAbbreviation($positionAbbreviation, $codeShift = null)
    {
        return $this->model->with(['organization.position'])->where(function ($query) use ($positionAbbreviation, $codeShift) {
            $query->whereHas('organization.position', function ($query) use ($positionAbbreviation) {
                $query->where('PositionAbbreviation', $positionAbbreviation);
            });
            if (! empty($codeShift)) {
                $query->whereHas('organization', function ($query) use ($codeShift) {
                    $query->where('Shift', $codeShift);
                    if ($codeShift != 'N-BIASA') {
                        $query->orWhere('Shift', 'N-BIASA');
                    }
                });
            }
        })->first();
    }

    /**
     * @param $originalPositionAbbreviation
     * @param $positionAbbreviationParent
     * @return \Illuminate\Database\Eloquent\Collection|static[]
     */
    public function findBawahan($originalPositionAbbreviation, $positionAbbreviationParent)
    {
        return $this->model->with(['organization.position'])->whereHas('organization.position', function ($query) use ($positionAbbreviationParent, $originalPositionAbbreviation) {
            $query->where(function ($query) use ($positionAbbreviationParent) {
                $query->where('PositionAbbreviation', 'LIKE', $positionAbbreviationParent.'%'.PositionAbbreviation::getRemainingLevelBawahanCode($positionAbbreviationParent, 2).'%');
                if ($positionAbbreviationParent == 1) { // bawahan dirut juga termasuk direksi lainnya
                    $query->orWhere('PositionAbbreviation', 'LIKE', '%000000000DS');
                }
            })->whereNotIn('PositionAbbreviation', [$originalPositionAbbreviation, str_replace('DS', 'LF', $originalPositionAbbreviation)]);
        })->get();
    }
}
