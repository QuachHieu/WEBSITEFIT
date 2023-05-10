<?php

namespace App\Http\Controllers\Admin;

use App\Models\Degree;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use App\Consts;
use App\Http\Services\ContentService;

class DegreeController extends Controller
{
    public function __construct()
    { 
      //dd(url()->full());
      $this->routeDefault  = 'degree';
      $this->viewPart = 'admin.pages.degree';
      $this->responseData['module_name'] = __('Học hàm, học vị');
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
        $this->responseData['rows'] =  ContentService::getCmsDegree($params)->paginate(Consts::DEFAULT_PAGINATE_LIMIT);

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
        $this->responseData['taxonomys'] = ContentService::getCmsDegree($params)->get();

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

        Degree::create($params);

        return redirect()->route($this->routeDefault . '.index')->with('successMessage', __('Add new successfully!'));
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Degree  $degree
     * @return \Illuminate\Http\Response
     */
    public function show(Degree $degree)
    {
        return redirect()->back();
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Degree  $degree
     * @return \Illuminate\Http\Response
     */
    public function edit(Degree $degree)
    {
        if(ContentService::checkRole($this->routeDefault,'edit') == 0){
            $this->responseData['module_name'] = __('Bạn không có quyền truy cập chức năng này');
            return $this->responseView($this->viewPart . '.404');
        }
        $params['status'] = Consts::TAXONOMY_STATUS['active'];
        $this->responseData['taxonomys'] = ContentService::getCmsDegree($params)->get();
        $this->responseData['detail'] = $degree;

        return $this->responseView($this->viewPart . '.edit');
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Degree  $degree
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Degree $degree)
    {
        $request->validate([
            'title' => 'required|max:255',
        ]);

        $params = $request->all();

        $params['admin_updated_id'] = Auth::guard('admin')->user()->id;

        $degree->fill($params);
        $degree->save();

        return redirect()->back()->with('successMessage', __('Successfully updated!'));
    }
    

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Degree  $degree
     * @return \Illuminate\Http\Response
     */
    public function destroy(Degree $degree)
    {
        if(ContentService::checkRole($this->routeDefault,'delete') == 0){
            $this->responseData['module_name'] = __('Bạn không có quyền truy cập chức năng này');
            return $this->responseView($this->viewPart . '.404');
        }
       
        $degree->delete();

        // Update delete status su

        return redirect()->back()->with('successMessage', __('Delete record successfully!'));
    }
}
