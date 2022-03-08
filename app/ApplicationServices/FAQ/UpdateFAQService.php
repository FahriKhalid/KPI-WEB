<?php
/**
 * Created by PhpStorm.
 * User: SENA
 * Date: 09/08/2017
 * Time: 02:30 PM
 */

namespace App\ApplicationServices\FAQ;

use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use App\ApplicationServices\ApplicationService;
use App\Domain\FAQ\Entities\FAQ;
use App\Domain\User\Entities\User;

class UpdateFAQService extends ApplicationService
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
     * @param array $data
     * @param User|null $ask
     * @param User|null $answer
     * @return array
     * @internal param User $ask
     */
    public function call($id, array $data, User $ask = null, User $answer)
    {
        DB::beginTransaction();
        $data = $this->avoidEmptyActive($data);
        $faq = $this->FAQ->find($id);
        try {
            // store data
            $faq->Question = $data['Question'];
            $faq->Answer = isset($data['Answer'])?$data['Answer']:$faq->Answer;
            $faq->Aktif  = isset($data['Aktif'])?$data['Aktif']=='1'?true:false:$faq->Aktif;
            $faq->AskedBy= isset($ask)?$ask->NPK:null;
            $faq->AskedOn= isset($ask)?Carbon::now():null;
            $faq->AnsweredBy= $answer->NPK;
            $faq->AnsweredOn= Carbon::now();
            $faq->save();
            DB::commit();
            return $this->successResponse();
        } catch (\Exception $e) {
            DB::rollback();
            return $this->errorResponse($e->getMessage());
        }
    }

    /**
     * avoid empty answer ond active status
     * @param $data
     * @return mixed
     */
    public function avoidEmptyActive($data)
    {
        if (isset($data['Answer']) and isset($data['Aktif'])) {
            if ($data['Answer']==null and $data['Aktif']==true) {
                throw new \DomainException('FAQ tidak dapat diaktifkan sebelum pertanyaan dijawab');
            }
        }
        return $data;
    }
}
