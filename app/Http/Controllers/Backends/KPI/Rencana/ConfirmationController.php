<?php

namespace App\Http\Controllers\Backends\KPI\Rencana;

use Illuminate\Http\Request;
use App\Http\Requests\KPI\Rencana\UnconfirmRencanaRequest;
use App\ApplicationServices\RencanaKPI\ConfirmRencanaKPIService;
use App\ApplicationServices\RencanaKPI\UnconfirmRencanaKPIService;
use App\Notifications\Notifikasi;

class ConfirmationController extends RencanaIndividuController
{
    /**
     * @param Request $request
     * @param ConfirmRencanaKPIService $confirmRencanaKPIService
     * @return \Illuminate\Http\JsonResponse
     */
    public function confirm(Request $request, ConfirmRencanaKPIService $confirmRencanaKPIService)
    {
        $result = $confirmRencanaKPIService->call($request->except('_token'), $request->user());
        if ($result['status']) {
            try {
                foreach ($request->get('id') as $id) {
                    $atasan = $this->rencanaKPIRepository->findKaryawanById($id)->karyawanatasanberikutnya;
                    $atasan->notify(new Notifikasi($request->id, 'rencana', 'confirmed'));
                }
                flash()->success('Rencana KPI telah berhasil dikonfirmasi')->important();
                return response()->json($result);
            } catch (\Exception $e) {
                flash()->success('Rencana KPI telah berhasil dikonfirmasi')->important();
                return response()->json($result);
            }
        }
        flash()->error($result['errors']);
        return response()->json($result);
    }

    /**
     * @param Request|UnconfirmRencanaRequest $request
     * @param UnconfirmRencanaKPIService $unconfirmRencanaKPIService
     * @return \Illuminate\Http\JsonResponse
     */
    public function unconfirm(UnconfirmRencanaRequest $request, UnconfirmRencanaKPIService $unconfirmRencanaKPIService)
    {
        $result = $unconfirmRencanaKPIService->call($request->except('_token'), $request->user());
        if ($result['status']) {
            $atasan = $this->rencanaKPIRepository->findKaryawanById(implode(',', $request->id))->karyawan;
            $atasan->notify(new Notifikasi($request->id, 'rencana', 'unconfirmed'));
            flash()->success('Rencana KPI telah berhasil di-Unconfirm')->important();
            return response()->json($result);
        }
        flash()->error($result['errors']);
        return response()->json($result);
    }
}
