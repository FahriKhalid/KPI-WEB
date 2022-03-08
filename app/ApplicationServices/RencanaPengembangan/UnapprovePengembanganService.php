<?php
/**
 * Created by PhpStorm.
 * User: SENA
 * Date: 10/23/2017
 * Time: 06:05 PM
 */

namespace App\ApplicationServices\RencanaPengembangan;

use Illuminate\Support\Facades\DB;
use App\ApplicationServices\ApplicationService;
use App\Infrastructures\Repositories\KPI\RealisasiKPIRepository;

class UnapprovePengembanganService extends ApplicationService
{
    /**
     * @var RealisasiKPIRepository
     */
    protected $realisasiKPIRepository;

    /**
     * ApproveRealisasiKPIService constructor.
     *
     * @param RealisasiKPIRepository $realisasiKPIRepository
     */
    public function __construct(RealisasiKPIRepository $realisasiKPIRepository)
    {
        $this->realisasiKPIRepository = $realisasiKPIRepository;
    }

    /**
     * @param array $data
     * @param $user
     * @return array
     */
    public function call(array $data, $user)
    {
        try {
            $array = (array)$data['id'];
            foreach ($array as $id) {
                DB::beginTransaction();
                $header = $this->realisasiKPIRepository->findById($id);
                if (!$header->isSuggested()) {
                    throw new \DomainException('Dokumen yang akan di-unapprove harus berstatus SUGGESTED (Pengembangan yang sudah disetujui sebelumnya).');
                }
                $header->IDStatusDokumen = 8;
                $header->update();
                DB::commit();
            }
            return $this->successResponse();
        } catch (\Exception $e) {
            DB::rollback();
            return $this->errorResponse($e->getMessage());
        }
    }
}
