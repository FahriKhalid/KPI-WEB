<?php

namespace App\Http\Controllers\Backends\KPI\Rencana;

use Illuminate\Http\Request;
use App\ApplicationServices\RencanaKPI\RegisterRencanaKPIService;
use App\ApplicationServices\RencanaKPI\UnregisterRencanaKPIService;
use App\Notifications\Notifikasi;

class RegisterController extends RencanaIndividuController
{
    /**
     * @param Request $request
     * @param RegisterRencanaKPIService $registerRencanaKPIService
     * @return \Illuminate\Http\JsonResponse
     */
    public function register(Request $request, RegisterRencanaKPIService $registerRencanaKPIService)
    {
        $result = $registerRencanaKPIService->call($request->except('_token'), $request->user());
        if ($result['status']) {
            foreach ($request->get('id') as $id) {
                try {
                    $atasan = $this->rencanaKPIRepository->findKaryawanById($id)->karyawanatasanlangsung;
                    $atasan->notify(new Notifikasi($id, 'rencana', 'registered'));
                } catch (\Exception $e) {
                    flash()->success('Data rencana KPI berhasil di register.')->important();
                    return response()->json($result);
                }
            }
            flash()->success('Data rencana KPI berhasil di register.')->important();
            return response()->json($result);
        }
        flash()->error($result['errors'])->important();
        return response()->json($result);
    }

    /**
     * @param Request $request
     * @param UnregisterRencanaKPIService $unregisterRencanaKPIService
     * @return \Illuminate\Http\JsonResponse
     */
    public function unregister(Request $request, UnregisterRencanaKPIService $unregisterRencanaKPIService)
    {
        $result = $unregisterRencanaKPIService->call($request->except('_token'), $request->user());
        if ($result['status']) {
            foreach ($request->get('id') as $id) {
                try {
                    $atasan = $this->rencanaKPIRepository->findKaryawanById($id)->karyawanatasanlangsung;
                    $atasan->notify(new Notifikasi($id, 'rencana', 'unregistered'));
                } catch (\Exception $e) {
                    flash()->success('Data rencana KPI berhasil di unregister.')->important();
                    return response()->json($result);
                }
            }
            flash()->success('Data rencana KPI berhasil di unregister.')->important();
            return response()->json($result);
        }
        flash()->error($result['errors'])->important();
        return response()->json($result);
    }
}
