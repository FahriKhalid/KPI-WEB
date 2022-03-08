<?php
namespace App\ApplicationServices\ValidasiUnitKerja;

use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use App\ApplicationServices\ApplicationService;
use App\Domain\Karyawan\Services\PositionAbbreviation;
use App\Domain\Realisasi\Entities\HeaderRealisasiKPI;
use App\Domain\Realisasi\Entities\ValidasiUnitKerja;
use App\Domain\Realisasi\Services\CalculateValidasiService;

class ApproveValidasiUnitKerjaService extends ApplicationService
{
    /**
     * @var ValidasiUnitKerja
     */
    protected $validasiUnitKerja;

    /**
     * @var HeaderRealisasiKPI
     */
    protected $headerRealisasi;

    /**
     * @var PositionAbbreviation
     */
    protected $positionAbbreviationService;

    /**
     * ApproveValidasiUnitKerjaService constructor.
     *
     * @param ValidasiUnitKerja $validasiUnitKerja
     * @param HeaderRealisasiKPI $headerRealisasiKPI
     * @param PositionAbbreviation $positionAbbreviation
     */
    public function __construct(
        ValidasiUnitKerja $validasiUnitKerja,
        HeaderRealisasiKPI $headerRealisasiKPI,
        PositionAbbreviation $positionAbbreviation
    ) {
        $this->validasiUnitKerja = $validasiUnitKerja;
        $this->headerRealisasi = $headerRealisasiKPI;
        $this->positionAbbreviationService = $positionAbbreviation;
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
            foreach ($data['id'] as $idHeaderRealisasi) {
                if (! empty($idHeaderRealisasi)) {
                    $validates = $this->validasiUnitKerja->where('IDKPIRealisasiHeader', $idHeaderRealisasi)->get();
                    foreach ($validates as $validate) {
                        if (! empty($validate)) {
                            if ($validate->isSubmitted()) {
                                $validate->IDStatusDokumen = 7; // validated
                                $validate->ValidatedBy = $user->NPK;
                                $validate->ValidatedOn = Carbon::now(config('app.timezone'));
                                $validate->save();

                                $this->calculateNilaiAkhirValidasi($validate->IDKPIRealisasiHeader);
                            }
                        }
                    }
                }
            }

            DB::commit();
            return $this->successResponse();
        } catch (\Exception $e) {
            DB::rollback();
            return $this->errorResponse($e->getMessage());
        }
    }

    /**
     * @param $headerRealisasiID
     */
    protected function calculateNilaiAkhirValidasi($headerRealisasiID)
    {
        $averageValidasiUnitKerja = $this->validasiUnitKerja->where('IDKPIRealisasiHeader', $headerRealisasiID)
                                                ->where('IDStatusDokumen', 7)
                                                ->avg('ValidasiUnitKerja');
        $validasiNilaiAkhir = round($averageValidasiUnitKerja, 2, PHP_ROUND_HALF_DOWN);
        $this->validasiUnitKerja->where('IDKPIRealisasiHeader', $headerRealisasiID)
                                ->where('IDStatusDokumen', 7)
                                ->update(['ValidasiAkhir' => $validasiNilaiAkhir]);

        $this->calculateNilaiAkhirRealisasi($headerRealisasiID, $validasiNilaiAkhir);
    }

    /**
     * @param $headerRealisasiID
     * @param $validasiNilaiAkhir
     */
    protected function calculateNilaiAkhirRealisasi($headerRealisasiID, $validasiNilaiAkhir)
    {
        $select = ['ID', 'NilaiAkhir', 'NilaiAkhirNonTaskForce', 'NilaiValidasiNonTaskForce', 'NilaiValidasi', 'KodePeriode', 'NPK', 'IDJenisPeriode'];
        $headerRealisasi = $this->headerRealisasi->select($select)->where('ID', $headerRealisasiID)->first();
        $this->storeValidasi($headerRealisasi, $validasiNilaiAkhir);
        $this->calculateNilaiAkhirBawahan($headerRealisasi, $validasiNilaiAkhir);
    }

    /**
     * @param $headerrealisasi
     * @param $validasiNilaiAkhir
     * @throws \App\Exceptions\DomainException
     */
    protected function calculateNilaiAkhirBawahan($headerrealisasi, $validasiNilaiAkhir)
    {
        if (! is_null($headerrealisasi->KodePeriode)) {
            $positionAbbreviation = $headerrealisasi->karyawan->organization->position->PositionAbbreviation;
            $this->positionAbbreviationService->position($positionAbbreviation);
            $parentPosition = $this->positionAbbreviationService->getParentPosition();

            // get kpi bawahan
            $select = $select = ['ID', 'NilaiAkhir', 'NilaiAkhirNonTaskForce', 'NilaiValidasiNonTaskForce', 'NilaiValidasi', 'KodePeriode', 'NPK', 'IDJenisPeriode'];
            $headersKPIBawahan = $this->headerRealisasi->whereHas('karyawan.organization.position', function ($query) use ($parentPosition) {
                $query->where('PositionAbbreviation', 'LIKE', $parentPosition.'%');
            })->where('KodePeriode', $headerrealisasi->KodePeriode)->where('IDStatusDokumen', 4)->select($select)->get();

            foreach ($headersKPIBawahan as $headerRealisasi) {
                $this->storeValidasi($headerRealisasi, $validasiNilaiAkhir);
            }
        }
    }

    /**
     * @param $headerRealisasi
     * @param $validasiNilaiAkhir
     */
    protected function storeValidasi($headerRealisasi, $validasiNilaiAkhir)
    {
        $headerRealisasi->NilaiValidasiNonTaskForce = CalculateValidasiService::calculateValidasi($headerRealisasi->NilaiAkhirNonTaskForce, $validasiNilaiAkhir);
        $headerRealisasi->NilaiValidasi = CalculateValidasiService::calculateValidasi($headerRealisasi->NilaiAkhir, $validasiNilaiAkhir);
        $headerRealisasi->save();
    }
}
