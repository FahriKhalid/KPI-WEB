<?php
namespace App\Infrastructures\Repositories\Master;

use App\Domain\KPI\Entities\MatrixValidation;
use App\Infrastructures\Repositories\KPI\RealisasiKPIRepository;

class ValidationMatrixRepository
{
    /**
     * @var MatrixValidation
     */
    protected $matrixValidation;

    /**
     * ValidationMatrixRepository constructor.
     *
     * @param MatrixValidation $matrixValidation
     */
    public function __construct(MatrixValidation $matrixValidation)
    {
        $this->matrixValidation = $matrixValidation;
    }

    /**
     * @return mixed
     */
    public function datatable()
    {
        $relationship = [
            'unitkerja' => function ($query) {
                $query->select('CostCenter', 'Deskripsi');
            },
            'unitkerjapenilai' => function ($query) {
                $query->select('CostCenter', 'Deskripsi');
            },
        ];
        return $this->matrixValidation->with($relationship);
    }

    /**
     * @param $id
     * @return \Illuminate\Database\Eloquent\Collection|\Illuminate\Database\Eloquent\Model|null|static|static[]
     */
    public function findById($id)
    {
        return $this->matrixValidation->find($id);
    }

    /**
     * @param $kodeunitkerjapenilai
     * @return \Illuminate\Database\Eloquent\Collection|static[]
     */
    public function getByKodeUnitKerjaPenilai($kodeunitkerjapenilai)
    {
        $relationship = ['unitkerja', 'unitkerjapenilai'];
        return $this->matrixValidation->with($relationship)->where('KodeUnitKerjaPenilai', $kodeunitkerjapenilai)->get();
    }

    /**
     * @param $kodeUnitKerja
     * @param $kodeUnitKerjaPenilai
     * @param null $id
     * @return bool
     */
    public function checkExist($kodeUnitKerja, $kodeUnitKerjaPenilai, $id = null)
    {
        $builder = $this->matrixValidation->where('KodeUnitKerja', $kodeUnitKerja)
            ->where('KodeUnitKerjaPenilai', $kodeUnitKerjaPenilai);
        if (isset($id)) {
            $builder->whereNotIn('ID', [$id]);
        }
        return $builder->exists();
    }
}
