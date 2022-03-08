<?php
/**
 * Created by PhpStorm.
 * User: SENA
 * Date: 07/26/2017
 * Time: 10:41 PM
 */

namespace App\Http\Controllers\Backends\Master;

use Illuminate\Http\Request;
use App\ApplicationServices\Master\User\UpdateUser;
use App\ApplicationServices\Master\User\UserPrivilege\UpdateUserPrivilege;
use App\Http\Controllers\Controller;
use App\Http\Requests\Master\User\UpdateUserPrivilegeRequest;
use App\Infrastructures\Repositories\User\UserPrivilegeRepository;
use App\Infrastructures\Repositories\User\UserRepository;
use App\Infrastructures\Repositories\User\UserRoleRepository;
use Yajra\Datatables\Datatables;

class UserPrivilegeController extends Controller
{
    /**
     * @var UserRepository
     */
    protected $userRepository;

    /**
     * @var UserRoleRepository
     */
    protected $userRoleRepository;

    /**
     * @var UserPrivilegeRepository
     */
    protected $userPrivilegeRepository;

    /**
     * UserController constructor.
     *
     * @param UserRepository $userRepository
     * @param UserRoleRepository $userRoleRepository
     * @param UserPrivilegeRepository $userPrivilegeRepository
     */
    public function __construct(UserRepository $userRepository, UserRoleRepository $userRoleRepository, UserPrivilegeRepository $userPrivilegeRepository)
    {
        $this->userRepository = $userRepository;
        $this->userRoleRepository = $userRoleRepository;
        $this->userPrivilegeRepository = $userPrivilegeRepository;
    }

    /**
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index(Request $request)
    {
        if ($request->ajax()) {
            return Datatables::eloquent($this->userRepository->datatable())
                ->setRowId('ID')
                ->addColumn('Aksi', 'backends.master.hakakses.actionbuttons')
                ->rawColumns(['Aksi'])
                ->make(true);
        }
       // $data['roles'] = $this->userRoleRepository->all()->whereNotIn('ID',3);
        return view('backends.master.hakakses.index');
    }

    /**
     * @param $id
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function edit($id)
    {
        $data['roles'] = $this->userRoleRepository->all()->whereNotIn('ID', 3);
        $data['user'] = $this->userRepository->findById($id);
        $data['privilege'] = $this->userPrivilegeRepository->findById($id)->pluck('IDRole')->toArray();
        return view('backends.master.hakakses.edit', compact('data'));
    }

    /**
     * @param $id
     * @param UpdateUserPrivilegeRequest $request
     * @param UpdateUser $updateUserService
     * @param UpdateUserPrivilege $updateUserPrivilege
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update($id, UpdateUserPrivilegeRequest $request, UpdateUser $updateUserService, UpdateUserPrivilege $updateUserPrivilege)
    {
        $result = $updateUserService->call($id, $request->except(['_token', '_method']));
        $resultPrivilege = $updateUserPrivilege->call($id, $this->privileges($request->IDRole));
        if ($result['status']&&$resultPrivilege['status']) {
            flash()->success('Hak Akses user telah berhasil diperbarui')->important();
            return redirect()->route('backend.master.user.privilege');
        }
        flash()->error($result['errors'])->important();
        flash()->error($resultPrivilege['errors'])->important();
        return redirect()->back();
    }

    /**
     * @param $IDRoles
     * @return array
     * @internal param $ide
     */
    public function privileges($IDRoles)
    {
        $IDRole = ['3'];
        if (isset($IDRoles)) {
            $IDRole = array_merge($IDRoles, $IDRole);
        }
        return $IDRole;
    }
}
