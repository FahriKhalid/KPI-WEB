<?php
/**
 * Created by PhpStorm.
 * User: SENA
 * Date: 07/25/2017
 * Time: 08:19 PM
 */

namespace App\ApplicationServices\Master\User\UserPrivilege;

use App\ApplicationServices\ApplicationService;
use App\Domain\User\Entities\UserPrivilege;

class CreateUserPrivilege extends ApplicationService
{
    /**
     * @param $id
     * @param array $privilege is containing privileges id
     * @return array
     */
  public function call($id, array $privilege)
  {
      try {
          foreach ($privilege as $IDRole) {
              $userPrivilege = new UserPrivilege();
              $userPrivilege->IDUser = $id;
              $userPrivilege->IDRole = $IDRole;
              $userPrivilege->save();
          }
          return $this->successResponse();
      } catch (\Exception $e) {
          return $this->errorResponse($e->getMessage());
      }
  }
}
