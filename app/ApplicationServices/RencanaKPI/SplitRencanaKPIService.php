<?php
namespace App\ApplicationServices\RencanaKPI;

use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use App\ApplicationServices\ApplicationService;
use App\Domain\Karyawan\Services\PositionAbbreviation;
use App\Domain\Rencana\Entities\DetilRencanaKPI;
use App\Domain\Rencana\Entities\HeaderRencanaKPI;
use App\Domain\Rencana\Services\ItemLimitationService;
use App\Exceptions\DomainException;
use App\Infrastructures\Repositories\KPI\RencanaKPIRepository;
use App\Domain\Rencana\Entities\KRAKPIDetail;
use Ramsey\Uuid\Uuid;


class SplitRencanaKPIService extends ApplicationService
{
    /**
     * @var RencanaKPIRepository
     */
    protected $rencanaKPIRepository;

    /**
     * StoreDetailRencanaService constructor.
     *
     * @param RencanaKPIRepository $rencanaKPIRepository
     */
    public function __construct(RencanaKPIRepository $rencanaKPIRepository)
    {
        $this->rencanaKPIRepository = $rencanaKPIRepository;
    }

    /**
     * @param $idRencanaHeader
     * @param array $data
     * @param $user
     * @return array
     */
    public function split($idRencanaHeader, array $request, $user)
    { 
        try {  
            $data['header'] = $this->rencanaKPIRepository->findHeaderByIdDetail($idRencanaHeader);
            $data['detail'] = $this->rencanaKPIRepository->findDetailById($idRencanaHeader);

            DB::beginTransaction();  

            if($data['detail']->IDKRAKPIRencanaDetil != null) // dari atasan
            { 
                // update is_split parent item KPI punya atasan
                $krakpi = KRAKPIDetail::findOrFail($data['detail']->IDKRAKPIRencanaDetil); 
                

                for ($i=0; $i < count($request["deskripsi_kpi"]) ; $i++) 
                {  
                    if($i == 0) // update data in Tr_KPIRencanaDetil
                    {
                        $krakpi->Target12 = $request["target"][$i];  
                        $krakpi->save();

                        // Update item KPI punya bawahan yang row pertama
                        $detailRencana = DetilRencanaKPI::where("ID",  $idRencanaHeader)->first(); 
                        $detailRencana->DeskripsiKPI = $request["deskripsi_kpi"][$i]; 
                        $detailRencana->Bobot = $request['bobot'][$i]; 
                        $detailRencana->Target12 = $request["target"][$i]; 
                        $detailRencana->IsKRABawahan = isset($request['is_turunan'][$i]) == false ? 0 : $request['is_turunan'][$i];
                        $detailRencana->UpdatedBy = $user->NPK; 
                        $detailRencana->is_split = 1;
                        $detailRencana->UpdatedOn = Carbon::now('Asia/Jakarta'); 
                        $detailRencana->save();
                    }
                    else // create data in Tr_KPIRencanaDetil
                    {
                        $kpi = new KRAKPIDetail();
                        $kpi->ID = Uuid::uuid4();
                        $kpi->IDKPIAtasan = $krakpi->IDKPIAtasan;
                        $kpi->NPKBawahan = $krakpi->NPKBawahan;
                        $kpi->IDMasterPosition = $krakpi->IDMasterPosition;
                        $kpi->NPKAtasanLangsung = $krakpi->NPKAtasanLangsung;
                        $kpi->JabatanAtasanLangsung = $krakpi->JabatanAtasanLangsung;
                        $kpi->PersentaseKRA = $krakpi->PersentaseKRA;
                        $kpi->Keterangan = $krakpi->Keterangan;
                        $kpi->CreatedBy = $user->NPK;
                        $kpi->CreatedOn = Carbon::now('Asia/Jakarta');
                        $kpi->Field1 = $krakpi->Field1;
                        $kpi->Field2 = $krakpi->Field2;
                        $kpi->Field3 = $krakpi->Field3;
                        $kpi->IsApproved = $krakpi->IsApproved;
                        $kpi->IsCascaded = $krakpi->IsCascaded; 
                        $kpi->Target12 = $request["target"][$i]; 
                        $kpi->save(); 

                        // Tambah item KPI yang di split dari row kedua
                        $detailRencana = new DetilRencanaKPI();
                        $detailRencana->ID = Uuid::uuid4();
                        $detailRencana->IDKPIRencanaHeader = $data['detail']->IDKPIRencanaHeader;
                        $detailRencana->IDJenisPeriode = $data['detail']->IDJenisPeriode;
                        $detailRencana->KodeRegistrasiKamus = $data['detail']->KodeRegistrasiKamus;
                        $detailRencana->IDKodeAspekKPI = $data['detail']->IDKodeAspekKPI;
                        $detailRencana->DeskripsiKRA = $data['detail']->DeskripsiKRA; 
                        $detailRencana->DeskripsiKPI = $request["deskripsi_kpi"][$i];
                        $detailRencana->IDSatuan = $data['detail']->IDSatuan;
                        $detailRencana->IDJenisAppraisal = $data['detail']->IDJenisAppraisal;
                        $detailRencana->IDPersentaseRealisasi = $data['detail']->IDPersentaseRealisasi;
                        $detailRencana->Bobot = $request['bobot'][$i];
                        $detailRencana->IsKRABawahan = $request['is_turunan'][$i] == null ? 0 : $request['is_turunan'][$i];
                        $detailRencana->Keterangan = $data['detail']->Keterangan;
                        $detailRencana->CreatedBy = $user->NPK;
                        $detailRencana->CreatedOn = Carbon::now('Asia/Jakarta');
                        $detailRencana->Target12 = $request["target"][$i]; 
                        $detailRencana->IDKRAKPIRencanaDetil = $kpi->ID; 
                        $detailRencana->IDSplitParent = $data['detail']->ID; // ID parent Tr_KPIRencanaDetil
                        $detailRencana->save();
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

    public function unsplit($id)
    {
        try {

            DB::beginTransaction();

            $data['detail'] = $this->rencanaKPIRepository->findDetailById($id); 
            
            if($data['detail']->is_split == 1) // parent split
            {
                // sum bobot
                $data['sum_bobot'] = $this->rencanaKPIRepository->findSumBobotSplit($id);

                // update Bobot parent (kembali seperti semula)
                $detailRencana = DetilRencanaKPI::findOrFail($id);
                $detailRencana->Bobot = $detailRencana->Bobot + $data['sum_bobot'];

                if($data['detail']->IDJenisAppraisal == 2){
                    // sum target
                    $data['sum_target'] = $this->rencanaKPIRepository->findSumTargetSplit($id);
                    $detailRencana->Target12 = $detailRencana->Target12 + $data['sum_target'];
                }

                $detailRencana->save();

                // delete Tr_KRAKPIRencanaDetil
                $detail = DetilRencanaKPI::where("IDSplitParent", $id)->get();
                foreach ($detail as $item) {
                    KRAKPIDetail::findOrFail($item->IDKRAKPIRencanaDetil)->delete();
                }

                // delete Tr_KPIRencanaDetil
                DetilRencanaKPI::where("IDSplitParent", $id)->delete();

            }   
            else // child split
            {
                // sum bobot
                $data['sum_bobot'] = $this->rencanaKPIRepository->findSumBobotSplit($data['detail']->IDSplitParent);

                // update Bobot parent (kembali seperti semula)
                $detailRencana = DetilRencanaKPI::findOrFail($data['detail']->IDSplitParent);
                $detailRencana->Bobot = $detailRencana->Bobot + $data['sum_bobot'];

                if($data['detail']->IDJenisAppraisal == 2){
                    // sum target
                    $data['sum_target'] = $this->rencanaKPIRepository->findSumTargetSplit($data['detail']->IDSplitParent);
                    $detailRencana->Target12 = $detailRencana->Target12 + $data['sum_target'];
                }

                $detailRencana->save();

                // delete Tr_KRAKPIRencanaDetil
                $detail = DetilRencanaKPI::where("IDSplitParent", $data['detail']->IDSplitParent)->get();
                foreach ($detail as $item) {
                    KRAKPIDetail::findOrFail($item->IDKRAKPIRencanaDetil)->delete();
                }

                // delete Tr_KPIRencanaDetil
                DetilRencanaKPI::where("IDSplitParent", $data['detail']->IDSplitParent)->delete();
            }

            DB::commit();
            return $this->successResponse();
        } catch (\Exception $e) {
            DB::rollback();
            return $this->errorResponse($e->getMessage());
        }
    }
     
}
