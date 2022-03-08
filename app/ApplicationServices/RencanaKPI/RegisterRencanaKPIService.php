<?php
namespace App\ApplicationServices\RencanaKPI;

use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use App\ApplicationServices\ApplicationService;
use App\Domain\Karyawan\Services\PositionAbbreviation;
use App\Domain\Rencana\Services\CascadePercentageService;
use App\Exceptions\DomainException;
use App\Domain\Rencana\Entities\DetilRencanaKPI;
use App\Domain\Rencana\Entities\KRAKPIDetail;
use App\Infrastructures\Repositories\KPI\RencanaKPIRepository;
use Illuminate\Support\Facades\Mail;
use App\Mail\SendMail;

class RegisterRencanaKPIService extends ApplicationService
{
    /**
     * @var RencanaKPIRepository
     */
    protected $rencanaKPIRepository;

    /**
     * @var KRAKPIDetail
     */
    protected $krakpiDetail;

    /**
     * @var DetilRencanaKPI
     */
    protected $detilRencanaKPI;

    /**
     * RegisterRencanaKPIService constructor.
     *
     * @param RencanaKPIRepository $rencanaKPIRepository
     * @param KRAKPIDetail $KRAKPIDetail
     * @param DetilRencanaKPI $detilRencanaKPI
     */
    public function __construct(
        RencanaKPIRepository $rencanaKPIRepository,
        KRAKPIDetail $KRAKPIDetail,
        DetilRencanaKPI $detilRencanaKPI
    ) {
        $this->rencanaKPIRepository = $rencanaKPIRepository;
        $this->krakpiDetail = $KRAKPIDetail;
        $this->detilRencanaKPI = $detilRencanaKPI;
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
            foreach ($data['id'] as $id) {
                $header = $this->rencanaKPIRepository->findById($id);
                $header->isAllowedToRegister();
                $this->itemIsValid($header->ID);
                $this->bobotIsValid($header->ID);
                $this->isRencanaAtasanIsApproved($header, $user);
                $this->checkPendingCascadingItems($user->NPK, $header->Tahun);
                $this->checkCascadingItemIsAlreadyAssigned($header->ID);
                $this->checkCascadingPercentage($user->NPK, $header->Tahun);

                $header->IDStatusDokumen = 2;
                $header->RegisteredBy = $user->NPK;
                $header->RegisteredOn = Carbon::now('Asia/Jakarta');
                $header->save();
            }
            DB::commit(); 

            return $this->successResponse();
        } catch (\Exception $e) {
            DB::rollback();
            return $this->errorResponse($e->getMessage());
        }
    }

    /**
     * @param $headerId
     * @return bool
     * @throws DomainException
     */
    protected function bobotIsValid($headerId)
    {
        $bobotNonTaskForce = $this->rencanaKPIRepository->countBobotItem($headerId);
        if ($bobotNonTaskForce != 100) {
            throw new DomainException('Bobot total rencana KPI Strategis & Rutin harus 100%. Bobot total rencana KPI Strategis & Rutin Anda saat ini adalah: ' . $bobotNonTaskForce . '%');
        }
        return true;
    }

    /**
     * @param $headerId
     * @return bool
     * @throws DomainException
     */
    public function itemIsValid($headerId)
    {
        $totalStrategis = $this->rencanaKPIRepository->countItemKPI($headerId, 1);
        $totalRutinStruktural = $this->rencanaKPIRepository->countItemKPI($headerId, 2);
        $totalOperasional = $this->rencanaKPIRepository->countItemKPI($headerId, 3);
        $totalNonTaskForce = $totalStrategis + $totalRutinStruktural + $totalOperasional; 


        if ($totalNonTaskForce < 5) {
            throw new DomainException('Jumlah item KPI Strategis & Rutin adalah 5 - 12. Jumlah item Anda adalah:  ' . $totalNonTaskForce);
        }
        return true;
    }

    /**
     * @param $npkBawahan
     * @param $tahun
     * @return bool
     * @throws DomainException
     */
    public function checkPendingCascadingItems($npkBawahan, $tahun)
    {
        // check if there are pending cascading items
        $count = $this->krakpiDetail->whereHas('detailkpi.headerrencana', function ($query) use ($tahun) {
            $query->where('Tahun', $tahun)->whereNotIn('IDStatusDokumen', [4, 5]);
        })->where('NPKBawahan', $npkBawahan)->where('IsApproved', 0)->count();
        if ($count > 0) {
            throw new DomainException('Ada item KPI diturunkan kepada Anda yang masih menunggu approval.');
        }
        return true;
    }

    /**
     * @param $npk
     * @param $tahun
     * @return bool
     * @throws DomainException
     */
    protected function checkCascadingPercentage($npk, $tahun)
    {
        $totalPercentage = $this->krakpiDetail->whereHas('detailkpi.headerrencana', function ($query) use ($npk, $tahun) {
            $query->where('Tahun', $tahun)->whereNotIn('IDStatusDokumen', [4, 5])->where('NPK', $npk);
        })->where('IsApproved', 0)->sum('PersentaseKRA');
        if (!CascadePercentageService::isValid($totalPercentage)) {
            throw new DomainException('Persentase KRA item KPI yang anda turunkan ada yang belum berjumlah 100%.');
        }
        return true;
    }

    /**
     * @param $headerId
     * @return bool
     * @throws DomainException
     */
    protected function checkCascadingItemIsAlreadyAssigned($headerId)
    {
        // select all item that flagged as KRA Bawahan
        $details = $this->detilRencanaKPI->where('IDKPIRencanaHeader', $headerId)
                                        ->asKRABawahan()->select('ID')->get();

        // loop through result to count if there are assigned data and push to temporary array
        // if there is 0 value, that means item KPI is not assigned yet
        $arrTemp = [];
        foreach ($details as $detail) {
            $arrTemp[] =  $this->krakpiDetail->where('IDKPIAtasan', $detail->ID)->count();
        }
        if (in_array(0, $arrTemp)) {
            throw new DomainException('Ada Item KPI yang belum diturunkan kepada bawahan Anda.');
        }
        return true;
    }

    /**
     * @param $header
     * @return bool
     * @throws DomainException
     */
    protected function isRencanaAtasanIsApproved($header, $user)
    { 
        if($user->IDRole == 8){
            $positionAbbreviation = $this->getDireksi($user->NPK)->PositionAbbreviation; 
        }else{ 
            $positionAbbreviation = $header->karyawan->organization->position->PositionAbbreviation;
        }
        
        $paService = new PositionAbbreviation();
        
        $paService->position($positionAbbreviation);

        if (! $paService->isDirut()) {
            $approvedHeaderAtasan = $this->rencanaKPIRepository->findApprovedRencanaByNPKTahun($header->NPKAtasanLangsung, $header->Tahun);
            if (is_null($approvedHeaderAtasan)) {
                throw new DomainException('KPI Anda bisa diregister jika KPI atasan langsung anda periode tahun '.$header->Tahun.' telah di-APPROVE.');
            }
            return true;
        }
        return true;
    }
}
