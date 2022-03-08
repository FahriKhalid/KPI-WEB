<?php

namespace App\ApplicationServices\Artikel;
use Illuminate\Support\Facades\DB;
use App\ApplicationServices\ApplicationService;
use App\Domain\ArtikelBeranda\Entities\Artikel;

class StoreArtikelService extends ApplicationService
{
    /**
     * @param array $data
     * @return array
     */
    public function call(array $data)
    {
        DB::beginTransaction();
        try {
            $narration = Artikel::first();
            if (empty($narration)) {
                Artikel::create(['Content' => $data['Content']]);
            } else {
                $narration->update(['Content' => $data['Content']]);
            }
            DB::commit();
            return $this->successResponse();
        } catch (\Exception $e) {
            DB::rollback();
            return $this->errorResponse($e->getMessage());
        }
    }
}
