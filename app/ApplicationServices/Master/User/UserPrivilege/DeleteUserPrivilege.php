<?php
/**
 * Created by PhpStorm.
 * User: SENA
 * Date: 07/25/2017
 * Time: 08:20 PM
 */

namespace App\ApplicationServices\Master\User\UserPrivilege;

use App\ApplicationServices\ApplicationService;
use App\Domain\User\Entities\UserPrivilege;

class DeleteUserPrivilege extends ApplicationService
{

    /**
     * @var UserPrivilege
     */
    protected $userPrivilege;
    /**
     * DeleteUser constructor.
     *
     * @param UserPrivilege $userPrivilege
     */
    public function __construct(UserPrivilege $userPrivilege)
    {
        $this->userPrivilege = $userPrivilege;
    }

    /**
     * @param $id
     * @return array
     */
    public function call($id)
    {
        try {
            $userPrivilege = $this->userPrivilege->where('IDUser', $id);
            $userPrivilege->delete();
            return $this->successResponse();
        } catch (\Exception $e) {
            return $this->errorResponse($e->getMessage());
        }
    }
}
