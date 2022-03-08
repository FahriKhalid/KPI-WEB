<?php
namespace App\Infrastructures\Repositories\KPI;

use App\Domain\KPI\Entities\Kamus;

class KamusRepository
{
    /**
     * @var Kamus
     */
    protected $model;

    /**
     * KamusRepository constructor.
     *
     * @param Kamus $model
     */
    public function __construct(Kamus $model)
    {
        $this->model = $model;
    }

    /**
     * @param $user
     * @return mixed
     */
    public function datatable($user)
    {
        $relationships = ['aspekkpi', 'jenisappraisal', 'satuan', 'persentaserealisasi', 'perioderealisasi'];
        $queryBuilder =  $this->model->with($relationships)->select('Ms_KamusKPI.*');
        if ($user->UserRole->Role != 'Administrator') {
            $queryBuilder->where('Status', '=', 'approved');
        }
        return $queryBuilder;
    }

    public function allApproved()
    {
        $relationships = ['aspekkpi', 'jenisappraisal', 'satuan', 'persentaserealisasi', 'perioderealisasi'];
        return $this->model->with($relationships)->select('Ms_KamusKPI.*')->where('Status', '=', 'approved');
    }

    /**
     * @param string $status
     * @return mixed
     */
    public function countByStatus($status = 'approved')
    {
        return $this->model->select('ID')->where('Status', $status)->count();
    }

    /**
     * @param $npk
     * @param $idStatusDokumen
     * @param null $tahun
     * @return int
     */
    public function countStatusUpdatedDocumentBy($npk, $idStatusDokumen, $tahun = null)
    {
        $builder = $this->model->select('ID');
        if ($tahun !=null) {
            $builder->whereYear('UpdatedOn', $tahun);
        }
        switch ($idStatusDokumen) {
            // Created by or Pending
            case 1:
                $builder->where('CreatedBy', $npk)->where('Status', ''.Kamus::PENDING);
                break;
            // Rejected by
            case 2:
                $builder->where('UpdatedBy', $npk)->where('Status', ''.Kamus::REJECTED);
                break;
            // Approved by
            case 3:
                $builder->where('ApprovedBy', $npk)->where('Status', ''.Kamus::APPROVED);
                break;
        }
        return $builder->count();
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
     * @param $kodeRegistrasi
     * @return mixed
     */
    public function findByKodeRegistrasi($kodeRegistrasi)
    {
        return $this->model->with(['aspekkpi', 'jenisappraisal', 'persentaserealisasi', 'satuan'])->where('KodeRegistrasi', $kodeRegistrasi)->first();
    }

    /**
     * @return array
     */
    public function getStatusList()
    {
        return [
            Kamus::PENDING=>ucfirst(Kamus::PENDING),
            Kamus::REJECTED=>ucfirst(Kamus::REJECTED),
            Kamus::APPROVED=>ucfirst(Kamus::APPROVED)
        ];
    }
}
