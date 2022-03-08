<?php
namespace App\ApplicationServices\RencanaKPI;

use App\ApplicationServices\ApplicationService;
use Illuminate\Support\Facades\DB;
use App\Domain\Rencana\Entities\DetilRencanaKPI;
use App\Domain\Rencana\Entities\KRAKPIDetail;
use App\Infrastructures\Repositories\KPI\RencanaKPIRepository;

class UnapproveRencanaKPIService extends ApplicationService
{
    /**
     * @var RencanaKPIRepository
     */
    protected $rencanaKPIRepository;

    /**
     * @var DetilRencanaKPI
     */
    protected $detilRencanaKPI;

    /**
     * @var KRAKPIDetail
     */
    protected $kraKpiDetail;

    /**
     * UnapproveRencanaKPIService constructor.
     *
     * @param RencanaKPIRepository $rencanaKPIRepository
     * @param DetilRencanaKPI $detilRencanaKPI
     * @param KRAKPIDetail $KRAKPIDetail
     */
    public function __construct(
        RencanaKPIRepository $rencanaKPIRepository,
        DetilRencanaKPI $detilRencanaKPI,
        KRAKPIDetail $KRAKPIDetail
    ) {
        $this->rencanaKPIRepository = $rencanaKPIRepository;
        $this->detilRencanaKPI = $detilRencanaKPI;
        $this->kraKpiDetail = $KRAKPIDetail;
    }

    /**
     * @param array $data
     * @param $user
     * @return array
     */
    public function call(array $data, $user)
    {
        try {
            DB::beginTransaction();
            $array = (array) $data['id'];
            foreach ($array as $id) {
                $header = $this->rencanaKPIRepository->findById($id);
                $header->isAllowedToUnapproved();  
 
                // get position user
                // $position = $this->rencanaKPIRepository->getPosition($header->NPK);
                // $substr = substr($position->PositionAbbreviation, 0, 8);
                // $PositionAbbreviation = rtrim($substr, 0);

                // update rencana header
                //$this->rencanaKPIRepository->updateBawahan($PositionAbbreviation, $header->Tahun, $position->Shift);

                $this->rencanaKPIRepository->updateBawahan($header); 
                $header->IDStatusDokumen = 1;  
                $header->CatatanUnapprove = (! empty($data['CatatanUnapprove'])) ? $data['CatatanUnapprove'] : null;
                $header->save();
 
                $this->updateAssociatedCascadingItem($header);  
            }
            DB::commit();
            return $this->successResponse();
        } catch (\Exception $e) {
            DB::rollback();
            return $this->errorResponse($e->getMessage());
        } 
    }
 
    /**
     * Handle only rencana is approved because if there are cascade items, it already cascaded to users
     * @param $headerRencana
     */
    protected function updateAssociatedCascadingItem($headerRencana)
    {
        // update cascading item to unapproved & delete associated data
        $collection = $this->kraKpiDetail->whereHas('detailkpi', function ($query) use ($headerRencana) {
            $query->where('IDKPIRencanaHeader', $headerRencana->ID);
        })->select(['ID', 'IDKPIAtasan']);
        $data = $collection->get();

        // set data to unapproved
        $collection->update(['IsApproved' => 0 , 'IsCascaded' => 0]);

        // delete associated cascaded data
        $dataID = [];
        foreach ($data as $key => $value) {
            $dataID[] = $value->ID;
        }

        if (count($dataID) > 0) {
            $this->detilRencanaKPI->whereIn('IDKRAKPIRencanaDetil', $dataID)->delete();
        }
    }
}
