<?php

namespace App\Http\Controllers\Backends\KPI\Realisasi;

use Illuminate\Http\Request;
use App\ApplicationServices\RencanaKPI\DeleteItemKPIService;
use App\ApplicationServices\RealisasiKPI\StoreHeaderRealisasiService;
use App\ApplicationServices\RealisasiKPI\StoreDetailRealisasiKPIService;
use App\Domain\KPI\Entities\JenisPeriode;
use App\Domain\Realisasi\Entities\HeaderRealisasiKPI;
use App\Domain\KPI\Services\RealisasiIndividuPDFDownloadService;
use App\Domain\Rencana\Services\TargetPeriodeService;
use App\Http\Controllers\Controller;
use App\ApplicationServices\RealisasiKPI\StoreNilaiService;
use App\Http\Requests\KPI\Realisasi\StoreHeaderRequest;
use App\Infrastructures\Repositories\Karyawan\KaryawanRepository;
use App\Infrastructures\Repositories\KPI\PeriodeAktifRepository;
use App\Infrastructures\Repositories\KPI\RealisasiKPIRepository;
use App\Infrastructures\Repositories\KPI\RencanaKPIRepository;
use Yajra\Datatables\Datatables;
use App\Domain\Rencana\Services\TargetParserService;
use App\Domain\KPI\Entities\AspekKPI;
use App\Domain\KPI\Entities\JenisAppraisal; 
use App\Domain\KPI\Entities\PersentaseRealisasi;
use App\Domain\KPI\Entities\PeriodeAktif;
use App\Domain\KPI\Entities\Satuan;
use App\Domain\Realisasi\Entities\DetilRealisasiKPI;
use App\Domain\Rencana\Entities\DetilRencanaKPI;
use App\Exceptions\DomainException;
use Ramsey\Uuid\Uuid; 
use App\Infrastructures\Repositories\KPI\PeriodeRealisasiRepository; 

class RealisasiIndividuController extends Controller
{
    /**
     * @var RencanaKPIRepository
     */
    protected $rencanaKPIRepository;

    /**
     * @var RealisasiKPIRepository
     */
    protected $realisasiKPIRepository;

    /**
     * @var KaryawanRepository
     */
    protected $karyawanRepository;

    /**
     * @var PeriodeAktifRepository
     */
    protected $periodeAktifRepository;

    /**
     * @var TargetParserService
     */
    protected $targetParserService;

    /**
     * RealisasiIndividuController constructor.
     *
     * @param RencanaKPIRepository $rencanaRepository
     * @param RealisasiKPIRepository $realisasiKPIRepository
     * @param KaryawanRepository $karyawanRepository
     * @param PeriodeAktifRepository $periodeAktifRepository
     * @param TargetParserService $targetParserService
     */
    public function __construct(
        RencanaKPIRepository $rencanaRepository,
        RealisasiKPIRepository $realisasiKPIRepository,
        KaryawanRepository $karyawanRepository,
        PeriodeAktifRepository $periodeAktifRepository,
        TargetParserService $targetParserService
    ) {
        $this->rencanaKPIRepository = $rencanaRepository;
        $this->realisasiKPIRepository = $realisasiKPIRepository;
        $this->karyawanRepository = $karyawanRepository;
        $this->periodeAktifRepository = $periodeAktifRepository;
        $this->targetParserService = $targetParserService;
    }

    /**
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index(Request $request)
    {
        $filters = $request->all();
        $data['jenisperiode'] = JenisPeriode::pluck('KodePeriode', 'ID');
        if ($request->ajax()) {
            return Datatables::of($this->realisasiKPIRepository->datatable($request->user(), $filters))
                ->setRowID('ID')
                ->setRowData(['IDStatusDokumen' => 'IDStatusDokumen'])
                ->addColumn('checkall', '<input type="checkbox" name="id[]" value="{{ $ID }}">')
                ->addColumn('Aksi', 'backends.kpi.realisasi.actionbuttons')
                ->rawColumns(['checkall', 'Aksi'])
                ->make(true);
        }
        return view('backends.kpi.realisasi.index', compact('data'));
    }

    /**
     * @param Request $request
     * @return mixed
     */
    public function getDataRealisasiIndividuInYear(Request $request)
    {
        return  Datatables::of($this->realisasiKPIRepository->checkAllItemRealizationHaveApproved($request->user()))->make();
    }

    /**
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\Http\RedirectResponse|\Illuminate\View\View
     */
    public function create(Request $request)
    {
        $data['user'] = $request->user();
        /**
         * Check if user has upper or lower abbreviation
         */
        $myabbrev = $data['user']->abbreviation();
        $data['approvedRencana'] = $this->rencanaKPIRepository->findApprovedRencanaByNPK($data['user']->NPK);
        if (empty($data['approvedRencana'])) {
            flash('Belum membuat rencana individu sebelumnya, buat rencana individu terlebih dahulu', 'danger')->important();
            return redirect()->back();
        }
        $data['periode'] = $this->periodeAktifRepository->findByTahun($data['approvedRencana']->Tahun, ['jenisperiode']);
        $data['atasanLangsung'] = $this->karyawanRepository->findByPositionAbbreviation($myabbrev->getPositionAbbreviationAtasanLangsung(), $myabbrev->getCodeShift());
        $data['atasanTakLangsung'] = $this->karyawanRepository->findByPositionAbbreviation($myabbrev->getPositionAbbreviationAtasanTakLangsung(), $myabbrev->getCodeShift());
        return view('backends.kpi.realisasi.create', compact('data'));
    }

    /**
     * @param StoreHeaderRequest $request
     * @param StoreHeaderRealisasiService $storeHeaderRealisasiService
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(StoreHeaderRequest $request, StoreHeaderRealisasiService $storeHeaderRealisasiService)
    {
        $result = $storeHeaderRealisasiService->call($request->except('_token'), $request->user());

        if ($result['status']) {
            flash()->success('Data berhasil disimpan.')->important();
            return redirect()->route('backends.kpi.realisasi.individu.editdetail', ['id' => $storeHeaderRealisasiService->getRealisasiHeader()->ID]);
        }
        flash()->error($result['errors'])->important();
        $request->flashExcept('_token');
        return redirect()->back();
    }

    /**
     * @param $id
     * @param Request $request
     * @param TargetPeriodeService $targetPeriode
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function show($id, Request $request, TargetPeriodeService $targetPeriode)
    {
        $data['header'] = $this->realisasiKPIRepository->findById($id);
        $data['headerrencana'] = $data['header']->headerrencanakpi;
        $data['karyawan'] = $request->user()->karyawan;
        $data['atasanLangsung'] = $this->karyawanRepository->findByNPK($data['header']->NPKAtasanLangsung);
        $data['atasanTakLangsung'] = $this->karyawanRepository->findByNPK($data['header']->NPKAtasanBerikutnya);
        $data['alldetail'] = $this->realisasiKPIRepository->allItemRealization($data['header']->ID);
        $data['periode'] = $data['header']->jenisperiode;
        $data['target'] = $targetPeriode->periodeID($data['periode']->ID, $data['headerrencana']->IsKPIUnitKerja)->getTargetCount();
        $data['periodeTarget'] = $targetPeriode->periodeTarget($data['periode']->ID, $data['headerrencana']->IsKPIUnitKerja)->getTargetCount();
        $data['targetRealization'] = $targetPeriode->targetParser($data['periode']->ID);


        return view('backends.kpi.realisasi.detail', compact('data'));
    }

    /**
     * @param $id
     * @param Request $request
     * @param TargetPeriodeService $targetPeriode
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\Http\RedirectResponse|\Illuminate\View\View
     */
    public function editDetail($id, Request $request, TargetPeriodeService $targetPeriode)
    {
        $data = $this->getSupportData(); 
        $data['header'] = $this->realisasiKPIRepository->findById($id);

        if ($data['header']->IDStatusDokumen != 1) {
            flash()->error('Status harus Draft')->important();
            return redirect()->back();
        }
        
        $data['head'] = $this->rencanaKPIRepository->findById($data['header']->IDKPIRencanaHeader);
        $data['karyawan'] = $request->user()->karyawan;
        $data['atasanLangsung'] = $this->karyawanRepository->findByNPK($data['header']->NPKAtasanLangsung);
        $data['atasanTakLangsung'] = $this->karyawanRepository->findByNPK($data['header']->NPKAtasanBerikutnya);
        $data['periode'] = $data['header']->jenisperiode;
        $data['periode_aktif'] = PeriodeAktif::where('Tahun', $data['header']->Tahun)->select('IDJenisPeriode')->first(); 
        $data['target'] = $targetPeriode->periodeID($data['periode']->ID)->getTargetCount();
        $data['targetRealization'] = $targetPeriode->targetParser($data['periode']->ID);
        $data['alldetail'] = $this->realisasiKPIRepository->allItemRealization($id);
        $data['periodeTarget']= $targetPeriode->periodeTarget($data['periode']->IDJenisPeriode, $data['header']->IsKPIUnitKerja)->getTargetCount();
        $data['karyawan'] = $this->karyawanRepository->findByNPK($data['header']->NPK);
        $data['posisi'] = substr($data['karyawan']->organization->position->PositionAbbreviation, 10); 
        return view('backends.kpi.realisasi.editrealisasi', compact('data'));
    }

    /**
     * @param $id
     * @param Request $request
     * @param StoreNilaiService $storeNilaiService
     * @return \Illuminate\Http\RedirectResponse
     */
    public function storeNilai($id, Request $request, StoreNilaiService $storeNilaiService)
    {
        $result = $storeNilaiService->call($id, $request->except('_token'), $request->user());
        if ($result['status']) {
            flash()->success('Nilai akhir berhasil dimasukkan')->important();
            return redirect()->route('backends.kpi.realisasi.individu');
        }
        flash()->error($result['errors'])->important();
        return redirect()->back();
    }

    /**
     * @param $npk
     * @param $year
     * @param $targetCount
     * @return mixed
     */
    protected function getTargetRealization($npk, $year, $targetCount)
    {
        $countRealisasi = HeaderRealisasiKPI::where('NPK', $npk)
            ->where('Tahun', $year)
            ->select('ID')->whereNotIn('IDStatusDokumen', [5])
            ->count();
        $targetMapped = $this->targetParserService->targetCount($targetCount);
        return $targetMapped[$countRealisasi - 1];
    }

    /**
     * @param $id
     * @param Request $request
     * @param RealisasiIndividuPDFDownloadService $realisasiIndividuPDFDownloadService
     * @return \Illuminate\Http\RedirectResponse
     */
    public function printPDF($id, Request $request, RealisasiIndividuPDFDownloadService $realisasiIndividuPDFDownloadService)
    {
         
        $result = $realisasiIndividuPDFDownloadService->create($id, $request->user());
        if ($result['status']) {
            return redirect()->back();
        }
        flash()->error('Dokumen realisasi individu gagal ditampilkan. ' . $result['errors'])->important();
        return redirect()->back();
    }

    /**
     * @param $year
     * @param Request $request
     * @param RealisasiIndividuPDFDownloadService $realisasiIndividuPDFDownloadService
     * @return \Illuminate\Http\RedirectResponse
     */
    public function printPDFYear($year, Request $request, RealisasiIndividuPDFDownloadService $realisasiIndividuPDFDownloadService)
    {
        $result = $realisasiIndividuPDFDownloadService->createYearReport($year, $request->user());
        if ($result['status']) {
            return redirect()->back();
        }
        flash()->error('Dokumen realisasi individu gagal ditampilkan. ' . $result['errors'])->important();
        return redirect()->back();
    }


    protected function getSupportData()
    {
        $data['aspekKpi'] = AspekKPI::pluck('AspekKPI', 'ID')->toArray();
        $data['jenisAppraisal'] = JenisAppraisal::pluck('JenisAppraisal', 'ID')->toArray();
        $data['persentaseRealisasi'] = PersentaseRealisasi::pluck('PersentaseRealisasi', 'ID')->toArray();
        $data['satuan'] = Satuan::pluck('Satuan', 'ID')->toArray();
        $data['jenisPeriode'] = JenisPeriode::pluck('NamaPeriodeKPI', 'ID')->toArray();
        return $data;
    }


    public function storeItem($id, Request $request)
    { 


        $realisasi_header = \DB::table("Tr_KPIRealisasiHeader")->where("ID", $id)->first();
 
        $jumlah_task_force = \DB::table("Tr_KPIRencanaDetil")->where("IDKPIRencanaHeader", $realisasi_header->IDKPIRencanaHeader)
                                ->where("IDKodeAspekKPI", 4)->get()->count();
                                
        $user = $request->user();  

        if($jumlah_task_force < 5)
        {  
            try 
            {                
                $detailRencana = new DetilRencanaKPI();
                $detailRencana->ID = Uuid::uuid4();
                $detailRencana->IDKPIRencanaHeader = $realisasi_header->IDKPIRencanaHeader;
                $detailRencana->IDJenisPeriode = $request['IDJenisPeriode'];
                $detailRencana->KodeRegistrasiKamus = (! empty($request['KodeRegistrasiKamus'])) ? $request['KodeRegistrasiKamus'] : null;
                $detailRencana->IDKodeAspekKPI = 4;
                $detailRencana->DeskripsiKRA = (! empty($request['DeskripsiKRA'])) ? $request['DeskripsiKRA'] : null;
                $detailRencana->DeskripsiKPI = (! empty($request['DeskripsiKPI'])) ? $request['DeskripsiKPI'] : null;
                $detailRencana->IDSatuan = $request['IDSatuan'];
                $detailRencana->IDJenisAppraisal = $request['IDJenisAppraisal'];
                $detailRencana->IDPersentaseRealisasi = $request['IDPersentaseRealisasi'];
                $detailRencana->Bobot = 2;
                $detailRencana->IsKRABawahan = (! empty($request['IsKRABawahan'])) ? true : false;
                $detailRencana->Keterangan = (! empty($request['Keterangan'])) ? $request['Keterangan'] : null;
                $detailRencana->CreatedBy = $user->NPK;
                $detailRencana->CreatedOn = \Carbon\Carbon::now('Asia/Jakarta');
                for ($i = 1; $i <= 12; $i++) {
                    $detailRencana->{'Target'.$i} = (array_key_exists('Target'.$i, $request) || ! empty($request['Target'.$i])) ? $request['Target'.$i] : null;
                }
                $save = $detailRencana->save();

                if($save){
                    $realisasiDetail = new DetilRealisasiKPI();
                    $realisasiDetail->ID = Uuid::uuid4();
                    $realisasiDetail->IDKPIRealisasiHeader = $id;
                    $realisasiDetail->IDRencanaKPIDetil = $detailRencana->ID;
                    $realisasiDetail->IDPeriodeKPI = $request['IDJenisPeriode']; 
                    $realisasiDetail->Keterangan = (! empty($request['Keterangan'])) ? $request['Keterangan'] : null;
                    $realisasiDetail->CreatedBy = $user->NPK;
                    $realisasiDetail->CreatedOn = \Carbon\Carbon::now('Asia/Jakarta');
                    $save = $realisasiDetail->save();

                    if($save){
                        return redirect()->back()->with('message', 'Simpan data berhasil');
                    }else{
                        return redirect()->back()->withErrors(['Gagal memyimpan Realisasi KPI']);
                    }
                } 
                else{
                    return redirect()->back()->withErrors(['Gagal memyimpan Rencanan KPI']);
                }
                
            } 
            catch (\Exception $e) 
            {
                return redirect()->back()->withErrors([$e->getMessage()]);
            }
        }else{
            return redirect()->back()->withErrors(['Jumlah task force tidak boleh melebihi 10%']);
        }
    }

    public function editItemRealisasi($id, PeriodeRealisasiRepository $periodeRealisasiRepository, TargetPeriodeService $targetPeriodeService){ 
        $data = $this->getSupportData();
        $data['header'] = $this->rencanaKPIRepository->findHeaderByIdDetail($id);
 
        $data['karyawan'] = $this->karyawanRepository->findByNPK($data['header']->NPK);
        $data['posisi'] = substr($data['karyawan']->organization->position->PositionAbbreviation, 10);
        $data['jenis'] = $periodeRealisasiRepository->findByTahun($data['header']->Tahun)->pluck('Urutan')->toArray();
        $data['detail'] = $this->rencanaKPIRepository->findDetailById($id);
        $data['periode'] = PeriodeAktif::where('Tahun', $data['header']->Tahun)->select('IDJenisPeriode')->first();
        $data['target'] =  $targetPeriodeService->periodeID($data['periode']->IDJenisPeriode, $data['header']->IsKPIUnitKerja)->getTargetCount();
        $data['periodeTarget']= $targetPeriodeService->periodeTarget($data['periode']->IDJenisPeriode, $data['header']->IsKPIUnitKerja)->getTargetCount();
        $data['route'] = route('backends.kpi.realisasi.individu.updateitem', ['id' => $data['detail']->ID]);
        $data['edit_realisasi'] = true; 
        return view('backends.kpi.rencana.edititem', compact('data'));
    } 

    public function updateItem($id, Request $request)
    {
        $data = DetilRencanaKPI::where('ID', $id)->first();   
        $data->DeskripsiKRA = $request->DeskripsiKRA;
        $data->DeskripsiKPI = $request->DeskripsiKPI;
        $data->IDJenisAppraisal = $request->IDJenisAppraisal;
        $data->IDPersentaseRealisasi = $request->IDPersentaseRealisasi;
        $data->IDSatuan = $request->IDSatuan;
        $data->Keterangan = $request->Keterangan;
        $update = $data->save();

        if($update){ 
            $realisasi = DetilRealisasiKPI::where('IDRencanaKPIDetil', $id)->first(); 
            return redirect()->route('backends.kpi.realisasi.individu.editdetail', $realisasi->IDKPIRealisasiHeader)->with('success', 'Update item KPI berhasil');
        }else{
            return redirect()->back()->withErrors(['Update item KPI tidak berhasil']);
        }
    }

    public function deleteItem(Request $request, DeleteItemKPIService $service)
    { 
        $result = $service->delete($request->except(['_token', '_method']), $request->user());
        if ($result['status']) {
            flash()->success('Item KPI telah berhasil dihapus.')->important();
            return response()->json($result);
        }
        flash()->error($result['errors'])->important();
        return response()->json($result); 
    }
}
