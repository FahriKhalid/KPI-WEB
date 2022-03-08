<?php
namespace App\ApplicationServices\InfoKPI;

use App\ApplicationServices\ApplicationService;
use App\Domain\KPI\Services\ImageUploadService;
use App\Infrastructures\Repositories\KPI\InfoRepository;
use App\Infrastructures\Shared\DeleteFileTrait;

class DeleteInfoKPI extends ApplicationService
{
    use DeleteFileTrait;

    /**
     * @var InfoRepository
     */
    protected $infoRepository;

    /**
     * @var ImageUploadService
     */
    protected $uploadService;

    /**
     * DeleteInfoKPI constructor.
     *
     * @param InfoRepository $infoRepository
     * @param ImageUploadService $imageUploadService
     */
    public function __construct(InfoRepository $infoRepository, ImageUploadService $imageUploadService)
    {
        $this->infoRepository = $infoRepository;
        $this->uploadService = $imageUploadService;
    }

    /**
     * @param $id
     * @return array
     */
    public function call($id)
    {
        $info = $this->infoRepository->findById($id);
        try {
            if (! is_null($info->Gambar)) {
                $this->deleteFile($this->uploadService->getBaseUrl().'/'.$info->Gambar);
            }
            $info->delete();
            return $this->successResponse();
        } catch (\Exception $e) {
            return $this->errorResponse($e->getMessage());
        }
    }
}
