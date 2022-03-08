<?php
namespace App\Infrastructures\Repositories\KPI;

use App\Domain\KPI\Entities\PeriodeAktif;

class PeriodeAktifRepository
{
    /**
     * @var PeriodeAktif
     */
    protected $model;

    /**
     * PeriodeAktifRepository constructor.
     *
     * @param PeriodeAktif $model
     */
    public function __construct(PeriodeAktif $model)
    {
        $this->model = $model;
    }

    /**
     * @return mixed
     */
    public function datatable()
    {
        return $this->model->with(['jenisperiode']);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Model|null|static
     */
    public function getActive()
    {
        return $this->model->select(['ID', 'NamaPeriode', 'Tahun', 'StartDate', 'EndDate', 'StatusPeriode', 'Aktif'])
                        ->where('Aktif', 1)->first();
    }

    /**
     * @param $id
     * @return mixed
     */
    public function findById($id)
    {
        return $this->model->find($id);
    }

    /**
     * @param $tahun
     * @param array $relationships
     * @return mixed
     */
    public function findByTahun($tahun, $relationships = [])
    {
        return $this->model->with($relationships)->where('Tahun', $tahun)->get();
    }

    /**
     * @return mixed
     */
    public function getAllPeriode()
    {
        return $this->model->select('Tahun')->groupBy('Tahun')->get();
    }

    /**
     * @return \Illuminate\Database\Eloquent\Model|null|static
     */
    public function getFirstPeriode()
    {
        return $this->model->select('Tahun', 'IDJenisPeriode')->first();
    }

    /**
     * @param $year
     * @return \Illuminate\Database\Eloquent\Model|null|static
     */
    public function getFirstPeriodeByYear($year)
    {
        return $this->model->select('Tahun', 'IDJenisPeriode')->where('Tahun', $year)->first();
    }

    /**
     * @return \Illuminate\Support\Collection
     */
    public function asList()
    {
        return $this->model->pluck('ID', 'ID');
    }
}
