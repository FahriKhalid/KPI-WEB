<?php
namespace App\Infrastructures\Repositories\Karyawan;

use App\Domain\Karyawan\Entities\OrganizationalAssignment;

class OrganizationalAssignmentRepository
{
    /**
     * @var OrganizationalAssignment
     */
    protected $model;

    /**
     *
     * @param OrganizationalAssignment $organizationalAssignment
     */
    public function __construct(OrganizationalAssignment $organizationalAssignment)
    {
        $this->model = $organizationalAssignment;
    }

    /**
     * @return mixed
     */
    public function datatable()
    {
        return $this->model->with(['position','position.unitkerja','karyawan'])->select('View_OrganizationalAssignment.*');
    }
}
