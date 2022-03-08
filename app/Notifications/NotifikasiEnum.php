<?php
/**
 * Created by PhpStorm.
 * User: SENA
 * Date: 08/16/2017
 * Time: 08:51 AM
 */

namespace App\Notifications;

use App\Domain\Karyawan\Entities\Karyawan;
use App\Infrastructures\Repositories\KPI\KPIGlossariumRepository;
use SebastianBergmann\ObjectEnumerator\Enumerator;

class NotifikasiEnum extends Enumerator
{
    /**
     * @var KPIGlossariumRepository
     */
    protected $KPIglossarium;

    /**
     *
     */
    const rencana = [
        'registered'=>'dokumen rencana menunggu konfirmasi',
        'confirmed'=>'dokumen rencana menunggu approval',
        'unregistered'=>'dokumen rencana batal untuk dikonfirmasi',
        'unconfirmed'=>'dokumen rencana batal untuk diapproval',
        'approved'=>'dokumen rencana telah disetujui',
        'unapproved'=>'dokumen rencana batal disetujui'
    ];

    /**
     *
     */
    const rencana_url = [
        'registered'=>'/kpi/rencana/bawahanlangsung',
        'confirmed'=>'/kpi/rencana/bawahantaklangsung',
        'unregistered'=>'/kpi/rencana/bawahanlangsung',
        'unconfirmed'=>'/kpi/rencana/individu',
        'approved'=> ['/kpi/rencana/individu','/kpi/rencana/bawahanlangsung'],
        'unapproved'=>['/kpi/rencana/individu','/kpi/rencana/bawahanlangsung']
    ];

    /**
     *
     */
    const realisasi = [
        'registered'=>'dokumen realisasi menunggu konfirmasi',
        'confirmed'=>'dokumen realisasi menunggu approval',
        'unregistered'=>'dokumen realisasi batal untuk dikonfirmasi',
        'unconfirmed'=>'dokumen realisasi batal untuk diapproval',
        'approved'=>'dokumen realisasi telah disetujui',
        'unapproved'=>'dokumen realisasi batal disetujui'
    ];

    /**
     *
     */
    const realisasi_url = [
        'registered'=>'/kpi/realisasi/bawahanlangsung',
        'confirmed'=>'/kpi/realisasi/bawahantaklangsung',
        'unregistered'=>'/kpi/realisasi/bawahanlangsung',
        'unconfirmed'=>'/kpi/realisasi/individu',
        'approved'=> ['/kpi/realisasi/individu','/kpi/realisasi/bawahanlangsung'],
        'unapproved'=>['/kpi/realisasi/individu','/kpi/realisasi/bawahanlangsung']
    ];

    /**
     *
     */
    const kamus = [
        'pending'=>'kamus menunggu persetujuan',
        'approved'=>'kamus telah disetujui',
        'rejected'=>'kamus ditolak'
    ];
    /**
     *
     */
    const kamus_url = [
        'pending'=>'kpi/kamus',
        'approved'=>'kpi/kamus',
        'rejected'=>'kpi/kamus'
    ];

    /**
     *
     */
    const pengembangan = [
        'approved'=>'pengembangan telah disetujui',
        'unapproved'=>'pengembangan batal disetujui'
    ];
    /**
     *
     */
    const pengembangan_url = [
        'approved'=>'kpi/rencana/pengembangan',
        'unapproved'=>'kpi/rencana/pengembangan'
    ];

    /**
     * NotifikasiEnum constructor.
     * @param KPIGlossariumRepository $KPIGlossarium
     */
    public function __construct(KPIGlossariumRepository $KPIGlossarium)
    {
        $this->KPIglossarium = $KPIGlossarium;
    }

    /**
     * @param $jenisKPI
     * @param Karyawan $user
     * @param $idStatusDokumen
     * @param null $tahun
     * @return string
     */
    public function documentCounter($jenisKPI, Karyawan $user, $idStatusDokumen/*='registered'*/, $tahun = null)
    {
        try {
            //$statusDokumen = $statusDokumen=='confirmed'?3:2;
            $repo = $this->KPIglossarium->countStatusUpdatedDocumentBy($jenisKPI, $user->NPK, $idStatusDokumen, $tahun);
            return $repo==1?'satu':$repo;
        } catch (\DomainException $de) {
            throw  new \DomainException('Gagal mengambil jumlah dokumen'.$de->getMessage());
        } catch (\ErrorException $exception) {
            throw  new \DomainException('Gagal mengambil jumlah dokumen'.$exception->getMessage());
        }
    }

    /**
     *
     */
    public function rencana()
    {
        return(object)[
            'rencana'=>(object) self::rencana,
            'rencana_url'=>(object) self::rencana_url
        ];
    }

    /**
     *
     */
    public function realisasi()
    {
        return(object)[
            'realisasi'=>(object) self::realisasi,
            'realisasi_url'=>(object) self::realisasi_url
        ];
    }

    /**
     *
     */
    public function kamus()
    {
        return(object)[
            'kamus'=>(object) self::kamus,
            'kamus_url'=>(object) self::kamus_url
        ];
    }

    /**
     *
     */
    public function pengembangan()
    {
        return(object)[
            'pengembangan'=>(object) self::pengembangan,
            'pengembangan_url'=>(object) self::pengembangan_url
        ];
    }
}
