<?php

namespace App\Domain\KPI\Services;

use Maatwebsite\Excel\Facades\Excel;
use App\ApplicationServices\ApplicationService;
use App\Domain\KPI\Entities\Kamus;

class KamusKPIExcelDownloadService extends ApplicationService
{
    /**
     * @var Kamus
     */
    protected $kamus;
    /**
     * @var Excel::load
     */
    protected $template;

    /**
     * KamusKPIExcelDownloadService constructor.
     * @param Kamus $kamus
     */
    public function __construct(Kamus $kamus)
    {
        $this->kamus=$kamus;
    }

    /**
     * @return array
     */
    public function create()
    {
        try {
            $this->template = Excel::load(public_path('repository/template/KamusKPI.xlsx'), function ($reader)/*use($a)*/ {
                $queryBuilder =  $this->kamus
                    ->JOIN('VL_AspekKPI', 'VL_AspekKPI.ID', '=', 'IDAspekKPI')
                    ->JOIN('VL_JenisAppraisal', 'VL_JenisAppraisal.ID', '=', 'IDJenisAppraisal')
                    ->JOIN('VL_Satuan', 'VL_Satuan.ID', '=', 'IDSatuan')
                    ->JOIN('VL_PersentaseRealisasi', 'VL_PersentaseRealisasi.ID', '=', 'IDPersentaseRealisasi')
                    ->select(
                        'Ms_KamusKPI.KodeRegistrasi', 'Ms_KamusKPI.KPI', 'Ms_KamusKPI.KodeUnitKerja', 'Ms_KamusKPI.IndikatorHasil',
                        'Ms_KamusKPI.IndikatorKinerja', 'Ms_KamusKPI.Deskripsi', 'VL_Satuan.Satuan', 'VL_AspekKPI.AspekKPI', 'VL_PersentaseRealisasi.PersentaseRealisasi',
                        'Ms_KamusKPI.Rumus', 'Ms_KamusKPI.SumberData', 'Ms_KamusKPI.PeriodeLaporan', 'Ms_KamusKPI.Jenis', 'VL_JenisAppraisal.JenisAppraisal',
                        'Ms_KamusKPI.Keterangan'
                    )->where('Status', 'approved')->get();
                $paymentsArray = [];
                foreach ($queryBuilder as $key=>$payment) {
                    $paymentsArray[] = $payment->toArray();
                    $paymentsArray[$key]['Deskripsi']=strip_tags($paymentsArray[$key]['Deskripsi']);
                }
                $sheet = $reader->getActiveSheet();
                $sheet->fromArray($paymentsArray, null, 'A3', false, false);
                return $this->successResponse();
            })->download('xlsx');
        } catch (\Exception $e) {
            return $this->errorResponse($e->getMessage());
        }
    }
}
