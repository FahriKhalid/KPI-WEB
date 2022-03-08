<?php

namespace App\Policies;

use App\Domain\User\Entities\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class RealisasiIndividuPolicy
{
    use HandlesAuthorization;

    /**
     * Create a new policy instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * @param User $user
     * @return bool
     */
    public function create(User $user)
    {
        $role = $user->UserRole->Role;

        return ($role == 'Karyawan' || $role == 'Direksi (BoD)');
    }

    /**
     * @param User $user
     * @return bool
     */
    public function revisiRealisasi(User $user)
    {
        $role = $user->UserRole->Role;

        return ($role == 'Karyawan' || $role == 'Direksi (BoD)');
    }

    /**
     * @param User $user
     * @return bool
     */
    public function register(User $user)
    {
        $role = $user->UserRole->Role;

        return ($role == 'Karyawan' || $role == 'Administrator' || $role == 'Direksi (BoD)');
    }

    /**
     * @param User $user
     * @return bool
     */
    public function cancel(User $user)
    {
        $role = $user->UserRole->Role;

        return ($role == 'Karyawan' || $role == 'Administrator' || $role == 'Direksi (BoD)');
    }

    /**
     * @param User $user
     * @return bool
     */
    public function adminConfirmation(User $user)
    {
        $role = $user->UserRole->Role;

        return $role == 'Administrator';
    }

    /**
     * @param User $user
     * @return bool
     */
    public function adminApproval(User $user)
    {
        $role = $user->UserRole->Role;

        return $role == 'Administrator';
    }
}
