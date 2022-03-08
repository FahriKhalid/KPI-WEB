<?php

namespace App\Http\Controllers\Backends\KPI\Realisasi;

use Illuminate\Http\Request;
use App\ApplicationServices\RealisasiKPI\ApproveRealisasiKPIService;
use App\ApplicationServices\RealisasiKPI\UnapproveRealisasiKPIService;
use App\Http\Controllers\Controller;
use App\Http\Requests\KPI\Realisasi\UnapproveRealisasiRequest;
use App\Notifications\Notifikasi;

class ApprovalController extends RealisasiIndividuController
{
    /**
     * @param Request $request
     * @param ApproveRealisasiKPIService $approveRealisasiKPIService
     * @return \Illuminate\Http\JsonResponse
     */
    public function approve(Request $request, ApproveRealisasiKPIService $approveRealisasiKPIService)
    {
        $result = $approveRealisasiKPIService->call($request->except('_token'), $request->user());
        if ($result['status']) {
            try {
                foreach ($request->get('id') as $id) {
                    $bawahan = $this->rencanaKPIRepository->findKaryawanById($id)->karyawan;
                    //$atasanlangsung = $this->realisasiKPIRepository->findKaryawanById($id)->karyawanatasanlangsung;
                    $bawahan->notify(new Notifikasi($request->id, 'realisasi', 'approved'));
                    //$atasanlangsung->notify(new Notifikasi($request->id,'realisasi','approved'));
                }
                flash()->success('Realisasi KPI telah berhasil di Approve')->important();
                return response()->json($result);
            } catch (\Exception $e) {
                flash()->success('Realisasi KPI telah berhasil di Approve')->important();
                return response()->json($result);
            }
        }
        flash()->error($result['errors'])->important();
        return response()->json($result);
    }

    /**
     * @param UnapproveRealisasiRequest $request
     * @param UnapproveRealisasiKPIService $unapproveRealisasiKPIService
     * @return \Illuminate\Http\JsonResponse
     */
    public function unapprove(UnapproveRealisasiRequest $request, UnapproveRealisasiKPIService $unapproveRealisasiKPIService)
    {
        $result = $unapproveRealisasiKPIService->call($request->except('_token'), $request->user());
        if ($result['status']) {
            try {
                foreach ($request->get('id') as $id) {
                    $bawahan = $this->realisasiKPIRepository->findKaryawanById($id)->karyawan;
                    //$atasanlangsung = $this->realisasiKPIRepository->findKaryawanById($id)->karyawanatasanlangsung;
                    $bawahan->notify(new Notifikasi($request->id, 'realisasi', 'unapproved'));
                    //$atasanlangsung->notify(new Notifikasi($request->id,'realisasi','unapproved'));
                }
                flash()->success('Realisasi KPI telah berhasil di Unapprove')->important();
                return response()->json($result);
            } catch (\Exception $e) {
                flash()->success('Realisasi KPI telah berhasil di Unapprove')->important();
                return response()->json($result);
            }
        }
        flash()->error($result['errors'])->important();
        return response()->json($result);
    }
}
