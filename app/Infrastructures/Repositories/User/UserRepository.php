<?php
namespace App\Infrastructures\Repositories\User;

use App\Domain\User\Entities\User;

class UserRepository
{
    /**
     * @var User
     */
    protected $model;

    /**
     * UserRepository constructor.
     *
     * @param User $user
     */
    public function __construct(User $user)
    {
        $this->model = $user;
    }

    /**
     * @return \Illuminate\Database\Query\Builder
     */
    public function datatable()
    {
        return $this->model->with(['karyawan', 'UserRole'/*,'Roles'*/])->select(['Users.*']);
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
