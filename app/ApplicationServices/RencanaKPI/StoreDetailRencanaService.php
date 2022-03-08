<?php
namespace App\ApplicationServices\RencanaKPI;

use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use App\ApplicationServices\ApplicationService;
use App\Domain\Karyawan\Services\PositionAbbreviation;
use App\Domain\Rencana\Entities\DetilRencanaKPI;
use App\Domain\Rencana\Entities\HeaderRencanaKPI;
use App\Domain\Rencana\Services\ItemLimitationService;
use App\Exceptions\DomainException;
use App\Infrastructures\Repositories\KPI\RencanaKPIRepository;
use Ramsey\Uuid\Uuid;

class StoreDetailRencanaService extends ApplicationService
{
    /**
     * @var RencanaKPIRepository
     */
    protected $rencanaKPIRepository;

    /**
     * StoreDetailRencanaService constructor.
     *
     * @param RencanaKPIRepository $rencanaKPIRepository
     */
    public function __construct(RencanaKPIRepository $rencanaKPIRepository)
    {
        $this->rencanaKPIRepository = $rencanaKPIRepository;
    }

    /**
     * @param $idRencanaHeader
     * @param array $data
     * @param $user
     * @return array
     */
    public function call($idRencanaHeader, array $data, $user)
    {
        try { 
            if ($data['IDKodeAspekKPI'] == 4) {
                $this->bobotIsValid($idRencanaHeader, 0);
            } else {
                $this->bobotIsValid($idRencanaHeader, $data['Bobot']);
            }

            $this->limitItemIsValid($idRencanaHeader, $data['IDKodeAspekKPI'], $user); 
            
            DB::beginTransaction();
            $detailRencana = new DetilRencanaKPI();
            $detailRencana->ID = Uuid::uuid4();
            $detailRencana->IDKPIRencanaHeader = $idRencanaHeader;
            $detailRencana->IDJenisPeriode = $data['IDJenisPeriode'];
            $detailRencana->KodeRegistrasiKamus = (! empty($data['KodeRegistrasiKamus'])) ? $data['KodeRegistrasiKamus'] : null;
            $detailRencana->IDKodeAspekKPI = $data['IDKodeAspekKPI'];
            $detailRencana->DeskripsiKRA = (! empty($data['DeskripsiKRA'])) ? $data['DeskripsiKRA'] : null;
            $detailRencana->DeskripsiKPI = (! empty($data['DeskripsiKPI'])) ? $data['DeskripsiKPI'] : null;
            $detailRencana->IDSatuan = $data['IDSatuan'];
            $detailRencana->IDJenisAppraisal = $data['IDJenisAppraisal'];
            $detailRencana->IDPersentaseRealisasi = $data['IDPersentaseRealisasi'];
            $detailRencana->Bobot = $data['Bobot'];
            $detailRencana->IsKRABawahan = (! empty($data['IsKRABawahan'])) ? true : false;
            $detailRencana->Keterangan = (! empty($data['Keterangan'])) ? $data['Keterangan'] : null;
            $detailRencana->CreatedBy = $user->NPK;
            $detailRencana->CreatedOn = Carbon::now('Asia/Jakarta');

            for ($i = 1; $i <= 12; $i++) {
                $detailRencana->{'Target'.$i} = (array_key_exists('Target'.$i, $data) || ! empty($data['Target'.$i])) ? $data['Target'.$i] : null;
            }
            $detailRencana->save();
            DB::commit();
            return $this->successResponse();
        } catch (\Exception $e) {
            DB::rollback();
            return $this->errorResponse($e->getMessage());
        }
    }

    /**
     * @param $headerId
     * @param $dataBobot
     * @return bool
     * @throws DomainException
     */
    protected function bobotIsValid($headerId, $dataBobot)
    {
        $bobotNonTaskForce = $this->rencanaKPIRepository->countBobotItem($headerId);
        $bobotTaskForce = $this->rencanaKPIRepository->countBobotItem($headerId, true);
        if (($bobotNonTaskForce + (int)$dataBobot) > 100.00) {
            throw new DomainException('Bobot total rencana KPI tidak boleh melebihi 100%.');
        }
        if ($bobotTaskForce > 10.00) {
            throw new DomainException('Bobot total rencana KPI Task Force anda tidak boleh melebihi 10%');
        }
        return true;
    }

    /**
     * @param $headerId
     * @param $idAspekKPI
     * @param $user
     * @throws DomainException
     */
    protected function limitItemIsValid($headerId, $idAspekKPI, $user)
    {
        $positionAbbreviation = new PositionAbbreviation();

        if($user->IDRole == 8){ 
            $positionAbbreviation->position($this->getDireksi($user->NPK)->PositionAbbreviation); 
        }else{
            $positionAbbreviation->position($user->karyawan->organization->position->PositionAbbreviation);
        } 
        
        if (! $positionAbbreviation->isDirektorat()) {
            $totalStrategis = $this->rencanaKPIRepository->countItemKPI($headerId, 1);
            $totalRutinStruktural = $this->rencanaKPIRepository->countItemKPI($headerId, 2);
            $totalOperasional = $this->rencanaKPIRepository->countItemKPI($headerId, 3);
            $totalTaskForce = $this->rencanaKPIRepository->countItemKPI($headerId, 4);
            $totalNonTaskForce = $totalStrategis + $totalRutinStruktural + $totalOperasional;
            $itemLimitation = new ItemLimitationService($totalNonTaskForce, $totalTaskForce);
            $itemLimitation->isAvailable($idAspekKPI);
        }
    }
}
