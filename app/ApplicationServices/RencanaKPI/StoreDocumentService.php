<?php
namespace App\ApplicationServices\RencanaKPI;

use Carbon\Carbon;
use App\ApplicationServices\ApplicationService;
use App\Domain\Rencana\Entities\Attachment;
use App\Http\Requests\KPI\Rencana\StoreDocumentRequest;
use Ramsey\Uuid\Uuid;

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
            $uploadedFilename = $request->file('File')->store('rencana', 'public');

            // store data
            $attachment = new Attachment();
            $attachment->ID = Uuid::uuid4();
            $attachment->IDKPIRencanaHeader = $id;
            $attachment->Caption = $request->get('Caption');
            $attachment->ContentType = $request->file('File')->getClientOriginalExtension();
            $attachment->FileSize = $request->file('File')->getSize();
            $attachment->FileName = $request->file('File')->getClientOriginalName();
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
