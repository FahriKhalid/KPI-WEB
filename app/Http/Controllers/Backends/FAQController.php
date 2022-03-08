<?php

namespace App\Http\Controllers\Backends;

use Illuminate\Http\Request;
use App\ApplicationServices\FAQ\DeleteFAQService;
use App\ApplicationServices\FAQ\UpdateFAQService;
use App\ApplicationServices\StoreFAQService;
use App\Http\Controllers\Controller;
use App\Http\Requests\FAQ\StoreFAQRequest;
use App\Http\Requests\FAQ\UpdateFAQRequest;
use App\Infrastructures\Repositories\FAQ\FAQRepository;

class FAQController extends Controller
{
    /**
     * @var FAQRepository
     */
    protected $FAQRepository;

    /**
     * FAQController constructor.
     * @param FAQRepository $FAQRepository
     */
    public function __construct(FAQRepository $FAQRepository)
    {
        $this->FAQRepository = $FAQRepository;
    }

    /**
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index(Request $request)
    {
        $data = $this->getSupportData($request);
        return view('backends.faq.index', compact('data'));
    }

    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function create()
    {
        return view('backends.faq.create');
    }
    /**
     * @param StoreFAQRequest $FAQRequest
     * @param StoreFAQService $storeFAQService
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(StoreFAQRequest $FAQRequest, StoreFAQService $storeFAQService)
    {
        $result = $storeFAQService->call($FAQRequest->all(), $FAQRequest->user(), $FAQRequest->user());
        if ($result['status']) {
            flash()->success('FAQ anda terkirim.')->important();
            return redirect()->route('faq.index');
        }
        flash()->error('FAQ gagal dikirim. '.$result['errors'])->important();
        return redirect()->back();
    }

    /**
     * @param $id
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function edit($id)
    {
        $data = $this->FAQRepository->findById($id);
        return view('backends.faq.edit', compact('data'));
    }

    /**
     * @param $id
     * @param UpdateFAQRequest $updateFAQRequest
     * @param UpdateFAQService $updateFAQService
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update($id, UpdateFAQRequest $updateFAQRequest, UpdateFAQService $updateFAQService)
    {
        $result = $updateFAQService->call($id, $updateFAQRequest->except(['_token', '_method']), null, $updateFAQRequest->user());
        if ($result['status']) {
            flash()->success('FAQ anda terupdate.')->important();
            return redirect()->route('faq.index');
        }
        flash()->error('FAQ gagal diupdate. '.$result['errors'])->important();
        return redirect()->back();
    }

    /**
     * @param $id
     * @param Request $request
     * @param DeleteFAQService $deleteFAQService
     * @return \Illuminate\Http\RedirectResponse
     */
    public function delete($id, Request $request, DeleteFAQService $deleteFAQService)
    {
        $result = $deleteFAQService->call($id);
        $data = $this->getSupportData($request);
        if ($result['status']) {
            flash()->success('Data FAQ dihapus.')->important();
            return response()->json($result);
        }
        flash()->error('Data FAQ gagal dihapus. '.$result['errors'])->important();
        return response()->json($result, 500);
    }
    /**
     * @param Request $request
     * @return mixed
     */
    protected function getSupportData(Request $request)
    {
        $data['user']=$request->user();
        if (isset($data['user'])) {
            if ($data['user']->UserRole->Role === 'Administrator') {
                $data['faq'] = $this->FAQRepository->getAll();
            } else {
                $data['faq'] = $this->FAQRepository->findByNPK($request->user()->NPK);
            }
        } else {
            $data['faq'] = $this->FAQRepository->findAllAnsweredAndActive();
        }
        return $data;
    }
}
