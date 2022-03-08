<?php

namespace App\Http\Controllers\Backends\Master;

use Illuminate\Http\Request;
use App\ApplicationServices\Master\Kompetensi\StoreKompetensiService;
use App\ApplicationServices\Master\Kompetensi\DeleteKompetensiService;
use App\ApplicationServices\Master\Kompetensi\UpdateKompetensiService;
use App\Http\Controllers\Controller;
use App\Http\Requests\Master\Kompetensi\StoreKompetensiRequest;
use App\Http\Requests\Master\Kompetensi\UpdateKompetensiRequest;
use App\Infrastructures\Repositories\KPI\KompetensiRepository;
use Yajra\Datatables\Datatables;
use App\Domain\KPI\Entities\Kompetensi;

class KompetensiController extends Controller
{
    /**
     * @var KompetensiRepository
     */
    protected $kompetensiRepository;

    /**
     * KompetensiController constructor.
     *
     * @param KompetensiRepository $kompetensiRepository
     */
    public function __construct(KompetensiRepository $kompetensiRepository)
    {
        $this->kompetensiRepository = $kompetensiRepository;
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        if ($request->ajax()) {
            return Datatables::of($this->kompetensiRepository->datatable())
                    ->setRowId('ID')
                    ->addColumn('Aksi', 'backends.master.kompetensi.actionbuttons')
                    ->rawColumns(['Aksi'])
                    ->make(true);
        }
       
        return view('backends.master.kompetensi.index', compact('data'));
    }

    public function data(Request $request)
    {
        if ($request->ajax()) {
            return Datatables::of($this->kompetensiRepository->datatable())
                        ->setRowId('ID')
                        ->addColumn('Aksi', 'backends.master.kompetensi.actionbuttons')
                        ->rawColumns(['Aksi', 'PositionID'])
                        ->make(true);
        }
        return view('backends.master.kompetensi.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('backends.master.kompetensi.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreKompetensiRequest $request, StoreKompetensiService $store)
    {
        $result = $store->call($request->except(['_token']), $request->user());
        if ($result['status']) {
            flash('Success to add data', 'success')->important();
            return redirect()->route('backend.master.kompetensi');
        }
        flash('Failed to add data. Error: '.$result['errors'], 'danger')->important();
        return redirect()->back();
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Kompetensi $kompetensi
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $data = $this->kompetensiRepository->findById($id);
        return view('backends.master.kompetensi.show', compact('data'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Kompetensi $kompetensi
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $data['kompetensi'] = $this->kompetensiRepository->findById($id);
        return view('backends.master.kompetensi.edit', compact('data'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Kompetensi  $kompetensi
     * @return \Illuminate\Http\Response
     */
    public function update($id, UpdateKompetensiRequest $request, UpdateKompetensiService $update)
    {
        $result = $update->call($id, $request->except(['_token', '_method']), $request->user());
        if ($result['status']) {
            flash('Success to edit data', 'success')->important();
            return redirect()->route('backend.master.kompetensi');
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
    public function delete($id, DeleteKompetensiService $deleteKompetensiService)
    {
        $result = $deleteKompetensiService->call($id);
        if ($result['status']) {
            flash()->success('Data Succesfully deleted')->important();
            return response()->json($result);
        }
        flash()->error('Failed to delete data. Error. '.$result['errors'])->important();
        return response()->json($result, 500);
    }
}
