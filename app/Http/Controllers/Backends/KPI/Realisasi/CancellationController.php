<?php

namespace App\Http\Controllers\Backends\KPI\Realisasi;

use Illuminate\Http\Request;
use App\ApplicationServices\RealisasiKPI\CancelRealisasiKPIService;
use App\Http\Controllers\Controller;

class CancellationController extends Controller
{
    /**
     * @param Request $request
     * @param CancelRealisasiKPIService $cancelRealisasiKPIService
     * @return \Illuminate\Http\JsonResponse
     */
    public function cancel(Request $request, CancelRealisasiKPIService $cancelRealisasiKPIService)
    {
        $result = $cancelRealisasiKPIService->call($request->except('_token'), $request->user());
        if ($result['status']) {
            flash()->success('Realisasi KPI berhasil di-cancel')->important();
            return response()->json($result);
        }
        flash()->error($result['errors'])->important();
        return response()->json($result);
    }
}
