<?php

namespace App\Http\Controllers\Backends\Master;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Infrastructures\Repositories\Karyawan\OrganizationalAssignmentRepository;
use Yajra\Datatables\Datatables;

class OrganizationalAssignmentController extends Controller
{
    /**
     * @var KaryawanRepository
     */
    protected $organizationalAssignmentRepository;

    /**
     * KaryawanController constructor.
     *
     * @param KaryawanRepository $karyawanRepository
     */
    public function __construct(OrganizationalAssignmentRepository $organizationalAssignmentRepository)
    {
        $this->organizationalAssignmentRepository = $organizationalAssignmentRepository;
    }

    /**
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index(Request $request)
    {
        if ($request->ajax()) {
            return Datatables::of($this->organizationalAssignmentRepository->datatable())->make(true);
        }
        return view('backends.master.organizationalAssignment.index');
    }
}
