<?php
namespace App\ApplicationServices\ValidasiUnitKerja;

use Illuminate\Support\Facades\DB;
use App\ApplicationServices\ApplicationService;
use App\Domain\Realisasi\Entities\ValidasiUnitKerja;

class CancelValidasiUnitKerjaService extends ApplicationService
{
    /**
     * @var ValidasiUnitKerja
     */
    protected $validasiUnitKerja;

    /**
     * CancelValidasiUnitKerjaService constructor.
     *
     * @param ValidasiUnitKerja $validasiUnitKerja
     */
    public function __construct(ValidasiUnitKerja $validasiUnitKerja)
    {
        $this->validasiUnitKerja = $validasiUnitKerja;
    }

    /**
     * @param array $data
     * @return array
     */
    public function call(array $data)
    {
        try {
            DB::beginTransaction();
            foreach ($data['id'] as $id) {
                if (! is_null($id)) {
                    $validasi = $this->validasiUnitKerja->find($id);
                    if ($validasi->isSubmitted()) {
                        $validasi->delete();
                    }
                }
            }
            DB::commit();
            return $this->successResponse();
        } catch (\Exception $e) {
            return $this->errorResponse($e->getMessage());
        }
    }
}
