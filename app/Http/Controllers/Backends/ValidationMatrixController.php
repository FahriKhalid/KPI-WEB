<?php

namespace App\Http\Controllers\Backends;

use Illuminate\Http\Request;
use App\ApplicationServices\Master\ValidationMatrix\DeleteMatrixService;
use App\ApplicationServices\Master\ValidationMatrix\StoreMatrixService;
use App\ApplicationServices\Master\ValidationMatrix\UpdateMatrixService;
use App\Http\Controllers\Controller;
use App\Http\Requests\Master\ValidationMatrix\DeleteRequest;
use App\Http\Requests\Master\ValidationMatrix\StoreRequest;
use App\Infrastructures\Repositories\KPI\UnitKerjaRepository;
use App\Infrastructures\Repositories\Master\ValidationMatrixRepository;
use Yajra\Datatables\Datatables;

class ValidationMatrixController extends Controller
{
    /**
     * @var UnitKerjaRepository
     */
    protected $unitKerjaRepository;

    /**
     * @var ValidationMatrixRepository
     */
    protected $matrixValidationRepository;

    /**
     * ValidationMatrixController constructor.
     *
     * @param UnitKerjaRepository $unitKerjaRepository
     * @param ValidationMatrixRepository $validationMatrixRepository
     */
    public function __construct(
        UnitKerjaRepository $unitKerjaRepository,
        ValidationMatrixRepository $validationMatrixRepository
    ) {
        $this->unitKerjaRepository = $unitKerjaRepository;
        $this->matrixValidationRepository = $validationMatrixRepository;
    }

    /**
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index(Request $request)
    {
        if ($request->ajax()) {
            return Datatables::of($this->matrixValidationRepository->datatable())
                    ->setRowID('ID')
                    ->addColumn('Aksi', 'backends.master.validationmatrix.actionbuttons')
                    ->rawColumns(['Aksi'])
                    ->make(true);
        }
        return view('backends.master.validationmatrix.index');
    }

    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function create()
    {
        $data['unitkerja'] = $this->unitKerjaRepository->all();
        return view('backends.master.validationmatrix.create', compact('data'));
    }

    /**
     * @param StoreRequest $request
     * @param StoreMatrixService $storeMatrixService
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(StoreRequest $request, StoreMatrixService $storeMatrixService)
    {
        $result = $storeMatrixService->call($request->except('_token'), $request->user());
        if ($result['status']) {
            flash()->success('Data matrix berhasil ditambahkan.')->important();
            return redirect()->route('validationmatrix.index');
        }
        $request->flashExcept('_token');
        flash()->error($result['errors'])->important();
        return redirect()->back();
    }

    /**
     * @param $id
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function edit($id)
    {
        $data['matrix'] = $this->matrixValidationRepository->findById($id);
        $data['unitkerja'] = $this->unitKerjaRepository->all();
        return view('backends.master.validationmatrix.edit', compact('data'));
    }

    /**
     * @param $id
     * @param StoreRequest $request
     * @param UpdateMatrixService $updateMatrixService
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update($id, StoreRequest $request, UpdateMatrixService $updateMatrixService)
    {
        $result = $updateMatrixService->call($id, $request->except(['_token', '_method']), $request->user());
        if ($result['status']) {
            flash()->success('Data matrix berhasil diperbarui.')->important();
            return redirect()->route('validationmatrix.index');
        }
        flash()->error($result['errors'])->important();
        return redirect()->back();
    }

    /**
     * @param $id
     * @param DeleteRequest $request
     * @param DeleteMatrixService $deleteMatrixService
     * @return \Illuminate\Http\JsonResponse
     */
    public function delete($id, DeleteRequest $request, DeleteMatrixService $deleteMatrixService)
    {
        $result = $deleteMatrixService->call($id);
        if ($result['status']) {
            flash()->success('Data matriks validasi berhasil dihapus.')->important();
            return response()->json($result);
        }
        flash()->error($result['errors'])->important();
        return response()->json($result, 500);
    }
}
