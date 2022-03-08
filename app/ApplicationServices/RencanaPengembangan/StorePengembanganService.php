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
use Ramsey\Uuid\Uuid;

class StorePengembanganService extends ApplicationService
{

    /**
     * @var DetilRencanaKPI
     */
    protected $detilRencanaKPI;

    /**
     * DeletePengembanganService constructor.
     * @param DetilRencanaKPI $detilRencanaKPI
     */
    public function __construct(DetilRencanaKPI $detilRencanaKPI)
    {
        $this->detilRencanaKPI = $detilRencanaKPI;
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
            $pengembangan = new RencanaPengembangan();
            $pengembangan->ID = Uuid::uuid4();
            $pengembangan->RencanaPengembangan = isset($data['FollowUp'])?$data['FollowUp']:'-';
            $pengembangan->Keterangan = isset($data['Keterangan'])?$data['Keterangan']:null;
            $pengembangan->save();
            $itemrencana = $this->detilRencanaKPI->find($iddetilrencana);
            $itemrencana->IDRencanaPengembangan=$pengembangan->ID;
            $itemrencana->RencanaPengembangan= isset($data['RencanaPengembangan'])?$data['RencanaPengembangan']:null;
            $itemrencana->update();
            DB::commit();
            return $this->successResponse();
        } catch (\Exception $e) {
            DB::rollback();
            return $this->errorResponse($e->getMessage());
        }
    }
}
