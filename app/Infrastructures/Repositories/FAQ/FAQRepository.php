<?php

namespace App\Infrastructures\Repositories\FAQ;

use App\Domain\FAQ\Entities\FAQ;

class FAQRepository
{
    /**
     * @var
     */
    protected $model;

    /**
     * FAQRepository constructor.
     * @param FAQ $faq
     */
    public function __construct(FAQ $faq)
    {
        $this->model = $faq;
    }

    /**
     * @param $id
     * @return \Illuminate\Database\Eloquent\Collection|\Illuminate\Database\Eloquent\Model|null|static|static[]
     */
    public function findById($id)
    {
        return $this->model->find($id);
    }

    /**
     * @param $npk
     * @return \Illuminate\Database\Eloquent\Collection|\Illuminate\Support\Collection|static[]
     */
    public function findByNPK($npk)
    {
        return $this->model->with(['askedby','answeredby'])->whereNotNull('Answer')->where('Aktif', true)->orWhere('AskedBy', $npk)->get();
    }
    /**
     * @return \Illuminate\Database\Eloquent\Collection|static[]
     */
    public function findAllActive()
    {
        return $this->model->with(['askedby','answeredby'])->where('Aktif', true)->get();
    }

    /**
     * @return \Illuminate\Database\Eloquent\Collection|static[]
     */
    public function findAllAnswered()
    {
        return $this->model->with(['askedby','answeredby'])->whereNotNull('Answer')->get();
    }

    /**
     * @return \Illuminate\Database\Eloquent\Collection|static[]
     */
    public function findAllAnsweredAndActive()
    {
        return $this->model->with(['askedby','answeredby'])->whereNotNull('Answer')->where('Aktif', true)->get();
    }

    /**
     * Check if user by npk is already asked and question answered
     * @param $npk
     * @return bool
     */
    public function isActiveSession($npk)
    {
        return $this->model->whereNotNull('Answer')->where('AskedBy', $npk)->exists();
    }
    /**
     * @return \Illuminate\Database\Eloquent\Collection|static[]
     */
    public function getAll()
    {
        return $this->model->with(['askedby','answeredby'])->get();
    }
}
