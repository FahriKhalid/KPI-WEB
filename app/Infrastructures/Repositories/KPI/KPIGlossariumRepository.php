<?php
/**
 * Created by PhpStorm.
 * User: SENA
 * Date: 10/17/2017
 * Time: 10:06 AM
 */

namespace App\Infrastructures\Repositories\KPI;

use App\Domain\Karyawan\Entities\Karyawan;
use App\Domain\KPI\Entities\Kamus;
use App\Domain\Realisasi\Entities\DetilRealisasiKPI;
use App\Domain\Realisasi\Entities\HeaderRealisasiKPI;
use App\Domain\Realisasi\Entities\ValidasiUnitKerja;
use App\Domain\Rencana\Entities\DetilRencanaKPI;
use App\Domain\Rencana\Entities\HeaderRencanaKPI;
use App\Domain\Rencana\Entities\Attachment as AttachmentRencana;
use App\Domain\Rencana\Entities\KRAKPIDetail;
use App\Domain\Realisasi\Entities\Attachment as AttachmentRealisasi;

class KPIGlossariumRepository
{
    /**
     * @var RencanaKPIRepository
     */
    protected $rencanaKPIRepository;

    /**
     * @var RealisasiKPIRepository
     */
    protected $realisasiKPIRepository;

    /**
     * @var KamusRepository
     */
    protected $kamusKPIRepository;

    /*
     * Constructor of KPIGlossariumRepository
     */
    public function __construct()
    {
        $rencana = new RencanaKPIRepository(new HeaderRencanaKPI(), new DetilRencanaKPI(), new AttachmentRencana(), new KRAKPIDetail());
        $realisasi = new RealisasiKPIRepository(new HeaderRealisasiKPI(), new DetilRealisasiKPI(), new AttachmentRealisasi(), new ValidasiUnitKerja(), new HeaderRencanaKPI());
        $kamus = new KamusRepository(new Kamus());
        $this->rencanaKPIRepository= $rencana;
        $this->realisasiKPIRepository= $realisasi;
        $this->kamusKPIRepository = $kamus;
    }

    /**
     * @param string $jenisKPI
     * @return mixed
     */
    public function getRepository($jenisKPI = 'rencana')
    {
        $this->validator($jenisKPI);
        $jenisKPI = $jenisKPI=='pengembangan'?'realisasi':$jenisKPI;
        return $this->{$jenisKPI.'KPIRepository'};
    }

    /**
     * @param string $jenisKPI
     * @param $idheader
     * @return mixed
     */
    public function findById($jenisKPI = 'rencana', $idheader)
    {
        return $this->getRepository($jenisKPI)->findById($idheader);
    }

    /**
     * @param string $jenisKPI
     * @param $idheader
     * @return mixed
     */
    public function findKaryawanById($jenisKPI = 'rencana', $idheader)
    {
        $_ = $this->getRepository($jenisKPI);
        if ($jenisKPI == 'kamus') {
            //            throw new \DomainException('Daftar karyawan tidak tersedia dalam repositori kamus');
            $karyawan_= $_->findById($idheader);
            return (object)[
                'karyawan'=> $karyawan_->createdby->karyawan,
                'karyawanatasanlangsung'=> $karyawan_->updatedby->karyawan != null ? $karyawan_->updatedby->karyawan:'',
                'karyawanatasanberikutnya'=> $karyawan_->updatedby->karyawan != null ? $karyawan_->updatedby->karyawan:''
            ];
        }
        return $_->findKaryawanById($idheader);
    }

    /**
     * @param string $jenisKPI
     * @param Karyawan $user
     * @param $idStatusDokumen
     * @param null $tahun
     * @return mixed
     */
    public function countStatusUpdatedDocumentBy($jenisKPI = 'rencana', Karyawan $user, $idStatusDokumen, $tahun = null)
    {
        $_ = $this->getRepository($jenisKPI);
        return $_->countStatusUpdatedDocumentBy($user->NPK, $idStatusDokumen, $tahun);
    }

    /**
     * @param $jenisKPI
     */
    protected function validator($jenisKPI)
    {
        if ($jenisKPI != 'rencana' and $jenisKPI != 'realisasi' and $jenisKPI != 'kamus' and $jenisKPI != 'pengembangan') {
            throw new \DomainException('Repositori tidak ditemukan');
        }
    }

    /**
     * @return RencanaKPIRepository
     */
    public function getRencanaKPIRepository()
    {
        return $this->rencanaKPIRepository;
    }

    /**
     * @return RealisasiKPIRepository
     */
    public function getRealisasiKPIRepository()
    {
        return $this->realisasiKPIRepository;
    }

    /**
     * @return KamusRepository
     */
    public function getKamusKPIRepository()
    {
        return $this->kamusKPIRepository;
    }
}
