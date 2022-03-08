<?php
/**
 * Created by PhpStorm.
 * User: SENA
 * Date: 10/30/2017
 * Time: 01:46 PM
 */

namespace App\Domain\KPI\Services;

use Maatwebsite\Excel\Facades\Excel as XLSX;
use App\ApplicationServices\ApplicationService;
use App\Domain\KPI\Services\RekapitulasiKPIExcelDownloadService as Excel;
use App\Infrastructures\Repositories\Reports\ReportRecapitulationRepository;

class ReportingKPIExcelDownloadService extends ApplicationService
{
    /**
     *
     */
    const RENCANAINDIVIDU = 'rencanaindividu';

    /**
     *
     */
    const REALISASIINDIVIDU = 'realisasiindividu';

    /**
     *
     */
    const RENCANAUNITKERJA = 'rencanaunitkerja';

    /**
     *
     */
    const REALISASIUNITKERJA = 'realisasiunitkerja';

    /**
     * @var RekapitulasiKPIExcelDownloadService
     */
    protected $downloadService;

    /**
     * @var string
     */
    protected $filename ='';

    /**
     * @var ReportRecapitulationRepository
     */
    protected $reportRecapitulationRepository;

    /**
     * ReportingKPIExcelDownloadService constructor.
     * @param RekapitulasiKPIExcelDownloadService $downloadService
     * @param ReportRecapitulationRepository $reportRecapitulationRepository
     */
    public function __construct(Excel $downloadService, ReportRecapitulationRepository $reportRecapitulationRepository)
    {
        $this->downloadService = $downloadService;
        $this->reportRecapitulationRepository = $reportRecapitulationRepository;
    }

    /**
     * @param string $type
     * @param $tahun
     * @param null $idjenisperiode
     */
    public function unduh($type = self::RENCANAINDIVIDU, $tahun, $idjenisperiode = null)
    {
        if ($type == self::REALISASIINDIVIDU) {
            $this->downloadService->create(Excel::INDIVIDU, $tahun, $idjenisperiode);
        } elseif ($type == self::REALISASIUNITKERJA) {
            $this->downloadService->create(Excel::UNITKERJA, $tahun, $idjenisperiode);
        } elseif ($type == self::RENCANAINDIVIDU) {
            $this->create(self::RENCANAINDIVIDU, $tahun);
        } elseif ($type == self::RENCANAUNITKERJA) {
            $this->create(self::RENCANAUNITKERJA, $tahun);
        }
    }

    /**
     * @param string $type
     * @param $tahun
     * @param null $filename
     * @return array
     */
    public function create($type = self::RENCANAINDIVIDU, $tahun, $filename= null)
    {
        try {
            $data = $this->getData($tahun);
            if ($tahun!=null) {
                $this->filename = $filename==null?'Laporan Rekapitulasi'.' '.$tahun:$filename;
            }
            XLSX::create($this->filename, function ($excel) use ($type, $data) {
                if ($type== self::RENCANAINDIVIDU) {
                    $excel->sheet('Rekap Rencana KPI Individu', function ($sheet) use ($data) {
                        $sheet->setOrientation('landscape');
                        $sheet->mergeCells('A1:A2');
                        $sheet->mergeCells('B1:B2');
                        $sheet->mergeCells('C1:C2');
                        $sheet->mergeCells('D1:D2');
                        $sheet->mergeCells('E1:E2');
                        $sheet->mergeCells('F1:F2');
                        $sheet->mergeCells('G1:I1');
                        $sheet->mergeCells('J1:J2');
                        $sheet->mergeCells('K1:K2');
                        $sheet->setAutoSize(true);
                        $sheet->setFreeze('D3');
                        $sheet->loadView('backends.kpi.rencana.printtemplate.laporanrekapitulasikpi', compact('data'));
                    });
                }
                if ($type == self::RENCANAUNITKERJA) {
                    $excel->sheet('Rekap Rencana KPI Unit Kerja', function ($sheet) use ($data) {
                        $sheet->setOrientation('landscape');
                        $sheet->mergeCells('A1:A2');
                        $sheet->mergeCells('B1:B2');
                        $sheet->mergeCells('C1:C2');
                        $sheet->mergeCells('D1:D2');
                        $sheet->mergeCells('E1:E2');
                        $sheet->mergeCells('F1:F2');
                        $sheet->setAutoSize(true);
                        $sheet->setFreeze('C3');
                        $sheet->loadView('backends.kpi.rencana.unitkerja.printtemplate.laporanrekapitulasikpi', compact('data'));
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
     * @return mixed
     */
    protected function getData($tahun)
    {
        $data[self::RENCANAINDIVIDU] = $this->reportRecapitulationRepository->reportKPIIndividuRencana($tahun)->get();
        $data[self::RENCANAUNITKERJA] = $this->reportRecapitulationRepository->reportKPIUnitKerjaRencana($tahun)->get();
        return $data;
    }
}
