<?php
/**
 * Created by PhpStorm.
 * User: SENA
 * Date: 09/08/2017
 * Time: 02:29 PM
 */

namespace App\ApplicationServices\FAQ;

use Illuminate\Support\Facades\DB;
use App\ApplicationServices\ApplicationService;
use App\Domain\FAQ\Entities\FAQ;

class DeleteFAQService extends ApplicationService
{
    /**
     * @var FAQ
     */
    protected $FAQ;

    /**
     * UpdateFAQService constructor.
     * @param FAQ $FAQ
     * @internal param FAQ $FAQ
     */
    public function __construct(FAQ $FAQ)
    {
        $this->FAQ= $FAQ;
    }

    /**
     * @param $id
     * @return array
     */
    public function call($id)
    {
        DB::beginTransaction();
        $faq = $this->FAQ->find($id);
        try {
            // store data
            $faq->delete();
            DB::commit();
            return $this->successResponse();
        } catch (\Exception $e) {
            DB::rollback();
            return $this->errorResponse($e->getMessage());
        }
    }
}
