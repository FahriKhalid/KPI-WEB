<?php

namespace App\Http\Controllers\Backends\KPI\Realisasi;

use Illuminate\Http\Request;
use App\Domain\KPI\Entities\JenisPeriode;
use App\Domain\Rencana\Services\TargetPeriodeService;
use Yajra\Datatables\Datatables;

class BawahanLangsungController extends RealisasiIndividuController
{
    /**
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function indexBawahanLangsung(Request $request)
    {
        if ($request->ajax()) {
            $datatable = Datatables::eloquent($this->realisasiKPIRepository->datatableBawahanLangsung($request->user(), $request->only(['jeniskpi', 'tahunperiode', 'jenisperiode'])))
                ->setRowID('ID')
                ->addColumn('checkall', '<input type="checkbox" name="id[]" value="{{ $ID }}">')
                ->addColumn('Aksi', 'backends.kpi.realisasi.bawahanlangsung.actionbuttons')
                ->rawColumns(['checkall', 'Aksi'])
                ->editColumn('IsUnitKerja', function ($header) {
                    return ($header->IsUnitKerja == 1) ? 'Unit Kerja' : 'Individu';
                });
            if ($request->get('jeniskpi') == 'unitkerja') {
                $datatable->editColumn('Pencapaian', function ($header) {
                    return round($header->detail->avg('PersentaseRealisasi'), 2, PHP_ROUND_HALF_DOWN).'%';
                });
            }
            return $datatable->make(true);
        }
        $data['allowApproval'] = ($request->user()->karyawan->isGeneralManager() || $request->user()->karyawan->isDireksi());
        $data['jenisperiode'] = JenisPeriode::select('ID', 'KodePeriode', 'NamaPeriodeKPI')->get();
        $data['registered'] = $this->realisasiKPIRepository->countWaitingDocumentByAtasan($request->user()->NPK);
        $data['approved'] = $this->realisasiKPIRepository->countStatusUpdatedDocumentBy($request->user()->NPK, 4);
        return view('backends.kpi.realisasi.bawahanlangsung.index', compact('data'));
    }

    /**
     * @param $id
     * @param TargetPeriodeService $targetPeriode
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function detailBawahanLangsung($id, TargetPeriodeService $targetPeriode)
    {
        $data['header'] = $this->realisasiKPIRepository->findById($id);
        $data['headerrencana'] = $data['header']->headerrencanakpi;
        $data['karyawan'] = $this->karyawanRepository->findByNPK($data['header']->NPK);
        $data['atasanLangsung'] = $this->karyawanRepository->findByNPK($data['header']->NPKAtasanLangsung);
        $data['atasanTakLangsung'] = $this->karyawanRepository->findByNPK($data['header']->NPKAtasanBerikutnya);
        $data['alldetail'] = $this->realisasiKPIRepository->allItemRealization($data['header']->ID);
        $data['periode'] = $data['header']->jenisperiode;
        $data['target'] = $targetPeriode->periodeID($data['periode']->ID, $data['header']->IsKPIUnitKerja)->getTargetCount();
        $data['periodeTarget']= $targetPeriode->periodeTarget($data['periode']->ID, $data['header']->IsKPIUnitKerja)->getTargetCount();
        $data['targetRealization'] = $targetPeriode->targetParser($data['periode']->ID);
        return view('backends.kpi.realisasi.bawahanlangsung.detail', compact('data'));
    }

    /**
     * @param $id
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function documentBawahanLangsung($id)
    {
        $data['headerrealisasi'] = $this->realisasiKPIRepository->findById($id);
        $data['karyawan'] = $this->karyawanRepository->findByNPK($data['headerrealisasi']->NPK);
        return view('backends.kpi.realisasi.bawahanlangsung.document', compact('data'));
    }
}
