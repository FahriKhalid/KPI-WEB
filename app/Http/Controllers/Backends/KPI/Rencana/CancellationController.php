<?php

namespace App\Http\Controllers\Backends\KPI\Rencana;

use Illuminate\Http\Request;
use App\ApplicationServices\RencanaKPI\CancelRencanaKPIService;
use App\Http\Controllers\Controller;

class CancellationController extends Controller
{
    /**
     * @param Request $request
     * @param CancelRencanaKPIService $cancelRencanaKPIService
     * @return \Illuminate\Http\JsonResponse
     */
    public function cancel(Request $request, CancelRencanaKPIService $cancelRencanaKPIService)
    {
        $result = $cancelRencanaKPIService->call($request->except('_token'), $request->user());
        if ($result['status']) {
            flash()->success('Rencana KPI berhasil di-cancel')->important();
            return response()->json($result);
        }
        flash()->error($result['errors'])->important();
        return response()->json($result);
    }
}
