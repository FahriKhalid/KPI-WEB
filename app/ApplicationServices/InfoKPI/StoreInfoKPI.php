<?php
namespace App\ApplicationServices\InfoKPI;

use App\ApplicationServices\ApplicationService;
use App\Domain\KPI\Entities\Info;
use App\Domain\KPI\Services\ImageUploadService;
use App\Infrastructures\Repositories\KPI\InfoRepository;

class StoreInfoKPI extends ApplicationService
{
    /**
     * @var InfoRepository
     */
    protected $infoRepository;

    /**
     * @var ImageUploadService
     */
    protected $uploadService;

    /**
     * StoreInfoKPI constructor.
     *
     * @param InfoRepository $infoRepository
     * @param ImageUploadService $uploadService
     */
    public function __construct(InfoRepository $infoRepository, ImageUploadService $uploadService)
    {
        $this->infoRepository = $infoRepository;
        $this->uploadService = $uploadService;
    }

    /**
     * @param array $data
     * @param $user
     * @return array
     */
    public function call(array $data, $user)
    {
        try {
            $info = new Info();
            $info->Judul = $data['Judul'];
            $info->Tanggal_publish = (! empty($data['Tanggal_publish'])) ? $data['Tanggal_publish'] : date('Y-m-d');
            $info->Tanggal_berakhir = (! empty($data['Tanggal_berakhir'])) ? $data['Tanggal_berakhir'] : null;
            $info->Informasi = $data['Informasi'];
            $info->user_id = $user->ID;
            $image = $this->uploadService->upload($data);
            $info->Gambar = (! is_null($image)) ? $image : null;
            $info->save();
            return $this->successResponse();
        } catch (\Exception $e) {
            return $this->errorResponse($e->getMessage());
        }
    }
}
