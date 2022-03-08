<?php

namespace App\ApplicationServices\RealisasiKPI;

use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use App\ApplicationServices\ApplicationService;
use App\Domain\Realisasi\Entities\DetilRealisasiKPI;
use App\Domain\Realisasi\Entities\HeaderRealisasiKPI;
use App\Domain\Realisasi\Services\ConvertionService;
use App\Domain\Realisasi\Services\FinalValueService;
use App\Domain\Realisasi\Services\TargetValueCalculationService;
use App\Domain\Rencana\Entities\HeaderRencanaKPI;
use App\Domain\KPI\Entities\PeriodeAktif;
use App\Domain\Rencana\Services\TargetPeriodeService;
use App\Exceptions\DomainException;
use App\Infrastructures\Repositories\KPI\RealisasiKPIRepository;
use App\Infrastructures\Repositories\KPI\RencanaKPIRepository;
use Ramsey\Uuid\Uuid;

class StoreHeaderRealisasiService extends ApplicationService
{
    /**
     * @var HeaderRencanaKPI
     */
    protected $realisasiHeader;

    /**
     * @var TargetPeriodeService
     */
    protected $targetPeriodeService;

    /**
     * @var RencanaKPIRepository
     */
    protected $rencanaKPIrepository;

    /**
     * @var RealisasiKPIRepository
     */
    protected $realisasiKPIRepository;

    /**
     * StoreHeaderRealisasiService constructor.
     *
     * @param RencanaKPIRepository $rencanaKPIRepository
     * @param RealisasiKPIRepository $realisasiKPIRepository
     * @param TargetPeriodeService $targetPeriodeService
     */
    public function __construct(
        RencanaKPIRepository $rencanaKPIRepository,
        RealisasiKPIRepository $realisasiKPIRepository,
        TargetPeriodeService $targetPeriodeService
    ) {
        $this->rencanaKPIrepository = $rencanaKPIRepository;
        $this->realisasiKPIRepository = $realisasiKPIRepository;
        $this->targetPeriodeService = $targetPeriodeService;
    }

    /**
     * @param array $data
     * @param $user
     * @return array
     */
    public function call(array $data, $user)
    {

        try {
            DB::beginTransaction();
            // check exist first
            $this->checkExist($user->NPK, $data);

            $realisasiHeader = new HeaderRealisasiKPI();
            $realisasiHeader->ID = Uuid::uuid4();
            $realisasiHeader->IDKPIRencanaHeader = $data['IDHeaderRencana'];
            $realisasiHeader->Tahun = $data['Tahun'];
            $realisasiHeader->NPK = $user->NPK;
            $realisasiHeader->KodePeriode = (array_key_exists('KodePeriode', $data)) ? $data['KodePeriode'] : null;

            $isUnitKerja = (array_key_exists('IsUnitKerja', $data)) ? $data['IsUnitKerja'] : 0;
            $realisasiHeader->IsUnitKerja = $isUnitKerja;

            $idJenisPeriode = $this->getJenisPeriode($data);
            $realisasiHeader->IDJenisPeriode = $idJenisPeriode;
            $realisasiHeader->Target = $this->targetPeriodeService->targetParser($idJenisPeriode);
            $realisasiHeader->Grade = $data['Grade'];
            $realisasiHeader->IDMasterPosition = $data['IDMasterPosition'];
            $realisasiHeader->NPKAtasanLangsung = $data['NPKAtasanLangsung'];
            $realisasiHeader->JabatanAtasanLangsung = $data['JabatanAtasanLangsung'];
            $realisasiHeader->NPKAtasanBerikutnya = $data['NPKAtasanBerikutnya'];
            $realisasiHeader->JabatanAtasanBerikutnya = $data['JabatanAtasanBerikutnya'];
            $realisasiHeader->IDStatusDokumen = 1;
            $realisasiHeader->Keterangan = (!empty($data['Keterangan'])) ? $data['Keterangan'] : null;
            $realisasiHeader->CreatedBy = $user->NPK;
            $realisasiHeader->CreatedOn = Carbon::now(config('app.timezone'));
            $realisasiHeader->save();
            $this->realisasiHeader = $realisasiHeader;

            // create realisisasi items
            $this->storeItemRencana($user, $data['Tahun'], $idJenisPeriode, $isUnitKerja);

            DB::commit();
            return $this->successResponse();
        } catch (\Exception $e) {
            DB::rollback();
            return $this->errorResponse($e->getMessage().' <br> Line -> '.$e->getLine());
        }
    }

    /**
     * @return mixed
     */
    public function getRealisasiHeader()
    {
        return $this->realisasiHeader;
    }

    /**
     * @param array $data
     * @return mixed
     */
    protected function getJenisPeriode(array $data)
    {
        if (array_key_exists('KodePeriode', $data)) {
            $periodeAktif = PeriodeAktif::select('IDJenisPeriode')->where('ID', $data['KodePeriode'])->first();
            return $periodeAktif->IDJenisPeriode;
        }
        return $data['IDJenisPeriode'];
    }

    /**
     * @param $npk
     * @param $data
     * @return bool
     * @throws DomainException
     */
    protected function checkExist($npk, array $data)
    {
        if ($this->realisasiKPIRepository->isRealizationExists($npk, $data)) {
            throw new DomainException('Rencana KPI anda telah siap direalisasikan (sudah ada) untuk periode yang anda pilih.');
        }
        return true;
    }

    /**
     * creating realization item based on created plan
     *
     * @param $karyawan
     * @param $tahun
     * @param $idJenisPeriode
     * @param $isUnitKerja
     */
    protected function storeItemRencana($karyawan, $tahun, $idJenisPeriode, $isUnitKerja = 0)
    {
        $itemRencana = $this->rencanaKPIrepository->getForCreatingRealization($this->realisasiHeader->IDKPIRencanaHeader, $isUnitKerja);
        //$x = [];
        foreach ($itemRencana as $item) {
            $itemRealisasi = new DetilRealisasiKPI();
            $itemRealisasi->ID = Uuid::uuid4();
            $itemRealisasi->IDKPIRealisasiHeader = $this->realisasiHeader->ID;
            $itemRealisasi->IDPeriodeKPI = $this->realisasiHeader->IDJenisPeriode;
            $itemRealisasi->IDRencanaKPIDetil = $item->ID;
            $itemRealisasi->CreatedBy = $karyawan->NPK;
            $itemRealisasi->CreatedOn = Carbon::now(config('app.timezone'));

            // get cascading items
            $cascade = $this->handleCascadeRealization($item, $tahun, $idJenisPeriode);

            if($cascade['realization'] != null){
                $itemRealisasi->Realisasi = $cascade['realization'];
            }

            $percentageRealization = TargetValueCalculationService::calculate($item->{'Target'.$this->targetPeriodeService->targetParser($idJenisPeriode)}, $cascade['realization'], $item->IDPersentaseRealisasi); 

            $convertion = ConvertionService::convert($percentageRealization);

            $itemRealisasi->PersentaseRealisasi = $percentageRealization;
            $itemRealisasi->KonversiNilai = $convertion;
            $itemRealisasi->NilaiAkhir = FinalValueService::calculate($convertion, $item->Bobot);
            $itemRealisasi->save();

            // $x[] = [
            //         'IDRencanaKPIDetil' => $item->ID,
            //         'Realisasi' => $cascade['realization'],
            //         'NilaiAkhir' => FinalValueService::calculate($convertion, $item->Bobot),
            //         'PersentaseRealisasi' => $percentageRealization,
            // ];
        }

        //dd($x);
    }

    /**
     * @param $detilRencana
     * @return array
     */
    protected function handleCascadeRealization($detilRencana, $tahun, $idJenisPeriode)
    {
        $cascades = $detilRencana->krakpicascade;
        $cascadeRealizationArr = [];

        if ($cascades->count() > 0) {
            foreach ($cascades as $cascade) {
                $cascadeId = $cascade->ID;
                $npkBawahan = $cascade->NPKBawahan;

                // find realisasi bawahan based on cascade
                $realisasiBawahan = DetilRealisasiKPI::whereHas('detilrencana', function ($query) use ($cascadeId) {
                    $query->where('IDKRAKPIRencanaDetil', $cascadeId);
                })->whereHas('headerrealisasikpi', function ($query) use ($npkBawahan, $tahun, $idJenisPeriode) {
                    $query->where('NPK', $npkBawahan)->where('IDJenisPeriode', $idJenisPeriode)->where('Tahun', $tahun)->whereNotIn('IDStatusDokumen', [5]);
                })->with('detilrencana.jenisappraisal')->select('ID', 'Realisasi', 'IDRencanaKPIDetil')->get();
//

                if ($realisasiBawahan->count() > 0) {
                    foreach ($realisasiBawahan as $key => $realizationItem) {
                        $cascadeRealizationArr[] = [
                            'realisasi' => $realizationItem->Realisasi,
                            'appraisalCode' => $realizationItem->detilrencana->jenisappraisal->KodeJenisAppraisal
                        ];
                    }
                }
            } // end of foreach

            if (count($cascadeRealizationArr) > 0) {
                $arrTempRealization = [];
                $appraisalCode = '';
                $totalRealization = null;
                foreach ($cascadeRealizationArr as $item) {
                    $appraisalCode = $item['appraisalCode'];
                    $arrTempRealization[] = $item['realisasi'];
                }
                if ($appraisalCode == 'KUM') {
                    $totalRealization = array_sum($arrTempRealization);
                } else {
                    $totalRealization = array_sum($arrTempRealization) / count($arrTempRealization);
                }
                return ['realization' => $totalRealization];
            }
            return ['realization' => null];
        }
        return ['realization' => null];
    }
}
