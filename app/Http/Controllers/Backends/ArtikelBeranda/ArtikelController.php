<?php
/**
 * Created by PhpStorm.
 * User: SENA
 * Date: 09/12/2017
 * Time: 01:36 PM
 */

namespace App\Http\Controllers\Backends\ArtikelBeranda;

use Illuminate\Http\Request;
use App\ApplicationServices\Artikel\StoreArtikelService;
use App\Domain\ArtikelBeranda\Entities\Artikel;
use App\Http\Controllers\Controller;
use App\Http\Requests\ArtikelBeranda\StoreArtikelRequest;

class ArtikelController extends Controller
{
    /**
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index(Request $request)
    {
        $data['artikel'] = Artikel::first();
        return view('backends.artikelberanda.edit', compact('data'));
    }


    /**
     * @param StoreArtikelRequest $request
     * @param StoreArtikelService $storeArtikelService
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(StoreArtikelRequest $request, StoreArtikelService $storeArtikelService)
    {
        $result = $storeArtikelService->call($request->except('_token'), $request->user());
        if ($result['status']) {
            flash()->success('Narasi berhasil disimpan.')->important();
            return redirect()->route('narration');
        }
        flash()->error('Narasi gagal disimpan. '.$result['errors'])->important();
        return redirect()->back();
    }
}
