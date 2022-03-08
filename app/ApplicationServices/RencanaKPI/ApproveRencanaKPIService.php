<?php
namespace App\ApplicationServices\RencanaKPI;

use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use App\ApplicationServices\ApplicationService;
use App\Domain\Rencana\Entities\DetilRencanaKPI;
use App\Domain\Rencana\Entities\HeaderRencanaKPI;
use App\Domain\Rencana\Entities\KRAKPIDetail;
use App\Domain\Rencana\Services\PercentageCascadeService;
use App\Exceptions\DomainException;
use App\Infrastructures\Repositories\KPI\RencanaKPIRepository;
use Ramsey\Uuid\Uuid;

class ApproveRencanaKPIService extends ApplicationService
{
    /**
     * @var RencanaKPIRepository
     */
    protected $rencanaKPIRepository;

    /**
     * @var DetilRencanaKPI
     */
    protected $detilRencanaKPI;

    /**
     * @var StoreDetailRencanaService
     */
    protected $storeDetailRencanaService;

    /**
     * @var HeaderRencanaKPI
     */
    protected $headerRencanaKPI;

    /**
     * ApproveRencanaKPIService constructor.
     *
     * @param RencanaKPIRepository $rencanaKPIRepository
     * @param DetilRencanaKPI $detilRencanaKPI
     * @param StoreDetailRencanaService $storeDetailRencanaService
     * @param HeaderRencanaKPI $headerRencanaKPI
     */
    public function __construct(
        RencanaKPIRepository $rencanaKPIRepository,
        DetilRencanaKPI $detilRencanaKPI,
        StoreDetailRencanaService $storeDetailRencanaService,
        HeaderRencanaKPI $headerRencanaKPI
    ) {
        $this->rencanaKPIRepository = $rencanaKPIRepository;
        $this->detilRencanaKPI = $detilRencanaKPI;
        $this->storeDetailRencanaService = $storeDetailRencanaService;
        $this->headerRencanaKPI = $headerRencanaKPI;
    }

    /**
     * @param array $data
     * @param $user
     * @return array
     */
    public function call(array $data, $user)
    {
        try {
            $array = (array) $data['id'];
            DB::beginTransaction();
            foreach ($array as $id) {
                $header = $this->rencanaKPIRepository->findById($id);
                if (! $header->isConfirmed()) {
                    throw new DomainException('Dokumen yang akan di-approve harus berstatus CONFIRMED.');
                }
                $header->IDStatusDokumen = 4;
                $header->ApprovedBy = $user->NPK;
                $header->ApprovedOn = Carbon::now(config('app.timezone'));
                $header->save();
                $this->storeCascade($header);
            }
            DB::commit();
            return $this->successResponse();
        } catch (\Exception $e) {
            DB::rollback();
            return $this->errorResponse($e->getMessage());
        }
    }

    /**
     * @param $header
     * @return void
     */
    public function storeCascade($header)
    {
        // insert all cascading item to bawahan
        $items = $this->detilRencanaKPI->with(['penurunan' => function ($query) {
            $query->select(['ID', 'NPKBawahan', 'PersentaseKRA', 'IDKPIAtasan', 'Target1', 'Target2', 'Target3', 'Target4', 'Target5', 'Target6', 'Target7', 'Target8', 'Target9', 'Target10', 'Target11', 'Target12']);
        }])->where('IsKRABawahan', 1)->where('IDKPIRencanaHeader', $header->ID)->get();

        foreach ($items as $item) {
            foreach ($item->penurunan as $cascade) {
                // get rencana user;
                $rencanaHeader = $this->headerRencanaKPI->where('NPK', $cascade->NPKBawahan)
                                        ->where('Tahun', $header->Tahun)
                                        ->where('IDStatusDokumen', 1)
                                        ->select('ID')->first();
                                        
                if (! is_null($rencanaHeader)) {
                    $detailRencana = new DetilRencanaKPI();
                    $detailRencana->ID = Uuid::uuid4();
                    $detailRencana->IDKRAKPIRencanaDetil = $cascade->ID;
                    $detailRencana->IDKPIRencanaHeader = $rencanaHeader->ID;
                    $detailRencana->IDJenisPeriode = $item->IDJenisPeriode;
                    $detailRencana->KodeRegistrasiKamus = $item->KodeRegistrasiKamus;
                    $detailRencana->IDKodeAspekKPI = $item->IDKodeAspekKPI;
                    $detailRencana->DeskripsiKRA = $item->DeskripsiKPI;
                    $detailRencana->DeskripsiKPI = null;
                    $detailRencana->IDSatuan = $item->IDSatuan;
                    $detailRencana->IDJenisAppraisal = $item->IDJenisAppraisal;
                    $detailRencana->IDPersentaseRealisasi = $item->IDPersentaseRealisasi;
                    $detailRencana->Bobot = $item->Bobot;
                    $detailRencana->IsKRABawahan = false;
                    $detailRencana->Keterangan = $cascade->Keterangan;
                    $detailRencana->CreatedBy = $cascade->NPKBawahan;
                    $detailRencana->CreatedOn = Carbon::now(config('app.timezone'));
                    for ($i = 1; $i <= 12; $i++) {
                        $detailRencana->{'Target'.$i} = $cascade->{'Target'.$i};
                    }
                    $detailRencana->save();

                    // flag item has been cascaded
                    $cascade->IsCascaded = true;
                }
                $cascade->IsApproved = true;
                $cascade->save();
            }
        }
    }
}
