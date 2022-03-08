<?php

namespace App\Http\Controllers\Backends;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Infrastructures\Repositories\KPI\InfoRepository;
use App\Infrastructures\Repositories\KPI\RencanaKPIRepository;

class RealisasiController extends Controller
{
    /**
     * @var InfoRepository
     */
    protected $infoKPIRepository;

    /**
     * @var RencanaKPIRepository
     */
    protected $rencanaKPIRepository;

    /**
     * RealisasiController constructor.
     *
     * @param InfoRepository $infoKPI
     * @param RencanaKPIRepository $rencanaKPIRepository
     */
    public function __construct(InfoRepository $infoKPI, RencanaKPIRepository $rencanaKPIRepository)
    {
        $this->infoKPIRepository = $infoKPI;
        $this->rencanaKPIRepository = $rencanaKPIRepository;
    }

    /**
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index(Request $request)
    {
        $data['pendingRegisteredCount'] = $this->rencanaKPIRepository->countPendingDocument($request->user(), 2);
        $data['pendingConfirmedCount'] = $this->rencanaKPIRepository->countPendingDocument($request->user(), 3);
       //dd($data);
        // dd(  $data['pendingRegisteredCount'], $data['pendingConfirmedCount']);
        $data['infoKPI'] = $this->infoKPIRepository->allActive(date('Y-m-d'));
        return view('backends.realisasi.index', compact('data'));
    }

    /**
     * @param $id
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function show($id)
    {
        $data = $this->infoKPIRepository->find($id);
        return view('frontends.realisasi.show', compact('data'));
    }
}
