<?php

namespace App\Http\Controllers\Backends\Reporting;

use Illuminate\Http\Request;
use App\Domain\KPI\Services\ReportingKPIExcelDownloadService as Excel;
use App\Http\Controllers\Controller;
use App\Infrastructures\Repositories\Karyawan\KaryawanRepository;
use App\Infrastructures\Repositories\KPI\PeriodeAktifRepository;
use App\Infrastructures\Repositories\Reports\ReportRencanaRepository;

class RencanaIndividuController extends Controller
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
     * RencanaIndividuController constructor.
     *
     * @param ReportRencanaRepository $reportRencanaRepository
     * @param KaryawanRepository $karyawanRepository
     * @param PeriodeAktifRepository $periodeAktifRepository
     */
    public function __construct(
        ReportRencanaRepository $reportRencanaRepository,
        KaryawanRepository $karyawanRepository,
        PeriodeAktifRepository $periodeAktifRepository
    ) {
        $this->reportRencanaRepository = $reportRencanaRepository;
        $this->karyawanRepository = $karyawanRepository;
        $this->periodeAktifRepository = $periodeAktifRepository;
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
        $data['collection'] = $this->reportRencanaRepository->datatableReportRencanaIndividu(25, $data['periode'], $request->except(['page']));
        $data['karyawanCount'] = $this->karyawanRepository->count();
        $data['approvedCount'] = $this->reportRencanaRepository->countProgressKPI($data['periode'], 'approved');
        $data['inprogressCount'] = $this->reportRencanaRepository->countProgressKPI($data['periode']);
        $data['periodeAktif'] = $this->periodeAktifRepository->getAllPeriode();
        if ($request->exists('unduh')) {
            $downloadService->unduh(Excel::RENCANAINDIVIDU, $request->get('tahunperiode'));
        } 
        return view('backends.reports.rencana.indexindividu', compact('data'));
    }
}
