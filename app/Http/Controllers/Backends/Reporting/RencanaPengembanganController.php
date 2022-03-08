<?php

namespace App\Http\Controllers\Backends\Reporting;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Infrastructures\Repositories\KPI\PeriodeAktifRepository;
use App\Infrastructures\Repositories\Reports\ReportRencanaPengembanganRepository;

class RencanaPengembanganController extends Controller
{
    /**
     * @var PeriodeAktifRepository
     */
    protected $periodeAktifRepository;

    /**
     * @var ReportRencanaPengembanganRepository
     */
    protected $reportRencanaPengembanganRepository;

    /**
     * RencanaPengembanganController constructor.
     *
     * @param PeriodeAktifRepository $periodeAktifRepository
     * @param ReportRencanaPengembanganRepository $rencanaPengembanganRepository
     */
    public function __construct(PeriodeAktifRepository $periodeAktifRepository, ReportRencanaPengembanganRepository $rencanaPengembanganRepository)
    {
        $this->periodeAktifRepository = $periodeAktifRepository;
        $this->reportRencanaPengembanganRepository = $rencanaPengembanganRepository;
    }

    /**
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function indexReport(Request $request)
    {
        $data['numbering'] = numberingPagination(25);
        $data['periode'] = ($request->has('tahunperiode') && $request->get('tahunperiode') != '') ? $request->get('tahunperiode') : $this->periodeAktifRepository->getFirstPeriode()->Tahun;
        $data['periodeAktif'] = $this->periodeAktifRepository->getAllPeriode();
        $data['collection'] = $this->reportRencanaPengembanganRepository->getReport(25, $data['periode'], $request->except('page'));
        return view('backends.reports.rencanapengembangan.index', compact('data'));
    }
}
