<?php

namespace App\Infrastructures\Shared;

use Symfony\Component\HttpFoundation\File\Exception\UploadException;

abstract class BaseUploader
{
    /**
     * @var String
     */
    protected $fieldName;

    /**
     * @var String
     */
    protected $baseUrl = null;

    /**
     * @param $data
     * @return null|string
     */
    public function upload($data)
    {
        $imageName = null;
        if (! empty($data[$this->fieldName])) {
            try {
                $extension = $data[$this->fieldName]->getClientOriginalExtension();
                $imageName = $this->fieldName.'-'.strtotime(date('Y-m-d H:i:s')).'.'.$extension;
                $data[$this->fieldName]->move($this->baseUrl, $imageName);
            } catch (UploadException $e) {
                throw new UploadException($e->getMessage());
            }
        }
        return $imageName;
    }

    /**
     * @return String
     */
    public function getBaseUrl()
    {
        return $this->baseUrl;
    }
}
