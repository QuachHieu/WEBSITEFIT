<?php

namespace App\Http\Controllers\Admin;

use App\Consts;
use App\Http\Services\ContentService;
//use App\Http\Controllers\Controller;
use App\Models\Introduction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class IntroductionController extends Controller
{
    public function __construct()
    {
        $this->routeDefault  = 'introduction';
        $this->viewPart = 'admin.pages.introduction';
        $this->responseData['module_name'] = __('Introduction Management');
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        //echo "AAAAAAAA";die;
        $params = $request->all();
        $params['is_type'] = Consts::POST_TYPE['intro'];
        // Get list post with filter params
        $rows = ContentService::getCmsPost($params)->paginate(Consts::DEFAULT_PAGINATE_LIMIT);
        $paramTaxonomys['status'] = Consts::TAXONOMY_STATUS['active'];
        $paramTaxonomys['taxonomy'] = Consts::CATEGORY['intro'];
        $this->responseData['parents'] = ContentService::getCmsTaxonomy($paramTaxonomys)->get();

        $this->responseData['rows'] =  $rows;
        $this->responseData['params'] = $params;
        $this->responseData['booleans'] = Consts::TITLE_BOOLEAN;
        $this->responseData['postStatus'] = Consts::STATUS;

        return $this->responseView($this->viewPart . '.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $paramTaxonomys['status'] = Consts::TAXONOMY_STATUS['active'];
        $paramTaxonomys['taxonomy'] = Consts::CATEGORY['intro'];
        $this->responseData['parents'] = ContentService::getCmsTaxonomy($paramTaxonomys)->get();

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
            'taxonomy_id' => 'required|max:255',
        ]);

        $params = $request->all();

        $params['is_type'] = Consts::POST_TYPE['intro'];
        //$params['brief'] = $params['json_params']['brief']['vi'];
        //$params['content'] = $params['json_params']['content']['vi'];
        $params['admin_created_id'] = Auth::guard('admin')->user()->id;
        $params['admin_updated_id'] = Auth::guard('admin')->user()->id;
        $params['url_part'] = Str::slug( $params['title']);

        Introduction::create($params);

        return redirect()->route($this->routeDefault . '.index')->with('successMessage', __('Add new successfully!'));
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Introduction  $introduction
     * @return \Illuminate\Http\Response
     */
    public function show(Introduction $introduction)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Introduction  $introduction
     * @return \Illuminate\Http\Response
     */
    public function edit(Introduction $introduction)
    {
        $paramTaxonomys['status'] = Consts::TAXONOMY_STATUS['active'];
        $paramTaxonomys['taxonomy'] = Consts::CATEGORY['intro'];
        $this->responseData['parents'] = ContentService::getCmsTaxonomy($paramTaxonomys)->get();
        $this->responseData['detail'] = $introduction;

        return $this->responseView($this->viewPart . '.edit');
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Introduction  $introduction
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Introduction $introduction)
    {
        $request->validate([
            'title' => 'required|max:255',
            'taxonomy_id' => 'required|max:255',
        ]);

        $params = $request->all();

        //dd($params['json_params']['brief']['vi']);

        //$params['brief'] = $params['json_params']['brief']['vi'];
        //$params['content'] = $params['json_params']['content']['vi'];
        $params['admin_updated_id'] = Auth::guard('admin')->user()->id;
        $params['url_part'] = Str::slug( $params['title']);

        $introduction->fill($params);
        $introduction->save();

        return redirect()->back()->with('successMessage', __('Successfully updated!'));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Introduction  $introduction
     * @return \Illuminate\Http\Response
     */
    public function destroy(Introduction $introduction)
    {
        if ($introduction->is_type != Consts::POST_TYPE['intro']) {
            return redirect()->back()->with('errorMessage', __('Permission denied!'));
        }

        $introduction->status = Consts::STATUS_DELETE;
        $introduction->save();

        return redirect()->route($this->routeDefault . '.index')->with('successMessage', __('Delete record successfully!'));
    }
}
