<?php
namespace App\ApplicationServices\RencanaKPI;

use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use App\ApplicationServices\ApplicationService;
use App\Domain\Karyawan\Entities\Karyawan;
use App\Domain\KPI\Entities\JenisPeriode;
use App\Domain\KPI\Entities\Kamus;
use App\Domain\KPI\Entities\PeriodeAktif;
use App\Domain\Rencana\Entities\DetilRencanaKPI;
use App\Domain\Rencana\Entities\HeaderRencanaKPI;
use App\Domain\Rencana\Services\PercentageCascadeService;
use App\Exceptions\DomainException;
use App\Infrastructures\Repositories\KPI\RencanaKPIRepository;
use Ramsey\Uuid\Uuid;

class StoreHeaderRencanaService extends ApplicationService
{
    /**
     * @var HeaderRencanaKPI
     */
    protected $rencanaHeader;

    /**
     * @var RencanaKPIRepository
     */
    protected $rencanaKPIRepository;

    /**
     * StoreHeaderRencanaService constructor.
     *
     * @param RencanaKPIRepository $rencanaKPIRepository
     */
    public function __construct(RencanaKPIRepository $rencanaKPIRepository)
    {
        $this->rencanaKPIRepository = $rencanaKPIRepository;
    }

    /**
     * @param array $data
     * @param $user
     * @return array
     */
    public function call(array $data, $user)
    { 
        try {
            // check exist first
            DB::beginTransaction();
            $this->checkExist($data['NPK'], $data['Tahun']); 

            $rencanaHeader = new HeaderRencanaKPI();
            $rencanaHeader->ID = Uuid::uuid4();
            $rencanaHeader->Tahun = $data['Tahun'];
            $rencanaHeader->NPK = $data['NPK'];
            $rencanaHeader->IDMasterPosition = $data['IDMasterPosition'];
            $rencanaHeader->Grade = $data['Grade'];
            $rencanaHeader->NPKAtasanLangsung = $data['NPKAtasanLangsung'];
            $rencanaHeader->JabatanAtasanLangsung = $data['JabatanAtasanLangsung'];
            
            if($user->IDRole == 8){
                $rencanaHeader->NPKAtasanBerikutnya = $data['NPKAtasanLangsung'];
                $rencanaHeader->JabatanAtasanBerikutnya = $data['JabatanAtasanLangsung'];
            }else{
                $rencanaHeader->NPKAtasanBerikutnya = $data['NPKAtasanBerikutnya'];
                $rencanaHeader->JabatanAtasanBerikutnya = $data['JabatanAtasanBerikutnya'];
            }

            $rencanaHeader->IDStatusDokumen = 1;
            $rencanaHeader->Keterangan = (! empty($data['Keterangan'])) ? $data['Keterangan'] : null;
            $rencanaHeader->CreatedBy = $user->NPK;
            $rencanaHeader->CreatedOn = Carbon::now('Asia/Jakarta');
            $rencanaHeader->save();
            $this->rencanaHeader = $rencanaHeader;

            $this->storeApprovedCascade($rencanaHeader);
            $this->autoInput($rencanaHeader, $user);
            DB::commit();
            return $this->successResponse();
        } catch (\Exception $e) {
            DB::rollback();
            return $this->errorResponse($e->getMessage());
        }
    }

    /**
     * @return mixed
     */
    public function getRencanaHeader()
    {
        return $this->rencanaHeader;
    }

    /**
     * @param $npk
     * @param $tahun
     * @return bool
     * @throws DomainException
     */
    protected function checkExist($npk, $tahun)
    {
        if ($this->rencanaKPIRepository->checkRencanaAlreadyExist($npk, $tahun)) {
            throw new DomainException('Anda sudah membuat rencana untuk periode tahun '.$tahun);
        }
        return true;
    }

    /**
     * Create item KPI if there is item from cascade
     * @param $header
     * @return void
     */
    protected function storeApprovedCascade($header)
    {
        $items = $this->rencanaKPIRepository->getApprovedCascadeItem($header);
        foreach ($items as $item) {
            $detailRencana = new DetilRencanaKPI();
            $detailRencana->ID = Uuid::uuid4();
            $detailRencana->IDKPIRencanaHeader = $header->ID;
            $detailRencana->IDKRAKPIRencanaDetil = $item->ID;
            $detailRencana->IDJenisPeriode = $item->detailkpi->IDJenisPeriode;
            $detailRencana->KodeRegistrasiKamus = $item->detailkpi->KodeRegistrasiKamus;
            $detailRencana->IDKodeAspekKPI = $item->detailkpi->IDKodeAspekKPI;
            $detailRencana->DeskripsiKRA = $item->detailkpi->DeskripsiKPI;
            $detailRencana->DeskripsiKPI = null;
            $detailRencana->IDSatuan = $item->detailkpi->IDSatuan;
            $detailRencana->IDJenisAppraisal = $item->detailkpi->IDJenisAppraisal;
            $detailRencana->IDPersentaseRealisasi = $item->detailkpi->IDPersentaseRealisasi;
            $detailRencana->Bobot = $item->detailkpi->Bobot;
            $detailRencana->IsKRABawahan = false;
            $detailRencana->Keterangan = $item->Keterangan;
            $detailRencana->CreatedBy = $header->NPK;
            $detailRencana->CreatedOn = Carbon::now(config('app.timezone'));
            for ($i = 1; $i <= 12; $i++) {
                $detailRencana->{'Target'.$i} = $item->{'Target'.$i};
            }
            $detailRencana->save();

            $item->IsCascaded = true;
            $item->save();
        }
    }

    /**
     * @param $header
     * @param $user
     */
    public function autoInput($header, $user)
    {
        $grade = $user->karyawan->organization->Grade;
        if (Karyawan::mustHaveMandatoryKPI($grade)) {
            $kamus = Kamus::whereIn('KodeRegistrasi', Kamus::mandatoryKPIRegistrationCodes())->get();
            $periode = PeriodeAktif::select('IDJenisPeriode')->where('Tahun', $header->Tahun)->first();

            foreach ($kamus as $item) {
                if (! DetilRencanaKPI::where('IDKPIRencanaHeader', $header->ID)->where('KodeRegistrasiKamus', $item->KodeRegistrasi)->exists()) {
                    $detailRencana = new DetilRencanaKPI();
                    $detailRencana->ID = Uuid::uuid4();
                    $detailRencana->IDKPIRencanaHeader = $header->ID;
                    $detailRencana->IDJenisPeriode = $periode->IDJenisPeriode;
                    $detailRencana->KodeRegistrasiKamus = $item->KodeRegistrasi;
                    $detailRencana->IDKodeAspekKPI = $item->IDAspekKPI;
                    $detailRencana->DeskripsiKRA = null;
                    $detailRencana->DeskripsiKPI = $item->KPI;
                    $detailRencana->IDSatuan = $item->IDSatuan;
                    $detailRencana->IDJenisAppraisal = $item->IDJenisAppraisal;
                    $detailRencana->IDPersentaseRealisasi = $item->IDPersentaseRealisasi;
                    $detailRencana->Bobot = 5;
                    $detailRencana->IsKRABawahan = false;
                    $detailRencana->Keterangan = $item->Keterangan;
                    $detailRencana->CreatedBy = $header->NPK;
                    $detailRencana->CreatedOn = Carbon::now(config('app.timezone'));
                    $detailRencana->save();
                }
            }
        }
    }
}
