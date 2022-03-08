<?php

namespace App\Http\Controllers\Backends;

use Illuminate\Http\Request;
use App\ApplicationServices\Dashboard\DashboardBuilder;
use App\Domain\KPI\Services\PeriodeAktifFactoryService;
use App\Http\Controllers\Controller;
use App\Infrastructures\Repositories\KPI\InfoRepository;
use App\Infrastructures\Repositories\KPI\PeriodeAktifRepository;
use App\Infrastructures\Repositories\KPI\RealisasiKPIRepository;
use App\Infrastructures\Repositories\KPI\RencanaKPIRepository;
use App\Domain\karyawan\Entities\KaryawanLeader;
use App\Domain\karyawan\Entities\Karyawan;
use App\Domain\karyawan\Entities\MasterPositionLeader;
use App\Domain\karyawan\Entities\MasterPosition;
use Illuminate\Support\Facades\Mail;
use Session;

class DashboardController extends Controller
{
    /**
     * @var InfoRepository
     */
    protected $infoKPIRepository;

    /**
     * @var DashboardBuilder
     */
    protected $dashboardBuilder;

    /**
     * @var RencanaKPIRepository
     */
    protected $rencanaKPIRepository;

    /**
     * @var RealisasiKPIRepository
     */
    protected $realisasiKPIRepository;

    /**
     * @var PeriodeAktifRepository
     */
    protected $periodeAktifRepository;

    /**
     * DashboardController constructor.
     *
     * @param InfoRepository $infoKPI
     * @param DashboardBuilder $dashboardBuilder
     * @param RencanaKPIRepository $rencanaKPIRepository
     * @param RealisasiKPIRepository $realisasiKPIRepository
     * @param PeriodeAktifRepository $periodeAktifRepository
     */
    public function __construct(
        InfoRepository $infoKPI,
        DashboardBuilder $dashboardBuilder,
        RencanaKPIRepository $rencanaKPIRepository,
        RealisasiKPIRepository $realisasiKPIRepository,
        PeriodeAktifRepository $periodeAktifRepository
    ) {
        $this->infoKPIRepository = $infoKPI;
        $this->dashboardBuilder = $dashboardBuilder;
        $this->rencanaKPIRepository = $rencanaKPIRepository;
        $this->realisasiKPIRepository = $realisasiKPIRepository;
        $this->periodeAktifRepository = $periodeAktifRepository;
    }

    /**
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index(Request $request)
    {   
        $dashboard = $this->dashboardBuilder->user($request->user())->build(); 
        $data = $dashboard['data'];  
        return view($dashboard['view'], compact('data')); 
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function dataSummaryRencana(Request $request)
    {
        $data['periodeYear'] = ($request->has('periodeTahunRencana')) ? $request->get('periodeTahunRencana') : PeriodeAktifFactoryService::getTahunRencana($this->periodeAktifRepository->getFirstPeriode());
        $data['summaryRencanaKPI']['draft'] = $this->rencanaKPIRepository->countByStatusDocument(1, $data['periodeYear']);
        $data['summaryRencanaKPI']['registered'] = $this->rencanaKPIRepository->countByStatusDocument(2, $data['periodeYear']);
        $data['summaryRencanaKPI']['confirmed'] = $this->rencanaKPIRepository->countByStatusDocument(3, $data['periodeYear']);
        $data['summaryRencanaKPI']['approved'] = $this->rencanaKPIRepository->countByStatusDocument(4, $data['periodeYear']);
        $data['all'] = $this->rencanaKPIRepository->countProgressKPI($data['periodeYear']) + $this->rencanaKPIRepository->countProgressKPI($data['periodeYear'], 'approved');
        return response()->json($data);
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function dataSummaryRealisasi(Request $request)
    {
        $data['periodeYear'] = ($request->has('periodeTahunRealisasi')) ? $request->get('periodeTahunRealisasi') : PeriodeAktifFactoryService::getTahunRencana($this->periodeAktifRepository->getFirstPeriode());
        $data['periodeRealisasi'] = ($request->has('periodeRealisasi')) ? $request->get('periodeRealisasi') : $this->periodeAktifRepository->getFirstPeriodeByYear($data['periodeYear'])->IDJenisPeriode;
        $data['summaryRealisasiKPI']['draft'] = $this->realisasiKPIRepository->countByStatusDocument(1, $data['periodeYear'], $data['periodeRealisasi']);
        $data['summaryRealisasiKPI']['registered'] = $this->realisasiKPIRepository->countByStatusDocument(2, $data['periodeYear'], $data['periodeRealisasi']);
        $data['summaryRealisasiKPI']['confirmed'] = $this->realisasiKPIRepository->countByStatusDocument(3, $data['periodeYear'], $data['periodeRealisasi']);
        $data['summaryRealisasiKPI']['approved'] = $this->realisasiKPIRepository->countByStatusDocument(4, $data['periodeYear'], $data['periodeRealisasi']);
        return response()->json($data);
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function dataSummaryRencanaBawahan(Request $request)
    {
        $data['periodeYearBawahan'] = ($request->has('periodeTahunRencana')) ? $request->get('periodeTahunRencana') : PeriodeAktifFactoryService::getTahunRencana($this->periodeAktifRepository->getFirstPeriode());
        $data['summaryRencanaKPIBawahan']['draft'] = $this->rencanaKPIRepository->countByStatusDocumentBawahan(1, $data['periodeYearBawahan'], $request->user()->karyawan->NPK);
        $data['summaryRencanaKPIBawahan']['registered'] = $this->rencanaKPIRepository->countByStatusDocumentBawahan(2, $data['periodeYearBawahan'], $request->user()->karyawan->NPK);
        $data['summaryRencanaKPIBawahan']['confirmed'] = $this->rencanaKPIRepository->countByStatusDocumentBawahan(3, $data['periodeYearBawahan'], $request->user()->karyawan->NPK);
        $data['summaryRencanaKPIBawahan']['approved'] = $this->rencanaKPIRepository->countByStatusDocumentBawahan(4, $data['periodeYearBawahan'], $request->user()->karyawan->NPK);
        return response()->json($data);
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function dataSummaryRealisasiBawahan(Request $request)
    {
        $data['periodeYearBawahan'] = ($request->has('periodeTahunRealisasi')) ? $request->get('periodeTahunRealisasi') : PeriodeAktifFactoryService::getTahunRencana($this->periodeAktifRepository->getFirstPeriode());
        $data['periodeRealisasiBawahan'] = ($request->has('periodeRealisasi')) ? $request->get('periodeRealisasi') : $this->periodeAktifRepository->getFirstPeriodeByYear($data['periodeYearBawahan'])->IDJenisPeriode;
        $data['summaryRealisasiKPIBawahan']['draft'] = $this->realisasiKPIRepository->countByStatusDocumentBawahan(1, $data['periodeYearBawahan'], $data['periodeRealisasiBawahan'], $request->user()->karyawan->NPK);
        $data['summaryRealisasiKPIBawahan']['registered'] = $this->realisasiKPIRepository->countByStatusDocumentBawahan(2, $data['periodeYearBawahan'], $data['periodeRealisasiBawahan'], $request->user()->karyawan->NPK);
        $data['summaryRealisasiKPIBawahan']['confirmed'] = $this->realisasiKPIRepository->countByStatusDocumentBawahan(3, $data['periodeYearBawahan'], $data['periodeRealisasiBawahan'], $request->user()->karyawan->NPK);
        $data['summaryRealisasiKPIBawahan']['approved'] = $this->realisasiKPIRepository->countByStatusDocumentBawahan(4, $data['periodeYearBawahan'], $data['periodeRealisasiBawahan'], $request->user()->karyawan->NPK);
        return response()->json($data);
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function dataSummaryRencanaPengembangan(Request $request)
    {
        $data['periodeYear'] = ($request->has('periodeTahunRealisasi')) ? $request->get('periodeTahunRealisasi') : date('Y');
        $data['summaryRencanaPengembanganSingle']['training'] = $this->realisasiKPIRepository->countRencanaPengembangan($data['periodeYear'], 1);
        $data['summaryRencanaPengembanganSingle']['nontraining'] = $this->realisasiKPIRepository->countRencanaPengembangan($data['periodeYear'], 2);
        $data['summaryRencanaPengembanganMultiple'][$data['periodeYear'] - 1]['training'] = $this->realisasiKPIRepository->countRencanaPengembangan($data['periodeYear'] - 1, 1);
        $data['summaryRencanaPengembanganMultiple'][$data['periodeYear'] - 1]['nontraining'] = $this->realisasiKPIRepository->countRencanaPengembangan($data['periodeYear'] - 1, 2);
        $data['summaryRencanaPengembanganMultiple'][$data['periodeYear']]['training'] = $this->realisasiKPIRepository->countRencanaPengembangan($data['periodeYear'], 1);
        $data['summaryRencanaPengembanganMultiple'][$data['periodeYear']]['nontraining'] = $this->realisasiKPIRepository->countRencanaPengembangan($data['periodeYear'], 2);
        $data['summaryRencanaPengembanganMultiple'][$data['periodeYear'] + 1]['training'] = $this->realisasiKPIRepository->countRencanaPengembangan($data['periodeYear'] + 1, 1);
        $data['summaryRencanaPengembanganMultiple'][$data['periodeYear'] + 1]['nontraining'] = $this->realisasiKPIRepository->countRencanaPengembangan($data['periodeYear'] + 1, 2);
        return response()->json($data);
    }

    /**
     * @param $id
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function show($id)
    {
        $data = $this->infoKPIRepository->findById($id);
        return view('backends.dashboard.detail', compact('data'));
    }
}
