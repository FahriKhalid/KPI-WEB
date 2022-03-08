<?php

namespace App\Http\Controllers\Frontends;

use Illuminate\Http\Request;
use App\Domain\ArtikelBeranda\Entities\Artikel;
use App\Http\Controllers\Controller;
use App\Infrastructures\Repositories\KPI\InfoRepository;

class HomeController extends Controller
{
    /**
     * @var InfoRepository
     */
    protected $infoKPIRepository;

    /**
     * HomeController constructor.
     *
     * @param InfoRepository $infoKPI
     */
    public function __construct(InfoRepository $infoKPI)
    {
        $this->infoKPIRepository = $infoKPI;
    }

    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index()
    { 
        $data = [];
        $data['infoKPI'] = $this->infoKPIRepository->allActive(date('Y-m-d H:i:s'));
        $data['artikel'] = Artikel::first();
        return view('frontends.home.index', compact('data'));
    }

    /**
     * @param $id
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function show($id)
    {
        $data = $this->infoKPIRepository->findById($id);
        $data['artikel'] = Artikel::first();
        return view('frontends.home.show', compact('data'));
    }
}
