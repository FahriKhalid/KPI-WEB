<?php

namespace App\Http\Controllers\Backends\KPI\Rencana;

use Illuminate\Support\Facades\Storage;
use App\ApplicationServices\RencanaKPI\DeleteAttachmentService;
use App\ApplicationServices\RencanaKPI\StoreDocumentService;
use App\Http\Requests\KPI\Rencana\DeleteAttachmentRequest;
use App\Http\Requests\KPI\Rencana\StoreDocumentRequest;

class DocumentController extends RencanaIndividuController
{
    /**
     * @param $id
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function indexDocument($id)
    {
        $data['header'] = $this->rencanaKPIRepository->findById($id);
        $data['karyawan'] = $this->karyawanRepository->findByNPK($data['header']->NPK);
        return view('backends.kpi.rencana.documents', compact('data'));
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
        flash()->error($result['errors'])->important();
        return redirect()->back();
    }

    /**
     * @param $id
     * @param $attachmentId
     * @return \Symfony\Component\HttpFoundation\BinaryFileResponse
     */
    public function downloadDocument($id, $attachmentId)
    {
        $document = $this->rencanaKPIRepository->findAttachmentById($attachmentId);
        $file = Storage::disk('public')->getDriver()->getAdapter()->applyPathPrefix($document->Attachment);
        return response()->download($file, $document->FileName);
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
     * @param $id
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function showDocument($id)
    {
        $data['header'] = $this->rencanaKPIRepository->findById($id);
        $data['karyawan'] = $this->karyawanRepository->findByNPK($data['header']->NPK);
        return view('backends.kpi.rencana.detaildocument', compact('data'));
    }
}
