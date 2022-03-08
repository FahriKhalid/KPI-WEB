<?php

namespace App\Http\Controllers\Backends\Master;

use Illuminate\Http\Request;
use App\Domain\KPI\Entities\JenisPeriode;
use App\ApplicationServices\Master\PeriodeAktif\StorePeriodeAktifService;
use App\ApplicationServices\Master\PeriodeAktif\DeletePeriodeAktifService;
use App\ApplicationServices\Master\PeriodeAktif\UpdatePeriodeAktifService;
use App\Http\Controllers\Controller;
use App\Http\Requests\Master\PeriodeAktif\StorePeriodeAktifRequest;
use App\Http\Requests\Master\PeriodeAktif\UpdatePeriodeAktifRequest;
use App\Infrastructures\Repositories\KPI\PeriodeAktifRepository;
use Yajra\Datatables\Datatables;
use App\Domain\KPI\Entities\PeriodeAktif;
use Carbon\Carbon;

class PeriodeAktifController extends Controller
{
    /**
     * @var PeriodeAktifRepository
     */
    protected $periodeaktifRepository;

    /**
     * PeriodeAktifController constructor.
     * @param PeriodeAktifRepository $periodeaktifRepository
     */
    public function __construct(PeriodeAktifRepository $periodeaktifRepository)
    {
        $this->periodeaktifRepository = $periodeaktifRepository;
    }

    /**
     * Display a listing of the resource.
     * @param Request $request
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        if ($request->ajax()) {
            return Datatables::of($this->periodeaktifRepository->datatable())
                    ->setRowId('Tahun')
                    ->addColumn('Aksi', 'backends.master.periodeaktif.actionbuttons')
                    ->rawColumns(['Aksi'])
                    ->make(true);
        }
       
        return view('backends.master.periodeaktif.index');
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
        $tahun = Carbon::now()->year;
        $data['tahunOpsi'] = array($tahun,$tahun+1,$tahun+2,$tahun+3,$tahun+4,$tahun+5,$tahun+6,$tahun+7,$tahun+8);
        return view('backends.master.periodeaktif.create', compact('data'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param Request|StorePeriodeAktifRequest $request
     * @param StorePeriodeAktifService $store
     * @return \Illuminate\Http\Response
     */
    public function store(StorePeriodeAktifRequest $request, StorePeriodeAktifService $store)
    {
        $result = $store->call($request->except(['_token']), $request->user());
        if ($result['status']) {
            flash('Berhasil menambah data', 'success')->important();
            return redirect()->route('backend.master.periodeaktif');
        }
        flash('Gagal menambah data. Galat: '.$result['errors'], 'danger')->important();
        return redirect()->back();
    }

    /**
     * Display the specified resource.
     *
     * @param $id
     * @return \Illuminate\Http\Response
     * @internal param \App\PeriodeAktif $periodeAktif
     */
    public function show($id)
    {
        $data['detail'] = $this->periodeaktifRepository->findByTahun($id);
        $data['head'] = $data['detail']->first();
        return view('backends.master.periodeaktif.show', compact('data'));
    }
    /**
     * Show the form for editing the specified resource.
     *
     * @param $id
     * @return \Illuminate\Http\Response
     * @internal param \App\PeriodeAktif $periodeAktif
     */
    public function edit($id)
    {
        $data['jenisPeriode'] = JenisPeriode::get();
        $data['periode'] = JenisPeriode::select('JenisPeriode')->groupBy('JenisPeriode')->get();
        $tahun = Carbon::now()->year;
        $data['tahunOpsi'] = array($tahun,$tahun+1,$tahun+2,$tahun+3,$tahun+4,$tahun+5,$tahun+6,$tahun+7,$tahun+8);
        $data['periodeaktif'] = $this->periodeaktifRepository->findByTahun($id);
        $data['thn'] = $id;
        return view('backends.master.periodeaktif.edit', compact('data'));
    }

    public function findPeriodeByJenisPeriode($jenisPeriode)
    {
        $parameter = array(
            'JenisPeriode' => $jenisPeriode
            );
        $data['periode'] = JenisPeriode::where($parameter)->get();
        return $data;
    }

    public function findPeriodeByJenisPeriodeAndTahun($jenisPeriode, $tahun)
    {
        $parameter = array(
            'JenisPeriode' => $jenisPeriode
        );
        $data['periode'] = JenisPeriode::where($parameter)->get();
        $data['aktif'] = $this->periodeaktifRepository->findByTahun($tahun);
        return $data;
    }
    /**
     * Update the specified resource in storage.
     *
     * @param $id
     * @param Request|UpdatePeriodeAktifRequest $request
     * @param UpdatePeriodeAktifService $update
     * @return \Illuminate\Http\Response
     * @internal param \App\PeriodeAktif $periodeAktif
     */
    public function update($id, UpdatePeriodeAktifRequest $request, UpdatePeriodeAktifService $update)
    {
        $result = $update->call($id, $request->except(['_token', '_method']), $request->user());
        if ($result['status']) {
            flash('Berhasil mengedit data', 'success')->important();
            return redirect()->route('backend.master.periodeaktif');
        }
        flash('Gagal mengedit data. Galat: '.$result['errors'], 'danger')->important();
        return redirect()->back();
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param $id
     * @param DeletePeriodeAktifService $deletePeriodeAktifService
     * @return \Illuminate\Http\Response
     * @internal param \App\PeriodeAktif $periodeAktif
     */
    public function delete($id, DeletePeriodeAktifService $deletePeriodeAktifService)
    {
        $result = $deletePeriodeAktifService->call($id);
        if ($result['status']) {
            flash()->success('Berhasil menghapus data')->important();
            return response()->json($result);
        }
        flash()->error('Gagal menghapus data. Galat: '.$result['errors'])->important();
        return response()->json($result, 500);
    }
}
