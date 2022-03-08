<?php
/**
 * Created by PhpStorm.
 * User: SENA
 * Date: 09/05/2017
 * Time: 11:32 AM
 */

namespace App\ApplicationServices\Notification;

use Illuminate\Support\Facades\DB;
use App\ApplicationServices\ApplicationService;
use App\Domain\Notification\Notification;
use App\Infrastructures\Repositories\KPI\KPIGlossariumRepository;

class DeleteNotificationService extends ApplicationService
{
    /**
     * @var Notification
     */
    protected $notification;
    /**
     * @var KPIGlossariumRepository
     */
    protected $KPIglossarium;

    /**
     * UpdateNotificationService constructor.
     * @param Notification $notification
     * @param KPIGlossariumRepository $KPIglossarium
     */
    public function __construct(Notification $notification, KPIGlossariumRepository $KPIglossarium)
    {
        $this->notification = $notification;
        $this->KPIglossarium = $KPIglossarium;
    }

    /**
     * @param $id
     * @return array
     */
    public function call($id)
    {
        DB::beginTransaction();
        try {
            $stack = $this->notification->find($id);
            $stack->delete();
            DB::commit();
            return $this->successResponse();
        } catch (\Exception $e) {
            DB::rollBack();
            return $this->errorResponse($e);
        }
    }

    /**
     * Clean notification that status has been updated
     */
    public function callcascade()
    {
        $all = auth()->user()->karyawan->notifications->all();
        if (!empty($all)) {
            foreach ($all as $value) {
                $data_ =(object)($value->data);
                $_ = $this->KPIglossarium->findById($data_->JenisKPI, $data_->IDHeader);
                $status = $data_->Status=='unregistered' || $data_->Status=='unconfirmed' || $data_->Status=='unapproved' ?'draft': $data_->Status;
                if (isset($_->statusdokumen->StatusDokumen)?strtolower($_->statusdokumen->StatusDokumen)!==$status?true:false:false) {
                    $this->call($value->id);
                } elseif (isset($_->Status)?$_->Status!==$status?true:false:false) {
                    $this->call($value->id);
                }
            }
            $this->callcascadelimit();
        }
    }

    /**
     * delete 2 oldest if limit exceeded
     */
    public function callcascadelimit()
    {
        if (count(auth()->user()->karyawan->notifications)-count(auth()->user()->karyawan->unreadNotifications)>20
            or count(auth()->user()->karyawan->unreadNotifications)>30) {
            $this->notification->where('notifiable_id', auth()->user()->karyawan->NPK)->orderBy('created_at')->limit(2)->delete();
        }
    }
}
