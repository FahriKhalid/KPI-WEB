<?php

namespace App\Http\Controllers\Backends\Reporting;

use Illuminate\Http\Request;
use App\Domain\KPI\Services\ReportingKPIExcelDownloadService as Excel;
use App\Domain\KPI\Entities\JenisPeriode;
use App\Domain\Rencana\Services\ProgressPercentageService;
use App\Http\Controllers\Controller;
use App\Infrastructures\Repositories\Karyawan\KaryawanRepository;
use App\Infrastructures\Repositories\KPI\PeriodeAktifRepository;
use App\Infrastructures\Repositories\KPI\UnitKerjaRepository;
use App\Infrastructures\Repositories\Reports\ReportRealisasiRepository;
use App\Infrastructures\Repositories\Reports\ReportRencanaRepository;

class RealisasiController extends Controller
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
     * @var ReportRealisasiRepository
     */
    protected $reportRealisasiRepository;

    /**
     * RealisasiController constructor.
     *
     * @param ReportRencanaRepository $reportRencanaRepository
     * @param KaryawanRepository $karyawanRepository
     * @param PeriodeAktifRepository $periodeAktifRepository
     * @param UnitKerjaRepository $unitKerjaRepository
     * @param ReportRealisasiRepository $reportRealisasiRepository
     */
    public function __construct(
        ReportRencanaRepository $reportRencanaRepository,
        KaryawanRepository $karyawanRepository,
        PeriodeAktifRepository $periodeAktifRepository,
        UnitKerjaRepository $unitKerjaRepository,
        ReportRealisasiRepository $reportRealisasiRepository
    ) {
        $this->reportRencanaRepository = $reportRencanaRepository;
        $this->karyawanRepository = $karyawanRepository;
        $this->periodeAktifRepository = $periodeAktifRepository;
        $this->unitkerjaRepository = $unitKerjaRepository;
        $this->reportRealisasiRepository = $reportRealisasiRepository;
    }

    /**
     * @param Request $request
     * @param Excel $downloadService
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function indexIndividu(Request $request, Excel $downloadService)
    {
        $data['numbering'] = numberingPagination(25);
        $data['periode'] = ($request->has('tahunperiode') && $request->get('tahunperiode') != '') ? $request->get('tahunperiode') : $this->periodeAktifRepository->getFirstPeriode()->Tahun;
        $data['perioderealisasi'] = ($request->has('perioderealisasi') && $request->get('perioderealisasi') != '') ? $request->get('perioderealisasi') : $this->periodeAktifRepository->getFirstPeriode()->IDJenisPeriode;
        $data['periodeAktif'] = $this->periodeAktifRepository->getAllPeriode();
        $data['jenisperiode'] = JenisPeriode::orderBy('ID')->whereNotIn('JenisPeriode', ['Bulanan'])->get(['ID', 'KodePeriode']);
        $data['parsePeriode'] = $data['jenisperiode']->where('ID', $data['perioderealisasi'])->first();

        $data['collection'] = $this->reportRealisasiRepository->datatableReportRealisasiIndividu(25, $data['periode'], $data['perioderealisasi'], $request->except(['page']));
        $data['karyawanCount'] = $this->karyawanRepository->count();
        $data['approvedCount'] = $this->reportRealisasiRepository->countProgressRealisasiKPI($data['periode'], $data['perioderealisasi'], 'approved');
        $data['inprogressCount'] = $this->reportRealisasiRepository->countProgressRealisasiKPI($data['periode'], $data['perioderealisasi']);
        if ($request->exists('unduh')) {
            $downloadService->unduh(Excel::REALISASIINDIVIDU, $request->get('tahunperiode'), $request->get('perioderealisasi'));
        }
        return view('backends.reports.realisasi.indexindividu', compact('data'));
    }

    /**
     * @param Request $request
     * @param Excel $downloadService
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function indexUnitKerja(Request $request, Excel $downloadService)
    {
        $data['numbering'] = numberingPagination(25);
        $data['periode'] = ($request->has('tahunperiode') && $request->get('tahunperiode') != '') ? $request->get('tahunperiode') : $this->periodeAktifRepository->getFirstPeriode()->Tahun;
        $data['perioderealisasi'] = ($request->has('perioderealisasi') && $request->get('perioderealisasi') != '') ? $request->get('perioderealisasi') : $this->periodeAktifRepository->getFirstPeriode()->IDJenisPeriode;
        $data['periodeAktif'] = $this->periodeAktifRepository->getAllPeriode();
        $data['jenisperiode'] = JenisPeriode::orderBy('ID')->whereNotIn('JenisPeriode', ['Bulanan'])->get(['ID', 'KodePeriode']);
        $data['parsePeriode'] = $data['jenisperiode']->where('ID', $data['perioderealisasi'])->first();

        $data['unitkerja'] = $this->unitkerjaRepository->asList()->toArray();
        $data['karyawanCount'] = $this->karyawanRepository->count();
        $data['collection'] = $this->reportRealisasiRepository->datatableReportRealisasiByUnitKerja(25, $data['periode'], $data['perioderealisasi'], $request->except(['page']));
        $data['collectedCount'] = $this->reportRealisasiRepository->countProgressRealisasiKPI($data['periode'], $data['perioderealisasi'], 'approved');
        $totalProgress = ProgressPercentageService::calculateProgressRencanaKaryawan($data['karyawanCount'], $data['collectedCount']);
        $data['uncollectedCount'] = $totalProgress['uncollected'];
        $data['progressPercentage'] = $totalProgress['progressPercentage'];
        if ($request->exists('unduh')) {
            $downloadService->unduh(Excel::REALISASIUNITKERJA, $request->get('tahunperiode'), $request->get('perioderealisasi'));
        }
        return view('backends.reports.realisasi.indexunitkerja', compact('data'));
    }
}
