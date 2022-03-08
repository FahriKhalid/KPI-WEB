<?php

namespace App\Policies;

use App\Domain\Realisasi\Entities\HeaderRealisasiKPI;
use App\Domain\User\Entities\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class ValidasiUnitKerjaPolicy
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
     * @param HeaderRealisasiKPI $headerRealisasiKPI
     * @return bool
     */
    public function createValidasi(User $user, HeaderRealisasiKPI $headerRealisasiKPI)
    {
        return ! $headerRealisasiKPI->validasiunitkerja()->whereNotIn('IDStatusDokumen', [5])->where('CreatedBy', $user->NPK)->exists();
    }

    /**
     * @param User $user
     * @param HeaderRealisasiKPI $headerRealisasiKPI
     * @return bool
     */
    public function editValidasi(User $user, HeaderRealisasiKPI $headerRealisasiKPI)
    {
        return $headerRealisasiKPI->validasiunitkerja()->where('IDStatusDokumen', 6)->where('CreatedBy', $user->NPK)->where('IDKPIRealisasiHeader', $headerRealisasiKPI->ID)->exists();
    }
}
