<?php
/**
 * Created by PhpStorm.
 * User: SENA
 * Date: 10/23/2017
 * Time: 08:46 PM
 */

namespace App\Domain\KPI\Services;

use Maatwebsite\Excel\Facades\Excel;
use App\ApplicationServices\ApplicationService;
use App\Domain\KPI\Entities\JenisPeriode;
use App\Infrastructures\Repositories\KPI\UnitKerjaRepository;
use App\Infrastructures\Repositories\Reports\ReportRecapitulationRepository;

class RekapitulasiKPIExcelDownloadService extends ApplicationService
{
    /**
     * Option All
     */
    const ALL = 'all';

    /**
     * Option Individu
     */
    const INDIVIDU = 'individu';

    /**
     * Option Unit Kerja
     */
    const UNITKERJA = 'unitkerja';

    /**
     * @var string
     */
    protected $filename = '';

    /**
     * @var ReportRecapitulationRepository
     */
    protected $reportRecapitulationRepository;

    /**
     * RekapitulasiKPIExcelDownloadService constructor.
     * @param ReportRecapitulationRepository $reportRecapitulationRepository
     */
    public function __construct(ReportRecapitulationRepository $reportRecapitulationRepository)
    {
        $this->reportRecapitulationRepository = $reportRecapitulationRepository;
    }

    /**
     * @param string $jenisKPI
     * @param null $tahun
     * @param null $periode
     * @param null $filename
     * @return array
     */
    public function create($jenisKPI = self::ALL, $tahun = null, $periode = null, $filename = null)
    {
        try {
            $data = $this->getData($tahun, $periode);
            if ($tahun!=null and $periode !=null) {
                $this->filename = $filename==null?'Laporan Rekapitulasi'.' '.$tahun.' - '.JenisPeriode::find($periode)->NamaPeriodeKPI:$filename;
            }
            Excel::create($this->filename, function ($excel) use ($jenisKPI, $data) {
                if ($jenisKPI== self::INDIVIDU or $jenisKPI== self::ALL) {
                    $excel->sheet('Rekap Realisasi KPI Individu', function ($sheet) use ($data) {
                        $sheet->setOrientation('landscape');
                        $sheet->mergeCells('A1:A2');
                        $sheet->mergeCells('B1:B2');
                        $sheet->mergeCells('C1:C2');
                        $sheet->mergeCells('D1:D2');
                        $sheet->mergeCells('E1:E2');
                        $sheet->mergeCells('F1:F2');
                        $sheet->mergeCells('G1:I1');
                        $sheet->mergeCells('J1:L1');
                        $sheet->mergeCells('M1:M2');
                        $sheet->mergeCells('N1:N2');
                        $sheet->mergeCells('O1:O2');
                        $sheet->mergeCells('P1:P2');
                        $sheet->setAutoSize(true);
                        $sheet->setFreeze('D3');
                        $sheet->loadView('backends.kpi.realisasi.printtemplate.laporanrekapitulasikpi', compact('data'));
                    });
                }
                if ($jenisKPI== self::UNITKERJA or $jenisKPI== self::ALL) {
                    $excel->sheet('Rekap Realisasi KPI Unit Kerja', function ($sheet) use ($data) {
                        $sheet->setOrientation('landscape');
                        $sheet->mergeCells('A1:A2');
                        $sheet->mergeCells('B1:B2');
                        $sheet->mergeCells('C1:C2');
                        $sheet->mergeCells('D1:D2');
                        $sheet->mergeCells('E1:E2');
                        $sheet->mergeCells('F1:F2');
                        $sheet->setAutoSize(true);
                        $sheet->setFreeze('C3');
                        $sheet->loadView('backends.kpi.realisasi.unitkerja.printtemplate.laporanrekapitulasikpi', compact('data'));
                    });
                }
                return $this->successResponse();
            })->download('xlsx');
        } catch (\Exception $e) {
            return $this->errorResponse($e->getMessage());
        }
    }

    /**
     * @param $tahun
     * @param $idjenisperiode
     * @return mixed
     */
    protected function getData($tahun, $idjenisperiode)
    {
        $data[self::INDIVIDU]=$this->reportRecapitulationRepository->reportKPIIndividu($tahun, $idjenisperiode)->get();
        $data[self::UNITKERJA]=$this->reportRecapitulationRepository->reportKPIUnitKerja($tahun, $idjenisperiode)->get();
        return $data;
    }
}
