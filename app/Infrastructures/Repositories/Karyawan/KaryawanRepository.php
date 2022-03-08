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
                ->leftJoin('View_OrganizationalAssignment', 'Ms_Karyawan.NPK', '=', 'View_OrganizationalAssignment.NPK')
                ->leftJoin('Ms_MasterPosition', 'Ms_MasterPosition.PositionID', '=', 'View_OrganizationalAssignment.PositionID')
                ->leftJoin('Ms_UnitKerja', 'Ms_MasterPosition.KodeUnitKerja', '=', 'Ms_UnitKerja.CostCenter')
                ->select([
                    'Ms_Karyawan.NPK',
                    'Ms_Karyawan.NamaKaryawan',
                    'Ms_Karyawan.Email',
                    'View_OrganizationalAssignment.Grade',
                    'View_OrganizationalAssignment.Shift',
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
        $karyawan = $this->model->with(['organization.position'])->where(function ($query) use ($positionAbbreviation, $codeShift) {
            $query->whereHas('organization.position', function ($query) use ($positionAbbreviation) {
                $query->where('PositionAbbreviation', 'LIKE', $positionAbbreviation);
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

        if($karyawan){
            return $karyawan;
        }else{
            return DB::table("Ms_Direksi")->where("PositionAbbreviation", "LIKE", $positionAbbreviation)->first();
        }
    }

    /**
     * @param $originalPositionAbbreviation
     * @param $positionAbbreviationParent
     * @return \Illuminate\Database\Eloquent\Collection|static[]
     */
    public function findBawahan($originalPositionAbbreviation, $positionAbbreviationParent)
    {

        /* original
        return $this->model->with(['organization.position'])->whereHas('organization.position', function ($query) use ($positionAbbreviationParent, $originalPositionAbbreviation) {
            $query->where(function ($query) use ($positionAbbreviationParent) {
                $query->where('PositionAbbreviation', 'LIKE', $positionAbbreviationParent.'%'.PositionAbbreviation::getRemainingLevelBawahanCode($positionAbbreviationParent, 2).'%');
                if ($positionAbbreviationParent == 1) { // bawahan dirut juga termasuk direksi lainnya
                    $query->orWhere('PositionAbbreviation', 'LIKE', '%000000000DS');
                }
            })->whereNotIn('PositionAbbreviation', [$originalPositionAbbreviation, str_replace('DS', 'LF', $originalPositionAbbreviation)]);
        })->get();*/

        //$idu = [auth()->user()->ID];
        $idu = auth()->user()->ID;

        //$cek = \DB::table('UserPrivileges')->where('IDRole', '8')->whereIn('IDUser', $idu)->get();
        $cek = \DB::table('UserPrivileges')->where('IDRole', '8')->where('IDUser', $idu)->get();

        //if (!is_null($cek)) {
        if (count($cek) > 0) {

            return $this->model->get();

        } else {

            // return $this->model->with(['organization.position'])->whereHas('organization.position', function ($query) use ($positionAbbreviationParent, $originalPositionAbbreviation) {
            //     $query->where(function ($query) use ($positionAbbreviationParent) {
            //         $query->where('PositionAbbreviation', 'LIKE', $positionAbbreviationParent.'%'.PositionAbbreviation::getRemainingLevelBawahanCode($positionAbbreviationParent, 2).'%');
            //         if ($positionAbbreviationParent == 1) { // bawahan dirut juga termasuk direksi lainnya
            //             $query->orWhere('PositionAbbreviation', 'LIKE', '%000000000DS');
            //         }
            //     })->whereNotIn('PositionAbbreviation', [$originalPositionAbbreviation, str_replace('DS', 'LF', $originalPositionAbbreviation)]);
            // })->get();


            $data = $this->model->with(['organization.position'])->whereHas('organization.position', function ($query) use ($positionAbbreviationParent, $originalPositionAbbreviation) {
                $query->where(function ($query) use ($positionAbbreviationParent, $originalPositionAbbreviation) {

                    if($originalPositionAbbreviation == '13240000ECCDSN') // spesial pak muslih
                    {
                        $query->where("KodeUnitKerja", "D003100000")->where("PositionAbbreviation", 'LIKE', '132%FN%');
                    }
                    else if($originalPositionAbbreviation == '13000000BAFDSN') // spesial pak qomaruzzaman
                    {
                        $query->where('PositionAbbreviation', 'LIKE', '13%')->orWhere('PositionAbbreviation', 'LIKE', '14%');
                    }
                    else
                    { 
                        $query->where('PositionAbbreviation', 'LIKE', $positionAbbreviationParent.'%');
                        
                        if ($positionAbbreviationParent == 1) { // bawahan dirut juga termasuk direksi lainnya
                            $query->orWhere('PositionAbbreviation', 'LIKE', '%000000000DS');
                        } 
                    }
                })->where('NPK', '!=', auth()->user()->NPK);
            })->get();


            if($positionAbbreviationParent == 1){
                $direksi = $this->model->whereIn("NPK", ["4204636", "4204635"])->get();
                $data = $data->merge($direksi);
            }

            return $data;
        }

    }
}













