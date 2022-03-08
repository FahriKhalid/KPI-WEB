<?php
namespace App\ApplicationServices; 

abstract class ApplicationService
{
    /**
     * @param null $message
     * @return array
     */
    protected function successResponse($message = null)
    {
        return ['status' => true, 'errors' => null, 'message' => $message];
    }

    /**
     * @param null $errorMessage
     * @param null $message
     * @return array
     */
    protected function errorResponse($errorMessage = null, $message = null)
    {
        return ['status' => false, 'errors' => 'Error. '.$errorMessage, 'message' => $message];
    }

    public function getDireksi($npk){
       return \DB::table("Ms_Direksi")->where("Npk", $npk)->first();
    }

    
}
