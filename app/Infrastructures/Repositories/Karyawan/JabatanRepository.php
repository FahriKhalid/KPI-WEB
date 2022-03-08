<?php 
namespace App\Infrastructures\Repositories\Karyawan;

use App\Domain\Karyawan\Entities\MasterPosition;

/**
 * summary
 */
class JabatanRepository
{
    /**
     * @var MasterPosition
     */
    protected $model;

    /**
     * JabatanRepository constructor.
     *
     * @param MasterPosition $jabatan
     */
    public function __construct(MasterPosition $jabatan)
    {
        $this->model = $jabatan;
    }

    /**
     * @return mixed
     */
    public function datatable()
    {
        return $this->model->with('unitkerja')->select('Ms_MasterPosition.*');
    }

    /**
     * @param $id
     * @return \Illuminate\Database\Eloquent\Collection|\Illuminate\Database\Eloquent\Model|null|static|static[]
     */
    public function findById($id)
    {
        return $this->model->find($id);
    }
}
