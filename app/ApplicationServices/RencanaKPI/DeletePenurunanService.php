<?php
namespace App\ApplicationServices\RencanaKPI;

use Illuminate\Support\Facades\DB;
use App\ApplicationServices\ApplicationService;
use App\Domain\Rencana\Entities\KRAKPIDetail;

class DeletePenurunanService extends ApplicationService
{
    /**
     * @param array $data
     * @return array
     */
    public function call(array $data)
    {
        try {
            DB::beginTransaction();
            KRAKPIDetail::destroy($data['id']);
            DB::commit();
            return $this->successResponse();
        } catch (\Exception $e) {
            DB::rollback();
            return $this->errorResponse($e->getMessage());
        }
    }
}
