<?php
namespace App\ApplicationServices\RencanaKPI;

use Illuminate\Support\Facades\DB;
use App\ApplicationServices\ApplicationService;
use App\Domain\Rencana\Entities\DetilRencanaKPI;
use App\Domain\Realisasi\Entities\DetilRealisasiKPI;
use App\Domain\User\Entities\User;
use App\Infrastructures\Repositories\KPI\RencanaKPIRepository;
use Auth;

class DeleteItemKPIService extends ApplicationService
{
    /**
     * @param array $data
     * @return array
     */
/*
//original error karna blum ada constructornya     
public function call(array $data)
    {
        try {
            DB::beginTransaction();
            foreach ($data['id'] as $itemId) {
                $detil = DetilRencanaKPI::where('ID', $itemId)->first();
                $detil->isAllowedToDelete();

                //====== tambah baru ==========
                $this->rencanakpiRepository->isAvailablePenurunan($user->NPK);
                
                $detil->delete();
            }
            DB::commit();
            return $this->successResponse();
        } catch (\Exception $e) {
            DB::rollback();
            return $this->errorResponse($e->getMessage());
        }
    }
*/
    public function __construct(RencanaKPIRepository $rencanakpiRepository)
    {
        $this->rencanakpiRepository = $rencanakpiRepository;
    }

    public function call(array $data, User $user)
    {
        try {
            DB::beginTransaction();
            foreach ($data['id'] as $itemId) {
                $detil = DetilRencanaKPI::where('ID', $itemId)->first(); 

                // jika admin
                if(Auth::user()->IDRole == 1){ 

                    $detailKPI = DetilRencanaKPI::where('ID', $detil->IDKRAKPIRencanaDetil)->first();

                    if($detailKPI){
                        throw new DomainException('Item KPI tidak dapat dihapus karena turunan dari atasan');
                        return false; 
                    }

                }else{
                    $detil->isAllowedToDelete();
                }

                $this->rencanakpiRepository->isAvailablePenurunan($detil);
                $detil->delete();
            }
            DB::commit();
            return $this->successResponse();
        } catch (\Exception $e) {
            DB::rollback();
            return $this->errorResponse($e->getMessage());
        }
    }

    public function delete(array $data, User $user)
    {
        try {
            DB::beginTransaction();
            foreach ($data['id'] as $itemId) {
                $detil = DetilRencanaKPI::where('ID', $itemId)->first(); 

                $realisasi = DetilRealisasiKPI::where('IDRencanaKPIDetil', $itemId)->first();

                // jika admin
                if(Auth::user()->IDRole != 1){ 
                    $detil->isAllowedToDelete();
                }

                $this->rencanakpiRepository->isAvailablePenurunan($detil);
                $detil->delete();
                $realisasi->delete();
            }
            DB::commit();
            return $this->successResponse();
        } catch (\Exception $e) {
            DB::rollback();
            return $this->errorResponse($e->getMessage());
        }
    }

}
