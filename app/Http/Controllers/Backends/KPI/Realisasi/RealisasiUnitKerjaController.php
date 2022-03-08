<?php
/**
 * Created by PhpStorm.
 * User: SENA
 * Date: 08/24/2017
 * Time: 10:08 AM
 */

namespace App\Http\Controllers\Backends\KPI\Realisasi;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use App\ApplicationServices\RealisasiKPI\DeleteAttachmentService;
use App\ApplicationServices\RealisasiKPI\StoreDocumentService;
use App\ApplicationServices\RealisasiKPI\StoreHeaderRealisasiService;
use App\ApplicationServices\RealisasiKPI\StoreNilaiService;
use App\Domain\KPI\Entities\JenisPeriode;
use App\Domain\KPI\Entities\PeriodeAktif;
use App\Domain\KPI\Services\RealisasiIndividuPDFDownloadService;
use App\Domain\Realisasi\Services\MatchPeriodeIndividuUnitKerjaService;
use App\Domain\Rencana\Entities\HeaderRencanaKPI;
use App\Domain\Rencana\Services\TargetPeriodeService;
use App\Http\Requests\KPI\Realisasi\DeleteAttachmentRequest;
use App\Http\Requests\KPI\Realisasi\StoreDocumentRequest;
use App\Http\Requests\KPI\Realisasi\StoreHeaderRequest;
use Yajra\Datatables\Datatables;

class RealisasiUnitKerjaController extends RealisasiIndividuController
{
    /**
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function indexUnitKerja(Request $request)
    {
        if ($request->ajax()) {
            return Datatables::eloquent($this->realisasiKPIRepository->datatableUnitKerja($request->user()))
                ->setRowID('ID')
                ->setRowData(['IDStatusDokumen'=>'IDStatusDokumen'])
                ->addColumn('checkall', '<input type="checkbox" name="id[]" value="{{ $ID }}">')
                ->addColumn('Aksi', 'backends.kpi.realisasi.unitkerja.actionbuttons')
                ->editColumn('periodeaktif.NamaPeriode', function ($query) {
                    return $query->jenisperiode->KodePeriode;
                })->editColumn('Pencapaian', function ($query) {
                    return round($query->detail->avg('PersentaseRealisasi'), 2, PHP_ROUND_HALF_DOWN).'%';
                })
                ->rawColumns(['checkall', 'Aksi'])
                ->make(true);
        }
        return view('backends.kpi.realisasi.unitkerja.index');
    }

    /**
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\Http\RedirectResponse|\Illuminate\View\View
     */
    public function createUnitKerja(Request $request)
    {
        $data['user'] = $request->user();
        /**
         * Check if user has upper or lower abbreviation
         */
        $myabbrev = $data['user']->abbreviation();
        $data['approvedRencana'] = $this->rencanaKPIRepository->findApprovedRencanaByNPK($data['user']->NPK);
        if (empty($data['approvedRencana'])) {
            flash('Belum membuat rencana sebelumnya, buat rencana terlebih dahulu', 'danger')->important();
            return redirect()->back();
        }
        $data['atasanLangsung'] = $this->karyawanRepository->findByPositionAbbreviation($myabbrev->getPositionAbbreviationAtasanLangsung(), $myabbrev->getCodeShift());
        $data['atasanTakLangsung'] = $this->karyawanRepository->findByPositionAbbreviation($myabbrev->getPositionAbbreviationAtasanTakLangsung(), $myabbrev->getCodeShift());
        $data['periodeAktif'] = $this->periodeAktifRepository->findByTahun($data['approvedRencana']->Tahun, ['jenisperiode']);
        $triwulan = JenisPeriode::triwulan()->get();
        $data['periode'] = $triwulan;
        return view('backends.kpi.realisasi.unitkerja.create', compact('data'));
    }

    /**
     * @param StoreHeaderRequest $request
     * @param StoreHeaderRealisasiService $storeHeaderRealisasiService
     * @return \Illuminate\Http\RedirectResponse
     */
    public function storeUnitKerja(StoreHeaderRequest $request, StoreHeaderRealisasiService $storeHeaderRealisasiService)
    {
        $result = $storeHeaderRealisasiService->call($request->except('_token'), $request->user());
        if ($result['status']) {
            flash()->success('Data berhasil disimpan.')->important();
            return redirect()->route('backends.kpi.realisasi.unitkerja.editdetail', ['id' => $storeHeaderRealisasiService->getRealisasiHeader()->ID]);
        }
        flash()->error($result['errors'])->important();
        $request->flashExcept('_token');
        return redirect()->back();
    }

    /**
     * @param $id
     * @param Request $request
     * @param TargetPeriodeService $targetPeriode
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\Http\RedirectResponse|\Illuminate\View\View
     */
    public function editDetailUnitKerja($id, Request $request, TargetPeriodeService $targetPeriode)
    {
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
        $data['target'] = $targetPeriode->periodeID($data['periode']->ID, $data['head']->IsKPIUnitKerja)->getTargetCount();
        $data['targetRealization'] = $targetPeriode->targetParser($data['periode']->ID);
        $data['alldetail'] = $this->realisasiKPIRepository->allItemRealization($id);
        return view('backends.kpi.realisasi.unitkerja.editrealisasi', compact('data'));
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
            return redirect()->route('backends.kpi.realisasi.unitkerja');
        }
        $request->flashExcept('_token');
        flash()->error($result['errors'])->important();
        return redirect()->back();
    }

    /**
     * @param $id
     * @param Request $request
     * @param TargetPeriodeService $targetPeriode
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function showUnitKerja($id, Request $request, TargetPeriodeService $targetPeriode)
    {
        $data['header'] = $this->realisasiKPIRepository->findById($id);
        $data['headerrencana'] = $data['header']->headerrencanakpi;
        $data['karyawan'] = $request->user()->karyawan;
        $data['atasanLangsung'] = $this->karyawanRepository->findByNPK($data['header']->NPKAtasanLangsung);
        $data['atasanTakLangsung'] = $this->karyawanRepository->findByNPK($data['header']->NPKAtasanBerikutnya);
        $data['alldetail'] = $this->realisasiKPIRepository->allItemRealization($data['header']->ID, true);
        $data['periode'] = $data['header']->jenisperiode;
        $data['target'] = $targetPeriode->periodeID($data['periode']->ID, $data['headerrencana']->IsKPIUnitKerja)->getTargetCount();
        $data['periodeTarget']= $targetPeriode->periodeTarget($data['periode']->ID, $data['headerrencana']->IsKPIUnitKerja)->getTargetCount();
        $data['targetRealization'] = $data['header']->Target;
        return view('backends.kpi.realisasi.unitkerja.detail', compact('data'));
    }

    /**
     * @param $id
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function document($id)
    {
        $data['headerrealisasi'] = $this->realisasiKPIRepository->findById($id);
        $data['karyawan'] = $this->karyawanRepository->findByNPK($data['headerrealisasi']->NPK);
        return view('backends.kpi.realisasi.unitkerja.documents', compact('data'));
    }

    /**
     * @param $id
     * @param StoreDocumentRequest $request
     * @param StoreDocumentService $storeDocumentService
     * @return \Illuminate\Http\RedirectResponse
     */
    public function storeDocument($id, StoreDocumentRequest $request, StoreDocumentService $storeDocumentService)
    {
        $result = $storeDocumentService->call($id, $request);
        if ($result['status']) {
            flash()->success('Dokumen berhasil diunggah')->important();
            return redirect()->back();
        }
        $request->flashOnly(['Caption', 'Keterangan']);
        flash()->error($result['errors'])->important();
        return redirect()->back();
    }

    /**
     * @param DeleteAttachmentRequest $request
     * @param DeleteAttachmentService $deleteAttachmentService
     * @return \Illuminate\Http\JsonResponse
     */
    public function deleteDocument(DeleteAttachmentRequest $request, DeleteAttachmentService $deleteAttachmentService)
    {
        $result = $deleteAttachmentService->call($request->except(['_token', '_method']));
        if ($result['status']) {
            flash()->success('Dokumen berhasil dihapus.')->important();
            return response()->json($result);
        }
        flash()->error($result['errors'])->important();
        return response()->json($result);
    }

    /**
     * @param $attachmentId
     * @return \Symfony\Component\HttpFoundation\BinaryFileResponse
     * @internal param $id
     */
    public function downloadDocument($id, $attachmentId)
    {
        $document = $this->realisasiKPIRepository->findAttachmentById($attachmentId);
        $file = Storage::disk('public')->getDriver()->getAdapter()->applyPathPrefix($document->Attachment);
        return response()->download($file);
    }

    /**
     * @param $id
     * @param Request $request
     * @param RealisasiIndividuPDFDownloadService $realisasiIndividuPDFDownloadService
     * @return \Illuminate\Http\RedirectResponse
     */
    public function printPDF($id, Request $request, RealisasiIndividuPDFDownloadService $realisasiIndividuPDFDownloadService)
    {
        $result = $realisasiIndividuPDFDownloadService->createUnitKerja($id, $request->user());
        if ($result['status']) {
            return redirect()->back();
        }
        flash()->error('Dokumen realisasi unit kerja gagal ditampilkan. '.$result['errors'])->important();
        return redirect()->back();
    }
}
