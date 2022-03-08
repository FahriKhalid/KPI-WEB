<?php
namespace App\ApplicationServices\Master\PeriodeAktif;

use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use App\ApplicationServices\ApplicationService;
use App\Domain\KPI\Entities\PeriodeAktif;
use App\Domain\KPI\Entities\JenisPeriode;
use App\Domain\User\Entities\User;
use App\Exceptions\DomainException;
use Ramsey\Uuid\Uuid;

class StorePeriodeAktifService extends ApplicationService
{
    /**
     * @param array $data
     * @param User $user
     * @return array
     */
    public function call(array $data, User $user)
    {
        try {
            DB::beginTransaction();
            $this->checkExist($data['Tahun']);
            $i = 0;
            foreach ($data['urutan'] as $value) {
                $IDJenisPeriod = JenisPeriode::select('ID')
                                    ->where('JenisPeriode', $data['JenisPeriode'])
                                    ->where('Urutan', $data['urutan'][$i]+1)->first();
                $periodeaktif = new PeriodeAktif();
                $periodeaktif->IDJenisPeriode = $IDJenisPeriod->ID;
                $periodeaktif->NamaPeriode = $data['NamaPeriode'][$i];
                $periodeaktif->Tahun = $data['Tahun'];
                $periodeaktif->StartDate = (! isset($data['StartDate'][$i])) ? null : $data['StartDate'][$i];
                $periodeaktif->EndDate = (! isset($data['EndDate'][$i])) ? null : $data['EndDate'][$i];
                $periodeaktif->StatusPeriode = (! empty($data['Aktif'][$i])) ? 'Aktif' : 'Tidak Aktif';
                $periodeaktif->Aktif  = (! empty($data['Aktif'][$i])) ? '1' : '0';
                $periodeaktif->Keterangan = (! empty($data['Keterangan'][$i])) ? $data['Keterangan'][$i] : null;
                $periodeaktif->CreatedBy = $user->ID;
                $periodeaktif->CreatedOn = Carbon::now();
                $periodeaktif->save();
                $i++;
            }
            DB::commit();
            return $this->successResponse();
        } catch (\Exception $e) {
            DB::rollback();
            return $this->errorResponse($e->getMessage());
        }
    }

    /**
     * @param $tahun
     * @return bool
     * @throws DomainException
     */
    protected function checkExist($tahun)
    {
        $year = PeriodeAktif::where('Tahun', $tahun)->exists();
        if ($year) {
            throw new DomainException('Anda sudah membuat periode aktif tahun '.$tahun);
        }
        return true;
    }
}
