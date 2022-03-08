<?php
namespace App\Infrastructures\Shared;

use Illuminate\Support\Facades\File;

trait DeleteFileTrait
{
    /**
     * Delete file from given path
     *
     * @param $path
     */
    protected function deleteFile($path)
    {
        if (File::exists($path)) {
            File::delete($path);
        }
    }
}
