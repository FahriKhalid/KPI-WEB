<?php
namespace App\ApplicationServices\RealisasiKPI;

use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use App\ApplicationServices\ApplicationService;
use App\Domain\Realisasi\Entities\DetilRealisasiKPI;
use App\Exceptions\DomainException;
use App\Infrastructures\Repositories\KPI\RealisasiKPIRepository;

class RegisterRealisasiKPIService extends ApplicationService
{
    /**
     * @var RealisasiKPIRepository
     */
    protected $realisasiKPIRepository;

    protected $detilRealisasi;

    /**
     * RegisterRealisasiKPIService constructor.
     *
     * @param RealisasiKPIRepository $realisasiKPIRepository
     */
    public function __construct(RealisasiKPIRepository $realisasiKPIRepository, DetilRealisasiKPI $detilRealisasiKPI)
    {
        $this->realisasiKPIRepository = $realisasiKPIRepository;
        $this->detilRealisasi = $detilRealisasiKPI;
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
                $header = $this->realisasiKPIRepository->findById($id);
                $header->isAllowedToRegister();
                $this->isAlreadyDoRealization($header);
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
     * @param $header
     * @return bool
     * @throws DomainException
     */
    protected function isAlreadyDoRealization($header)
    {
        $nullExist = $this->detilRealisasi->where('IDKPIRealisasiHeader', $header->ID)->whereNull('Realisasi')->exists();
        if ($nullExist) {
            throw new DomainException('Register hanya bisa dilakukan jika semua nilai realisasi KPI telah diisi.');
        }
        return true;
    }
}
