<?php

namespace App\Http\Controllers\Backends\KPI\Rencana;

use Illuminate\Http\Request;
use App\Http\Requests\KPI\Rencana\UnapproveRencanaRequest;
use App\ApplicationServices\RealisasiKPI\StoreHeaderRealisasiService;
use App\ApplicationServices\RencanaKPI\ApproveRencanaKPIService;
use App\ApplicationServices\RencanaKPI\UnapproveRencanaKPIService;
use App\Notifications\Notifikasi;

class ApprovalController extends RencanaIndividuController
{
    /**
     * @param Request $request
     * @param ApproveRencanaKPIService $approveRencanaKPIService
     * @param StoreHeaderRealisasiService $storeHeaderRealisasiService
     * @return \Illuminate\Http\JsonResponse
     */
    public function approve(Request $request, ApproveRencanaKPIService $approveRencanaKPIService, StoreHeaderRealisasiService $storeHeaderRealisasiService)
    {
        $result = $approveRencanaKPIService->call($request->except('_token'), $request->user());
        if ($result['status']) {
            try {
                foreach ($request->get('id') as $id) {
                    $bawahan = $this->rencanaKPIRepository->findKaryawanById($id)->karyawan;
                    //$atasanlangsung = $this->rencanaKPIRepository->findKaryawanById($id)->karyawanatasanlangsung;
                    $bawahan->notify(new Notifikasi($request->id, 'rencana', 'approved'));
                    //$atasanlangsung->notify(new Notifikasi($request->id,'rencana','approved'));
                }
                flash()->success('Rencana KPI telah berhasil di Approve')->important();
                return response()->json($result);
            } catch (\Exception $e) {
                flash()->success('Rencana KPI telah berhasil di Approve')->important();
                return response()->json($result);
            }
        }
        flash()->error($result['errors'])->important();
        return response()->json($result);
    }

    /**
     * @param Request|UnapproveRencanaRequest $request
     * @param UnapproveRencanaKPIService $unapproveRencanaKPIService
     * @return \Illuminate\Http\JsonResponse
     */
    public function unapprove(UnapproveRencanaRequest $request, UnapproveRencanaKPIService $unapproveRencanaKPIService)
    { 
        $result = $unapproveRencanaKPIService->call($request->except('_token'), $request->user()); 
        if ($result['status']) {
            try {
                foreach ($request->get('id') as $id) {
                    $bawahan = $this->rencanaKPIRepository->findKaryawanById($id)->karyawan;
                    //$atasanlangsung = $this->rencanaKPIRepository->findKaryawanById($id)->karyawanatasanlangsung;
                    $bawahan->notify(new Notifikasi($request->id, 'rencana', 'unapproved'));
                    //$atasanlangsung->notify(new Notifikasi($request->id,'rencana','unapproved'));
                }
                flash()->success('Rencana KPI telah berhasil di Unapprove')->important();
                return response()->json($result);
            } catch (\Exception $e) {
                flash()->success('Rencana KPI telah berhasil di Unapprove')->important();
                return response()->json($result);
            }
        }
        flash()->error($result['errors'])->important();
        return response()->json($result);
    }
}
