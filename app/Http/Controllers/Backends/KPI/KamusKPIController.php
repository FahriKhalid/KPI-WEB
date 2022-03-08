<?php

namespace App\Http\Controllers\Backends\KPI;

use Illuminate\Http\Request;
use App\ApplicationServices\KamusKPI\DeleteKamusKPIService;
use App\ApplicationServices\KamusKPI\StoreKamusKPIService;
use App\ApplicationServices\KamusKPI\UpdateKamusKPIService;
use App\Domain\KPI\Entities\AspekKPI;
use App\Domain\KPI\Entities\JenisAppraisal;
use App\Domain\KPI\Entities\PersentaseRealisasi;
use App\Domain\KPI\Entities\Satuan;
use App\Domain\KPI\Entities\JenisPeriode;
use App\Domain\KPI\Services\KamusKPIExcelDownloadService;
use App\Domain\KPI\Services\KamusKPIExcelUploadService;
use App\Domain\User\Entities\UserPrivilege;
use App\Http\Controllers\Controller;
use App\Http\Requests\KPI\Kamus\StoreKamusKPIDocumentRequest;
use App\Http\Requests\KPI\Kamus\StoreKamusKPIRequest;
use App\Http\Requests\KPI\Kamus\UpdateKamusKPIRequest;
use App\Infrastructures\Repositories\KPI\KamusRepository;
use App\Infrastructures\Repositories\KPI\UnitKerjaRepository;
use App\Notifications\Notifikasi;
use Yajra\Datatables\Datatables;

class KamusKPIController extends Controller
{
    /**
     * @var KamusRepository
     */
    protected $kamusRepository;
    /**
     * @var UnitKerjaRepository
     */
    protected $unitKerjaRepository;
    /**
     * @var UnitKerjaRepository
     */
    public function __construct(KamusRepository $kamusRepository, UnitKerjaRepository $unitKerjaRepository)
    {
        $this->kamusRepository = $kamusRepository;
        $this->unitKerjaRepository = $unitKerjaRepository;
    }

    /**
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index(Request $request)
    {
        $data['approved'] = $this->kamusRepository->countByStatus();
        $data['pending'] = $this->kamusRepository->countByStatus('pending');
        $data['rejected'] = $this->kamusRepository->countByStatus('rejected');
        return view('backends.kpi.kamus.index', compact('data'));
    }

    /**
     * retieve data safely by post method, avoid unathorized exceptions
     * @param Request $request
     * @return mixed
     */
    public function data(Request $request)
    {
        if ($request->ajax()) {
            return Datatables::eloquent($this->kamusRepository->datatable($request->user()))
                ->setRowId('ID')
                ->setRowData(['Status'=>'Status'])
                ->addColumn('Aksi', 'backends.kpi.kamus.actionbuttons')
                ->editColumn('Status', function ($query) {
                    return  ucfirst($query->Status);
                })
                ->editColumn('KPI', function ($query) {
                    $trim = substr(strip_tags($query->KPI, ''), 0, 30);
                    return  strlen(strip_tags($query->KPI, ''))>30? $trim.' ...': $trim;
                })
                ->editColumn('IndikatorHasil', function ($query) {
                    $trim = substr(strip_tags($query->IndikatorHasil, ''), 0, 15);
                    return  $query->IndikatorHasil!=null? (strlen(strip_tags($query->IndikatorHasil, ''))>15? $trim.' ...': $trim):'-';
                })
                ->editColumn('IndikatorKinerja', function ($query) {
                    $trim = substr(strip_tags($query->IndikatorKinerja, ''), 0, 15);
                    return  $query->IndikatorKinerja!=null? (strlen(strip_tags($query->IndikatorKinerja, ''))>15? $trim.' ...': $trim):'-';
                })
                ->editColumn('Deskripsi', function ($query) {
                    $trim = substr(strip_tags($query->Deskripsi, ''), 0, 30);
                    return  strlen(strip_tags($query->Deskripsi, ''))>30? $trim.' ...': $trim;
                })
                ->editColumn('Rumus', function ($query) {
                    $trim = substr(strip_tags($query->Rumus, ''), 0, 15);
                    return  $query->Rumus!=null? (strlen(strip_tags($query->Rumus, ''))>15? $trim.' ...': $trim):'-';
                })
                ->editColumn('SumberData', function ($query) {
                    $trim = substr(strip_tags($query->SumberData, ''), 0, 15);
                    return  $query->SumberData!=null? (strlen(strip_tags($query->SumberData, ''))>15? $trim.' ...': $trim):'-';
                })
                ->editColumn('PeriodeLaporan', function ($query) {
                    $trim = substr(strip_tags($query->PeriodeLaporan, ''), 0, 15);
                    return  $query->PeriodeLaporan!=null? (strlen(strip_tags($query->PeriodeLaporan, ''))>15? $trim.' ...': $trim):'-';
                })
                ->editColumn('Jenis', function ($query) {
                    $trim = substr(strip_tags($query->Jenis, ''), 0, 15);
                    return  $query->Jenis!=null? (strlen(strip_tags($query->Jenis, ''))>15? $trim.' ...': $trim):'-';
                })
                ->editColumn('Catatan', function ($query) {
                    $trim = substr(strip_tags($query->Catatan, ''), 0, 15);
                    return  $query->Catatan!=null? (strlen(strip_tags($query->Catatan, ''))>15? $trim.' ...': $trim):'-';
                })
                ->rawColumns(['Aksi'])
                ->make(true);
        }
        return view('backends.kpi.kamus.index', compact('data'));
    }
    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function create()
    {
        $data = $this->getSupportData();
        return view('backends.kpi.kamus.create', compact('data'));
    }


    /**
     * @param StoreKamusKPIRequest $request
     * @param StoreKamusKPIService $storeKamusKPIService
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(StoreKamusKPIRequest $request, StoreKamusKPIService $storeKamusKPIService)
    {
        $result = $storeKamusKPIService->call($request->except('_token'), $request->user());
        $_=$request->user()->IDRole===3;
        if ($result['status']) {
            try {
                if ($_) {
                    foreach (UserPrivilege::where('IDRole', 1)->get() as $user) {
                        $admin = $user->user->karyawan;
                        $admin->notify(new Notifikasi($storeKamusKPIService->getId(), 'kamus', 'pending'));
                    }
                }
                flash()->success(''.$_?'Kamus berhasil disimpan, dengan status pending':'Kamus berhasil disimpan.')->important();
                return redirect()->route('backend.kpi.kamus');
            } catch (\Exception $e) {
                flash()->success(''.$_?'Kamus berhasil disimpan, dengan status pending':'Kamus berhasil disimpan.')->important();
                return redirect()->route('backend.kpi.kamus');
            }
        }
        flash()->error('Kamus gagal disimpan. '.$result['errors'])->important();
        return redirect()->back();
    }

    /**
     * @param StoreKamusKPIDocumentRequest $request
     * @param KamusKPIExcelUploadService $kamusKPIExcelUploadService
     * @return \Illuminate\Http\RedirectResponse
     */
    public function document(StoreKamusKPIDocumentRequest $request, KamusKPIExcelUploadService $kamusKPIExcelUploadService)
    {
        $result = $kamusKPIExcelUploadService->read($request->Excel, $request->user());
        if ($result['status']) {
            $_=$request->user()->IDRole===3?', dengan status pending':'.';
            flash()->success('Kamus berhasil disimpan'.$_)->important();
            return redirect()->route('backend.kpi.kamus');
        }
        flash()->error('Kamus gagal disimpan. '.$result['errors'])->important();
        return redirect()->back();
    }

    /**
     * @param KamusKPIExcelDownloadService $kamusKPIExcelDownloadService
     * @return \Illuminate\Http\RedirectResponse
     */
    public function excel(KamusKPIExcelDownloadService $kamusKPIExcelDownloadService)
    {
        $result = $kamusKPIExcelDownloadService->create();
        if ($result['status']) {
            flash()->success('Dokumen Excel berhasil diunduh.')->important();
            return redirect()->route('backend.kpi.kamus');
        }
        flash()->error('Dokumen Excel gagal diunduh. '.$result['errors'])->important();
        return redirect()->back();
    }
    /**
     * @param $id
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function edit($id)
    {
        $data = $this->getSupportData();
        $data['kamus'] = $this->kamusRepository->findById($id);
        $data['status'] = $this->kamusRepository->getStatusList();
        // if($data['kamus']->Status == 'approved'){
        //     return redirect()->back();
        // }
        return view('backends.kpi.kamus.edit', compact('data'));
    }

    /**
     * @param $id
     * @param UpdateKamusKPIRequest $request
     * @param UpdateKamusKPIService $updateKamusKPIService
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update($id, UpdateKamusKPIRequest $request, UpdateKamusKPIService $updateKamusKPIService)
    {
        $result = $updateKamusKPIService->call($id, $request->except(['_token', '_method']), $request->user());
        $_=$request->user()->IDRole===1;
        if ($result['status']) {
            try {
                if ($_ and $request->Status!='pending') {
                    $user = $this->kamusRepository->findById($id)->createdby->karyawan;
                    $user->notify(new Notifikasi($id, 'kamus', $request->Status));
                }
                flash()->success('Kamus berhasil diperbaharui')->important();
                return redirect()->route('backend.kpi.kamus');
            } catch (\Exception $e) {
                flash()->success('Kamus berhasil diperbaharui')->important();
                return redirect()->route('backend.kpi.kamus');
            }
        }
        flash()->error('Kamus gagal disimpan. '.$result['errors'])->important();
        return redirect()->back();
    }

    /**
     * @param $id
     * @param DeleteKamusKPIService $deleteKamusKPIService
     * @return \Illuminate\Http\JsonResponse
     */
    public function delete($id, DeleteKamusKPIService $deleteKamusKPIService)
    {
        $result = $deleteKamusKPIService->call($id);
        if ($result['status']) {
            flash()->success('Kamus berhasil dihapus')->important();
            return response()->json($result);
        }
        flash()->error('Gagal menghapus kamus. '.$result['errors'])->important();
        return response()->json($result, 500);
    }

    /**
     * @param $id
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function show($id)
    {
        $data = $this->kamusRepository->findById($id);
        return view('backends.kpi.kamus.show', compact('data'));
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function apiGetSingle(Request $request)
    {
        $data = $this->kamusRepository->findByKodeRegistrasi($request->get('koderegistrasi'));
        return response()->json($data);
    }
    
    /**
     * @return array
     */
    protected function getSupportData()
    {
        $data['costcenter'] = $this->unitKerjaRepository->asList()->map(function ($item, $key) {
            return "$key - $item";
        })->toArray();
        $data['aspekKpi'] = AspekKPI::pluck('AspekKPI', 'ID')->toArray();
        $data['jenisAppraisal'] = JenisAppraisal::pluck('JenisAppraisal', 'ID')->toArray();
        $data['persentaseRealisasi'] = PersentaseRealisasi::pluck('PersentaseRealisasi', 'ID')->toArray();
        $data['jenisPeriod'] = JenisPeriode::pluck('JenisPeriode', 'ID')->toArray();
        $data['satuan'] = Satuan::pluck('Satuan', 'ID')->toArray();
        return $data;
    }
}
