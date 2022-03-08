<?php
namespace App\ApplicationServices\RealisasiKPI;

use App\ApplicationServices\ApplicationService;
use App\Http\Requests\KPI\Realisasi\StoreDocumentRequest;
use App\Domain\Realisasi\Entities\Attachment;
use Ramsey\Uuid\Uuid;
use Carbon\Carbon;

class StoreDocumentService extends ApplicationService
{
    /**
     * @param $id
     * @param StoreDocumentRequest $request
     * @return array
     */
    public function call($id, StoreDocumentRequest $request)
    {
        try {
            // upload file
            $filename = $request->file('File')->getClientOriginalName();
            $uploadedFilename = $request->file('File')->storeAs('realisasi', $filename, 'public');

            // store data
            $attachment = new Attachment();
            $attachment->ID = Uuid::uuid4();
            $attachment->IDKPIRealisasiHeader = $id;
            $attachment->Caption = $request->get('Caption');
            $attachment->ContentType = $request->file('File')->getClientOriginalExtension();
            $attachment->FileSize = $request->file('File')->getSize();
            $attachment->FileName = $filename;
            $attachment->Attachment = $uploadedFilename;
            $attachment->Keterangan = ($request->has('Keterangan')) ? $request->get('Keterangan') : null;
            $attachment->CreatedBy = $request->user()->NPK;
            $attachment->CreatedOn = Carbon::now();
            $attachment->save();
            return $this->successResponse();
        } catch (\Exception $e) {
            return $this->errorResponse($e->getMessage());
        }
    }
}
