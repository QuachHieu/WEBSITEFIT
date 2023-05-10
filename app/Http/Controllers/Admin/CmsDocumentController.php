<?php

namespace App\Http\Controllers\Admin;

//use App\Http\Controllers\Controller;
use App\Models\CmsDocument;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Consts;
use App\Models\Admin;
use App\Models\Department;
use App\Http\Services\ContentService;
use Illuminate\Support\Arr;

class CmsDocumentController extends Controller
{

    public function __construct()
    { 
      //dd(url()->full());
      $this->routeDefault  = 'cms_document';
      $this->viewPart = 'admin.pages.document';
      $this->responseData['module_name'] = __('Danh sách nhận/gửi tài liệu');
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

        $params_cs = array();
        
        $task = $request->get('task');
        
        if($task == ''){
            $task = 'receive';
            $_REQUEST['task'] = 'receive';
        }

        if(isset($params['keyword'])){
            $params_cs['keyword'] = $params['keyword'];
        }
        if(isset($params['department_id'])){
            $params_cs['department_id'] = $params['department_id'];
        }
        $array_lable = array('0'=>'-Chọn người nhận-','1'=>'Người nhận');

        if($task == 'send'){
            $params_cs['admin_created_id'] = Auth::guard('admin')->user()->id; // Là tài liệu đã gửi
            if(isset($params['admin_receive_id'])){
                $params_cs['admin_receive_id'] = $params['admin_receive_id'];
            }
        }else{
            $array_lable = array('0'=>'-Chọn người gửi-','1'=>'Người gửi');
            $params_cs['admin_receive_id'] = Auth::guard('admin')->user()->id; // Là tài đã nhận
            if(isset($params['admin_receive_id'])){
                $params_cs['admin_created_id'] = $params['admin_receive_id']; // Admin này đã gửi cho bạn
            }
        }
        //dd($params_cs);
        $departments = Department::where('status','active')->get();
        $this->responseData['departments'] = $departments;

        $admins = Admin::where('status','active')->get();
        $this->responseData['admins'] = $admins;
        $this->responseData['array_lable'] = $array_lable;
        $this->responseData['params'] = $params;

        $rows = CmsDocument::getDocumentByAdmin($params_cs)->paginate(Consts::DEFAULT_PAGINATE_LIMIT);
        $this->responseData['rows'] = $rows;
        return $this->responseView($this->viewPart . '.index');

    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
        if(ContentService::checkRole($this->routeDefault,'create') == 0){
            $this->responseData['module_name'] = __('Bạn không có quyền truy cập chức năng này');
            return $this->responseView($this->viewPart . '.404');
        }
        
        $admins = Admin::where('status','active')->where('id','!=',Auth::guard('admin')->user()->id)->get();
        $this->responseData['admins'] = $admins;

        $departments = Department::where('status','active')->get();
        $this->responseData['departments'] = $departments;

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
        if(ContentService::checkRole($this->routeDefault,'create') == 0){
            $this->responseData['module_name'] = __('Bạn không có quyền truy cập chức năng này');
            return $this->responseView($this->viewPart . '.404');
        }
        $request->validate([
            'title' => 'required|max:255',
            'content' => 'required',
        ]);
        $params = $request->all();
        
        $title = $params['title'];
        $content = $params['content'];
        $department = isset($params['department']) ? $params['department'] : '' ;
        $user_receive = $params['user_receive'];

        if($department !=''){
            //echo "AAAAA";
            
            $admins = Admin::whereIn('department_id',$department)->where('id','!=',Auth::guard('admin')->user()->id)->get();
            foreach($admins as $admin){
                $cmsDocument = new CmsDocument();
                $cmsDocument->title = $title;
                $cmsDocument->content = $content;
                $cmsDocument->admin_created_id = Auth::guard('admin')->user()->id;
                $cmsDocument->admin_receive_id = $admin->id;
                $cmsDocument->department_id = $admin->department_id;
                $cmsDocument->date_at = null;
                $cmsDocument->save();
            }

        }else{
            //echo "BBBBB";
            if(count($user_receive) > 0){
                foreach($user_receive as $user_id){
                    
                    $cmsDocument = new CmsDocument();
                    $cmsDocument->title = $title;
                    $cmsDocument->content = $content;
                    $cmsDocument->admin_created_id = Auth::guard('admin')->user()->id;
                    $cmsDocument->admin_receive_id = $user_id;
                    $cmsDocument->department_id = null;
                    $cmsDocument->date_at = null;
                    $cmsDocument->save();
                }
            }

        }
        return redirect()->route($this->routeDefault . '.index')->with('successMessage', __('Add new successfully!'));
        //dd($params);

    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\CmsDocument  $cmsDocument
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request, CmsDocument $cmsDocument)
    {
        //

        $params = $request->all();
        $task = $params['task'];
       
        // Check là đã đọc hay chưa
        if($cmsDocument->admin_receive_id == Auth::guard('admin')->user()->id){
            if($cmsDocument->date_at == ''){
                $cmsDocument->date_at = date('Y-m-d H:i:s');
            }
            if($cmsDocument->ip_user==''){ 
                $cmsDocument->ip_user = $_SERVER['REMOTE_ADDR'];
            }
            
            $cmsDocument->save();
        } 

        $params_cs = array();
        $params_cs['admin_created_id'] = $cmsDocument->admin_created_id;
        $params_cs['admin_receive_id'] = $cmsDocument->admin_receive_id;
        $params_cs['id'] = $cmsDocument->id;
        $rows = CmsDocument::getDocumentById($params_cs)->first();
        $data_post = '';
        if($rows){
            $dadoc = $rows->date_at != '' ? date("H:i d/m/Y",strtotime($rows->updated_at)) : '';
            //dd($rows);
            $data_post ='
            <div class="row">
                <div class="col-sm-6 col-md-6">
                <label class="control-label">Người gửi: <b>'.$rows->user_send.'</b> </label>
                </div>
                <div class="col-sm-6 col-md-6">
                <label class="control-label">Người nhận: <b>'.$rows->user_receive.'</b> </label>
                </div>
            </div>
            <div class="row">
                <div class="col-sm-6 col-md-6">
                <label class="control-label">Gửi lúc: <b>'.date("H:i d/m/Y",strtotime($rows->created_at)).'</b> </label>
                </div>
                <div class="col-sm-6 col-md-6">
                <label class="control-label">Đã đọc: <b>'.$dadoc.'</b></label>
                </div>
            </div>
            <hr>
            <div class="row">
                <div class="col-xs-12 col-sm-12 col-md-3 col-lg-3">
                <label class="control-label">Tiêu đề</label>
                </div>
                <div class="col-xs-12 col-sm-12 col-md-9 col-lg-9">
                <p>
                    <strong>'.$rows->title.'</strong>
                </p>
                </div>
            </div>
            <div class="row">
                <div class="col-xs-12 col-sm-12 col-md-3 col-lg-3">
                <label class="control-label">Nội dung</label>
                </div>
                <div class="col-xs-12 col-sm-12 col-md-9 col-lg-9">
                    '.$rows->content.'
                </div>
            </div>';
        }

        return $data_post;
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\CmsDocument  $cmsDocument
     * @return \Illuminate\Http\Response
     */
    public function edit(CmsDocument $cmsDocument)
    {
        return redirect()->back();
        //
        //dd($cmsDocument);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\CmsDocument  $cmsDocument
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, CmsDocument $cmsDocument)
    {
        return redirect()->back();
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\CmsDocument  $cmsDocument
     * @return \Illuminate\Http\Response
     */
    public function destroy(CmsDocument $cmsDocument)
    {
        return redirect()->back();
        //
    }
}
