<?php
namespace App\ApplicationServices\KamusKPI;

use Carbon\Carbon;
use App\ApplicationServices\ApplicationService;
use App\Domain\KPI\Entities\Kamus;
use App\Domain\User\Entities\User;
use App\Infrastructures\Repositories\KPI\KamusRepository;

class UpdateKamusKPIService extends ApplicationService
{
    /**
     * @var KamusRepository
     */
    protected $kamusRepository;

    /**
     * UpdateKamusKPIService constructor.
     *
     * @param KamusRepository $kamusRepository
     */
    public function __construct(KamusRepository $kamusRepository)
    {
        $this->kamusRepository = $kamusRepository;
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
            //DB:beginTransactions();
            $kamusKPI = $this->kamusRepository->findById($id);
            // if($kamusKPI->Status=='approved'){throw  new \DomainException('Kamus tidak dapat diupdate jika berstatus approve');}
            $kamusKPI->KodeRegistrasi = strtoupper($data['KodeRegistrasi']);
            $kamusKPI->KodeUnitKerja = $data['KodeUnitKerja'];
            $kamusKPI->IDAspekKPI = $data['IDAspekKPI'];
            $kamusKPI->IDJenisAppraisal = $data['IDJenisAppraisal'];
            $kamusKPI->IDPersentaseRealisasi = $data['IDPersentaseRealisasi'];
            $kamusKPI->IDSatuan = $data['IDSatuan'];
            $kamusKPI->KPI = $data['KPI'];
            if (isset($data['Status']) and $kamusKPI->Status !=Kamus::APPROVED) {
                $kamusKPI->Status = $data['Status'];
            }
            $kamusKPI->Deskripsi = $data['Deskripsi'];
            $kamusKPI->Rumus = (! empty($data['Rumus'])) ? $data['Rumus'] : null;
            $kamusKPI->PeriodeLaporan = (! empty($data['PeriodeLaporan'])) ? $data['PeriodeLaporan'] : null;
            $kamusKPI->Keterangan = (! empty($data['Keterangan'])) ? $data['Keterangan'] : null;
            $kamusKPI->UpdatedBy = $user->ID;
            $kamusKPI->UpdatedOn = Carbon::now();
            $kamusKPI->IndikatorHasil = isset($data['IndikatorHasil'])?$data['IndikatorHasil']:null;
            $kamusKPI->IndikatorKinerja = isset($data['IndikatorKinerja'])?$data['IndikatorKinerja']:null;
            $kamusKPI->SumberData = isset($data['SumberData'])?$data['SumberData']:null;
            $kamusKPI->Jenis = isset($data['Jenis'])?$data['Jenis']:null;
            $kamusKPI->update();
            return $this->successResponse();
        } catch (\Exception $e) {
            return $this->errorResponse($e->getMessage());
        }
    }
}
