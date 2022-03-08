<?php
namespace App\Infrastructures\Repositories\KPI;

use App\Domain\KPI\Entities\Kompetensi;

class KompetensiRepository
{
    /**
     * @var Kompetensi
     */
    protected $model;

    /**
     * KompetensiRepository constructor.
     *
     * @param Kompetensi $model
     */
    public function __construct(Kompetensi $model)
    {
        $this->model = $model;
    }

    /**
     * @return mixed
     */
    public function datatable()
    {
        return $this->model->select('Ms_Kompetensi.*');
    }

    /**
     * @param $id
     * @return mixed
     */
    public function findById($id)
    {
        return $this->model->find($id);
    }

    public function asList()
    {
        return $this->model->pluck('ID', 'ID');
    }
}
