<?php
/**
 * Created by PhpStorm.
 * User: SENA
 * Date: 09/07/2017
 * Time: 08:57 PM
 */

namespace App\Http\Controllers\Backends;

use Illuminate\Http\Request;

use App\ApplicationServices\Profile\UpdatePasswordService;
use App\Http\Controllers\Controller;
use App\Http\Requests\UpdateProfileRequest;

class ProfileController extends Controller
{
    /**
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index(Request $request)
    {
        $data['user'] = $request->user();
        $data['karyawan'] = $request->user()->karyawan;
        $data['organization'] = $data['karyawan']->organization;
        $data['position'] = $data['organization']->position;
        $data['unitkerja'] = $data['position']->unitkerja;
        $data['role']= implode(' & ', $request->user()->roles->wherenotin('Role', 'Karyawan')->pluck('Role')->toArray());
        return view('backends.profile.index', compact('data'));
    }

    /**
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function edit(Request $request)
    {
        $data['karyawan'] = $request->user()->karyawan;
        return view('backends.profile.settings.edit', compact('data'));
    }

    /**
     * @param UpdateProfileRequest $request
     * @param UpdatePasswordService $updatePasswordService
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function update(UpdateProfileRequest $request, UpdatePasswordService $updatePasswordService)
    {
        $result = $updatePasswordService->call($request->except('_token'), $request->user());
        if ($result['status']) {
            flash()->success('Password telah diperbarui')->important();
            return redirect()->route('profile');
        }
        flash()->error($result['errors'])->important();
        return redirect()->back();
    }
}
