<?php
/**
 * Created by PhpStorm.
 * User: SENA
 * Date: 08/03/2017
 * Time: 09:33 AM
 */

namespace App\ApplicationServices\RealisasiKPI;

use Carbon\Carbon;
use App\ApplicationServices\ApplicationService;
use App\Infrastructures\Repositories\KPI\RealisasiKPIRepository;

class UpdateHeaderRealisasiService extends ApplicationService
{
    /**
     * @var RealisasiKPIRepository
     */
    protected $realisasiKPIRepository;

    /**
     * UpdateHeaderRealisasiService constructor.
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
    public function call($id, array $data, $user)
    {
        try {
            $realisasiHeader = $this->realisasiKPIRepository->findById($id);
            $realisasiHeader->IDKPIRencanaHeader = $data['IDKPIRencanaHeader'];
            $realisasiHeader->IDJenisPeriode = $data['IDJenisPeriode'];
            $realisasiHeader->KodePeriode = $data['KodePeriode'];
            $realisasiHeader->Grade = $data['Grade'];
            $realisasiHeader->IDMasterPosition = $data['IDMasterPosition'];
            $realisasiHeader->NPKAtasanLangsung = $data['NPKAtasanLangsung'];
            $realisasiHeader->JabatanAtasanLangsung = $data['JabatanAtasanLangsung'];
            $realisasiHeader->NPKAtasanBerikutnya = $data['NPKAtasanBerikutnya'];
            $realisasiHeader->JabatanAtasanBerikutnya = $data['JabatanAtasanBerikutnya'];
            $realisasiHeader->IDStatusDokumen = 1;
            $realisasiHeader->NilaiAkhir = (! empty($data['NilaiAkhir'])) ? $data['NilaiAkhir'] : null;
            $realisasiHeader->NilaiValidasi = (! empty($data['NilaiValidasi'])) ? $data['NilaiValidasi'] : null;
            $realisasiHeader->Keterangan = (! empty($data['Keterangan'])) ? $data['Keterangan'] : null;
            $realisasiHeader->UpdatedBy = $user->NPK;
            $realisasiHeader->UpdatedOn = Carbon::now('Asia/Jakarta');
            $realisasiHeader->save();
            return $this->successResponse();
        } catch (\Exception $e) {
            return $this->errorResponse($e->getMessage());
        }
    }
}
