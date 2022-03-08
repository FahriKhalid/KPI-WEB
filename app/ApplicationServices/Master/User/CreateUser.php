<?php
namespace App\ApplicationServices\Master\User;

use Illuminate\Support\Facades\DB;
use App\ApplicationServices\ApplicationService;
use App\Domain\User\Entities\User;
use App\Domain\User\Services\CheckUserDuplication;
use App\Domain\User\Entities\UserPrivilege;
use App\Exceptions\DomainException;

class CreateUser extends ApplicationService
{
    /**
     * @var CheckUserDuplication
     */
    protected $checkUserDuplication;

    /**
     * CreateUser constructor.
     *
     * @param CheckUserDuplication $checkUserDuplication
     */
    public function __construct(CheckUserDuplication $checkUserDuplication)
    {
        $this->checkUserDuplication = $checkUserDuplication;
    }

    /**
     * @param array $data
     * @return array
     */
    public function call(array $data)
    {
        try {
            DB::beginTransaction();
            // check NPK && RoleID duplication
            $this->checkUserDuplication->check($data['NPK']);

            $user = new User();
            $user->IDRole = 3;
            $user->NPK = $data['NPK'];
            $user->username = $data['username'];
            $user->password = bcrypt($data['password']);
            $user->Ldap_active = $data['ldap_active'];
            $user->save();

            $this->update_privilege($user->ID, $data); //call function parsing
            DB::commit();
            return $this->successResponse();
        } catch (\Exception $e) {
            DB::rollback();
            return $this->errorResponse($e->getMessage());
        }
    }
    //=========================================================================================
    public function call_privilege($id, array $data)
    {
        $user = User::find($id);
        try {
            $user->IDRole = $data['IDRole'] === null ? 3 : $data['IDRole'];
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
                UserPrivilege::create(array(
                    'IDUser'    => $id,
                    'IDRole'    => $role
                ));
            }
            return $this->successResponse();
        } catch (\Exception $e) {
            return $this->errorResponse($e->getMessage());
        }
    }
}
