<?php
namespace App\ApplicationServices\RencanaKPI;

use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use App\ApplicationServices\ApplicationService;
use App\Infrastructures\Repositories\KPI\RencanaKPIRepository;

class DeleteAttachmentService extends ApplicationService
{
    /**
     * @var RencanaKPIRepository
     */
    protected $rencanaKPIRepository;

    /**
     * DeleteAttachmentService constructor.
     *
     * @param RencanaKPIRepository $rencanaKPIRepository
     */
    public function __construct(RencanaKPIRepository $rencanaKPIRepository)
    {
        $this->rencanaKPIRepository = $rencanaKPIRepository;
    }

    /**
     * @param array $data
     * @return array
     */
    public function call(array $data)
    {
        try {
            DB::beginTransaction();
            foreach ($data['id'] as $id) {
                $attachment = $this->rencanaKPIRepository->findAttachmentById($id);
                $file = Storage::disk('public')->getDriver()->getAdapter()->applyPathPrefix($attachment->Attachment);
                File::delete($file);
                $attachment->delete();
            }
            DB::commit();
            return $this->successResponse();
        } catch (\Exception $e) {
            DB::rollback();
            return $this->errorResponse($e->getMessage());
        }
    }
}
