<?php
namespace App\ApplicationServices\RealisasiKPI;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;
use App\ApplicationServices\ApplicationService;
use App\Infrastructures\Repositories\KPI\RealisasiKPIRepository;

class DeleteAttachmentService extends ApplicationService
{
    /**
     * @var RealisasiKPIRepository
     */
    protected $realisasiKPIRepository;

    /**
     * DeleteAttachmentService constructor.
     *
     * @param RealisasiKPIRepository $realisasiKPIRepository
     */
    public function __construct(RealisasiKPIRepository $realisasiKPIRepository)
    {
        $this->realisasiKPIRepository = $realisasiKPIRepository;
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
                $attachment = $this->realisasiKPIRepository->findAttachmentById($id);
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
