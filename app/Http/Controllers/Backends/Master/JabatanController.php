<?php

namespace App\Http\Controllers\Backends\Master;

use Illuminate\Http\Request;

use App\Http\Controllers\Controller;
use App\ApplicationServices\Master\Jabatan\DeleteJabatanService;
use App\ApplicationServices\Master\Jabatan\UpdateJabatanService;
use App\ApplicationServices\Master\Jabatan\StoreJabatanService as CreateJabatan;
use App\Http\Requests\Master\Jabatan\StoreJabatanRequest;
use App\Http\Requests\Master\Jabatan\UpdateJabatanRequest;
use App\Infrastructures\Repositories\Karyawan\JabatanRepository;
use App\Infrastructures\Repositories\KPI\UnitKerjaRepository;
use Yajra\Datatables\Datatables;

class JabatanController extends Controller
{
    /**
     * @var JabatanRepository
     */
    protected $jabatanRepository;

    /**
     * @var UnitKerjaRepository
     */
    protected $unitKerjaRepository;

    /**
     * JabatanController constructor.
     *
     * @param JabatanRepository $jabatanRepository
     * @param UnitKerjaRepository $unitKerjaRepository
     */
    public function __construct(JabatanRepository $jabatanRepository, UnitKerjaRepository $unitKerjaRepository)
    {
        $this->jabatanRepository = $jabatanRepository;
        $this->unitKerjaRepository = $unitKerjaRepository;
    }

    /**
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index(Request $request)
    {
        if ($request->ajax()) {
            return Datatables::eloquent($this->jabatanRepository->datatable())
            ->setRowId('ID')
            ->addColumn('Aksi', 'backends.master.jabatan.actionbuttons')
            ->rawColumns(['Aksi'])
            ->make(true);
        }
        return view('backends.master.jabatan.index');
    }

    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function create()
    {
        $data['costcenter'] = $this->unitKerjaRepository->asList()->toArray();
        return view('backends.master.jabatan.create', compact('data'));
    }

    /**
     * @param StoreJabatanRequest $request
     * @param CreateJabatan $createJabatanService
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(StoreJabatanRequest $request, CreateJabatan $createJabatanService)
    {
        $result = $createJabatanService->call($request->all(), $request->user());
        if ($result['status']) {
            flash()->success('Data jabatan telah berhasil ditambahkan')->important();
            return redirect()->route('backend.master.jabatan');
        }
        
        flash()->error($result['errors'])->important();
        return redirect()->back();
    }

    /**
     * @param $id
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function edit($id)
    {
        $data['jabatan'] = $this->jabatanRepository->findById($id);
        $data['costcenter'] = $this->unitKerjaRepository->asList()->toArray();
        return view('backends.master.jabatan.edit', compact('data'));
    }

    /**
     * @param $id
     * @param UpdateJabatanRequest $request
     * @param UpdateJabatanService $updateJabatanService
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update($id, UpdateJabatanRequest $request, UpdateJabatanService $updateJabatanService)
    {
        $result = $updateJabatanService->call($id, $request->all(), $request->user());
        if ($result['status']) {
            flash()->success('Data jabatan telah berhasil diperbarui')->important();
            return redirect()->route('backend.master.jabatan');
        }
        flash()->error($result['errors'])->important();
        return redirect()->back();
    }

    /**
     * @param $id
     * @param DeleteJabatanService $deleteJabatanService
     * @return \Illuminate\Http\JsonResponse
     */
    public function delete($id, DeleteJabatanService $deleteJabatanService)
    {
        $result = $deleteJabatanService->call($id);
        if ($result['status']) {
            flash()->success('Data jabatan telah berhasil dihapus')->important();
            return response()->json($result);
        }
        flash()->error($result['errors'])->important();
        return response()->json($result, 500);
    }
}
