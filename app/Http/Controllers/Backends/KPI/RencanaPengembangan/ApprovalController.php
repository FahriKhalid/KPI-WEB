<?php
/**
 * Created by PhpStorm.
 * User: SENA
 * Date: 10/23/2017
 * Time: 06:03 PM
 */

namespace App\Http\Controllers\Backends\KPI\RencanaPengembangan;

use Illuminate\Http\Request;
use App\ApplicationServices\RencanaPengembangan\ApprovePengembanganService;
use App\ApplicationServices\RencanaPengembangan\UnapprovePengembanganService;
use App\Http\Controllers\Backends\KPI\Realisasi\RealisasiIndividuController;
use App\Notifications\NotifikasiPengembangan;

class ApprovalController extends RealisasiIndividuController
{
    /**
     * @param Request $request
     * @param ApprovePengembanganService $approvePengembanganService
     * @return \Illuminate\Http\JsonResponse
     */
    public function approve(Request $request, ApprovePengembanganService $approvePengembanganService)
    {
        $result = $approvePengembanganService->call($request->except('_token'), $request->user());
        if ($result['status']) {
            try {
                foreach ($request->get('id') as $id) {
                    $bawahan = $this->rencanaKPIRepository->findKaryawanById($id)->karyawan;
                    $bawahan->notify(new NotifikasiPengembangan($request->id, 'approved', $request->user()));
                }
                flash()->success('Rencana pengembangan telah berhasil di Approve')->important();
                return response()->json($result);
            } catch (\Exception $e) {
                flash()->success('Rencana pengembangan telah berhasil di Approve')->important();
                return response()->json($result);
            }
        }
        flash()->error($result['errors'])->important();
        return response()->json($result);
    }

    /**
     * @param Request $request
     * @param UnapprovePengembanganService $unapprovePengembanganService
     * @return \Illuminate\Http\JsonResponse
     */
    public function unapprove(Request $request, UnapprovePengembanganService $unapprovePengembanganService)
    {
        $result =$unapprovePengembanganService->call($request->except('_token'), $request->user());
        if ($result['status']) {
            try {
                foreach ($request->get('id') as $id) {
                    $bawahan = $this->realisasiKPIRepository->findKaryawanById($id)->karyawan;
                    $bawahan->notify(new NotifikasiPengembangan($request->id, 'unapproved', $request->user()));
                }
                flash()->success('Rencana pengembangan telah berhasil di Unapprove')->important();
                return response()->json($result);
            } catch (\Exception $e) {
                flash()->success('Rencana pengembangan telah berhasil di Unapprove')->important();
                return response()->json($result);
            }
        }
        flash()->error($result['errors'])->important();
        return response()->json($result);
    }
}
