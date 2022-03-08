<?php
namespace App\ApplicationServices\ValidasiUnitKerja;

use Carbon\Carbon;
use App\ApplicationServices\ApplicationService;
use App\Domain\Karyawan\Services\PositionAbbreviation;
use App\Domain\Realisasi\Entities\HeaderRealisasiKPI;
use App\Domain\Realisasi\Entities\ValidasiUnitKerja;
use App\Infrastructures\Repositories\Karyawan\KaryawanRepository;
use Ramsey\Uuid\Uuid;

class StoreValidasiUnitKerjaService extends ApplicationService
{
    /**
     * @var PositionAbbreviation
     */
    protected $positionAbbreviation;

    /**
     * @var KaryawanRepository
     */
    protected $karyawanRepository;

    /**
     * @var HeaderRealisasiKPI
     */
    protected $headerRealisasi;

    /**
     * StoreValidasiUnitKerjaService constructor.
     *
     * @param PositionAbbreviation $positionAbbreviation
     * @param KaryawanRepository $karyawanRepository
     * @param HeaderRealisasiKPI $headerRealisasiKPI
     */
    public function __construct(
        PositionAbbreviation $positionAbbreviation,
        KaryawanRepository $karyawanRepository,
        HeaderRealisasiKPI $headerRealisasiKPI
    ) {
        $this->positionAbbreviation = $positionAbbreviation;
        $this->karyawanRepository = $karyawanRepository;
        $this->headerRealisasi = $headerRealisasiKPI;
    }

    /**
     * @param array $data
     * @param $user
     * @return array
     */
    public function call(array $data, $user)
    {
        try {
            $positionPenilai = $user->karyawan->organization->position;
            $unitkerjapenilai = $positionPenilai->unitkerja;

            $header = $this->headerRealisasi->find($data['IDKPIRealisasiHeader']);
            $positionDinilai = $header->karyawan->organization->position;
            $this->positionAbbreviation->position($positionDinilai->PositionAbbreviation)->codeShift($header->karyawan->organization->Shift);
            $atasanLangsung = $this->karyawanRepository->findByPositionAbbreviation($this->positionAbbreviation->getPositionAbbreviationAtasanLangsung(), $this->positionAbbreviation->getCodeShift());

            $validasi = new ValidasiUnitKerja();
            $validasi->ID = Uuid::uuid4();
            $validasi->IDKPIRealisasiHeader = $data['IDKPIRealisasiHeader'];
            $validasi->ValidasiUnitKerja = $data['ValidasiUnitKerja'];
            $validasi->IDStatusDokumen = 6;
            $validasi->KodeUnitKerjaPenilai = (! empty($unitkerjapenilai)) ? $unitkerjapenilai->CostCenter : null;
            $validasi->NPKAtasanLangsung = $atasanLangsung->NPK;
            $validasi->CreatedBy = $user->NPK;
            $validasi->CreatedOn = Carbon::now(config('app.timezone'));
            $validasi->save();
            return $this->successResponse();
        } catch (\Exception $e) {
            return $this->errorResponse($e->getMessage());
        }
    }
}
