<?php
/**
 * Created by PhpStorm.
 * User: SENA
 * Date: 09/24/2017
 * Time: 12:45 PM
 */

namespace App\Http\Controllers\Backends;

use Illuminate\Support\Facades\Auth;
use App\ApplicationServices\Master\User\UpdateUser;
use App\ApplicationServices\Notification\DeleteNotificationService;
use App\ApplicationServices\Notification\UpdateNotificationService;
use App\Domain\Notification\Notification;
use App\Http\Controllers\Controller;

class NotificationController extends Controller
{
    public function get(DeleteNotificationService $deleteNotificationService)
    {
        $deleteNotificationService->callcascade();
        $item = auth()->user()->karyawan->notifications->all();
        $data = [
            'all' => [],
            'notif' => count(auth()->user()->karyawan->notifications),
            'unread' => count(auth()->user()->karyawan->unreadNotifications)
        ];
        if (!empty($item)) {
            foreach ($item as $value) {
                $_="";
                $data_ =(object)($value->data);
                $basic = "Dokumen ".$data_->JenisKPI." NPK :".$data_->Bawahan;
                if ($data_->Status=="registered") {
                    $_ = $basic." menunggu konfirmasi.";
                }
                if ($data_->Status=="unregistered") {
                    $_ = $basic."<b> membatalkan</b> registrasi.";
                }
                if ($data_->Status=="confirmed") {
                    $_ = $basic." menunggu persetujuan.";
                }
                if ($data_->Status=="unconfirmed") {
                    $_ = $basic."<b> membatalkan</b> konfirmasi.";
                }
                if ($data_->Status=="approved") {
                    $_ = $basic." telah disetujui.";
                }
                if ($data_->Status=="unapproved") {
                    $_ = $basic."<b> tidak</b> disetujui.";
                }
                if ($data_->Status=="pending") {
                    $_ = $basic." menunggu persetujuan <span style='color:red'>Administrator</span>";
                }
                if ($data_->Status=="rejected") {
                    $_ = $basic."<b> tidak</b> disetujui.";
                }
                if ($data_->Status=="canceled") {
                    $_ = $basic."<b> ditunda</b>.";
                }
                $data['all'][]=['id'=>$value->id,'data'=>$_,'read_at'=>$value->read_at];
            }
        }
        return response()->json($data);
    }

    /**
     * @param $id
     * @param UpdateNotificationService $notificationService
     * @param UpdateUser $updateUser
     * @return \Illuminate\Contracts\Routing\UrlGenerator|\Illuminate\Http\RedirectResponse|string
     */
    public function markAsRead($id, UpdateNotificationService $notificationService, UpdateUser $updateUser)
    {
        $result = $notificationService->call($id);
        if ($result['status']) {
            $data = Notification::find($id)->data();
            if ($data->JenisKPI == "kamus" and $data->Status == "pending" and in_array(1, Auth::user()->Roles->pluck('ID')->toArray())) {
                $updateUser->activatePrivilege(Auth::user()->ID, ['IDRole'=>'1']);
            }
            return redirect(url(is_array($data->URL)?$data->URL[0]:$data->URL));
        }
        flash()->error($result['errors'])->important();
        return redirect()->back();
    }
}
