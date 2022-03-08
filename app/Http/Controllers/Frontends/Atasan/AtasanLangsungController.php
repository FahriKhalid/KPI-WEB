<?php

namespace App\Http\Controllers\Frontends\Atasan;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class AtasanLangsungController extends Controller
{
    public function index()
    {
        return view('frontends.kpi.rencana.atasan.atasanlangsung');
    }
}
