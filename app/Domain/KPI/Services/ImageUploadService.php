<?php

namespace App\Domain\KPI\Services;

use App\Infrastructures\Shared\BaseUploader;

class ImageUploadService extends BaseUploader
{
    /**
     * @var string
     */
    protected $fieldName = 'Gambar';

    /**
     * UploadService constructor.
     */
    public function __construct()
    {
        $this->baseUrl = public_path().'/images/kpiinfo';
    }
}
