<?php
namespace App\ApplicationServices\RencanaKPI;

use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use App\ApplicationServices\ApplicationService;
use App\Domain\User\Entities\User;
use App\Infrastructures\Repositories\KPI\RencanaKPIRepository;

class UpdateDetailRencanaService extends ApplicationService
{
    /**
     * @var RencanaKPIRepository
     */
    protected $rencanakpiRepository;

    /**
     * UpdateDetailRencanaService constructor.
     * @param RencanaKPIRepository $rencanakpiRepository
     */
    public function __construct(RencanaKPIRepository $rencanakpiRepository)
    {
        $this->rencanakpiRepository = $rencanakpiRepository;
    }

    /**
     * @param $id
     * @param array $data
     * @param User $user
     * @return array
     */
    public function call($id, array $data, User $user)
    {
        try {
            DB::beginTransaction();
            $detailRencana = $this->rencanakpiRepository->findDetailById($id);

            if (!$data['IsKRABawahan']) {
                $this->rencanakpiRepository->isAvailablePenurunan($detailRencana);
            }

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
            $detailRencana->UpdatedBy = $user->NPK;
            $detailRencana->UpdatedOn = Carbon::now('Asia/Jakarta');
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

}
