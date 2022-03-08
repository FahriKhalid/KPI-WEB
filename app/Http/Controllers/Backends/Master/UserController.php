<?php

namespace App\Http\Controllers\Backends\Master;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\ApplicationServices\Master\User\CreateUser;
use App\ApplicationServices\Master\User\DeleteUser;
use App\ApplicationServices\Master\User\UpdateUser;
use App\ApplicationServices\Master\User\UserPrivilege\CreateUserPrivilege;
use App\ApplicationServices\Master\User\UserPrivilege\DeleteUserPrivilege;
use App\Http\Controllers\Controller;
use App\Http\Requests\Master\User\StoreUserRequest;
use App\Http\Requests\Master\User\UpdateUserRequest;
use App\Infrastructures\Repositories\User\UserPrivilegeRepository;
use App\Infrastructures\Repositories\User\UserRepository;
use App\Infrastructures\Repositories\User\UserRoleRepository;
use Yajra\Datatables\Datatables;

use App\Domain\karyawan\Entities\KaryawanLeader;
use App\Domain\karyawan\Entities\Karyawan;

class UserController extends Controller
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
    public function __construct(
        UserRepository $userRepository,
        UserRoleRepository $userRoleRepository,
        UserPrivilegeRepository $userPrivilegeRepository
    ) {
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
                    ->addColumn('Aksi', 'backends.master.user.actionbuttons')
                    // ->addColumn('Nama', function($data){
                    //     return $data->karyawan->NamaKaryawan;
                    // })
                    ->editColumn('LdapActive', function ($user) {
                        return ($user->Ldap_active == 1) ? '<span class="label label-success"> Ya </span>' : '<span class="label label-default"> Tidak </span>';
                    })
                    ->rawColumns(['Aksi', 'LdapActive'])
                    ->make(true);
        }
        return view('backends.master.user.index');
    }

    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function create()
    {
        $data['roles'] = $this->userRoleRepository->all()->whereNotIn('ID', 3);
        return view('backends.master.user.create', compact('data'));
    }

    /**
     * @param StoreUserRequest $request
     * @param CreateUser $createUserService
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(StoreUserRequest $request, CreateUser $createUserService)
    {
        $result = $createUserService->call($request->except('_token'));
        if ($result['status']) {
            flash()->success('Data user telah berhasil ditambahkan')->important();
            return redirect()->route('backend.master.user');
        }
        $request->flashExcept(['password', 'password_confirmation', '_token']);
        flash()->error($result['errors'])->important();
        return redirect()->back();
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
        return view('backends.master.user.edit', compact('data'));
    }

    /**
     * @param $id
     * @param UpdateUserRequest $request
     * @param UpdateUser $updateUserService
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update($id, UpdateUserRequest $request, UpdateUser $updateUserService)
    {
        $result = $updateUserService->call($id, $request->except(['_token', '_method']));
        if ($result['status']) {
            flash()->success('Data user telah berhasil diperbarui')->important();
            return redirect()->route('backend.master.user');
        }
        flash()->error($result['errors'])->important();
        return redirect()->back();
    }

    /**
     * @param $id
     * @param DeleteUser $deleteUserService
     * @param DeleteUserPrivilege $deleteUserPrivilege
     * @return \Illuminate\Http\JsonResponse
     */
    public function delete($id, DeleteUser $deleteUserService, DeleteUserPrivilege $deleteUserPrivilege)
    {
        $result = $deleteUserService->call($id);
        $resultPrivilege = $deleteUserPrivilege->call($id);
        if ($result['status']&&$resultPrivilege['status']) {
            flash()->success('Data user telah berhasil dihapus')->important();
            return response()->json($result);
        }
        flash()->error($result['errors'])->important();
        flash()->error($resultPrivilege['errors'])->important();
        return response()->json($result, 500);
    }
}
