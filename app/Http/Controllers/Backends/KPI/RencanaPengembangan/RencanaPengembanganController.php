<?php
/**
 * Created by PhpStorm.
 * User: SENA
 * Date: 08/21/2017
 * Time: 12:44 PM
 */

namespace App\Http\Controllers\Backends\KPI\RencanaPengembangan;

use Illuminate\Http\Request;
use App\ApplicationServices\RencanaPengembangan\StoreRencanaPengembanganService;
use App\Domain\KPI\Services\RencanaPengembanganPDFDownloadService;
use App\Http\Controllers\Controller;
use App\Infrastructures\Repositories\KPI\RealisasiKPIRepository;
use App\Infrastructures\Repositories\KPI\RencanaPengembanganRepository;
use Yajra\Datatables\Datatables;

class RencanaPengembanganController extends Controller
{
    /**
     * @var RencanaPengembanganRepository
     */
    protected $rencanaPengembanganRepository;

    /**
     * @var RealisasiKPIRepository
     */
    protected $realisasiKPIRepository;

    /**
     * RencanaPengembanganController constructor.
     *
     * @param RencanaPengembanganRepository $rencanaPengembanganRepository
     * @param RealisasiKPIRepository $realisasiKPIRepository
     */
    public function __construct(RencanaPengembanganRepository $rencanaPengembanganRepository, RealisasiKPIRepository $realisasiKPIRepository)
    {
        $this->rencanaPengembanganRepository =$rencanaPengembanganRepository;
        $this->realisasiKPIRepository = $realisasiKPIRepository;
    }

    /**
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index(Request $request)
    {
        if ($request->ajax()) {
            return Datatables::eloquent($this->rencanaPengembanganRepository->datatable($request->user()))
                    ->setRowID('ID')
                    ->addColumn('Aksi', 'backends.kpi.rencanapengembangan.actionbuttons')
                    ->rawColumns(['Aksi'])->make(true);
        }
        return view('backends.kpi.rencanapengembangan.index');
    }

    /**
     * @param $idheaderrealisasi
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function show($idheaderrealisasi)
    {
        $data['header'] = $this->realisasiKPIRepository->findById($idheaderrealisasi);
        $data['alldetail'] = $this->realisasiKPIRepository->allItemRealization($data['header']->ID);
        $data['followup'] = $this->rencanaPengembanganRepository->getFollowUpList();
        return view('backends.kpi.rencanapengembangan.detail', compact('data'));
    }

    /**
     * @param $idheaderrealisasi
     * @param Request $request
     * @param StoreRencanaPengembanganService $storeRencanaPengembanganService
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update($idheaderrealisasi, Request $request, StoreRencanaPengembanganService $storeRencanaPengembanganService)
    {
        $result = $storeRencanaPengembanganService->call($request->except('_token'));
        if ($result['status']) {
            flash()->success('Data rencana pengembangan berhasil disimpan.')->important();
            return redirect()->route('backends.kpi.rencanapengembangan');
        }
        $request->flashExcept('_token');
        flash()->error($result['errors'])->important();
        return redirect()->back();
    }

    /**
     * @param $id
     * @param Request $request
     * @param RencanaPengembanganPDFDownloadService $rencanaPengembanganPDFDownloadService
     * @return \Illuminate\Http\RedirectResponse
     */
    public function printPDF($id, Request$request, RencanaPengembanganPDFDownloadService $rencanaPengembanganPDFDownloadService)
    {
        $result = $rencanaPengembanganPDFDownloadService->create($id, $request->user());
        if ($result['status']) {
            return redirect()->back();
        }
        flash()->error('Dokumen rencana pengembangan gagal ditampilkan. '.$result['errors'])->important();
        return redirect()->back();
    }
}
