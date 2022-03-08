<?php
/**
 * Created by PhpStorm.
 * User: SENA
 * Date: 09/05/2017
 * Time: 11:32 AM
 */

namespace App\ApplicationServices\Notification;

use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use App\ApplicationServices\ApplicationService;
use App\Domain\Notification\Notification;

class UpdateNotificationService extends ApplicationService
{
    /**
     * @var Notification
     */
    protected $notification;

    /**
     * UpdateNotificationService constructor.
     * @param Notification $notification
     */
    public function __construct(Notification $notification)
    {
        $this->notification = $notification;
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
            $stack->read_at == null? $stack->read_at = Carbon::now() : true;
            //DB::update('update notifications set read_at = IF(read_at IS NULL, NOW(), read_at) where id = :id', ['id' => $id]);
            $stack->update();
            DB::commit();
            return $this->successResponse();
        } catch (\Exception $e) {
            DB::rollBack();
            return $this->errorResponse($e);
        }
    }
}
