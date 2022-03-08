<?php
namespace App\ApplicationServices\Master\ValidationMatrix;

use Carbon\Carbon;
use App\ApplicationServices\ApplicationService;
use App\Domain\KPI\Entities\MatrixValidation;
use App\Exceptions\DomainException;
use App\Infrastructures\Repositories\Master\ValidationMatrixRepository;
use Ramsey\Uuid\Uuid;

class StoreMatrixService extends ApplicationService
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
     * @param array $data
     * @param $user
     * @return array
     */
    public function call(array $data, $user)
    {
        try {
            $this->checkExist($data);
            $matrix = new MatrixValidation();
            $matrix->ID = Uuid::uuid4();
            $matrix->KodeUnitKerja = $data['KodeUnitKerja'];
            $matrix->KodeUnitKerjaPenilai = $data['KodeUnitKerjaPenilai'];
            $matrix->Keterangan = (! empty($data['Keterangan'])) ? $data['Keterangan'] : null;
            $matrix->CreatedBy = $user->NPK;
            $matrix->CreatedOn = Carbon::now(config('app.timezone'));
            $matrix->save();
            return $this->successResponse();
        } catch (\Exception $e) {
            return $this->errorResponse($e->getMessage());
        }
    }

    /**
     * @param array $data
     * @throws DomainException
     */
    public function checkExist(array $data)
    {
        $exists = $this->matrixRepository->checkExist($data['KodeUnitKerja'], $data['KodeUnitKerjaPenilai']);
        if ($exists) {
            throw new DomainException('Matriks Unit Kerja yang dimasukkan telah ada dalam database.');
        }
    }
}
