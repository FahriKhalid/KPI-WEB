<?php
namespace App\ApplicationServices\Authentication;

interface AuthenticationContract
{
    /**
     * @param $credential
     * @return mixed
     */
    public function attempt($credential);
}
