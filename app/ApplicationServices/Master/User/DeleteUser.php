<?php
namespace App\ApplicationServices\Master\User;

use App\ApplicationServices\ApplicationService;
use App\Domain\User\Entities\User;

class DeleteUser extends ApplicationService
{
    /**
     * @var User
     */
    protected $user;

    /**
     * DeleteUser constructor.
     *
     * @param User $user
     */
    public function __construct(User $user)
    {
        $this->user = $user;
    }

    /**
     * @param $id
     * @return array
     */
    public function call($id)
    {
        $user = $this->user->find($id);
        try {
            $user->delete();
            return $this->successResponse();
        } catch (\Exception $e) {
            return $this->errorResponse($e->getMessage());
        }
    }
}
