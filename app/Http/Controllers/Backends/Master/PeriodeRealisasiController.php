<?php

namespace App\Http\Controllers\Backends\Master;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Domain\KPI\Entities\JenisPeriode;
use App\Domain\KPI\Entities\PeriodeRealisasi;
use App\Infrastructures\Repositories\KPI\PeriodeRealisasiRepository;
use App\ApplicationServices\Master\PeriodeRealisasi\StorePeriodeRealisasiService;
use App\ApplicationServices\Master\PeriodeRealisasi\UpdatePeriodeRealisasiService;
use App\ApplicationServices\Master\PeriodeRealisasi\DeletePeriodeRealisasiService;
use App\ApplicationServices\Master\PeriodeAktif\UpdatePeriodeAktifService;
use App\Http\Requests\Master\PeriodeRealisasi\StorePeriodeRealisasiRequest;
use Yajra\Datatables\Datatables;
use Carbon\Carbon;

class PeriodeRealisasiController extends Controller
{
    protected $perioderealisasiRepository;

    public function __construct(PeriodeRealisasiRepository $perioderealisasiRepository)
    {
        $this->perioderealisasiRepository = $perioderealisasiRepository;
    }

    public function index(Request $request)
    {
        if ($request->ajax()) {
            //dd($this->perioderealisasiRepository->datatable()->get());
            return Datatables::of($this->perioderealisasiRepository->datatable())
                    ->setRowId('ID')
                    ->addColumn('Aksi', 'backends.master.periodeRealisasi.actionbuttons')
                    ->rawColumns(['Aksi'])
                    ->make(true);
        }
        return view('backends.master.periodeRealisasi.index', compact('data'));
    }

    public function data(Request $request)
    {
        if ($request->ajax()) {
            return Datatables::of($this->periodeRealisasiRepository->datatable())
                        ->setRowId('ID')
                        ->make(true);
        }
        return view('backends.master.periodeRealisasi.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $data['jenisPeriode'] = JenisPeriode::get();
        $data['periode'] = JenisPeriode::select('JenisPeriode')->groupBy('JenisPeriode')->get();
        // dd($data['periode']);
        $tahun = Carbon::now()->year;
        $data['tahunOpsi'] = array($tahun,$tahun+1,$tahun+2,$tahun+3,$tahun+4,$tahun+5,$tahun+6,$tahun+7,$tahun+8);
        return view('backends.master.periodeRealisasi.create', compact('data'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param Request|StorePeriodeRealisasiRequest $request
     * @param StorePeriodeRealisasiService $store
     * @return \Illuminate\Http\Response
     */
    public function store(StorePeriodeRealisasiRequest $request, StorePeriodeRealisasiService $store)
    {
        $result = $store->call($request->all(), $request->user());
        if ($result['status']) {
            flash('Success to add data', 'success')->important();
            return redirect()->route('backend.master.periodeRealisasi');
        }
        flash('Failed to add data. Error: '.$result['errors'], 'danger')->important();
        return redirect()->back();
    }

    
    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Kompetensi $kompetensi
     * @return \Illuminate\Http\Response
     */
    public function edit($tahun, UpdatePeriodeRealisasiService $update)
    {
        $data['jenisperiode'] = $this->perioderealisasiRepository->findByTahun($tahun)->get();
        $data['array'] = $data['jenisperiode']->pluck('ID')->toArray();
        $data['tahun'] = $tahun;
        $data['periodeRealisasi'] = jenisPeriode::select('*')->where('JenisPeriode', $data['jenisperiode'][0]->JenisPeriode)->get();
        // dd($data['periodeRealisasi']);
        return view('backends.master.periodeRealisasi.edit', compact('data'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Kompetensi  $kompetensi
     * @return \Illuminate\Http\Response
     */
    public function update(StorePeriodeRealisasiRequest $request, StorePeriodeRealisasiService $store, DeletePeriodeRealisasiService $deletePeriodeRealisasiService)
    {
        $delete = $deletePeriodeRealisasiService->call($request->Tahun);
        $result = $store->call($request->all(), $request->user());
        if ($result['status'] && $delete['status']) {
            flash('Success to edit data', 'success')->important();
            return redirect()->route('backend.master.periodeRealisasi');
        }
        flash('Failed to edit data. Error: '.$result['errors'], 'danger')->important();
        return redirect()->back();
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Kompetensi  $kompetensi
     * @return \Illuminate\Http\Response
     */

    public function show($tahun)
    {
        $data = $this->perioderealisasiRepository->findByTahun($tahun)->get();
        // dd($data);
        return view('backends.master.periodeRealisasi.show', compact('data'));
    }
}
