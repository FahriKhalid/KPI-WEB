<?php

namespace App\Http\Controllers\Backends\KPI\Realisasi;

use Illuminate\Http\Request;
use App\ApplicationServices\RealisasiKPI\ConfirmRealisasiKPIService;
use App\ApplicationServices\RealisasiKPI\UnconfirmRealisasiKPIService;
use App\Http\Requests\KPI\Realisasi\UnconfirmRealisasiRequest;
use App\Notifications\Notifikasi;

class ConfirmationController extends RealisasiIndividuController
{
    /**
     * @param Request $request
     * @param ConfirmRealisasiKPIService $confirmRealisasiKPIService
     * @return \Illuminate\Http\JsonResponse
     */
    public function confirm(Request $request, ConfirmRealisasiKPIService $confirmRealisasiKPIService)
    {
        $result = $confirmRealisasiKPIService->call($request->except('_token'), $request->user());
        if ($result['status']) {
            try {
                foreach ($request->get('id') as $id) {
                    $atasan = $this->realisasiKPIRepository->findKaryawanById($id)->karyawanatasanberikutnya;
                    $atasan->notify(new Notifikasi($request->id, 'realisasi', 'confirmed'));
                }
                flash()->success('Realisasi KPI telah berhasil dikonfirmasi')->important();
                return response()->json($result);
            } catch (\Exception $e) {
                flash()->success('Realisasi KPI telah berhasil dikonfirmasi')->important();
                return response()->json($result);
            }
        }
        flash()->error($result['errors']);
        return response()->json($result);
    }

    /**
     * @param UnconfirmRealisasiRequest $request
     * @param UnconfirmRealisasiKPIService $unconfirmRealisasiKPIService
     * @return \Illuminate\Http\JsonResponse
     */
    public function unconfirm(UnconfirmRealisasiRequest $request, UnconfirmRealisasiKPIService $unconfirmRealisasiKPIService)
    {
        $result = $unconfirmRealisasiKPIService->call($request->except('_token'), $request->user());
        if ($result['status']) {
            $atasan = $this->realisasiKPIRepository->findKaryawanById(implode(',', $request->id))->karyawan;
            $atasan->notify(new Notifikasi($request->id, 'realisasi', 'unconfirmed'));
            flash()->success('Realisasi KPI telah berhasil di-Unconfirm')->important();
            return response()->json($result);
        }
        flash()->error($result['errors']);
        return response()->json($result);
    }
}
