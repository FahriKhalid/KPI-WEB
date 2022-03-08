<?php

namespace App\Http\Controllers\Backends\KPI\Realisasi;

use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Http\Request;
use App\ApplicationServices\ValidasiUnitKerja\ApproveValidasiUnitKerjaService;
use App\ApplicationServices\ValidasiUnitKerja\CancelValidasiUnitKerjaService;
use App\ApplicationServices\ValidasiUnitKerja\StoreValidasiUnitKerjaService;
use App\ApplicationServices\ValidasiUnitKerja\UpdateValidasiUnitKerjaService;
use App\Domain\Realisasi\Entities\ValidasiUnitKerja;
use App\Domain\Realisasi\Services\CalculateValidasiService;
use App\Http\Controllers\Controller;
use App\Http\Requests\KPI\Validasi\StoreValidasiRequest;
use App\Infrastructures\Repositories\KPI\RealisasiKPIRepository;
use App\Infrastructures\Repositories\Master\ValidationMatrixRepository;
use Yajra\Datatables\Datatables;

class ValidasiUnitKerjaController extends Controller
{
    /**
     * @var ValidationMatrixRepository
     */
    protected $matrixValidasiRepository;

    /**
     * @var RealisasiKPIRepository
     */
    protected $realisasiRepository;

    /**
     * ValidasiUnitKerjaController constructor.
     *
     * @param ValidationMatrixRepository $validationMatrixRepository
     * @param RealisasiKPIRepository $realisasiKPIRepository
     */
    public function __construct(
        ValidationMatrixRepository $validationMatrixRepository,
        RealisasiKPIRepository $realisasiKPIRepository
    ) {
        $this->matrixValidasiRepository = $validationMatrixRepository;
        $this->realisasiRepository = $realisasiKPIRepository;
    }

    /**
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index(Request $request)
    {
        if ($request->user()->karyawan->isManager()) {
            $data['position'] = 'manager';
            $views = 'backends.kpi.validasiunitkerja.index';
        } elseif ($request->user()->karyawan->isGeneralManager()) {
            $data['position'] = 'generalmanager';
            $views = 'backends.kpi.validasiunitkerja.indexgm';
        }

        if ($request->ajax()) {
            $user = $request->user();
            if ($data['position'] === 'manager') {
                $unitkerja = $user->karyawan->organization->position->unitkerja;
                $headers = $this->realisasiRepository->datatableValidasiUnitKerjaByUnitKerjaPenilai($unitkerja->CostCenter);
                return Datatables::of($headers)
                    ->setRowID('ID')
                    ->addColumn('checkall', '<input type="checkbox" name="id[]" value="{{ $ID }}" data-validasi = "{{ $IDValidasi }}">')
                    ->rawColumns(['Aksi', 'checkall'])->make(true);
            }
            if ($data['position'] === 'generalmanager') {
                $headers = $this->realisasiRepository->datatableValidasiUnitKerjaByNPKAtasan($user->NPK);
                return Datatables::of($headers)
                    ->setRowID('ID')
                    ->addColumn('checkall', function ($headers) {
                        $progress = ($headers->TotalCountValidasi / $headers->headerrealisasi->masterposition->unitkerja->matriksvalidasi->count()) * 100;
                        if ($progress < 100 || $headers->IDStatusDokumen == 7) {
                            return '<input type="checkbox" disabled="disabled" title="KPI dapat di approve jika progress pengumpulan nilai validasi sudah 100%.">';
                        }
                        return '<input type="checkbox" name="id[]" value="'.$headers->IDKPIRealisasiHeader.'" data-unitkerja="'.$headers->headerrealisasi->masterposition->KodeUnitKerja.'">';
                    })
                    ->editColumn('AvgValidasi', function($validasiUnitKerja) {
                        return numberDisplay($validasiUnitKerja->AvgValidasi);
                    })
                    ->editColumn('TotalProgress', function ($headers) {
                        return ($headers->TotalCountValidasi / $headers->headerrealisasi->masterposition->unitkerja->matriksvalidasi->count()) * 100 .'%';
                    })
                    ->setRowClass(function (ValidasiUnitKerja $validasiUnitKerja) {
                        $progress = ($validasiUnitKerja->TotalCountValidasi / $validasiUnitKerja->headerrealisasi->masterposition->unitkerja->matriksvalidasi->count()) * 100;
                        return ($progress < 100) ? 'warning' : 'success';
                    })
                    ->rawColumns(['checkall'])->make(true);
            }
       }
       return view($views, compact('data'));
    }

    /**
     * @param $idheaderealisasi
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function create($idheaderealisasi)
    {
        $data['headerrealisasi'] = $this->realisasiRepository->findById($idheaderealisasi);
        try {
            $this->authorize('createValidasi', $data['headerrealisasi']);
            return view('backends.kpi.validasiunitkerja.create', compact('data'));
        } catch (AuthorizationException $exception) {
            flash()->error('Data Realisasi yang dipilih sedang dilakukan atau sudah divalidasi.')->important();
            return redirect()->back();
        }
    }

    /**
     * @param StoreValidasiRequest $request
     * @param StoreValidasiUnitKerjaService $service
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(StoreValidasiRequest $request, StoreValidasiUnitKerjaService $service)
    {
        $result = $service->call($request->except(['_token']), $request->user());
        if ($result['status']) {
            flash()->success('Data nilai validasi KPI unit kerja berhasil disimpan.')->important();
            return redirect()->route('backends.realisasi.validasi.unitkerja.index');
        }
        flash()->error($result['errors'])->important();
        return redirect()->back();
    }

    /**
     * @param $idheaderealisasi
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function edit($idheaderealisasi, Request $request)
    {
        $data['validasi'] = $this->realisasiRepository->findValidasiByCreatedAndHeaderRealisasi($request->user()->NPK, $idheaderealisasi);
        try {
            if ($data['validasi'] !== null) {
                $this->authorize('editValidasi', $data['validasi']->headerrealisasi);
                return view('backends.kpi.validasiunitkerja.edit', compact('data'));
            }
            throw new AuthorizationException('Dokumen yang bisa diedit hanya yang berstatus SUBMITTED.');
        } catch (AuthorizationException $exception) {
            flash()->error('Dokumen yang bisa diedit hanya yang berstatus SUBMITTED.');
            return redirect()->back();
        }
    }

    /**
     * @param $idheaderealisasi
     * @param Request $request
     * @param UpdateValidasiUnitKerjaService $updateValidasiUnitKerjaService
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update($idheaderealisasi, Request $request, UpdateValidasiUnitKerjaService $updateValidasiUnitKerjaService)
    {
        $result = $updateValidasiUnitKerjaService->call($request->except(['_token', '_method']));
        if ($result['status']) {
            flash()->success('Data nilai validasi KPI unit kerja berhasil diperbarui.')->important();
            return redirect()->route('backends.realisasi.validasi.unitkerja.index');
        }
        flash()->error($result['errors'])->important();
        return redirect()->back();
    }

    /**
     * @param Request $request
     * @param CancelValidasiUnitKerjaService $cancelValidasiUnitKerjaService
     * @return \Illuminate\Http\JsonResponse
     */
    public function cancel(Request $request, CancelValidasiUnitKerjaService $cancelValidasiUnitKerjaService)
    {
        $result = $cancelValidasiUnitKerjaService->call($request->except(['_token']));
        if ($result['status']) {
            flash()->success('Data nilai validasi KPI unit kerja berhasil diperbarui.')->important();
            return response()->json($result);
        }
        flash()->error($result['errors'])->important();
        return response()->json($result);
    }

    /**
     * @param Request $request
     * @param ApproveValidasiUnitKerjaService $service
     * @return \Illuminate\Http\JsonResponse
     */
    public function approve(Request $request, ApproveValidasiUnitKerjaService $service)
    {
        $result = $service->call($request->except('_token'), $request->user());
        if ($result['status']) {
            flash()->success('Data validasi berhasil di Approve.')->important();
            return response()->json($result);
        }
        flash()->error($result['errors'])->important();
        return response()->json($result, 500);
    }
}
