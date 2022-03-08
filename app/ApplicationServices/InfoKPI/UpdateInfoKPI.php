<?php
namespace App\ApplicationServices\InfoKPI;

use App\ApplicationServices\ApplicationService;
use App\Domain\KPI\Services\ImageUploadService;
use App\Infrastructures\Repositories\KPI\InfoRepository;
use App\Infrastructures\Shared\DeleteFileTrait;

class UpdateInfoKPI extends ApplicationService
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
     * UpdateInfoKPI constructor.
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
     * @param array $data
     * @param $user
     * @return array
     */
    public function call($id, array $data, $user)
    {
        try {
            $info = $this->infoRepository->findById($id);
            $info->Judul = $data['Judul'];
            $info->Tanggal_publish = (! empty($data['Tanggal_publish'])) ? $data['Tanggal_publish'] : date('Y-m-d');
            $info->Tanggal_berakhir = (! empty($data['Tanggal_berakhir'])) ? $data['Tanggal_berakhir'] : null;
            $info->Informasi = $data['Informasi'];
            $info->user_id = $user->ID;
            $image = $this->uploadService->upload($data);
            if (! is_null($image)) {
                if (! is_null($info->Gambar)) {
                    $this->deleteFile($this->uploadService->getBaseUrl().'/'.$info->Gambar);
                }
                $info->Gambar = $image;
            }
            $info->save();
            return $this->successResponse();
        } catch (\Exception $e) {
            return $this->errorResponse($e->getMessage());
        }
    }
}
