<?php

namespace App\Domain\KPI\Services;

use Maatwebsite\Excel\Facades\Excel;
use App\ApplicationServices\ApplicationService;
use App\ApplicationServices\KamusKPI\StoreKamusKPIService;
use App\ApplicationServices\KamusKPI\UpdateKamusKPIService;
use App\Domain\KPI\Entities\AspekKPI;
use App\Domain\KPI\Entities\JenisAppraisal;
use App\Domain\KPI\Entities\Kamus;
use App\Domain\KPI\Entities\PersentaseRealisasi;
use App\Domain\KPI\Entities\Satuan;
use App\Domain\KPI\Entities\UnitKerja;
use App\Domain\User\Entities\User;

class KamusKPIExcelUploadService extends ApplicationService
{
    /**
     * @var int
     */
    protected $startRow;
    /**
     * @var StoreKamusKPIService
     */
    protected $storeKamusKPIService;
    /**
     * @var UpdateKamusKPIService
     */
    protected $updateKamusKPIService;
    /**
     * KamusKPIExcelUploadService constructor.
     * @param StoreKamusKPIService $storeKamusKPIService
     */
    public function __construct(StoreKamusKPIService $storeKamusKPIService, UpdateKamusKPIService $updateKamusKPIService)
    {
        $this->storeKamusKPIService = $storeKamusKPIService;
        $this->updateKamusKPIService = $updateKamusKPIService;
        $this->startRow = 2;
        //ini_set('memory_limit', '512M');
    }

    /**
     * @param $file
     * @param User $user
     * @return array
     */
    public function read($file, User $user)
    {
        try {
            config(['excel.import.startRow' => $this->startRow ]);
            $excel = Excel::load($file, function ($reader) {
            })->all();
            foreach ($excel as $key=>$column) {
                $row_ = $key + $this->startRow + 1;
                $koderegistrasi = $this->isValidKodeRegistrasi($row_, $column->kode_registrasi);
                $data['KodeRegistrasi'] = $koderegistrasi['status'] ? $koderegistrasi['value'] : null;

                $judulkpi = $this->isValidJudulKPI($row_, $column->judul_kpi);
                $data['KPI'] = $judulkpi['status'] ? $judulkpi['value'] : null;

                $kodeunitkerja = $this->isValidKodeUnitKerja($row_, $column->kode_unit_kerja);
                $data['KodeUnitKerja'] = $kodeunitkerja['status'] ? $kodeunitkerja['value'] : null;

                $data['IndikatorHasil'] = isset($column->hasil) ? $column->hasil : null;
                $data['IndikatorKinerja'] = isset($column->kinerja) ? $column->kinerja : null;

                $deskripsi = $this->isValidDeskripsi($row_, $column->deskripsi);
                $data['Deskripsi'] = $deskripsi['status'] ? $deskripsi['value'] : null;

                $idsatuan = $this->getIdSatuan($row_, $column->satuan);
                $data['IDSatuan'] = $idsatuan['status'] ? (string)$idsatuan['value'] : null;

                $idaspekkpi = $this->getIdAspekKPI($row_, $column->kelompok);
                $data['IDAspekKPI'] = $idaspekkpi['status'] ? (string)$idaspekkpi['value'] : null;

                $idpresentaserealisasi = $this->getIdPersentaseRealisasi($row_, $column->persentase_realisasi);
                $data['IDPersentaseRealisasi'] = $idpresentaserealisasi['status'] ? (string)$idpresentaserealisasi['value'] : null;

                $data['Rumus'] = isset($column->rumus) ? $column->rumus : null;
                $data['SumberData'] = isset($column->sumber_data) ? $column->sumber_data : null;
                $data['PeriodeLaporan'] = isset($column->periode_laporan) ? $column->periode_laporan : null;
                $data['Jenis'] = isset($column->jenis) ? $column->jenis : null;

                $idjenisappraisal = $this->getIdJenisAppraisal($row_, $column->sifat);
                $data['IDJenisAppraisal'] = $idjenisappraisal['status'] ? (string)$idjenisappraisal['value'] : null;

                $data['Keterangan'] = isset($column->keterangan) ? $column->keterangan : null;

                $result = $this->storeKamusKPIService->call($data, $user);
            }
            return $this->successResponse();
        } catch (\Exception $e) {
            return $this->errorResponse($e->getMessage());
        }
    }

    /**
     * @param $index
     * @param $data
     * @return array
     */
    public function isValidKodeRegistrasi($index, $data)
    {
        if (!isset($data)) {
            $result['status']= false;
            throw new \DomainException("kolom: Kode registrasi baris:".$index." tidak boleh kosong");
        }
        $result['status'] = ! Kamus::where('KodeRegistrasi', 'like', $data)->exists();
        if ($result['status']) {
            $result['value'] = strtoupper($data);
            return $result;
        } else {
            throw new \DomainException("kolom: Kode registrasi baris:".$index." sudah ada dalam database kamus, buat lebih unik");
        }
    }

    /**
     * @param $index
     * @param $data
     * @return mixed
     */
    public function isValidJudulKPI($index, $data)
    {
        if (!isset($data)) {
            $result['status']= false;
            throw new \DomainException("kolom: Judul KPI baris:".$index." tidak boleh kosong");
        } else {
            $result['status']= true;
            $result['value'] = $data;
            return $result;
        }
    }
    /**
     * @param $index
     * @param $data
     * @return mixed
     */
    public function isValidKodeUnitKerja($index, $data)
    {
        if (!isset($data)) {
            $result['status']= false;
            throw new \DomainException("kolom: Kode unit kerja baris:".$index." tidak boleh kosong");
        }
        $result['status'] = UnitKerja::where('CostCenter', 'like', $data)->exists();
        if ($result['status']) {
            $result['value'] = UnitKerja::where('CostCenter', 'like', $data)->first()->CostCenter;
            return $result;
        } else {
            throw new \DomainException("kolom: Kode unit kerja baris:".$index." belum terdaftar atau tidak dikenal");
        }
    }

    /**
     * @param $index
     * @param $data
     * @return mixed
     */
    public function isValidDeskripsi($index, $data)
    {
        if (!isset($data)) {
            $result['status']= false;
            throw new \DomainException("kolom: Deskripsi baris:".$index." tidak boleh kosong");
        } else {
            $result['status']= true;
            $result['value'] = $data;
            return $result;
        }
    }

    /**
     * @param $index
     * @param $data
     * @return mixed
     */
    public function getIdAspekKPI($index, $data)
    {
        if (!isset($data)) {
            $result['status']= false;
            throw new \DomainException("kolom: Aspek KPI baris:".$index." tidak boleh kosong");
        }
        $result['status'] =AspekKPI::where('AspekKPI', 'like', '%'.$data.'%')->exists();
        if ($result['status']) {
            $result['value'] = AspekKPI::where('AspekKPI', 'like', '%'.$data.'%')->first()->ID;
            return $result;
        } else {
            throw new \DomainException("kolom: Aspek KPI baris:".$index." belum terdaftar atau tidak dikenal");
        }
    }

    /**
     * @param $index
     * @param $data
     * @return mixed
     */
    public function getIdJenisAppraisal($index, $data)
    {
        if (!isset($data)) {
            $result['status']= false;
            throw new \DomainException("kolom: Jenis Appraisal baris:".$index." tidak boleh kosong");
        }
        $result['status'] = JenisAppraisal::where('JenisAppraisal', 'like', '%'.$data.'%')->exists();
        if ($result['status']) {
            $result['value'] = JenisAppraisal::where('JenisAppraisal', 'like', '%'.$data.'%')->first()->ID;
            return $result;
        } else {
            throw new \DomainException("kolom: Jenis appraisal baris:".$index." belum terdaftar atau tidak dikenal");
        }
    }

    /**
     * @param $index
     * @param $data
     * @return mixed
     */
    public function getIdPersentaseRealisasi($index, $data)
    {
        if (!isset($data)) {
            $result['status']= false;
            throw new \DomainException("kolom: Persentase realisasi baris:".$index." tidak boleh kosong");
        }
        $result['status'] = PersentaseRealisasi::where('PersentaseRealisasi', 'like', '%'.$data.'%')->exists();
        if ($result['status']) {
            $result['value'] = PersentaseRealisasi::where('PersentaseRealisasi', 'like', '%'.$data.'%')->first()->ID;
            return $result;
        } else {
            throw new \DomainException("kolom: Persentase realisasi baris:".$index." belum terdaftar atau tidak dikenal");
        }
    }

    /**
     * @param $index
     * @param $data
     * @return mixed
     */
    public function getIdSatuan($index, $data)
    {
        if (!isset($data)) {
            $result['status']= false;
            throw new \DomainException("kolom: Satuan baris:".$index." tidak boleh kosong");
        }
        $data=$data==='%'?'\%':$data;
        $result['status'] = Satuan::where('Satuan', 'like', $data)->exists();
        if ($result['status']) {
            $result['value'] = Satuan::where('Satuan', 'like', $data)->first()->ID;
            return $result;
        } else {
            throw new \DomainException("kolom: Satuan baris:".$index." belum terdaftar atau tidak dikenal");
        }
    }
}
