<?php
namespace App\Domain\User\Services;

use App\Domain\User\Entities\User;
use App\Exceptions\DomainException;

class CheckUserDuplication
{
    /**
     * @var User
     */
    protected $user;

    /**
     * CheckUserDuplication constructor.
     *
     * @param User $user
     */
    public function __construct(User $user)
    {
        $this->user = $user;
    }

    /**
     * @param $npk
     * @param $idRole
     * @param null $userId
     * @return bool
     * @throws DomainException
     */
    public function check($npk, $userId = null)
    {
        $data = $this->user->select('ID')->where('NPK', $npk)->first();
        if (! is_null($data)) {
            if (isset($userId)) {
                if ($data->ID == $userId) {
                    throw new DomainException('User dengan NPK yang didaftarkan telah ada di database.');
                }
                return true;
            }
            throw new DomainException('User dengan NPK yang didaftarkan telah ada di database.');
        }
        return true;
    }
}
