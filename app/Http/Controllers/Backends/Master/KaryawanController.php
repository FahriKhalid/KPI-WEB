<?php

namespace App\Http\Controllers\Backends\Master;

use Illuminate\Http\Request;
use App\Domain\Karyawan\Services\PositionAbbreviation;
use App\Http\Controllers\Controller;
use App\Infrastructures\Repositories\Karyawan\KaryawanRepository;
use Yajra\Datatables\Datatables;

class KaryawanController extends Controller
{
    /**
     * @var KaryawanRepository
     */
    protected $karyawanRepository;

    /**
     * KaryawanController constructor.
     *
     * @param KaryawanRepository $karyawanRepository
     */
    public function __construct(KaryawanRepository $karyawanRepository)
    {
        $this->karyawanRepository = $karyawanRepository;
    }

    /**
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index(Request $request)
    {
        if ($request->ajax()) {
            return Datatables::of($this->karyawanRepository->datatable())
                            ->setRowID('NPK')
                            ->addColumn('Aksi', 'backends.master.karyawan.actionbuttons')
                            ->rawColumns(['Aksi'])
                            ->make(true);
        }
        return view('backends.master.karyawan.index');
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function searchnpk(Request $request)
    {
        $karyawan = $this->karyawanRepository->getByNPK($request->get('keyword'));
        
        if ($request->has('generic')) {
            return response()->json($karyawan);
        }
        return response()->json(['data' => $karyawan]);
    }

    /**
     * @param $npk
     * @param PositionAbbreviation $positionAbbreviation
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function detail($npk, PositionAbbreviation $positionAbbreviation)
    {
        $data['karyawan'] =  $this->karyawanRepository->findByNPK($npk);
        $positionAbbreviation->position($data['karyawan']->organization->position->PositionAbbreviation)->codeShift($data['karyawan']->organization->Shift);
        $data['atasanLangsung'] = $this->karyawanRepository->findByPositionAbbreviation($positionAbbreviation->getPositionAbbreviationAtasanLangsung(), $positionAbbreviation->getCodeShift());
        $data['atasanTakLangsung'] = $this->karyawanRepository->findByPositionAbbreviation($positionAbbreviation->getPositionAbbreviationAtasanTakLangsung(), $positionAbbreviation->getCodeShift());  
 
        return view('backends.master.karyawan.detail', compact('data'));
    }
}
