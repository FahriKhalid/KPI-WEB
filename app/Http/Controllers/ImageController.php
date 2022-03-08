<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;

class ImageController extends Controller
{
    /**
     * ImageController constructor.
     */
    public function __construct()
    {
        ini_set('memory_limit', '512M');
    }

    /**
     * @param $moduleName
     * @param $width
     * @param $height
     * @param $imageName
     * @return mixed
     */
    public function resize($moduleName, $width, $height, $imageName)
    {
        $imageName = (file_exists($this->getBaseUrl($moduleName).$imageName)) ? $imageName : 'dummy.png';

        $img = \Image::make($this->getBaseUrl($moduleName).$imageName)->fit((int) $width, (int) $height);

        return $img->response('jpg');
    }

    /**
     * @param $moduleName
     * @param $width
     * @param $imageName
     * @return mixed
     */
    public function resizegallery($moduleName, $width, $imageName)
    {
        $imageName = (file_exists($this->getBaseUrl($moduleName).$imageName)) ? $imageName : 'dummy.png';
        
        $img = \Image::make($this->getBaseUrl($moduleName).$imageName)->resize($width, null, function ($constraint) {
            $constraint->aspectRatio();
        });

        return $img->response('jpg');
    }

    /**
     * @param $moduleName
     * @return string
     */
    private function getBaseUrl($moduleName)
    {
        switch ($moduleName) {
            case 'info':
                $baseUrl = public_path().'/images/kpiinfo/';
                break;
            case 'dummy':
                $baseUrl = public_path().'/assets/img/';
                break;
            default:
                $baseUrl = public_path().'/images/kpiinfo/';
                break;
        }
        return $baseUrl;
    }
}
