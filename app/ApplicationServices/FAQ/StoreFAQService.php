<?php

namespace App\ApplicationServices;

use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use App\Domain\FAQ\Entities\FAQ;
use App\Domain\User\Entities\User;
use App\Infrastructures\Repositories\FAQ\FAQRepository;
use Ramsey\Uuid\Uuid;

class StoreFAQService extends ApplicationService
{
    /**
     * @var FAQRepository
     */
    protected $FAQRepository;

    /**
     * StoreFAQService constructor.
     * @param FAQRepository $FAQRepository
     */
    public function __construct(FAQRepository $FAQRepository)
    {
        $this->FAQRepository = $FAQRepository;
    }

    /**
     * @param array $data
     * @param User $ask
     * @param User|null $answer
     * @return array
     */
    public function call(array $data, User $ask, User $answer=null)
    {
        DB::beginTransaction();
        try {
            // store data
            $this->isOpenSession($ask);
            $faq = new FAQ();
            $faq->ID = Uuid::uuid4();
            $faq->Question = $data['Question'];
            $faq->Answer = isset($data['Answer'])?$data['Answer']:null;
            $faq->Aktif  = isset($data['Aktif'])?$data['Aktif']:false;
            $faq->AskedBy= $ask->NPK;
            $faq->AskedOn= Carbon::now();
            $faq->AnsweredBy= isset($answer)?$answer->NPK:null;
            $faq->AnsweredOn= isset($answer)?Carbon::now():null;
            $faq->save();
            DB::commit();
            return $this->successResponse();
        } catch (\Exception $e) {
            DB::rollback();
            return $this->errorResponse($e->getMessage());
        }
    }

    /**
     * Avoid spamming question in FAQ
     * @param User $ask
     * @return bool
     */
    public function isOpenSession(User $ask)
    {
        if (isset($ask)) {
            if ($ask->UserRole->Role != 'Administrator' and !$this->FAQRepository->isActiveSession($ask->NPK)) {
                throw new \DomainException('Kesempatan bertanya Anda ditunda (dibatasi)');
            }
        }
        return true;
    }
}
