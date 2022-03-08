<?php

namespace App\Http\Controllers\Frontends\KPI;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class RencanaKPIController extends Controller
{
    public function index()
    {
        return view('frontends.kpi.rencana.index');
    }

    public function create()
    {
        return view('frontends.kpi.rencana.create');
    }

    public function edit()
    {
        return view('frontends.kpi.rencana.edit');
    }

    public function penurunanIndividuEdit()
    {
        return view('frontends.kpi.rencana.editpenurunan');
    }

    public function dokumenIndividuEdit()
    {
        return view('frontends.kpi.rencana.editdokumen');
    }
}
