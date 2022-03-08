<?php
/**
 * Created by PhpStorm.
 * User: SENA
 * Date: 07/25/2017
 * Time: 09:12 PM
 */

namespace App\Infrastructures\Repositories\User;

use App\Domain\User\Entities\UserPrivilege;

class UserPrivilegeRepository
{
    protected $model;

    public function __construct(UserPrivilege $userPrivilege)
    {
        $this->model = $userPrivilege;
    }
    /**
     * @param $id
     * @return mixed
     */
    public function findById($id)
    {
        return $this->model->where('IDUser', $id)->get();
    }
}
