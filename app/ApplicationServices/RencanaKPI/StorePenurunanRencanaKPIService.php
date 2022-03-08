<?php
namespace App\ApplicationServices\RencanaKPI;

use Carbon\Carbon;
use App\ApplicationServices\ApplicationService;
use App\Domain\Rencana\Entities\KRAKPIDetail;
use App\Domain\Rencana\Services\CascadePercentageService;
use App\Exceptions\DomainException;
use App\Infrastructures\Repositories\Karyawan\KaryawanRepository;
use App\Infrastructures\Repositories\KPI\RencanaKPIRepository;
use Ramsey\Uuid\Uuid;

class StorePenurunanRencanaKPIService extends ApplicationService
{
    /**
     * @var KaryawanRepository
     */
    protected $karyawanRepository;

    /**
     * @var RencanaKPIRepository
     */
    protected $rencanaKPIRepository;

    /**
     * StorePenurunanRencanaKPIService constructor.
     *
     * @param KaryawanRepository $karyawanRepository
     * @param RencanaKPIRepository $rencanaKPIRepository
     */
    public function __construct(KaryawanRepository $karyawanRepository, RencanaKPIRepository $rencanaKPIRepository)
    {
        $this->karyawanRepository = $karyawanRepository;
        $this->rencanaKPIRepository = $rencanaKPIRepository;
    }

    /**
     * @param array $data
     * @param $userAtasan
     * @return array
     */
    public function call(array $data, $userAtasan)
    {

       try {    
            $jumlahPersentaseKra = 0;
            for ($i=0; $i < count($data['PersentaseKRA']) ; $i++) {  
                $jumlahPersentaseKra += $data['PersentaseKRA'][$i];
            } 

            $this->totalPercentageItemIsAllowable($data['IDKPIAtasan'], $jumlahPersentaseKra);   

            for ($i=0; $i < count($data["NPKBawahan"]) ; $i++) {   
             
                $karyawanBawahan = $this->karyawanRepository->findByNPK($data['NPKBawahan'][$i]);
                $kpiTurunan = new KRAKPIDetail();
                $kpiTurunan->ID = Uuid::uuid4();
                $kpiTurunan->IDKPIAtasan = $data['IDKPIAtasan'];
                $kpiTurunan->NPKBawahan = $data['NPKBawahan'][$i];
                $kpiTurunan->IDMasterPosition = $karyawanBawahan->organization->position->PositionID;
                $kpiTurunan->NPKAtasanLangsung = $userAtasan->NPK;
                $kpiTurunan->JabatanAtasanLangsung = $userAtasan->karyawan->organization->position->PositionTitle;
                $kpiTurunan->PersentaseKRA = (! empty($data['PersentaseKRA'][$i])) ? $data['PersentaseKRA'][$i] : null;
                $kpiTurunan->Keterangan = (! empty($data['Keterangan'])) ? $data['Keterangan'] : null;
                $kpiTurunan->CreatedBy = $userAtasan->NPK;
                $kpiTurunan->CreatedOn = Carbon::now();


                // for ($i = 1; $i <= 12; $i++) {
                //     $target = null;
                //     if (array_key_exists('Target'.$i, $data)) {
                //         if (empty($data['Target'.$i[$i]])) {
                //             $target = $data['originalTarget'.$i];
                //         } else {
                //             $target = $data['Target'.$i];
                //         }
                //     }
                //     $kpiTurunan->{'Target'.$i} = $target;
                // }


                for ($x = 1; $x <= 12; $x++) { 
                    $target = null;


                    if(array_key_exists('Target'.$x, $data)){
                        if ($data['Target'.$x][$i] == null && empty($data['Target'.$x][$i])) {
                            $target = $data['originalTarget'.$x][$i];
                        } else {
                            $target = $data['Target'.$x][$i];
                        }
                    }
                    
                    $kpiTurunan->{'Target'.$x} = $target;
                    
                }

                $kpiTurunan->save();
            }
 
            return $this->successResponse();
        } catch (\Exception $e) {
            return $this->errorResponse($e->getMessage());
        }
    }

    /**
     * @param $npkBawahan
     * @param $idKPIAtasan
     * @return bool
     * @throws DomainException
     */
    protected function assignedItemToUserIsValid($npkBawahan, $idKPIAtasan)
    {
        if ($this->rencanaKPIRepository->isCascadeItemAlreadyAssigned($npkBawahan, $idKPIAtasan)) {
            throw new DomainException('KPI sudah diturunkan pada bawahan yang Anda pilih.');
        }
        return true;
    }

    /**
     * @param $idKPIAtasan
     * @param $addedPercentage
     * @return bool
     * @throws DomainException
     */
    protected function totalPercentageItemIsAllowable($idKPIAtasan, $addedPercentage)
    {
        $storedPercentage = $this->rencanaKPIRepository->getPercentageCascadedItems($idKPIAtasan); 

        if (! CascadePercentageService::isAllowableToStore($storedPercentage, $addedPercentage)) {
            throw new DomainException('Total persentase KRA item KPI yang diturunkan harus berjumlah 100%.');
        }
        return true;
    }



    protected function totalPercentageItemIsAllowable2($idKPIAtasan, $addedPercentage)
    {
        $storedPercentage = $this->rencanaKPIRepository->getPercentageCascadedItems($idKPIAtasan); 

        $hasil = $storedPercentage + $addedPercentage;

        if($hasil <= 100){ 
            $x = array('status' => 'error', 'message' => 'Total persentase KRA item KPI yang diturunkan harus berjumlah 100%.');
        }elseif ($hasil > 100) { 
            $x = array('status' => 'error', 'message' => 'Total persentase KRA item KPI yang diturunkan lebih dari 100%.');
        } 
        return $x;
    }
}
