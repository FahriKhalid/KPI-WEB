<?php
/**
 * Created by PhpStorm.
 * User: SENA
 * Date: 09/22/2017
 * Time: 09:31 PM
 */

namespace App\Domain\KPI\Services;

use Illuminate\Support\Facades\Log;
use niklasravnsborg\LaravelPdf\Facades\Pdf;
use App\ApplicationServices\ApplicationService;
use App\Domain\Realisasi\Entities\HeaderRealisasiKPI;
use App\Domain\Rencana\Services\TargetParserService;
use App\Domain\Rencana\Services\TargetPeriodeService;
use App\Domain\KPI\Entities\PeriodeAktif;
use App\Domain\User\Entities\User;
use App\Infrastructures\Repositories\Karyawan\KaryawanRepository;
use App\Infrastructures\Repositories\KPI\RealisasiKPIRepository;
use App\Domain\Realisasi\Entities\DetilRealisasiKPI;

class RealisasiIndividuPDFDownloadService extends ApplicationService
{
    protected $realisasiKPIRepository;
    /**
     * @var KaryawanRepository
     */
    protected $karyawanRepository;
    /**
     * @var TargetPeriodeService
     */
    protected $targetPeriodeService;
    /**
     * @var HeaderRealisasiKPI
     */
    protected $headerRealisasiKPI;
    /**
     * @var TargetParserService
     */
    protected $targetParserService;

    /**
     * RealisasiIndividuPDFDownloadService constructor.
     * @param RealisasiKPIRepository $realisasiKPIRepository
     * @param KaryawanRepository $karyawanRepository
     * @param TargetPeriodeService $targetPeriodeService
     * @param HeaderRealisasiKPI $headerRealisasiKPI
     * @param TargetParserService $targetParserService
     */
    public function __construct(
        RealisasiKPIRepository $realisasiKPIRepository,
        KaryawanRepository $karyawanRepository,
        TargetPeriodeService $targetPeriodeService,
        HeaderRealisasiKPI $headerRealisasiKPI,
        TargetParserService $targetParserService
    ) {
        $this->realisasiKPIRepository = $realisasiKPIRepository;
        $this->karyawanRepository = $karyawanRepository;
        $this->targetPeriodeService = $targetPeriodeService;
        $this->headerRealisasiKPI = $headerRealisasiKPI;
        $this->targetParserService = $targetParserService;
    }

    /**
     * @param $id
     * @param User $user
     * @return array
     */
    public function createYearReport($year, User $user)
    {
        try {
            $data['alldetail'] = $this->realisasiKPIRepository->allItemRealizationGroupByYear($user, $year);
            $data['periode'] = PeriodeAktif::where('Tahun', $year)->select('IDJenisPeriode')->first();
            $data['target'] = $this->targetPeriodeService->periodeID($data['periode']->IDJenisPeriode)->getTargetCount();
            $data['periodeTarget'] = $this->targetPeriodeService->periodeTarget($data['periode']->IDJenisPeriode)->getTargetCount();

            /*
             * Load PDF
             */
            $pdf = PDF::loadView('backends.kpi.realisasi.printtemplate.laporanrealisasikpi3', compact('data'));
            $pdf->SetProtection(['modify', 'print', 'copy', 'print-highres'], null, null);
            $_=$data['header']->IsUnitKerja?'Unit Kerja':'Individu';
            $pdf->download('RealisasiKPI'.$_.'-'.$user->NPK.'.pdf');
            return $this->successResponse();
        } catch (\Exception $e) {
            return $this->errorResponse($e->getMessage());
        }
    }

    /**
     * @param $id
     * @param User $user
     * @return array
     */
    public function create($id, User $user)
    {
        try {


            $data['header'] = $this->realisasiKPIRepository->findById($id); 
            if ($data['header']->IDStatusDokumen != 4) {
                throw new \DomainException('Dokumen yang dicetak hanya yang berstatus \'approved\'');
            }
            $data['headerrencana'] = $data['header']->headerrencanakpi;
            $data['karyawan'] = $this->karyawanRepository->getByNPK($data['header']->NPK)[0];
            $data['alldetail'] = $this->realisasiKPIRepository->allItemRealization($data['header']->ID);

            //dd(\DB::table("Tr_KPIRealisasiDetil")->get()->take(10));

            $data['periode'] = $data['header']->jenisperiode;
            $data['targetRealization'] = $data['header']->Target;

            /*
             * Load PDF
             */
            $pdf = PDF::loadView('backends.kpi.realisasi.printtemplate.laporanrealisasikpi', compact('data'));
            $pdf->SetProtection(['modify', 'print', 'copy', 'print-highres'], null, null);
            $_=$data['header']->IsUnitKerja?'Unit Kerja':'Individu';
            $pdf->download('RealisasiKPI'.$_.'-'.$user->NPK.'.pdf');
            return $this->successResponse();
        } catch (\Exception $e) {
            return $this->errorResponse($e->getMessage());
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
            $data['header'] = $this->realisasiKPIRepository->findById($id);
            if ($data['header']->IDStatusDokumen != 4) {
                throw new \DomainException('Dokumen yang dicetak hanya yang berstatus \'approved\'');
            }
            $data['headerrencana'] = $data['header']->headerrencanakpi;
            $data['karyawan'] = $user->karyawan;
            $data['alldetail'] = $this->realisasiKPIRepository->allItemRealization($data['header']->ID, true);
            $data['periode'] = $data['header']->jenisperiode;
            $data['targetRealization'] = $data['header']->Target;
            /*
             * Load PDF
             */
            $pdf = PDF::loadView('backends.kpi.realisasi.printtemplate.printrealisasiunitkerja', compact('data'));
            $pdf->SetProtection(['modify', 'print', 'copy', 'print-highres'], null, null);
            $pdf->download('RealisasiKPIUnitKerja-'.$user->NPK.'.pdf');
            return $this->successResponse();
        } catch (\Exception $e) {
            Log::error($e->getTraceAsString());
            return $this->errorResponse($e->getMessage());
        }
    }
}
