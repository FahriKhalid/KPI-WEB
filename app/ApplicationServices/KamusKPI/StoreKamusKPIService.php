<?php
namespace App\ApplicationServices\KamusKPI;

use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use App\ApplicationServices\ApplicationService;
use App\Domain\KPI\Entities\Kamus;
use App\Domain\User\Entities\User;
use Ramsey\Uuid\Uuid;
use App\Infrastructures\Repositories\KPI\KamusRepository;

class StoreKamusKPIService extends ApplicationService
{
    /**
     * @var uuid
     */
    protected $id;
    /**
     * @param array $data
     * @param User $user
     * @return array
     */
    public function call(array $data, User $user)
    {
        try {
            $role = $user->UserRole->Role;
            DB::beginTransaction();
            $kamusKPI = new Kamus();
            $kamusKPI->ID = Uuid::uuid4();
            $kamusKPI->KodeRegistrasi = (! empty($data['KodeRegistrasi'])) ? strtoupper($data['KodeRegistrasi']) : null;
            $kamusKPI->KodeUnitKerja = $data['KodeUnitKerja'];
            $kamusKPI->IDAspekKPI = $data['IDAspekKPI'];
            $kamusKPI->IDJenisAppraisal = $data['IDJenisAppraisal'];
            $kamusKPI->IDPersentaseRealisasi = $data['IDPersentaseRealisasi'];
            $kamusKPI->IDSatuan = $data['IDSatuan'];
            $kamusKPI->KPI = $data['KPI'];
            $kamusKPI->Deskripsi = $data['Deskripsi'];
            $kamusKPI->Rumus = (! empty($data['Rumus'])) ? $data['Rumus'] : null;
            $kamusKPI->PeriodeLaporan = (! empty($data['PeriodeLaporan'])) ? $data['PeriodeLaporan'] : null;
            $kamusKPI->Keterangan = (! empty($data['Keterangan'])) ? $data['Keterangan'] : null;
            $kamusKPI->CreatedBy = $user->ID;
            $kamusKPI->CreatedOn = Carbon::now();
            
            if ($role == 'Administrator') {
                $kamusKPI->Status = 'approved';
            }
            $kamusKPI->IndikatorHasil = isset($data['IndikatorHasil'])?$data['IndikatorHasil']:null;
            $kamusKPI->IndikatorKinerja = isset($data['IndikatorKinerja'])?$data['IndikatorKinerja']:null;
            $kamusKPI->SumberData = isset($data['SumberData'])?$data['SumberData']:null;
            $kamusKPI->Jenis = isset($data['Jenis'])?$data['Jenis']:null;
            $kamusKPI->save();
            $this->id = $kamusKPI->ID;
            DB::commit();
            return $this->successResponse();
        } catch (\Exception $e) {
            DB::rollback();
            return $this->errorResponse($e->getMessage());
        }
    }

    /**
     * @return mixed
     */
    public function getId()
    {
        return $this->id;
    }
}
