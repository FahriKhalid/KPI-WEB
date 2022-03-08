<?php

namespace App\Http\Controllers\Backends\KPI\Rencana;

use Illuminate\Http\Request;
use App\ApplicationServices\RencanaKPI\DeleteItemKPIService;
use App\ApplicationServices\RencanaKPI\StoreDetailRencanaService;
use App\ApplicationServices\RencanaKPI\StoreHeaderRencanaService;
use App\ApplicationServices\RencanaKPI\UpdateDetailRencanaService;
use App\ApplicationServices\RencanaKPI\SplitRencanaKPIService;
use App\Domain\KPI\Entities\AspekKPI;
use App\Domain\KPI\Entities\JenisAppraisal;
use App\Domain\KPI\Entities\JenisPeriode;
use App\Domain\KPI\Entities\PersentaseRealisasi;
use App\Domain\KPI\Entities\PeriodeAktif;
use App\Domain\KPI\Entities\Satuan;
use App\Domain\KPI\Services\RencanaIndividuPDFDownloadService;
use App\Domain\Rencana\Services\TargetPeriodeService;
use App\Http\Controllers\Controller;
use App\Http\Requests\KPI\Rencana\StoreDetailRencanaRequest;
use App\Http\Requests\KPI\Rencana\StoreHeaderRequest;
use App\Http\Requests\KPI\Rencana\UpdateRencanaIndividuRequest;
use App\Infrastructures\Repositories\Karyawan\KaryawanRepository;
use App\Infrastructures\Repositories\KPI\KamusRepository;
use App\Infrastructures\Repositories\KPI\RencanaKPIRepository;
use App\Infrastructures\Repositories\KPI\PeriodeRealisasiRepository;
use Yajra\Datatables\Datatables;
use Auth;

class RencanaIndividuController extends Controller
{
    /**
     * @var RencanaKPIRepository
     */
    protected $rencanaKPIRepository;

    /**
     * @var KaryawanRepository
     */
    protected $karyawanRepository;

    /**
     * @var TargetPeriodeService
     */
    protected $targetPeriodeService;

    /**
     * @var PeriodeRealisasiRepository
     */
    protected $periodeRealisasiRepository;

    /**
     * @var KamusRepository
     */
    protected $kamusRepository;

    /**
     * @var StoeSplitRencanaKPI
     */
    protected $StoreSplitRencanaKPI;

    /**
     * RencanaIndividuController constructor.
     *
     * @param RencanaKPIRepository $rencanaKPIRepository
     * @param KaryawanRepository $karyawanRepository
     * @param TargetPeriodeService $targetPeriodeService
     * @param PeriodeRealisasiRepository $periodeRealisasiRepository
     * @param KamusRepository $kamusRepository
     */
    public function __construct(
        RencanaKPIRepository $rencanaKPIRepository,
        KaryawanRepository $karyawanRepository,
        TargetPeriodeService $targetPeriodeService,
        PeriodeRealisasiRepository $periodeRealisasiRepository,
        KamusRepository $kamusRepository
    ) {
        $this->rencanaKPIRepository = $rencanaKPIRepository;
        $this->karyawanRepository = $karyawanRepository;
        $this->periodeRealisasiRepository = $periodeRealisasiRepository;
        $this->targetPeriodeService = $targetPeriodeService;
        $this->kamusRepository = $kamusRepository;
    }

    /**
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index(Request $request)
    {
        $filter = $request->all();
        if ($request->ajax()) {
            return Datatables::of($this->rencanaKPIRepository->datatable($request->user(), $filter))
                ->setRowID('ID')
                ->setRowData(['IDStatusDokumen'=>'IDStatusDokumen'])
                ->addColumn('checkall', '<input type="checkbox" name="id[]" value="{{ $ID }}">')
                ->addColumn('Aksi', 'backends.kpi.rencana.actionbuttons')
                ->rawColumns(['checkall', 'Aksi'])
                ->make(true);
        }
        return view('backends.kpi.rencana.index');
    }

    /**
     * @param $id
     * @return mixed
     */
    public function getdatadetail($id)
    {
        return $this->rencanaKPIRepository->findDetailById($id);
    } 

    /**
     * @param $id
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function show($id)
    { 
        $ID=$id;
        $data['header'] = $this->rencanaKPIRepository->findById($id);
        $data['karyawan'] = $this->karyawanRepository->findByNPK($data['header']->NPK);
        $data['atasanLangsung'] = $this->karyawanRepository->findByNPK($data['header']->NPKAtasanLangsung);
        $data['atasanTakLangsung'] = $this->karyawanRepository->findByNPK($data['header']->NPKAtasanBerikutnya);
        $data['alldetail'] = $this->rencanaKPIRepository->getAllDetail($id);
        $data['periode'] = PeriodeAktif::where('Tahun', $data['header']->Tahun)->select('IDJenisPeriode')->first();
        $data['target'] = $this->targetPeriodeService->periodeID($data['periode']->IDJenisPeriode, $data['header']->IsKPIUnitKerja)->getTargetCount();
        $data['periodeTarget']= $this->targetPeriodeService->periodeTarget($data['periode']->IDJenisPeriode)->getTargetCount();
        
        return view('backends.kpi.rencana.detail', compact('data', 'ID'));
    }

    /**
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function createStep1(Request $request)
    {
        $data['user'] = $request->user();
        /**
         * Check if user has upper or lower abbreviation
         */
        $myabbrev = $data['user']->abbreviation();
        $data['atasanLangsung'] = $this->karyawanRepository->findByPositionAbbreviation($myabbrev->getPositionAbbreviationAtasanLangsung(), $myabbrev->getCodeShift());
        $data['atasanTakLangsung'] = $this->karyawanRepository->findByPositionAbbreviation($myabbrev->getPositionAbbreviationAtasanTakLangsung(), $myabbrev->getCodeShift());
        $data['periode'] = PeriodeAktif::select('Tahun')->groupBy(['Tahun'])->get();  

        return view('backends.kpi.rencana.createstep1', compact('data'));
    }

    /**
     * @param StoreHeaderRequest $request
     * @param StoreHeaderRencanaService $storeHeaderRencanaService
     * @return \Illuminate\Http\RedirectResponse
     */
    public function storeStep1(StoreHeaderRequest $request, StoreHeaderRencanaService $storeHeaderRencanaService)
    {
        $result = $storeHeaderRencanaService->call($request->except('_token'), $request->user());
        if ($result['status']) {
            return redirect()->route('backends.kpi.rencana.individu.editdetail', ['id' => $storeHeaderRencanaService->getRencanaHeader()->ID]);
        } 
        $request->flashExcept('_token');
        flash()->error($result['errors'])->important();
        return redirect()->back();
    }
    

    public function splitItem($id)
    { 
        $data['header'] = $this->rencanaKPIRepository->findHeaderByIdDetail($id);
        $data['detail'] = $this->rencanaKPIRepository->findDetailById($id); 
 
        return response()->json($data);  
    }


    public function storeSplitItem($id, Request $request, SplitRencanaKPIService $split){
       
        $result = $split->split($id, $request->except('_token'), $request->user());
         
        if ($result['status']) {
            $response = array("status"=>"success", "message"=>"Split item berhasil");
        }else{
            $response = array("status"=>"error", "message"=>"Split item tidak berhasil");
        }

        return response()->json($response);
    }


    public function unsplitItem($id, SplitRencanaKPIService $split)
    {
        $data['data'] = $this->rencanaKPIRepository->findSplitGroup($id);
        
        if($data['data']){
            $response = array('status' => 'success', 'data' => $data);
        }else{
            $response = array('status' => 'error', 'message' => 'Item tidak dapat di unsplit');
        } 

        return response()->json($response); 
    }


    public function storeUnsplitItem($id, SplitRencanaKPIService $split){
       
        $result = $split->unsplit($id);
        
        if ($result['status']) {
            $response = array("status"=>"success", "message"=>"Unsplit item berhasil");
        }else{
            $response = array("status"=>"error", "message"=>"Unsplit item tidak berhasil");
        }

        return response()->json($response);
    }

    /**
     * @param $id
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function editDetail($id, Request $request)
    {
        $ID = $id; 
        $data = $this->getSupportData();
        $data['header'] = $this->rencanaKPIRepository->findById($id); 
        if ($data['header']->IDStatusDokumen != 1) {
            flash()->error('Rencana tidak dapat di-edit karena status bukan Draft')->important();
            return redirect()->back();
        }
        $data['karyawan'] = $this->karyawanRepository->findByNPK($data['header']->NPK);
        $data['posisi'] = substr($data['karyawan']->organization->position->PositionAbbreviation, 10);
        $data['atasanLangsung'] = $this->karyawanRepository->findByNPK($data['header']->NPKAtasanLangsung);
        $data['atasanTakLangsung'] = $this->karyawanRepository->findByNPK($data['header']->NPKAtasanBerikutnya);
        $data['periode'] = PeriodeAktif::where('Tahun', $data['header']->Tahun)->select('IDJenisPeriode')->first();
        $data['target'] = $this->targetPeriodeService->periodeID($data['periode']->IDJenisPeriode, $data['header']->IsKPIUnitKerja)->getTargetCount();
        $data['alldetail'] = $this->rencanaKPIRepository->getAllDetail($id); 
    
        $data['periodeTarget']= $this->targetPeriodeService->periodeTarget($data['periode']->IDJenisPeriode, $data['header']->IsKPIUnitKerja)->getTargetCount(); 

        return view('backends.kpi.rencana.editdetail', compact('data', 'ID'));
    }

    /**
     * @param $id
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function editItem($id)
    { 
        $data = $this->getSupportData();
        $data['header'] = $this->rencanaKPIRepository->findHeaderByIdDetail($id);
        if ($data['header']->IDStatusDokumen != 1) {
            flash()->error('Status harus Draft')->important();
            return redirect()->back();
        }
        $data['karyawan'] = $this->karyawanRepository->findByNPK($data['header']->NPK);
        $data['posisi'] = substr($data['karyawan']->organization->position->PositionAbbreviation, 10);
        $data['jenis'] = $this->periodeRealisasiRepository->findByTahun($data['header']->Tahun)->pluck('Urutan')->toArray();
        $data['detail'] = $this->rencanaKPIRepository->findDetailById($id);
        $data['periode'] = PeriodeAktif::where('Tahun', $data['header']->Tahun)->select('IDJenisPeriode')->first();
        $data['target'] =  $this->targetPeriodeService->periodeID($data['periode']->IDJenisPeriode, $data['header']->IsKPIUnitKerja)->getTargetCount();
        $data['periodeTarget']= $this->targetPeriodeService->periodeTarget($data['periode']->IDJenisPeriode, $data['header']->IsKPIUnitKerja)->getTargetCount();
        $data['route'] = route('backends.kpi.rencana.individu.updateitem', ['id' => $data['detail']->ID]);
        $data['edit_realisasi'] = false;

        return view('backends.kpi.rencana.edititem', compact('data'));
    }

    

    /**
     * @param $id
     * @param UpdateRencanaIndividuRequest $request
     * @param UpdateDetailRencanaService $updatedetail
     * @return \Illuminate\Http\JsonResponse
     */
    public function updateItem($id, UpdateRencanaIndividuRequest $request, UpdateDetailRencanaService $updatedetail)
    { 
        if($request->IDKodeAspekKPI != 4){

            if(Auth::user()->IDRole == 8){
                if($request->Bobot < 2.5){ 
                    flash('Gagal mengupdate item KPI. Error: Bobot KPI tidak boleh kurang dari 2.5%', 'danger')->important();
                    return redirect()->back();
                }
                if($request->Bobot > 20){
                    flash('Gagal mengupdate item KPI. Bobot KPI tidak boleh lebih dari 20%', 'danger')->important();
                    return redirect()->back();
                }
            }else{
                if($request->Bobot < 5){ 
                    flash('Gagal mengupdate item KPI. Error: Bobot KPI tidak boleh kurang dari 5%', 'danger')->important();
                    return redirect()->back();
                }
                if($request->Bobot > 20){
                    flash('Gagal mengupdate item KPI. Bobot KPI tidak boleh lebih dari 20%', 'danger')->important();
                    return redirect()->back();
                }
            }

            
        }
        

        $result = $updatedetail->call($id, $request->except(['_token', '_method']), $request->user()); 
        if ($result['status']) {
            flash('Sukses mengupdate item KPI', 'success')->important();
            $idheader = $this->rencanaKPIRepository->findHeaderByIdDetail($id)->ID;
            if ($request->has('source') && $request->get('source') == 'unitkerja') {
                return redirect()->route('backends.kpi.rencana.individu.unitkerja.index', $idheader);
            }
            return redirect()->route('backends.kpi.rencana.individu.editdetail', $idheader);
        }
        flash('Gagal mengupdate item KPI. Error: '.$result['errors'], 'danger')->important();
        return redirect()->back();
        
    }

    /**
     * @param $id
     * @param StoreDetailRencanaRequest $request
     * @param StoreDetailRencanaService $storeDetailRencanaService
     * @return \Illuminate\Http\RedirectResponse
     */
    public function storeDetail($id, StoreDetailRencanaRequest $request, StoreDetailRencanaService $storeDetailRencanaService)
    { 
        $result = $storeDetailRencanaService->call($id, $request->except('_token'), $request->user());
        if ($result['status']) {
            flash('Sukses menambah detil rencana KPI', 'success')->important();
            return redirect()->back();
        }
        $request->flashExcept('_token');
        flash()->error($result['errors'])->important();
        return redirect()->back();
    }

    /**
     * @param Request $request
     * @param DeleteItemKPIService $service
     * @return \Illuminate\Http\JsonResponse
     */
    public function deleteItem(Request $request, DeleteItemKPIService $service)
    {
        //original
        //$result = $service->call($request->except(['_token', '_method']));
        $result = $service->call($request->except(['_token', '_method']), $request->user());
        if ($result['status']) {
            flash()->success('Item KPI telah berhasil dihapus.')->important();
            return response()->json($result);
        }
        flash()->error($result['errors'])->important();
        return response()->json($result);
    }

    /**
     * Print PDF Rencana Individu
     * @param string $id
     * @param Request $request
     * @param RencanaIndividuPDFDownloadService $rencanaIndividuPDFDownloadService
     * @return \Illuminate\Http\RedirectResponse
     */
    public function printPDF($id, Request $request, RencanaIndividuPDFDownloadService $rencanaIndividuPDFDownloadService)
    {
        $result = $rencanaIndividuPDFDownloadService->create($id, $request->user());
        if ($result['status']) {
            return redirect()->back();
        }
        flash()->error('Dokumen rencana individu gagal ditampilkan. '.$result['errors'])->important();
        return redirect()->back();
    }

    /**
     * @param $id
     * @return mixed
     */
    public function unitkerja($id)
    {
        $data = $this->getSupportData();
        $data['header'] = $this->rencanaKPIRepository->findById($id);
        $data['karyawan'] = $this->karyawanRepository->findByNPK($data['header']->NPK);
        $data['posisi'] = substr($data['karyawan']->organization->position->PositionAbbreviation, 10);
        $data['atasanLangsung'] = $this->karyawanRepository->findByNPK($data['header']->NPKAtasanLangsung);
        $data['atasanTakLangsung'] = $this->karyawanRepository->findByNPK($data['header']->NPKAtasanBerikutnya);
        $data['periode'] = PeriodeAktif::where('Tahun', $data['header']->Tahun)->select('IDJenisPeriode')->first();
        $data['target'] = $this->targetPeriodeService->periodeID($data['periode']->IDJenisPeriode, true)->getTargetCount();
        $data['alldetail'] = $this->rencanaKPIRepository->getAllDetail($id, false);
        $data['periodeTarget']= $this->targetPeriodeService->periodeTarget($data['periode']->IDJenisPeriode, true)->getTargetCount();
        return view('backends.kpi.rencana.unitkerja.index', compact('data'));
    }

    /**
     * @param $iditem
     * @return mixed
     */
    public function editItemUnitKerja($iditem)
    {
        $data = $this->getSupportData();
        $data['source'] = 'unitkerja';
        $data['header'] = $this->rencanaKPIRepository->findHeaderByIdDetail($iditem);
        if ($data['header']->IDStatusDokumen != 1) {
            flash()->error('Status harus Draft')->important();
            return redirect()->back();
        }
        $data['karyawan'] = $this->karyawanRepository->findByNPK($data['header']->NPK);
        $data['posisi'] = substr($data['karyawan']->organization->position->PositionAbbreviation, 10);
        $data['jenis'] = $this->periodeRealisasiRepository->findByTahun($data['header']->Tahun)->pluck('Urutan')->toArray();
        $data['detail']=$this->rencanaKPIRepository->findDetailById($iditem);
        $data['periode'] = PeriodeAktif::where('Tahun', $data['header']->Tahun)->select('IDJenisPeriode')->first();
        $data['target'] =  $this->targetPeriodeService->periodeID($data['periode']->IDJenisPeriode, true)->getTargetCount();
        $data['periodeTarget']= $this->targetPeriodeService->periodeTarget($data['periode']->IDJenisPeriode, true)->getTargetCount();
        $data['route'] = route('backends.kpi.rencana.individu.updateitem', ['id' => $data['detail']->ID, 'source' => 'unitkerja']);
        return view('backends.kpi.rencana.edititem', compact('data'));
    }

    /**
     * @param $id
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function showUnitKerja($id)
    {
        $data['header'] = $this->rencanaKPIRepository->findById($id);
        $data['karyawan'] = $this->karyawanRepository->findByNPK($data['header']->NPK);
        $data['posisi'] = substr($data['karyawan']->organization->position->PositionAbbreviation, 10);
        $data['atasanLangsung'] = $this->karyawanRepository->findByNPK($data['header']->NPKAtasanLangsung);
        $data['atasanTakLangsung'] = $this->karyawanRepository->findByNPK($data['header']->NPKAtasanBerikutnya);
        $data['periode'] = PeriodeAktif::where('Tahun', $data['header']->Tahun)->select('IDJenisPeriode')->first();
        $data['target'] = $this->targetPeriodeService->periodeID($data['periode']->IDJenisPeriode, true)->getTargetCount();
        $data['periodeTarget']= $this->targetPeriodeService->periodeTarget($data['periode']->IDJenisPeriode, true)->getTargetCount();
        $data['alldetail'] = $this->rencanaKPIRepository->getAllDetail($id, false);
        return view('backends.kpi.rencana.unitkerja.show', compact('data'));
    }

    /**
     * @param $id
     * @param Request $request
     * @param RencanaIndividuPDFDownloadService $rencanaIndividuPDFDownloadService
     * @return \Illuminate\Http\RedirectResponse
     */
    public function printUnitKerja($id, Request $request, RencanaIndividuPDFDownloadService $rencanaIndividuPDFDownloadService)
    {
        $result = $rencanaIndividuPDFDownloadService->createUnitKerja($id, $request->user());
        if ($result['status']) {
            return redirect()->back();
        }
        flash()->error('Dokumen rencana Unit Kerja gagal ditampilkan. '.$result['errors'])->important();
        return redirect()->back();
    }

    /**
     * @return array
     */
    protected function getSupportData()
    {
        $data['aspekKpi'] = AspekKPI::pluck('AspekKPI', 'ID')->toArray();
        $data['jenisAppraisal'] = JenisAppraisal::pluck('JenisAppraisal', 'ID')->toArray();
        $data['persentaseRealisasi'] = PersentaseRealisasi::pluck('PersentaseRealisasi', 'ID')->toArray();
        $data['satuan'] = Satuan::pluck('Satuan', 'ID')->toArray();
        $data['jenisPeriode'] = JenisPeriode::pluck('NamaPeriodeKPI', 'ID')->toArray();
        return $data;
    }

    /**
     * @param Request $request
     * @return mixed
     */
    public function kamus(Request $request)
    {
        if ($request->ajax()) {
            return Datatables::eloquent($this->kamusRepository->allApproved())
                ->setRowData(['KodeRegistrasi'=>'KodeRegistrasi'])
                ->addColumn('Aksi', 'backends.kpi.rencana.kamus.actionbuttons')
                ->editColumn('KPI', function ($query) {
                    $trim = substr(strip_tags($query->KPI, ''), 0, 30);
                    return  strlen(strip_tags($query->KPI, ''))>30? $trim.' ...': $trim;
                })
                ->editColumn('IndikatorHasil', function ($query) {
                    $trim = substr(strip_tags($query->IndikatorHasil, ''), 0, 15);
                    return  strlen(strip_tags($query->IndikatorHasil, ''))>15? $trim.' ...': $trim;
                })
                ->editColumn('IndikatorKinerja', function ($query) {
                    $trim = substr(strip_tags($query->IndikatorKinerja, ''), 0, 15);
                    return  strlen(strip_tags($query->IndikatorKinerja, ''))>15? $trim.' ...': $trim;
                })
                ->editColumn('Deskripsi', function ($query) {
                    $trim = substr(strip_tags($query->Deskripsi, ''), 0, 30);
                    return  strlen(strip_tags($query->Deskripsi, ''))>30? $trim.' ...': $trim;
                })
                ->editColumn('Rumus', function ($query) {
                    $trim = substr(strip_tags($query->Rumus, ''), 0, 15);
                    return  strlen(strip_tags($query->Rumus, ''))>15? $trim.' ...': $trim;
                })
                ->editColumn('SumberData', function ($query) {
                    $trim = substr(strip_tags($query->SumberData, ''), 0, 15);
                    return  strlen(strip_tags($query->SumberData, ''))>15? $trim.' ...': $trim;
                })
                ->editColumn('PeriodeLaporan', function ($query) {
                    $trim = substr(strip_tags($query->PeriodeLaporan, ''), 0, 15);
                    return  strlen(strip_tags($query->PeriodeLaporan, ''))>15? $trim.' ...': $trim;
                })
                ->editColumn('Jenis', function ($query) {
                    $trim = substr(strip_tags($query->Jenis, ''), 0, 15);
                    return  strlen(strip_tags($query->Jenis, ''))>15? $trim.' ...': $trim;
                })
                ->editColumn('Catatan', function ($query) {
                    $trim = substr(strip_tags($query->Catatan, ''), 0, 15);
                    return  strlen(strip_tags($query->Catatan, ''))>15? $trim.' ...': $trim;
                })
                ->rawColumns(['Aksi'])
                ->make(true);
        }
    }
}
