<?php
namespace App\ApplicationServices\Master\User;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use App\ApplicationServices\ApplicationService;
use App\Domain\User\Entities\User;
use App\Domain\User\Services\CheckUserDuplication;
use App\Domain\User\Entities\UserPrivilege;

class UpdateUser extends ApplicationService
{
    /**
     * @var CheckUserDuplication
     */
    protected $checkUserDuplication;

    /**
     * UpdateUser constructor.
     *
     * @param CheckUserDuplication $checkUserDuplication
     */
    public function __construct(CheckUserDuplication $checkUserDuplication)
    {
        $this->checkUserDuplication = $checkUserDuplication;
    }

    /**
     * @param $id
     * @param array $data
     * @return array
     */
    public function call($id, array $data)
    {
        DB::beginTransaction();
        $user = User::find($id);
        try {
            $this->update_privilege($id, $data); //call function with parsing data
            $user->username = $data['username'];
            $user->Ldap_active = $data['ldap_active'];
            if (! empty($data['password'])) {
                $user->password = bcrypt($data['password']);
            }
            $user->save();
            DB::commit();
            return $this->successResponse();
        } catch (\Exception $e) {
            DB::rollback();
            return $this->errorResponse($e->getMessage());
        }
    }

    /**
     * @param $id
     * @param array $data
     * @return array
     */
    public function activatePrivilege($id, array $data)
    {
        $user = User::find($id);
        try {
            $user->IDRole = $data['IDRole']===null ? 3 : $data['IDRole'];
            $user->save();
            return $this->successResponse();
        } catch (\Exception $e) {
            return $this->errorResponse($e->getMessage());
        }
    }

    /**
     * @param $id
     * @param array $data
     * @return array
     */
    public function update_privilege($id, array $data)
    {
        try {
            UserPrivilege::where('IDUser', $id)->delete(); //biar ga duplikat
            UserPrivilege::create(array(
                'IDUser'    => $id,
                'IDRole'    => 3
            ));
            foreach ($data['IDRole'] as $role) {
                UserPrivilege::create([
                    'IDUser'    => $id,
                    'IDRole'    => $role
                ]);
            }
            
            return $this->successResponse();
        } catch (\Exception $e) {
            return $this->errorResponse($e->getMessage());
        }
    }

    /**
     * @param $id
     * @return array
     */
    public function isLogout($id)
    {
        $user = User::find($id);
        try {
            $user->isLogin = false;
            $user->save();
            return $this->successResponse();
        } catch (\Exception $e) {
            return $this->errorResponse($e->getMessage());
        }
    }
}
