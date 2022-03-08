<?php
namespace App\ApplicationServices\RencanaKPI;

use Carbon\Carbon;
use App\Domain\Rencana\Services\CascadePercentageService;
use App\Domain\User\Entities\User;
use App\Domain\Rencana\Entities\KRAKPIDetail;
use Illuminate\Support\Facades\DB;
use Ramsey\Uuid\Uuid;

class UpdatePenurunanRencanaKPIService extends StorePenurunanRencanaKPIService
{
    /**
     * @param $cascadeid
     * @param array $data
     * @param User $userAtasan
     * @return array
     */
    public function callUpdate($cascadeid, array $data, User $userAtasan)
    {  
        $total_persentase = 0;
        for ($i=0; $i < count($data["PersentaseKRA"]) ; $i++) { 
            $total_persentase += $data["PersentaseKRA"][$i];
        }

        if($total_persentase <= 100.0){

        }
        

        DB::beginTransaction();  

        try {

            // update 
            for ($i=0; $i < count($data["NPKBawahan"]) ; $i++) { 
                
                    $karyawanBawahan = $this->karyawanRepository->findByNPK($data['NPKBawahan'][$i]);

                    $update = KRAKPIDetail::findOrFail($data["ID"][$i]); 
                    $update->IDKPIAtasan = $data['IDKPIAtasan'];
                    $update->NPKBawahan = $data['NPKBawahan'][$i];
                    $update->IDMasterPosition = $karyawanBawahan->organization->position->PositionID;
                    $update->PersentaseKRA =(! empty($data['PersentaseKRA'][$i])) ? $data['PersentaseKRA'][$i] : null;
                    $update->Keterangan = (! empty($data['Keterangan'])) ? $data['Keterangan'] : null;
                    $update->UpdatedBy = $userAtasan->NPK;
                    $update->UpdatedOn = Carbon::now();
                    $update->Target1 = (! empty($data['Target1'][$i])) ? $data['Target1'][$i] : null;
                    $update->Target2 = (! empty($data['Target2'][$i])) ? $data['Target2'][$i] : null;
                    $update->Target3 = (! empty($data['Target3'][$i])) ? $data['Target3'][$i] : null;
                    $update->Target4 = (! empty($data['Target4'][$i])) ? $data['Target4'][$i] : null;
                    $update->Target5 = (! empty($data['Target5'][$i])) ? $data['Target5'][$i] : null;
                    $update->Target6 = (! empty($data['Target6'][$i])) ? $data['Target6'][$i] : null;
                    $update->Target7 = (! empty($data['Target7'][$i])) ? $data['Target7'][$i] : null;
                    $update->Target8 = (! empty($data['Target8'][$i])) ? $data['Target8'][$i] : null;
                    $update->Target9 = (! empty($data['Target9'][$i])) ? $data['Target9'][$i] : null;
                    $update->Target10 = (! empty($data['Target10'][$i])) ? $data['Target10'][$i] : null;
                    $update->Target11 = (! empty($data['Target11'][$i])) ? $data['Target11'][$i] : null;
                    $update->Target12 = (! empty($data['Target12'][$i])) ? $data['Target12'][$i] : null;
                    $update->save(); 
            } 

            if(! empty($data['new_NPKBawahan'])){
                for ($i=0; $i < count($data['new_NPKBawahan']) ; $i++) { 
                    $karyawanBawahan = $this->karyawanRepository->findByNPK($data['new_NPKBawahan'][$i]);
                    $add = new KRAKPIDetail();
                    $add->ID = Uuid::uuid4();
                    $add->IDKPIAtasan = $data['IDKPIAtasan'];
                    $add->NPKBawahan = $data['new_NPKBawahan'][$i];
                    $add->IDMasterPosition = $karyawanBawahan->organization->position->PositionID;
                    $add->NPKAtasanLangsung = $userAtasan->NPK;
                    $add->JabatanAtasanLangsung = $userAtasan->karyawan->organization->position->PositionTitle;
                    $add->PersentaseKRA = (! empty($data['new_PersentaseKRA'][$i])) ? $data['new_PersentaseKRA'][$i] : null;
                    $add->Keterangan = (! empty($data['Keterangan'])) ? $data['Keterangan'] : null;
                    $add->CreatedBy = $userAtasan->NPK;
                    $add->CreatedOn = Carbon::now();

                    for ($x = 1; $x <= 12; $x++) { 
                        $target = null;

                        if(array_key_exists('new_Target'.$x, $data)){
                            if ($data['new_Target'.$x][$i] == null && empty($data['new_Target'.$x][$i])) {
                                $target = $data['originalTarget'.$x][$i];
                            } else {
                                $target = $data['new_Target'.$x][$i];
                            }
                        }
                        
                        $add->{'Target'.$x} = $target;
                    }

                    $add->save();
                }
            }

            DB::commit();
            return $this->successResponse();

        } catch (\Exception $e) {
            DB::rollback();
            return $this->errorResponse($e->getMessage().' line-'.$e->getLine());
        }

        // try {
        //     $this->totalPercentageItemIsAllowableToUpdate($data['IDKPIAtasan'], $cascadeid, $data['PersentaseKRA']);

        //     $karyawanBawahan = $this->karyawanRepository->findByNPK($data['NPKBawahan']);
        //     $kpiTurunan = $this->rencanaKPIRepository->findPenurunanById($cascadeid);
        //     $kpiTurunan->IDKPIAtasan = $data['IDKPIAtasan'];
        //     $kpiTurunan->NPKBawahan = $data['NPKBawahan'];
        //     $kpiTurunan->IDMasterPosition = $karyawanBawahan->organization->position->PositionID;
        //     $kpiTurunan->NPKAtasanLangsung = $userAtasan->NPK;
        //     $kpiTurunan->JabatanAtasanLangsung = $userAtasan->karyawan->organization->position->PositionTitle;
        //     $kpiTurunan->PersentaseKRA = (! empty($data['PersentaseKRA'])) ? $data['PersentaseKRA'] : null;
        //     $kpiTurunan->Keterangan = (! empty($data['Keterangan'])) ? $data['Keterangan'] : null;
        //     $kpiTurunan->UpdatedBy = $userAtasan->NPK;
        //     $kpiTurunan->UpdatedOn = Carbon::now();
        //     for ($i = 1; $i <= 12; $i++) {
        //         $kpiTurunan->{'Target'.$i} = (array_key_exists('Target'.$i, $data) || ! empty($data['Target'.$i])) ? $data['Target'.$i] : null;
        //     }
        //     $kpiTurunan->save();
        //     return $this->successResponse();
        // } catch (\Exception $e) {
        //     return $this->errorResponse($e->getMessage());
        // }
    }

    /**
     * @param $idKPIAtasan
     * @param $idCascaded
     * @param $addedPercentage
     * @return bool
     */
    public function totalPercentageItemIsAllowableToUpdate($idKPIAtasan, $idCascaded, $addedPercentage)
    {
        $storedPercentage = $this->rencanaKPIRepository->getPercentageCascadedItemExcept($idKPIAtasan, $idCascaded);
        if (! CascadePercentageService::isAllowableToStore($storedPercentage, $addedPercentage)) {
            throw new DomainException('Total persentase KRA item KPI yang diturunkan harus berjumlah 100%.');
        }
        return true;
    }
}
