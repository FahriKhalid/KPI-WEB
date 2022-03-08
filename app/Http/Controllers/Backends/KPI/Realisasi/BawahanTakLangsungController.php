<?php
/**
 * Created by PhpStorm.
 * User: SENA
 * Date: 08/24/2017
 * Time: 09:42 AM
 */

namespace App\Http\Controllers\Backends\KPI\Realisasi;

use Illuminate\Http\Request;
use App\Domain\Rencana\Services\TargetPeriodeService;
use Yajra\Datatables\Datatables;

class BawahanTakLangsungController extends RealisasiIndividuController
{
    /**
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function indexBawahanTakLangsung(Request $request)
    {
        if ($request->ajax()) {
            return Datatables::eloquent($this->realisasiKPIRepository->datatableBawahanTakLangsung($request->user()))
                ->setRowID('ID')
                ->addColumn('checkall', '<input type="checkbox" name="id[]" value="{{ $ID }}">')
                ->addColumn('Aksi', 'backends.kpi.realisasi.bawahantaklangsung.actionbuttons')
                ->rawColumns(['checkall', 'Aksi'])
                ->editColumn('IsUnitKerja', function ($header) {
                    if ($header->IsUnitKerja == 1) {
                        return 'Unit Kerja';
                    }
                    return 'Individu';
                })->make(true);
        }
        $data['confirmed'] = $this->realisasiKPIRepository->countWaitingDocumentByAtasan($request->user()->NPK, 'taklangsung');
        $data['approved'] = $this->realisasiKPIRepository->countStatusUpdatedDocumentBy($request->user()->NPK, 4);
        return view('backends.kpi.realisasi.bawahantaklangsung.index', compact('data'));
    }

    /**
     * @param $id
     * @param TargetPeriodeService $targetPeriode
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function detailBawahanTakLangsung($id, TargetPeriodeService $targetPeriode)
    {
        $data['header'] = $this->realisasiKPIRepository->findById($id);
        $data['headerrencana'] = $data['header']->headerrencanakpi;
        $data['karyawan'] = $this->karyawanRepository->findByNPK($data['header']->NPK);
        $data['atasanLangsung'] = $this->karyawanRepository->findByNPK($data['header']->NPKAtasanLangsung);
        $data['atasanTakLangsung'] = $this->karyawanRepository->findByNPK($data['header']->NPKAtasanBerikutnya);
        $data['alldetail'] = $this->realisasiKPIRepository->allItemRealization($data['header']->ID);
        $data['periode'] = $data['header']->jenisperiode;
        $data['target'] = $targetPeriode->periodeID($data['periode']->ID, $data['headerrencana']->IsKPIUnitKerja)->getTargetCount();
        $data['periodeTarget']= $targetPeriode->periodeTarget($data['periode']->ID, $data['headerrencana']->IsKPIUnitKerja)->getTargetCount();
        $data['targetRealization'] = $targetPeriode->targetParser($data['periode']->ID);
        return view('backends.kpi.realisasi.bawahantaklangsung.detail', compact('data'));
    }

    /**
     * @param $id
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function documentBawahanTakLangsung($id)
    {
        $data['headerrealisasi'] = $this->realisasiKPIRepository->findById($id);
        $data['karyawan'] = $this->karyawanRepository->findByNPK($data['headerrealisasi']->NPK);
        return view('backends.kpi.realisasi.bawahantaklangsung.document', compact('data'));
    }
}
