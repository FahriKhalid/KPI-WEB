<?php

namespace App\Http\Controllers\Backends\KPI\Realisasi;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use App\ApplicationServices\RealisasiKPI\DeleteAttachmentService;
use App\ApplicationServices\RealisasiKPI\StoreDocumentService;
use App\Http\Requests\KPI\Realisasi\DeleteAttachmentRequest;
use App\Http\Requests\KPI\Realisasi\StoreDocumentRequest;

class DocumentController extends RealisasiIndividuController
{
    /**
     * @param $id
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function indexDocument($id)
    {
        $data['headerrealisasi'] = $this->realisasiKPIRepository->findById($id);
        $data['karyawan'] = $this->karyawanRepository->findByNPK($data['headerrealisasi']->NPK);
        return view('backends.kpi.realisasi.documents', compact('data'));
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
}
