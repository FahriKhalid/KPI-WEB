<?php
/**
 * Created by PhpStorm.
 * User: SENA
 * Date: 07/25/2017
 * Time: 08:20 PM
 */

namespace App\ApplicationServices\Master\User\UserPrivilege;

use Illuminate\Support\Facades\DB;
use App\ApplicationServices\ApplicationService;

class UpdateUserPrivilege extends ApplicationService
{
    /**
     * @var DeleteUserPrivilege
     */
    protected $deleteUserPrivilege;

    /**
     * @var CreateUserPrivilege
     */
    protected $createUserPrivilege;

    /**
     * UpdateUserPrivilege constructor.
     *
     * @param CreateUserPrivilege $createUserPrivilege
     * @param DeleteUserPrivilege $deleteUserPrivilege
     */
    public function __construct(CreateUserPrivilege $createUserPrivilege, DeleteUserPrivilege $deleteUserPrivilege)
    {
        $this->createUserPrivilege = $createUserPrivilege;
        $this->deleteUserPrivilege = $deleteUserPrivilege;
    }

    /**
     * @param $id
     * @param array $privilege is containing privileges id
     * @return array
     */
    public function call($id, array $privilege)
    {
        try {
            DB::beginTransaction();
            $this->deleteUserPrivilege->call($id);
            $this->createUserPrivilege->call($id, $privilege);
            DB::commit();
            return $this->successResponse();
        } catch (\Exception $e) {
            DB::rollback();
            return $this->errorResponse($e->getMessage());
        }
    }
}
