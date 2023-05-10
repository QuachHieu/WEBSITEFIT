<?php

namespace App\Http\Controllers\Admin;

use App\Consts;
use App\Http\Services\ContentService;
use App\Models\Admin;
use App\Models\Event;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class EventController extends Controller
{   
    public function __construct()
    {
        $this->routeDefault  = 'events';
        $this->viewPart = 'admin.pages.events';
        $this->responseData['module_name'] = __('Event Management');
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
        $rows = ContentService::getEvent($params)->paginate(Consts::DEFAULT_PAGINATE_LIMIT);
        $paramTaxonomys['status'] = Consts::TAXONOMY_STATUS['active'];
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
    public function create(Request $request)
    {   
        if(ContentService::checkRole($this->routeDefault,'create') == 0){
            $this->responseData['module_name'] = __('Bạn không có quyền truy cập chức năng này');
            return $this->responseView($this->viewPart . '.404');
          }
        $paramTaxonomys['status'] = Consts::TAXONOMY_STATUS['active'];  
        $admins = Admin::where('status', '=', Consts::USER_STATUS['active'])->orderByRaw('status ASC, id DESC')->get();
        $this->responseData['members'] = $admins;
        //echo $this->viewPart . '.create';die;
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

        Event::create($params);

        return redirect()->route($this->routeDefault . '.index')->with('successMessage', __('Add new successfully!'));
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Event  $event
     * @return \Illuminate\Http\Response
     */
    public function show(Event $event)
    {
        return redirect()->back();
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Event  $event
     * @return \Illuminate\Http\Response
     */
    public function edit(Event $event)
    {   
        if(ContentService::checkRole($this->routeDefault,'edit') == 0){
            $this->responseData['module_name'] = __('Bạn không có quyền truy cập chức năng này');
            return $this->responseView($this->viewPart . '.404');
          }
        $paramTaxonomys['status'] = Consts::TAXONOMY_STATUS['active'];
        $admins = Admin::where('status', '=', Consts::USER_STATUS['active'])->orderByRaw('status ASC, id DESC')->get();
        $this->responseData['members'] = $admins;
        $this->responseData['detail'] = $event;

        return $this->responseView($this->viewPart . '.edit');
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Event  $event
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Event $event)
    {
        $request->validate([
            'title' => 'required|max:255'
        ]);

        $params = $request->all();

        $params['admin_updated_id'] = Auth::guard('admin')->user()->id;

        $event->fill($params);
        $event->save();

        return redirect()->back()->with('successMessage', __('Successfully updated!'));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Event  $event
     * @return \Illuminate\Http\Response
     */
    public function destroy(Event $event)
    {   
        if(ContentService::checkRole($this->routeDefault,'delete') == 0){
            $this->responseData['module_name'] = __('Bạn không có quyền truy cập chức năng này');
            return $this->responseView($this->viewPart . '.404');
          }
        $event->status = Consts::STATUS_DELETE;
        $event->save();

        return redirect()->route($this->routeDefault . '.index')->with('successMessage', __('Delete record successfully!'));
    }
}
