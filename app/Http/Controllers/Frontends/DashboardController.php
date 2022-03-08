<?php

namespace App\Http\Controllers\Frontends;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Infrastructures\Repositories\KPI\InfoRepository;

class DashboardController extends Controller
{
    protected $infoKPI;

    public function __construct(InfoRepository $infoKPI)
    {
        $this->infoKPI = $infoKPI;
    }

    public function index()
    {
        $data['infoKPI'] = $this->infoKPI->all('paginate', 4);
        return view('frontends.dashboard.index', compact('data'));
    }

    public function getlist()
    {
        $data['infoKPI'] = $this->infoKPI->all('paginate', 12);
        return view('frontends.dashboard.list', compact('data'));
    }

    public function show($id)
    {
        $data = $this->infoKPI->findById($id);
        return view('frontends.dashboard.show', compact('data'));
    }
}
