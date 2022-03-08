<?php
/**
 * Created by PhpStorm.
 * User: SENA
 * Date: 08/14/2017
 * Time: 11:31 AM
 */

namespace App\Domain\KPI\Services;

use App\ApplicationServices\ApplicationService;
use niklasravnsborg\LaravelPdf\Facades\Pdf;
use App\Domain\KPI\Entities\PeriodeAktif;
use App\Domain\Rencana\Services\TargetPeriodeService;
use App\Domain\User\Entities\User;
use App\Infrastructures\Repositories\Karyawan\KaryawanRepository;
use App\Infrastructures\Repositories\KPI\RencanaKPIRepository;

class RencanaIndividuPDFDownloadService extends ApplicationService
{
    /**
     * @var RencanaKPIRepository
     */
    protected $rencanaKPIRepository;
    /**
     * @var KaryawanRepository
     */
    protected $karyawanRepository;
    /**
     * @var TargetPeriodeService
     */
    protected $targetPeriodeService;

    /**
     * RencanaIndividuPDFDownloadService constructor.
     * @param Object RencanaKPIRepository $rencanaKPIRepository
     * @param Object KaryawanRepository $karyawanRepository
     * @param TargetPeriodeService $targetPeriodeService
     */
    public function __construct(RencanaKPIRepository $rencanaKPIRepository, KaryawanRepository $karyawanRepository, TargetPeriodeService $targetPeriodeService)
    {
        $this->rencanaKPIRepository = $rencanaKPIRepository;
        $this->karyawanRepository = $karyawanRepository;
        $this->targetPeriodeService = $targetPeriodeService;
    }

    /**
     * @param string $id
     * @param User $user
     * @return array array
     */
    public function create($id, User $user)
    {
        try {
            $data['header'] = $this->rencanaKPIRepository->findById($id);
            if ($data['header']->IDStatusDokumen != 4) {
                throw new \DomainException('Dokumen yang dicetak hanya yang berstatus \'approved\'');
            }
            $data['karyawan'] = $this->karyawanRepository->getByNPK($data['header']->NPK)[0];
            $data['atasanLangsung'] = $this->karyawanRepository->findByNPK($data['header']->NPKAtasanLangsung);
            $data['atasanTakLangsung'] = $this->karyawanRepository->findByNPK($data['header']->NPKAtasanBerikutnya);
            $data['alldetail'] = $this->rencanaKPIRepository->getAllDetail($id)->sortBy('IDKodeAspekKPI')->groupBy('IDKodeAspekKPI');
            $data['periode'] = PeriodeAktif::where('Tahun', $data['header']->Tahun)->select('IDJenisPeriode')->first();
            $data['target'] = $this->targetPeriodeService->periodeID($data['periode']->IDJenisPeriode)->getTargetCount();
            $data['periodeTarget'] = $this->targetPeriodeService->periodeTarget($data['periode']->IDJenisPeriode)->getTargetCount();
 
            /*
             * Load PDF
             */
            $pdf = PDF::loadView('backends.kpi.rencana.printtemplate.laporanrencanakpi3', compact('data'));
            $pdf->SetProtection(['modify', 'print', 'copy', 'print-highres'], null, null);
            return $pdf->download('RencanaKPIIndividu-' . $user->NPK . '.pdf');
        } catch (\Exception $e) {
            return $this->errorResponse($e->getTraceAsString());
        }
    }

    /**
     * @param $id
     * @param User $user
     * @return array
     */
    public function createUnitKerja($id, User $user)
    {
        try {
            $data['header'] = $this->rencanaKPIRepository->findById($id);
            if ($data['header']->IDStatusDokumen != 4) {
                throw new \DomainException('Dokumen yang dicetak hanya yang berstatus \'approved\'');
            }
            $data['karyawan'] = $user->karyawan;
            $data['periode'] = PeriodeAktif::where('Tahun', $data['header']->Tahun)->select('IDJenisPeriode')->first();
            $data['target'] = $this->targetPeriodeService->periodeID($data['periode']->IDJenisPeriode, true)->getTargetCount();
            $data['periodeTarget']= $this->targetPeriodeService->periodeTarget($data['periode']->IDJenisPeriode, true)->getTargetCount();
            $data['alldetail'] = $this->rencanaKPIRepository->getAllDetail($id, false);
            /*
             * Load PDF
             */
            $pdf = PDF::loadView('backends.kpi.rencana.unitkerja.printtemplate.printrencanakpiunitkerja', compact('data'));
            $pdf->SetProtection(['modify', 'print', 'copy', 'print-highres'], null, null);
            $pdf->download('RencanaKPIUnitKerja-' . $user->NPK . '.pdf');
            return $this->successResponse();
        } catch (\Exception $e) {
            return $this->errorResponse($e->getMessage());
        }
    }
}
