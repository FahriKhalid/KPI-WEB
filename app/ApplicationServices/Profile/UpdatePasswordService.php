<?php
/**
 * Created by PhpStorm.
 * User: SENA
 * Date: 09/18/2017
 * Time: 10:08 AM
 */

namespace App\ApplicationServices\Profile;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use App\ApplicationServices\ApplicationService;
use App\Domain\User\Entities\User;
use App\Exceptions\DomainException;

class UpdatePasswordService extends ApplicationService
{
    /**
     * @param array $data
     * @param User $user
     * @return array
     */
    public function call(array $data, User $user)
    {
        DB::beginTransaction();
        try {
            $this->validate($data, $user);
            $user->password = bcrypt($data['password']);
            $user->save();
            DB::commit();
            return $this->successResponse();
        } catch (\Exception $e) {
            DB::rollback();
            return $this->errorResponse($e->getMessage());
        }
    }

    /**
     * @param $data
     * @param $user
     * @throws DomainException
     */
    public function validate($data, $user)
    {
        if (! Hash::check($data['old_password'], $user->password)) {
            throw new DomainException('Password lama tidak sesuai');
        }
    }
}
