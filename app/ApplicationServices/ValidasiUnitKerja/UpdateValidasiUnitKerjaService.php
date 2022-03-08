<?php
namespace App\ApplicationServices\ValidasiUnitKerja;

use App\ApplicationServices\ApplicationService;
use App\Domain\Realisasi\Entities\ValidasiUnitKerja;

class UpdateValidasiUnitKerjaService extends ApplicationService
{
    /**
     * @param array $data
     * @return array
     */
    public function call(array $data)
    {
        try {
            $validasi = ValidasiUnitKerja::where('ID', $data['IDValidasiUnitKerja'])->first();
            $validasi->isEditable();
            $validasi->ValidasiUnitKerja = $data['ValidasiUnitKerja'];
            $validasi->save();
            return $this->successResponse();
        } catch (\Exception $e) {
            return $this->errorResponse($e->getMessage());
        }
    }
}
