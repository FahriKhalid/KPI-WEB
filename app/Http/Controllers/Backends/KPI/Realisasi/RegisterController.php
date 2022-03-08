<?php

namespace App\Http\Controllers\Backends\KPI\Realisasi;

use Illuminate\Http\Request;
use App\ApplicationServices\RealisasiKPI\RegisterRealisasiKPIService;
use App\ApplicationServices\RealisasiKPI\UnregisterRealisasiKPIService;
use App\Domain\Realisasi\Entities\DetilRealisasiKPI;
use App\Notifications\Notifikasi;

class RegisterController extends RealisasiIndividuController
{
    /**
     * @param Request $request
     * @param RegisterRealisasiKPIService $registerRealisasiKPIService
     * @return \Illuminate\Http\JsonResponse
     */
    public function register(Request $request, RegisterRealisasiKPIService $registerRealisasiKPIService)
    {
        $result = $registerRealisasiKPIService->call($request->except('_token'), $request->user());
        if ($result['status']) {
            $atasan = $this->realisasiKPIRepository->findKaryawanById(implode(',', $request->id))->karyawanatasanlangsung;
            $atasan->notify(new Notifikasi($request->id, 'realisasi', 'registered'));
            flash()->success('Data realisasi KPI berhasil di register.')->important();
            return response()->json($result);
        }
        flash()->error($result['errors'])->important();
        return response()->json($result);
    }

    /**
     * @param Request $request
     * @param UnregisterRealisasiKPIService $unregisterRealisasiKPIService
     * @return \Illuminate\Http\JsonResponse
     */
    public function unregister(Request $request, UnregisterRealisasiKPIService $unregisterRealisasiKPIService)
    {
        $result = $unregisterRealisasiKPIService->call($request->except('_token'), $request->user());
        if ($result['status']) {
            $atasan = $this->realisasiKPIRepository->findKaryawanById(implode(',', $request->id))->karyawanatasanlangsung;
            $atasan->notify(new Notifikasi($request->id, 'realisasi', 'unregistered'));
            flash()->success('Data realisasi KPI berhasil di unregister.')->important();
            return response()->json($result);
        }
        flash()->error($result['errors'])->important();
        return response()->json($result);
    }
}
