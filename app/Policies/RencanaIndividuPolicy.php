<?php

namespace App\Policies;

use App\Domain\Rencana\Entities\HeaderRencanaKPI;
use App\Domain\User\Entities\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class RencanaIndividuPolicy
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
     * Header rencana must be owned by user, user must logged in as karyawan, & document status is draft
     *
     * @param User $user
     * @param HeaderRencanaKPI $headerRencanaKPI
     * @return bool
     */
    public function createPenurunan(User $user, HeaderRencanaKPI $headerRencanaKPI)
    {
        return $this->generalRole($user, $headerRencanaKPI);
    }

    /**
     * Header rencana must be owned by user, user must logged in as karyawan, & document status is draft
     *
     * @param User $user
     * @param HeaderRencanaKPI $headerRencanaKPI
     * @return bool
     */
    public function createDocument(User $user, HeaderRencanaKPI $headerRencanaKPI)
    {
        return $this->generalRole($user, $headerRencanaKPI);
    }

    /**
     * Header rencana must be owned by user, user must logged in as karyawan, & document status is draft
     *
     * @param User $user
     * @param HeaderRencanaKPI $headerRencanaKPI
     * @return bool
     */
    public function deleteItems(User $user, HeaderRencanaKPI $headerRencanaKPI)
    {
        return $this->generalRole($user, $headerRencanaKPI);
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
    public function revisiTarget(User $user)
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

    /**
     * @param $user
     * @param $headerRencanaKPI
     * @return bool
     */
    protected function generalRole($user, $headerRencanaKPI)
    {
        $role = $user->UserRole->Role;
        if ($headerRencanaKPI->isDraft()) {
            if ($role == 'Administrator') {
                return true;
            }

            if ($role == 'Karyawan' && ($user->NPK == $headerRencanaKPI->NPK)) {
                return true;
            }

            if($role == 'Direksi (BoD)' && ($user->NPK == $headerRencanaKPI->NPK)){
                return true;
            }
        }
        return false;
    }
}
