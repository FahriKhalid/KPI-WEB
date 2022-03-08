<?php
namespace App\ApplicationServices\Master\ValidationMatrix;

use Carbon\Carbon;
use App\ApplicationServices\ApplicationService;
use App\Exceptions\DomainException;
use App\Infrastructures\Repositories\Master\ValidationMatrixRepository;

class UpdateMatrixService extends ApplicationService
{
    /**
     * @var ValidationMatrixRepository
     */
    protected $matrixRepository;

    /**
     * UpdateMatrixService constructor.
     *
     * @param ValidationMatrixRepository $validationMatrixRepository
     */
    public function __construct(ValidationMatrixRepository $validationMatrixRepository)
    {
        $this->matrixRepository = $validationMatrixRepository;
    }

    /**
     * @param $id
     * @param array $data
     * @param $user
     * @return array
     */
    public function call($id, array $data, $user)
    {
        try {
            $this->checkExist($id, $data);
            $matrix = $this->matrixRepository->findById($id);
            $matrix->KodeUnitKerja = $data['KodeUnitKerja'];
            $matrix->KodeUnitKerjaPenilai = $data['KodeUnitKerjaPenilai'];
            $matrix->Keterangan = (! empty($data['Keterangan'])) ? $data['Keterangan'] : null;
            $matrix->UpdatedBy = $user->NPK;
            $matrix->UpdatedOn = Carbon::now(config('app.timezone'));
            $matrix->save();
            return $this->successResponse();
        } catch (\Exception $e) {
            return $this->errorResponse($e->getMessage());
        }
    }

    /**
     * @param $id
     * @param array $data
     * @throws DomainException
     */
    public function checkExist($id, array $data)
    {
        $exists = $this->matrixRepository->checkExist($data['KodeUnitKerja'], $data['KodeUnitKerjaPenilai'], $id);
        if ($exists) {
            throw new DomainException('Matriks Unit Kerja yang dimasukkan telah ada dalam database.');
        }
    }
}
