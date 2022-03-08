<?php
namespace App\Infrastructures\Repositories\KPI;

use App\Domain\KPI\Entities\Info;

class InfoRepository
{
    /**
     * @var Info
     */
    protected $model;

    /**
     * InfoRepository constructor.
     *
     * @param Info $info
     */
    public function __construct(Info $info)
    {
        $this->model = $info;
    }

    /**
     * @return mixed
     */
    public function allRaw()
    {
        return $this->model->get();
    }

    /**
     * @param $date
     * @return mixed
     */
    public function allActive($date)
    {
        return $this->model
            ->where([ ['Tanggal_publish', '<=', $date],['Tanggal_berakhir', '>=', $date] ])
            ->orwhere([ ['Tanggal_publish', '<=', $date],['Tanggal_berakhir', '=', null] ])
            ->orderBy('Tanggal_publish', 'desc')->get();
    }

    /**
     * @param $id
     * @return mixed
     */
    public function findById($id)
    {
        return $this->model->find($id);
    }
}
