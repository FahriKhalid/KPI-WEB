<?php
namespace App\Domain\Rencana\Services;

use App\Exceptions\DomainException;

class ItemLimitationService
{
    protected $nonTaskForceCount;
    protected $taskForceCount;
    protected $minNonTaskForce = 5;
    protected $maxNonTaskForce = 12;
    protected $maxTaskForce = 5;
    protected $totalMaxLimit = 17;

    /**
     * ItemLimitationService constructor.
     *
     * @param $nonTaskForceCount
     * @param $taskForceCount
     */
    public function __construct($nonTaskForceCount, $taskForceCount)
    {
        $this->nonTaskForceCount = $nonTaskForceCount;
        $this->taskForceCount = $taskForceCount;
    }

    /**
     * @param $idAspekKPI
     * @return bool
     * @throws DomainException
     */
    public function isAvailable($idAspekKPI)
    {
        // non task force ID should 1 - 3

        if(\Auth::user()->IDRole == 8) // akun direksi
        {

        }
        else
        {
            if ($idAspekKPI != 4) {
                if ($this->nonTaskForceCount === $this->maxNonTaskForce) {
                    throw new DomainException('Jumlah item KPI Strategis & Rutin adalah 5 - 12. Jumlah item kpi yang Anda simpan adalah: '.$this->nonTaskForceCount);
                }
                return true;
            } else {
                if ($this->taskForceCount === $this->maxTaskForce) {
                    throw new DomainException('Jumlah maksimal item KPI Task Force adalah 5. Jumlah item kpi task force yang Anda simpan adalah:  '.$this->taskForceCount);
                }
                return true;
            }
        } 
    }
}
