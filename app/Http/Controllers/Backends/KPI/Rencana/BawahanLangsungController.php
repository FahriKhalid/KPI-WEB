<?php

namespace App\Http\Controllers\Backends\KPI\Rencana;

use Illuminate\Http\Request;
use App\Domain\KPI\Entities\PeriodeAktif;
use App\Domain\KPI\Services\RencanaIndividuPDFDownloadService;
use Yajra\Datatables\Datatables;

class BawahanLangsungController extends RencanaIndividuController
{
    /**
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function indexBawahanLangsung(Request $request)
    {
        $filter = $request->all();
        if ($request->ajax()) {
            return Datatables::of($this->rencanaKPIRepository->datatableBawahanLangsung($request->user(), $filter))
                ->setRowID('ID')
                ->addColumn('checkall', '<input type="checkbox" name="id[]" value="{{ $ID }}">')
                ->addColumn('Aksi', 'backends.kpi.rencana.bawahanlangsung.actionbuttons')
                ->rawColumns(['checkall', 'Aksi'])
                ->make(true);
        }
        $data['waitingConfirmed'] = $this->rencanaKPIRepository->countWaitingDocumentByAtasan($request->user()->NPK);
        $data['confirmed'] = $this->rencanaKPIRepository->countStatusUpdatedDocumentBy($request->user()->NPK, 3);
        
        return view('backends.kpi.rencana.bawahanlangsung.index', compact('data'));
    }

    /**
     * @param $id
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function detailBawahanLangsung($id)
    {
        $data['header'] = $this->rencanaKPIRepository->findById($id);
        $data['karyawan'] = $this->karyawanRepository->findByNPK($data['header']->NPK);
        $data['atasanLangsung'] = $this->karyawanRepository->findByNPK($data['header']->NPKAtasanLangsung);
        $data['atasanTakLangsung'] = $this->karyawanRepository->findByNPK($data['header']->NPKAtasanBerikutnya);
        $data['alldetail'] = $this->rencanaKPIRepository->getAllDetail($id);
        $data['periode'] = PeriodeAktif::where('Tahun', $data['header']->Tahun)->select('IDJenisPeriode')->first();
        $data['target'] = $this->targetPeriodeService->periodeID($data['periode']->IDJenisPeriode, $data['header']->IsKPIUnitKerja)->getTargetCount();
        $data['periodeTarget']= $this->targetPeriodeService->periodeTarget($data['periode']->IDJenisPeriode, $data['header']->IsKPIUnitKerja)->getTargetCount();
        return view('backends.kpi.rencana.bawahanlangsung.detail', compact('data'));
    }

    /**
     * @param $id
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function unitkerjaBawahanLangsung($id)
    {
        $data['header'] = $this->rencanaKPIRepository->findById($id);
        $data['karyawan'] = $this->karyawanRepository->findByNPK($data['header']->NPK);
        $data['atasanLangsung'] = $this->karyawanRepository->findByNPK($data['header']->NPKAtasanLangsung);
        $data['atasanTakLangsung'] = $this->karyawanRepository->findByNPK($data['header']->NPKAtasanBerikutnya);
        $data['periode'] = PeriodeAktif::where('Tahun', $data['header']->Tahun)->select('IDJenisPeriode')->first();
        $data['target'] = $this->targetPeriodeService->periodeID($data['periode']->IDJenisPeriode, true)->getTargetCount();
        $data['alldetail'] = $this->rencanaKPIRepository->getAllDetail($id, false);
        $data['periodeTarget']= $this->targetPeriodeService->periodeTarget($data['periode']->IDJenisPeriode, true)->getTargetCount();
        return view('backends.kpi.rencana.bawahanlangsung.unitkerja', compact('data'));
    }

    /**
     * @param $id
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function penurunanBawahanLangsung($id)
    {
        $data['header'] = $this->rencanaKPIRepository->findById($id);
        $data['karyawan'] = $this->karyawanRepository->findByNPK($data['header']->NPK);
        $data['items'] = $this->rencanaKPIRepository->findItemIsKRA($id);
        $data['periode'] = PeriodeAktif::where('Tahun', $data['header']->Tahun)->select('IDJenisPeriode')->first();
        $data['target'] = $this->targetPeriodeService->periodeID($data['periode']->IDJenisPeriode, $data['header']->IsKPIUnitKerja)->getTargetCount();
        $data['periodeTarget']= $this->targetPeriodeService->periodeTarget($data['periode']->IDJenisPeriode, $data['header']->IsKPIUnitKerja)->getTargetCount();
        $data['cascadeItems'] = $this->rencanaKPIRepository->getAllPenurunanItem($id);
        return view('backends.kpi.rencana.bawahanlangsung.penurunan', compact('data'));
    }

    /**
     * @param $id
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function documentBawahanLangsung($id)
    {
        $data['header'] = $this->rencanaKPIRepository->findById($id);
        $data['karyawan'] = $this->karyawanRepository->findByNPK($data['header']->NPK);
        return view('backends.kpi.rencana.bawahanlangsung.documents', compact('data'));
    }

    /**
     * @param $id
     * @param Request $request
     * @param RencanaIndividuPDFDownloadService $exportService
     * @return \Illuminate\Http\RedirectResponse
     */
    public function exportToPdf($id, Request $request, RencanaIndividuPDFDownloadService $exportService)
    {
        if ($request->get('jenisrencana') == 'unitkerja') {
            $result = $exportService->createUnitKerja($id, $request->user());
        } else {
            $result = $exportService->create($id, $request->user());
        }
        if ($result['status']) {
            return redirect()->back();
        }
        flash()->error('Dokumen rencana gagal ditampilkan. '.$result['errors'])->important();
        return redirect()->back();
    }
}
