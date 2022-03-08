<?php
namespace App\Infrastructures\Repositories\KPI;

use App\Domain\KPI\Entities\UnitKerja;

class UnitKerjaRepository
{
    /**
     * @var UnitKerja
     */
    protected $model;

    /**
     * UnitKerjaRepository constructor.
     *
     * @param UnitKerja $model
     */
    public function __construct(UnitKerja $model)
    {
        $this->model = $model;
    }

    /**
     * @return mixed
     */
    public function datatable()
    {
        return $this->model->select('Ms_UnitKerja.*');
    }

    /**
     * @return int
     */
    public function count()
    {
        return $this->model->count();
    }

    /**
     * @return \Illuminate\Database\Eloquent\Collection|static[]
     */
    public function all()
    {
        return $this->model->select('CostCenter', 'Deskripsi')->orderBy('CostCenter')->get();
    }

    /**
     * @param $costcenter
     * @return mixed
     */
    public function findById($costcenter)
    {
        return $this->model->find($costcenter);
    }

    /**
     * @return \Illuminate\Support\Collection
     */
    public function asList()
    {
        return $this->model->pluck('Deskripsi', 'CostCenter');
    }
}
