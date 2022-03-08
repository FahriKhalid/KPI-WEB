<?php
/**
 * Created by PhpStorm.
 * User: SENA
 * Date: 10/01/2017
 * Time: 07:47 PM
 */

namespace App\ApplicationServices\RencanaPengembangan;

use Illuminate\Support\Facades\DB;
use App\ApplicationServices\ApplicationService;
use App\Domain\KPI\Entities\RencanaPengembangan;

class DeletePengembanganService extends ApplicationService
{
    /**
     * @var RencanaPengembangan
     */
    protected $rencanaPengembangan;

    /**
     * DeletePengembanganService constructor.
     * @param RencanaPengembangan $rencanaPengembangan
     */
    public function __construct(RencanaPengembangan $rencanaPengembangan)
    {
        $this->rencanaPengembangan = $rencanaPengembangan;
    }

    /**
     * @return array
     */
    public function call($id)
    {
        try {
            DB::beginTransaction();
            $pengembangan = $this->rencanaPengembangan->find($id);
            $pengembangan->detilrencana->first()->update(['IDRencanaPengembangan'=>null,'RencanaPengembangan'=>null]);
            $pengembangan->delete();
            DB::commit();
            return $this->successResponse();
        } catch (\Exception $e) {
            DB::rollback();
            return $this->errorResponse($e->getMessage());
        }
    }
}
