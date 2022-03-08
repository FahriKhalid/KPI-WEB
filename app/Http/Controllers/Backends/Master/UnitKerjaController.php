<?php

namespace App\Http\Controllers\Backends\Master;

use Illuminate\Http\Request;
use App\ApplicationServices\Master\UnitKerja\StoreUnitKerjaService;
use App\ApplicationServices\Master\UnitKerja\DeleteUnitKerjaService;
use App\ApplicationServices\Master\UnitKerja\UpdateUnitKerjaService;
use App\Http\Controllers\Controller;
use App\Http\Requests\Master\UnitKerja\StoreUnitKerjaRequest;
use App\Http\Requests\Master\UnitKerja\UpdateUnitKerjaRequest;
use App\Infrastructures\Repositories\KPI\UnitKerjaRepository;
use Yajra\Datatables\Datatables;
use App\Domain\KPI\Entities\UnitKerja;

class UnitKerjaController extends Controller
{
    /**
     * @var UnitKerjaRepository
     */
    protected $unitkerjaRepository;

    /**
     * UnitKerjaController constructor.
     *
     * @param UnitKerjaRepository $unitkerjaRepository
     */
    public function __construct(UnitKerjaRepository $unitkerjaRepository)
    {
        $this->unitkerjaRepository = $unitkerjaRepository;
    }

    /**
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index(Request $request)
    {
        return view('backends.master.unitkerja.index');
    }


    public function data(Request $request)
    {
        if ($request->ajax()) {
            return Datatables::of($this->unitkerjaRepository->datatable())
                    ->setRowId('CostCenter')
                    ->addColumn('Aksi', 'backends.master.unitkerja.actionbuttons')
                    ->editColumn('Aktif', function ($query) {
                        return  $query->Aktif?'<i class="fa fa-check" style="color:green;"></i>':'<i class="fa fa-times" style="color:red;"></i>';
                    })
                    ->rawColumns(['Aksi', 'Aktif', 'Keterangan'])
                    ->make(true);
        }
    }
    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function create()
    {
        // dd(auth()->user()->UserRole->Role);
        return view('backends.master.unitkerja.create');
    }

    
    public function store(StoreUnitKerjaRequest $request, StoreUnitKerjaService $store)
    {
        $result = $store->call($request->except(['_token']), $request->user());
        if ($result['status']) {
            flash('Sukses menambah unit kerja', 'success')->important();
            return redirect()->route('backend.master.unitkerja');
        }
        flash('Gagal menambah unit kerja. Galat: '.$result['errors'], 'danger')->important();
        return redirect()->back();
    }

     /**
     * @param $costcenter
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function edit($costcenter)
    {
        $data['unitkerja'] = $this->unitkerjaRepository->findById($costcenter);
        return view('backends.master.unitkerja.edit', compact('data'));
    }

    /**
     * @param $costcenter
     * @param UpdateUnitKerjaRequest $request
     * @param UpdateUnitKerjaService $update
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update($costcenter, UpdateUnitKerjaRequest $request, UpdateUnitKerjaService $update)
    {
        // dd($request->all());
        $result = $update->call($costcenter, $request->except(['_token', '_method']), $request->user());
        //dd($result);
        if ($result['status']) {
            flash('Sukses memperbaharui unit kerja', 'success')->important();
            return redirect()->route('backend.master.unitkerja');
        }
        flash('Gagal memperbaharui unit kerja. Galat: '.$result['errors'], 'danger')->important();
        return redirect()->back();
    }

    /**
     * @param $costcenter
     * @param DeleteUnitKerjaService $deleteUnitKerjaService
     * @return array
     * @internal param DeleteUnitKerja $delete
     */
    public function delete($costcenter, DeleteUnitKerjaService $deleteUnitKerjaService)
    {
        $result = $deleteUnitKerjaService->call($costcenter);
        if ($result['status']) {
            flash()->success('Sukses menghapus unit kerja')->important();
            return response()->json($result);
        }
        flash()->error('Gagal menghapus unit kerja. Galat. '.$result['errors'])->important();
        return response()->json($result, 500);
    }

    public function show($costcenter)
    {
        $data = $this->unitkerjaRepository->findById($costcenter);
        return view('backends.master.unitkerja.show', compact('data'));
    }
}
