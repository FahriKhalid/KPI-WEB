<?php

namespace App\Http\Controllers\Backends\Reporting;

use Illuminate\Http\Request;
use App\Domain\KPI\Services\ReportingKPIExcelDownloadService as Excel;
use App\Domain\Rencana\Services\ProgressPercentageService;
use App\Http\Controllers\Controller;
use App\Infrastructures\Repositories\Karyawan\KaryawanRepository;
use App\Infrastructures\Repositories\KPI\PeriodeAktifRepository;
use App\Infrastructures\Repositories\KPI\UnitKerjaRepository;
use App\Infrastructures\Repositories\Reports\ReportRencanaRepository;

class RencanaUnitKerjaController extends Controller
{
    /**
     * @var ReportRencanaRepository
     */
    protected $reportRencanaRepository;

    /**
     * @var KaryawanRepository
     */
    protected $karyawanRepository;

    /**
     * @var PeriodeAktifRepository
     */
    protected $periodeAktifRepository;

    /**
     * @var UnitKerjaRepository
     */
    protected $unitkerjaRepository;

    /**
     * RencanaUnitKerjaController constructor.
     *
     * @param ReportRencanaRepository $reportRencanaRepository
     * @param KaryawanRepository $karyawanRepository
     * @param PeriodeAktifRepository $periodeAktifRepository
     * @param UnitKerjaRepository $unitKerjaRepository
     */
    public function __construct(
        ReportRencanaRepository $reportRencanaRepository,
        KaryawanRepository $karyawanRepository,
        PeriodeAktifRepository $periodeAktifRepository,
        UnitKerjaRepository $unitKerjaRepository
    ) {
        $this->reportRencanaRepository = $reportRencanaRepository;
        $this->karyawanRepository = $karyawanRepository;
        $this->periodeAktifRepository = $periodeAktifRepository;
        $this->unitkerjaRepository = $unitKerjaRepository;
    }

    /**
     * @param Request $request
     * @param Excel $downloadService
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index(Request $request, Excel $downloadService)
    {
        $data['numbering'] = numberingPagination(25);
        $data['periode'] = ($request->has('tahunperiode') && $request->get('tahunperiode') != '') ? $request->get('tahunperiode') : $this->periodeAktifRepository->getFirstPeriode()->Tahun;
        $data['periodeAktif'] = $this->periodeAktifRepository->getAllPeriode();
        $data['unitkerja'] = $this->unitkerjaRepository->asList()->toArray();
        $data['karyawanCount'] = $this->karyawanRepository->count();
        $data['collection'] = $this->reportRencanaRepository->datatableReportRencanaUnitKerja(25, $data['periode'], $request->except(['page']));
        $data['collectedCount'] = $this->reportRencanaRepository->countProgressKPI($data['periode'], 'approved');
        $totalProgress = ProgressPercentageService::calculateProgressRencanaKaryawan($data['karyawanCount'], $data['collectedCount']);
        $data['uncollectedCount'] = $totalProgress['uncollected'];
        $data['progressPercentage'] = $totalProgress['progressPercentage'];
        if ($request->exists('unduh')) {
            $downloadService->unduh(Excel::RENCANAUNITKERJA, $request->get('tahunperiode'));
        }
        return view('backends.reports.rencana.indexunitkerja', compact('data'));
    }
}
