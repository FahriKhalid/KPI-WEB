<?php
namespace App\Infrastructures\Repositories\KPI;

use App\Domain\KPI\Entities\PeriodeRealisasi;

class PeriodeRealisasiRepository
{
    protected $model;

    public function __construct(PeriodeRealisasi $periodeRealisasi)
    {
        $this->model = $periodeRealisasi;
    }

    public function datatable()
    {
        $query = $this->model->select('VL_PeriodeRealisasi.Tahun')->distinct();
        return $query;
    }
    
    public function findById($id)
    {
        return $this->model->find($id);
    }

    public function findByTahun($tahun)
    {
        return $this->model->join('VL_JenisPeriode', 'VL_JenisPeriode.ID', '=', 'VL_PeriodeRealisasi.IDJenisPeriode')->select('VL_JenisPeriode.*')->where('VL_PeriodeRealisasi.Tahun', $tahun);
    }
}
