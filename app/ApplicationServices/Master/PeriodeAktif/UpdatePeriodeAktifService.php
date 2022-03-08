<?php
namespace App\ApplicationServices\Master\PeriodeAktif;

use Illuminate\Support\Facades\DB;
use App\ApplicationServices\ApplicationService;
use App\Domain\User\Entities\User;
use App\Infrastructures\Repositories\KPI\PeriodeAktifRepository;

class UpdatePeriodeAktifService extends ApplicationService
{
    /**
     * @var PeriodeAktifRepository
     */
    protected $periodeaktifRepository;

    /**
     * @var
     */
    protected $deletePeriodeAktifService;

    /**
     * @var
     */
    protected $storePeriodeAktifService;

    /**
     * UpdatePeriodeAktifService constructor.
     *
     * @param PeriodeAktifRepository $periodeaktifRepository
     * @param DeletePeriodeAktifService $deletePeriodeAktifService
     * @param StorePeriodeAktifService $storePeriodeAktifService
     */
    public function __construct(
        PeriodeAktifRepository $periodeaktifRepository,
        DeletePeriodeAktifService $deletePeriodeAktifService,
        StorePeriodeAktifService $storePeriodeAktifService
    ) {
        $this->periodeaktifRepository = $periodeaktifRepository;
        $this->deletePeriodeAktifService = $deletePeriodeAktifService;
        $this->storePeriodeAktifService = $storePeriodeAktifService;
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
            $this->deletePeriodeAktifService->call($id);
            $this->storePeriodeAktifService->call($data, $user);
            DB::commit();
            return $this->successResponse();
        } catch (\Exception $e) {
            DB::rollback();
            return $this->errorResponse($e->getMessage());
        }
    }
}
