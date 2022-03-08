<?php
namespace App\Infrastructures\Repositories\User;

use App\Domain\User\Entities\UserRole;

class UserRoleRepository
{
    protected $model;

    public function __construct(UserRole $userRole)
    {
        $this->model = $userRole;
    }

    public function asList()
    {
        return $this->model->pluck('Role', 'ID');
    }
    public function all()
    {
        return $this->model->all();
    }
}
