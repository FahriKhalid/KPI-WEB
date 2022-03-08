<?php
/**
 * Created by PhpStorm.
 * User: SENA
 * Date: 10/01/2017
 * Time: 03:42 PM
 */

namespace App\Infrastructures\Repositories\KPI;

use Illuminate\Support\Facades\DB;
use App\Domain\KPI\Entities\RencanaPengembangan;
use App\Domain\Realisasi\Entities\HeaderRealisasiKPI;
use App\Domain\User\Entities\User;
use App\Infrastructures\Repositories\Karyawan\KaryawanRepository;

class RencanaPengembanganRepository
{
    /**
     * @var RencanaPengembangan
     */
    protected $rencanaPengembangan;

    /**
     * @var HeaderRealisasiKPI
     */
    protected $headerRealisasiKPI;

    /**
     * @var KaryawanRepository
     */
    protected $karyawanRepository;

    /**
     * RencanaPengembanganRepository constructor.
     * @param RencanaPengembangan $model
     * @param HeaderRealisasiKPI $header
     * @param KaryawanRepository $karyawan
     */
    public function __construct(RencanaPengembangan $model, HeaderRealisasiKPI $header, KaryawanRepository $karyawan)
    {
        $this->rencanaPengembangan = $model;
        $this->headerRealisasiKPI = $header;
        $this->karyawanRepository=$karyawan;
    }

    /**
     * @param User $user
     * @return $this|\Illuminate\Database\Query\Builder
     */
    public function datatable(User $user)
    {
        $query = $this->headerRealisasiKPI->with(['masterposition.unitkerja','statusdokumen', 'periodeaktif', 'jenisperiode', 'karyawan'])
            ->whereIn('IDStatusDokumen', [4,6,7,8])
            ->where('NPKAtasanLangsung', $user->NPK);
        return $query->select([
            'Tr_KPIRealisasiHeader.*',
            DB::raw('(SELECT COUNT(IDRencanaPengembangan) 
                    FROM Tr_KPIRealisasiDetil LEFT JOIN Tr_KPIRealisasiHeader ON Tr_KPIRealisasiHeader.ID = Tr_KPIRealisasiDetil.IDKPIRealisasiHeader 
                    WHERE IDRencanaPengembangan IS NOT NULL) as CountRencanaPengembangan')
        ]);
    }

    /**
     * @param $id
     * @return mixed
     */
    public function findById($id)
    {
        return $this->rencanaPengembangan->find($id);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Collection|static[]
     */
    public function getFollowUpList()
    {
        return $this->rencanaPengembangan->get();
    }
}
