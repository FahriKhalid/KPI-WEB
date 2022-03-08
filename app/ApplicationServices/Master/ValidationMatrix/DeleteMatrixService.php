<?php
namespace App\ApplicationServices\Master\ValidationMatrix;

use App\ApplicationServices\ApplicationService;
use App\Infrastructures\Repositories\Master\ValidationMatrixRepository;

class DeleteMatrixService extends ApplicationService
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
     * @return array
     */
    public function call($id)
    {
        try {
            $matrix = $this->matrixRepository->findById($id);
            $matrix->delete();
            return $this->successResponse();
        } catch (\Exception $e) {
            return $this->errorResponse($e->getMessage());
        }
    }
}
