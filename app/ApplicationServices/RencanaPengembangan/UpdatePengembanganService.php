<?php
/**
 * Created by PhpStorm.
 * User: SENA
 * Date: 10/01/2017
 * Time: 07:46 PM
 */

namespace App\ApplicationServices\RencanaPengembangan;

use Illuminate\Support\Facades\DB;
use App\ApplicationServices\ApplicationService;
use App\Domain\KPI\Entities\RencanaPengembangan;
use App\Domain\Rencana\Entities\DetilRencanaKPI;

class UpdatePengembanganService extends ApplicationService
{
    /**
     * @var DetilRencanaKPI
     */
    protected $detilRencanaKPI;

    /**
     * @var RencanaPengembangan
     */
    protected $rencanaPengembangan;

    /**
     * DeletePengembanganService constructor.
     * @param DetilRencanaKPI $detilRencanaKPI
     * @param RencanaPengembangan $rencanaPengembangan
     */
    public function __construct(DetilRencanaKPI $detilRencanaKPI, RencanaPengembangan $rencanaPengembangan)
    {
        $this->detilRencanaKPI = $detilRencanaKPI;
        $this->rencanaPengembangan =$rencanaPengembangan;
    }

    /**
     * @param $iddetilrencana
     * @param array $data
     * @return array
     */
    public function call($iddetilrencana, array $data)
    {
        try {
            DB::beginTransaction();
            $itemrencana = $this->detilRencanaKPI->find($iddetilrencana);
            $itemrencana->RencanaPengembangan = isset($data['RencanaPengembangan'])?$data['RencanaPengembangan']:null;
            $itemrencana->update();
            $pengembangan = $this->rencanaPengembangan->find($itemrencana->IDRencanaPengembangan);
            $pengembangan->RencanaPengembangan = isset($data['FollowUp'])?$data['FollowUp']:'-';
            $pengembangan->Keterangan = isset($data['Keterangan'])?$data['Keterangan']:null;
            $pengembangan->update();
            DB::commit();
            return $this->successResponse();
        } catch (\Exception $e) {
            DB::rollback();
            return $this->errorResponse($e->getMessage());
        }
    }
}
