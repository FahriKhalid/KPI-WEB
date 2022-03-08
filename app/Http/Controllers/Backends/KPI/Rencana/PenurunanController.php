<?php

namespace App\Http\Controllers\Backends\KPI\Rencana;

use Illuminate\Http\Request;
use App\ApplicationServices\RencanaKPI\DeletePenurunanService;
use App\ApplicationServices\RencanaKPI\StorePenurunanRencanaKPIService;
use App\ApplicationServices\RencanaKPI\UpdatePenurunanRencanaKPIService;
use App\Domain\Karyawan\Services\PositionAbbreviation;
use App\Domain\KPI\Entities\PeriodeAktif;
use App\Http\Requests\KPI\Rencana\DeletePenurunanKPIRequest;
use App\Http\Requests\KPI\Rencana\StorePenurunanRencanaRequest;
use App\Http\Requests\KPI\Rencana\UpdatePenurunanRencanaRequest; 

class PenurunanController extends RencanaIndividuController
{
    /**
     * @param $id
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function indexPenurunan($id)
    {
        $data['header'] = $this->rencanaKPIRepository->findById($id);
        $data['karyawan'] = $this->karyawanRepository->findByNPK($data['header']->NPK);
        $data['items'] = $this->rencanaKPIRepository->findItemIsKRA($id);

        $posAbbreviation = new PositionAbbreviation();

        $positionAbbreviation = $data['karyawan']->organization->position->PositionAbbreviation;
        $posAbbreviation->position($data['karyawan']->organization->position->PositionAbbreviation);
 
        $data['bawahan'] = $this->karyawanRepository->findBawahan($positionAbbreviation, $posAbbreviation->getParentPosition()); 

        $data['periode'] = PeriodeAktif::where('Tahun', $data['header']->Tahun)->select('IDJenisPeriode')->first();
        $data['target'] = $this->targetPeriodeService->periodeID($data['periode']->IDJenisPeriode)->getTargetCount();
        $data['periodeTarget']= $this->targetPeriodeService->periodeTarget($data['periode']->IDJenisPeriode)->getTargetCount();

        $data['cascadeItems'] = $this->rencanaKPIRepository->getAllPenurunanItem($id); 

        return view('backends.kpi.rencana.penurunan', compact('data'));
    }

    /**
     * @param $id
     * @param StorePenurunanRencanaRequest $request
     * @param StorePenurunanRencanaKPIService $service
     * @return \Illuminate\Http\RedirectResponse
     */
    public function storePenurunan($id, Request $request, StorePenurunanRencanaKPIService $service)
    {

        $result = $service->call($request->except(['_token']), $request->user());
 
        if ($result['status']) {
            flash()->success('KPI yang diturunkan berhasil disimpan.')->important();
            return redirect()->back();
        }
        // $request->flashExcept(['_token']);
        flash()->error($result['errors'])->important();
        return redirect()->back();
    }

    /**
     * @param DeletePenurunanKPIRequest $request
     * @param DeletePenurunanService $deletePenurunanService
     * @return \Illuminate\Http\JsonResponse
     */
    public function deleteCascade(DeletePenurunanKPIRequest $request, DeletePenurunanService $deletePenurunanService)
    {
        $result = $deletePenurunanService->call($request->except(['_token', '_method']));
        if ($result['status']) {
            flash()->success('Data penurunan KPI telah berhasil dihapus.')->important();
            return response()->json($result);
        }
        flash()->error($result['errors'])->important();
        return response()->json($result);
    }

    public function deleteCascade2(Request $request, DeletePenurunanService $deletePenurunanService)
    { 
        $result = $deletePenurunanService->call($request->except(['_token']));
        if ($result['status']) {
             $response = array('status'=>'success', 'message'=>'Hapus item turunan berhasil');
        }else{
             $response = array('status'=>'error', 'message'=>'Hapus item turunan tidak berhasil');
        }
        
        return response()->json($response);    
    }

    /**
     * @param $id
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function showPenurunan($id)
    {
        $data['header'] = $this->rencanaKPIRepository->findById($id);
        $data['karyawan'] = $this->karyawanRepository->findByNPK($data['header']->NPK);
        $data['periode'] = PeriodeAktif::where('Tahun', $data['header']->Tahun)->select('IDJenisPeriode')->first();
        $data['target'] = $this->targetPeriodeService->periodeID($data['periode']->IDJenisPeriode, $data['header']->IsKPIUnitKerja)->getTargetCount();
        $data['periodeTarget']= $this->targetPeriodeService->periodeTarget($data['periode']->IDJenisPeriode, $data['header']->IsKPIUnitKerja)->getTargetCount();
        $data['cascadeItems'] = $this->rencanaKPIRepository->getAllPenurunanItem($id);   
        //dd($data);

        return view('backends.kpi.rencana.detailpenurunan', compact('data'));
    }

    /**
     * @param $id
     * @param $idcascade
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function editPenurunan($id, $idcascade)
    {
        $data['header'] = $this->rencanaKPIRepository->findById($id);
        $data['karyawan'] = $this->karyawanRepository->findByNPK($data['header']->NPK);
        $data['items'] = $this->rencanaKPIRepository->findItemIsKRA($id);

        $posAbbreviation = new PositionAbbreviation();
        
        if(\Auth::user()->IDRole == 8){
            $positionAbbreviation = $posAbbreviation->position(getDireksi(\Auth::user()->NPK)->PositionAbbreviation);
            $posAbbreviation->position(getDireksi(\Auth::user()->NPK)->PositionAbbreviation);
        }else{
            $positionAbbreviation = $data['karyawan']->organization->position->PositionAbbreviation;
            $posAbbreviation->position($data['karyawan']->organization->position->PositionAbbreviation);
        }

        $data['bawahan'] = $this->karyawanRepository->findBawahan($positionAbbreviation, $posAbbreviation->getParentPosition());

        $data['periode'] = PeriodeAktif::where('Tahun', $data['header']->Tahun)->select('IDJenisPeriode')->first();
        $data['target'] = $this->targetPeriodeService->periodeID($data['periode']->IDJenisPeriode)->getTargetCount();
        $data['periodeTarget']= $this->targetPeriodeService->periodeTarget($data['periode']->IDJenisPeriode)->getTargetCount();
        $data['cascadeitem'] = $this->rencanaKPIRepository->findPenurunanById($idcascade);
        $data['itemTurunan'] = $this->rencanaKPIRepository->getPenurunanById($data['cascadeitem']->IDKPIAtasan);
        $data['parentKPI'] = $this->rencanaKPIRepository->findDetailById($data['cascadeitem']->IDKPIAtasan);

        return view('backends.kpi.rencana.editpenurunan', compact('data','id'));
    }

    /**
     * @param $id
     * @param $idcascade
     * @param UpdatePenurunanRencanaRequest $request
     * @param UpdatePenurunanRencanaKPIService $service
     * @return \Illuminate\Http\RedirectResponse
     */
    // public function updatePenurunan($id, $idcascade, UpdatePenurunanRencanaRequest $request, UpdatePenurunanRencanaKPIService $service)
    // {
    //     $result = $service->callUpdate($idcascade, $request->except(['_token']), $request->user());
    //     if ($result['status']) {
    //         flash()->success('Data penurunan KPI berhasil diperbarui.')->important();
    //         return redirect()->route('backends.kpi.rencana.individu.pennurunan', ['id' => $id]);
    //     }
    //     flash()->error($result['errors'])->important();
    //     return redirect()->back();
    // }

    public function updatePenurunan($id, $idcascade, Request $request, UpdatePenurunanRencanaKPIService $service)
    { 
        $result = $service->callUpdate($idcascade, $request->except(['_token']), $request->user());
        if ($result['status']) {
            // flash()->success('Data penurunan KPI berhasil diperbarui.')->important();
            // return redirect()->route('backends.kpi.rencana.individu.pennurunan', ['id' => $id]);

        }
        // flash()->error($result['errors'])->important();
        // return redirect()->back();

        return json_encode($result);
    }

    /**
     * @param $iddetailrencana
     * @return \Illuminate\Http\JsonResponse
     */
    public function apiGetDetilRencanaKPI($iddetailrencana)
    {
        $data = $this->rencanaKPIRepository->findDetailById($iddetailrencana);
        return response()->json($data);
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function apiCheckExistCascade(Request $request)
    {

        $return = false;

        for ($i=0; $i < count($request->NPKBawahan) ; $i++) { 
            $x = $this->rencanaKPIRepository->isCascadeItemAlreadyAssigned($request->NPKBawahan[$i], $request->get('IDKPIAtasan'));
            if($x){
                $return = true;
            }
        }   

        return response()->json(['status' => $return]);
    }
}
