<?php

namespace App\Http\Controllers\Admin;

use App\Models\CmsFunction;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use App\Consts;
use App\Http\Services\ContentService;
use Symfony\Polyfill\Intl\Idn\Resources\unidata\Regex;

class CmsFunctionController extends Controller
{
    public function __construct()
    { 
      //dd(url()->full());
      $this->routeDefault  = 'function';
      $this->viewPart = 'admin.pages.function';
      $this->responseData['module_name'] = __('Chức vụ');
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        if(ContentService::checkRole($this->routeDefault,'index') == 0){
            $this->responseData['module_name'] = __('Bạn không có quyền truy cập chức năng này');
            return $this->responseView($this->viewPart . '.404');
        }
        $params = $request->all();
        $this->responseData['rows'] =  ContentService::getCmsFunction($params)->paginate(Consts::DEFAULT_PAGINATE_LIMIT);

        return $this->responseView($this->viewPart . '.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        if(ContentService::checkRole($this->routeDefault,'create') == 0){
            $this->responseData['module_name'] = __('Bạn không có quyền truy cập chức năng này');
            return $this->responseView($this->viewPart . '.404');
        }
        $params['status'] = Consts::TAXONOMY_STATUS['active'];
        $this->responseData['taxonomys'] = ContentService::getCmsFunction($params)->get();

        return $this->responseView($this->viewPart . '.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|max:255',
        ]);

        $params = $request->all();

        $params['admin_created_id'] = Auth::guard('admin')->user()->id;
        $params['admin_updated_id'] = Auth::guard('admin')->user()->id;

        CmsFunction::create($params);

        return redirect()->route($this->routeDefault . '.index')->with('successMessage', __('Add new successfully!'));
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\CmsFunction  $cmsFunction
     * @return \Illuminate\Http\Response
     */
    public function show(CmsFunction $cmsFunction)
    {
        return redirect()->back();
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\CmsFunction  $cmsFunction
     * @return \Illuminate\Http\Response
     */
    public function edit(CmsFunction $cmsFunction)
    {
        if(ContentService::checkRole($this->routeDefault,'edit') == 0){
            $this->responseData['module_name'] = __('Bạn không có quyền truy cập chức năng này');
            return $this->responseView($this->viewPart . '.404');
        }
        $params['status'] = Consts::TAXONOMY_STATUS['active'];
        $this->responseData['taxonomys'] = ContentService::getCmsFunction($params)->get();
        $this->responseData['detail'] = $cmsFunction;

        return $this->responseView($this->viewPart . '.edit');
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\CmsFunction  $cmsFunction
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, CmsFunction $cmsFunction)
    {
        $request->validate([
            'title' => 'required|max:255',
        ]);

        $params = $request->all();

        $params['admin_updated_id'] = Auth::guard('admin')->user()->id;

        $cmsFunction->fill($params);
        $cmsFunction->save();

        return redirect()->back()->with('successMessage', __('Successfully updated!'));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\CmsFunction  $cmsFunction
     * @return \Illuminate\Http\Response
     */
    public function destroy(CmsFunction $cmsFunction)
    {
        if(ContentService::checkRole($this->routeDefault,'delete') == 0){
            $this->responseData['module_name'] = __('Bạn không có quyền truy cập chức năng này');
            return $this->responseView($this->viewPart . '.404');
        }
       

        $cmsFunction->delete();

        // Update delete status su

        return redirect()->back()->with('successMessage', __('Delete record successfully!'));
    }
}
