<?php

namespace App\Http\Controllers\Backends\KPI;

use Illuminate\Http\Request;
use App\ApplicationServices\InfoKPI\DeleteInfoKPI;
use App\ApplicationServices\InfoKPI\StoreInfoKPI;
use App\ApplicationServices\InfoKPI\UpdateInfoKPI;
use App\Http\Controllers\Controller;
use App\Http\Requests\KPI\Info\Validator;
use App\Infrastructures\Repositories\KPI\InfoRepository;
use Yajra\Datatables\Datatables;
use App\Http\Requests\KPI\Info\StoreInfoKPIRequest;
use App\Http\Requests\KPI\Info\UpdateInfoKPIRequest;

class InfoKPIController extends Controller
{
    /**
     * @var InfoRepository
     */
    protected $infoRepository;

    /**
     * InfoKPIController constructor.
     *
     * @param InfoRepository $infoRepository
     */
    public function __construct(InfoRepository $infoRepository)
    {
        $this->infoRepository = $infoRepository;
    }

    /**
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index(Request $request)
    {
        if ($request->ajax()) {
            return Datatables::of($this->infoRepository->allRaw())
                ->setRowId('ID')
                ->setRowData(['Gambar'=>'Gambar'])
                ->addColumn('Gambar', '<img src="{{ $Gambar != null ? route(\'image.resize\', [\'modulename\' => \'info\', \'width\' => 960, \'height\' => 637, \'imagename\' => $Gambar]):asset(\'assets/img/dummy.png\') }}" class="responsive-image">')
                ->addColumn('Aksi', 'backends.kpi.info.actionbuttons')
                ->editColumn('Informasi', function ($query) {
                    $trim = substr(strip_tags($query->Informasi, ''), 0, 100);
                    return  strlen($query->Informasi)>100? $trim.' ...': $trim;
                })
                ->rawColumns(['Aksi', 'Gambar', 'Informasi'])->make(true);
        }
        return view('backends.kpi.info.index');
    }

    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function create()
    {
        return view('backends.kpi.info.create');
    }

    /**
     * @param Validator $request
     * @param StoreInfoKPI $store
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(Validator $request, StoreInfoKPI $store)
    {
        $result = $store->call($request->all(), $request->user());
        if ($result['status']) {
            flash('Berhasil menambahkan info KPI', 'success')->important();
            return redirect()->route('backend.kpi.info');
        }
        flash('Gagal menambahkan info KPI. Galat: '.$result['errors'], 'danger')->important();
        return redirect()->back();
    }

    /**
     * @param $id
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function edit($id)
    {
        $data = $this->infoRepository->findById($id);
        return view('backends.kpi.info.edit', compact('data'));
    }

    /**
     * @param $id
     * @param Validator $request
     * @param UpdateInfoKPI $update
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update($id, Validator $request, UpdateInfoKPI $update)
    {
        $result = $update->call($id, $request->except(['_token', '_method']), $request->user());
        if ($result['status']) {
            flash('Berhasil merubah info KPI', 'success')->important();
            return redirect()->route('backend.kpi.info');
        }
        flash('Gagal merubah info KPI. Galat: '.$result['errors'], 'danger')->important();
        return redirect()->back();
    }

    /**
     * @param $id
     * @param DeleteInfoKPI $delete
     * @return array
     */
    public function delete($id, DeleteInfoKPI $delete)
    {
        $result = $delete->call($id);
        if ($result['status']) {
            flash()->success('Hapus info KPI berhasil')->important();
            return $result;
        }
        flash()->error('Gagal hapus info KPI. Galat: '.$result['errors'])->important();
        return $result;
    }

    /**
     * @param $id
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function show($id)
    {
        $data = $this->infoRepository->findById($id);
        // dd($data);
        return view('backends.kpi.info.show', compact('data'));
    }
}
