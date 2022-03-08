<?php
/**
 * Created by PhpStorm.
 * User: SENA
 * Date: 10/01/2017
 * Time: 03:31 PM
 */

namespace App\Domain\KPI\Services;

use niklasravnsborg\LaravelPdf\Facades\Pdf;
use App\ApplicationServices\ApplicationService;
use App\Domain\Realisasi\Entities\HeaderRealisasiKPI;
use App\Domain\Rencana\Services\TargetParserService;
use App\Domain\Rencana\Services\TargetPeriodeService;
use App\Domain\User\Entities\User;
use App\Infrastructures\Repositories\KPI\RealisasiKPIRepository;

class RencanaPengembanganPDFDownloadService extends ApplicationService
{
    /**
     * @var RealisasiKPIRepository
     */
    protected $realisasiKPIRepository;
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
     * @param TargetPeriodeService $targetPeriodeService
     * @param HeaderRealisasiKPI $headerRealisasiKPI
     * @param TargetParserService $targetParserService
     */
    public function __construct(RealisasiKPIRepository $realisasiKPIRepository, TargetPeriodeService $targetPeriodeService, HeaderRealisasiKPI $headerRealisasiKPI, TargetParserService $targetParserService)
    {
        $this->realisasiKPIRepository = $realisasiKPIRepository;
        $this->targetPeriodeService = $targetPeriodeService;
        $this->headerRealisasiKPI = $headerRealisasiKPI;
        $this->targetParserService = $targetParserService;
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
            $data['karyawan'] = $user->karyawan;
            $data['alldetail'] = $this->realisasiKPIRepository->allItemRealization($data['header']->ID);
            $data['periode'] = $data['header']->periodeaktif;
            $data['target'] = $this->targetPeriodeService->periodeID($data['periode']->IDJenisPeriode, $data['headerrencana']->IsKPIUnitKerja)->getTargetCount();
            $countRealisasi = $this->headerRealisasiKPI->where('NPK', $data['header']->NPK)->where('Tahun', $data['header']->Tahun)->select('ID')->whereNotIn('IDStatusDokumen', [5])->count();
            $data['targetRealization'] = $this->targetParserService->targetCount($data['target'])[$countRealisasi - 1 ];
            /*
             * Load PDF
             */
            $pdf = PDF::loadView('backends.kpi.rencanapengembangan.printtemplate.laporanpengembangankpi', compact('data'));
            $pdf->SetProtection(['modify', 'print', 'copy', 'print-highres'], null, null);
            $pdf->download('Rencanapengembangan-'.$user->NPK.'.pdf');
            return $this->successResponse();
        } catch (\ErrorException $errorException) {
            return $this->errorResponse($errorException->getMessage());
        } catch (\Exception $e) {
            return $this->errorResponse($e->getMessage());
        }
    }
}
