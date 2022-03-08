<?php
/**
 * Created by PhpStorm.
 * User: SENA
 * Date: 10/01/2017
 * Time: 08:49 PM
 */

namespace App\ApplicationServices\RencanaPengembangan;

use Illuminate\Support\Facades\DB;
use App\ApplicationServices\ApplicationService;
use App\Infrastructures\Repositories\KPI\RealisasiKPIRepository;

class SubmitPengembanganService extends ApplicationService
{
    /**
     * @var RealisasiKPIRepository
     */
    protected $realisasiKPIRepository;
    protected $store;
    protected $update;

    /**
     * RegisterRealisasiKPIService constructor.
     *
     * @param RealisasiKPIRepository $realisasiKPIRepository
     * @param StorePengembanganService $store
     * @param UpdatePengembanganService $update
     */
    public function __construct(RealisasiKPIRepository $realisasiKPIRepository, StorePengembanganService $store, UpdatePengembanganService $update)
    {
        $this->realisasiKPIRepository = $realisasiKPIRepository;
        $this->store=$store;
        $this->update=$update;
    }

    /**
     * @param array $data
     * @return array
     */
    public function call(array $data)
    {
        try {
            DB::beginTransaction();
            $counter = count($data['idrealisasiitem']);
            for ($i = 0; $i < $counter; $i++) {
                $item = $this->realisasiKPIRepository->findDetailById($data['idrealisasiitem'][$i]);
                $iddetilrencana = $item->detilrencana->ID;
                $_=[
                    'FollowUp' =>  $data['FollowUp'][$i],
                    'RencanaPengembangan' =>  $data['RencanaPengembangan'][$i],
                    'Keterangan' =>  $data['Keterangan'][$i],
                ];
                if (empty($item->detilrencana->IDRencanaPengembangan)) {
                    $this->store->call($iddetilrencana, $_);
                } else {
                    $this->update->call($iddetilrencana, $_);
                }
            }
            DB::commit();
            return $this->successResponse();
        } catch (\Exception $e) {
            DB::rollback();
            return $this->errorResponse($e->getMessage());
        }
    }
}
