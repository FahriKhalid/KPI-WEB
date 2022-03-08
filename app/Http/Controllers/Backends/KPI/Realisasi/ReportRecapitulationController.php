<?php
/**
 * Created by PhpStorm.
 * User: SENA
 * Date: 10/24/2017
 * Time: 08:09 AM
 */

namespace App\Http\Controllers\Backends\KPI\Realisasi;

use Illuminate\Http\Request;
use App\Domain\KPI\Services\RekapitulasiKPIExcelDownloadService;
use App\Http\Requests\KPI\Realisasi\ReportRecapitulationRequest;
use App\Infrastructures\Repositories\KPI\PeriodeAktifRepository;

class ReportRecapitulationController
{
    /**
     * @var PeriodeAktifRepository
     */
    protected $periodeAktifRepository;

    /**
     * ReportRecapitulationController constructor.
     * @param PeriodeAktifRepository $periodeAktifRepository
     */
    public function __construct(PeriodeAktifRepository $periodeAktifRepository)
    {
        $this->periodeAktifRepository =$periodeAktifRepository;
    }

    /**
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index(Request $request)
    {
        try {
            $data = $this->getSupportData($request);
            $data['Individu'] =true;
            $data['UnitKerja'] =true;
            return view('backends.kpi.realisasi.grafiknilai.document', compact('data'));
        } catch (\ErrorException $errorException) {
            flash()->error('Galat :'.$errorException->getMessage())->important();
            return view('backends.kpi.realisasi.grafiknilai.document', compact('data'));
        }
    }

    /**
     * @param ReportRecapitulationRequest $request
     * @param RekapitulasiKPIExcelDownloadService $rekapitulasiKPIExcelDownloadService
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function excel(ReportRecapitulationRequest $request, RekapitulasiKPIExcelDownloadService $rekapitulasiKPIExcelDownloadService)
    {
        $data = $this->getSupportData($request);
        $data['Individu'] = isset($request->Individu) ? true : false;
        $data['UnitKerja'] = isset($request->UnitKerja) ? true : false;
        if (!$data['Individu'] and $data['UnitKerja']) {
            $result = $rekapitulasiKPIExcelDownloadService->create(RekapitulasiKPIExcelDownloadService::UNITKERJA, $request->Tahun, $request->IDJenisPeriode);
        } elseif (!$data['UnitKerja'] and $data['Individu']) {
            $result = $rekapitulasiKPIExcelDownloadService->create(RekapitulasiKPIExcelDownloadService::INDIVIDU, $request->Tahun, $request->IDJenisPeriode);
        } else {
            $result = $rekapitulasiKPIExcelDownloadService->create(RekapitulasiKPIExcelDownloadService::ALL, $request->Tahun, $request->IDJenisPeriode);
        }
        if ($result['status']) {
            flash()->success('Dokumen Excel Rekapitulasi berhasil diunduh.')->important();
            return view('backends.kpi.realisasi.grafiknilai.document', compact('data'));
        }
        flash()->error('Galat :'.$result['errors'])->important();
        return view('backends.kpi.realisasi.grafiknilai.document', compact('data'));
    }

    /**
     * @param Request $request
     * @return mixed
     */
    protected function getSupportData(Request $request)
    {
        $data['Tahun'] = isset($request->Tahun)?$request->Tahun:$this->periodeAktifRepository->datatable()->max('Tahun');
        $data['tahun'] = $this->periodeAktifRepository->datatable()->pluck('Tahun', 'Tahun')->toArray();
        return $data;
    }

    /**
     * @param $tahun
     * @return mixed
     */
    public function findPeriodeByTahun($tahun)
    {
        return $this->periodeAktifRepository->findByTahun($tahun, ['jenisperiode']);
    }
}
