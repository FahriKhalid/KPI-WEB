<?php

namespace App\Http\Controllers\Frontends;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Infrastructures\KPI\InfoKPIRepository;

class RealisasiController extends Controller
{
    protected $infoKPI;

    public function __construct(InfoKPIRepository $infoKPI)
    {
        $this->infoKPI = $infoKPI;
    }

    public function index()
    {
        $data['infoKPI'] = $this->infoKPI->all('paginate', 4);
        return view('frontends.realisasi.index', compact('data'));
    }

    public function getlist()
    {
        $data['infoKPI'] = $this->infoKPI->all('paginate', 12);
        return view('frontends.realisasi.list', compact('data'));
    }

    public function show($id)
    {
        $data = $this->infoKPI->find($id);
        return view('frontends.realisasi.show', compact('data'));
    }
}
