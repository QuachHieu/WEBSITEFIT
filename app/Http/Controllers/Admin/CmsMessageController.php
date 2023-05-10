<?php

namespace App\Http\Controllers\Admin;

//use App\Http\Controllers\Controller;
use App\Models\CmsMessage;
use Illuminate\Http\Request;
use App\Consts;
use App\Models\Admin;
use App\Http\Services\ContentService;
use App\Http\Services\AdminService;
use Illuminate\Support\Facades\Auth;

class CmsMessageController extends Controller
{
    public function __construct()
    {  
      //dd(url()->full());
      $this->routeDefault  = 'cms_message';
      $this->viewPart = 'admin.pages.message';
      $this->responseData['module_name'] = __('Tất cả tin nhắn');
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        //
        $list_message = array();
        if(ContentService::checkRole($this->routeDefault,'index') == 0){
            $this->responseData['module_name'] = __('Bạn không có quyền truy cập chức năng này');
            return $this->responseView($this->viewPart . '.404');
        }
        $admin_created_id = Auth::guard('admin')->user()->id;
        $params = $request->all();

    
        if (session('admin_receive_id')){
          $admin_receive_id = session('admin_receive_id');
          if($admin_created_id > $admin_receive_id){
            $user_key = $admin_receive_id.'_'.$admin_created_id;
          }else{
            $user_key = $admin_created_id.'_'.$admin_receive_id;
          }
          
          $list_message = CmsMessage::getMessageByParam(array('user_key'=>$user_key))->get();
        }
        //print_r($params);
        $this->responseData['list_message'] = $list_message;

        // Danh sách tài khoản admin để nhận gửi tin nhắn
        $admins = Admin::where('status','active')->where('id','!=',Auth::guard('admin')->user()->id)->get();
        $this->responseData['admins'] = $admins;

        $rows = CmsMessage::selectRaw('user_key, Max(updated_at) as updated_at, date_at, content')->groupBy('user_key')->orderBy('updated_at','DESC')->get();
        $array_message = array();
        foreach($rows as $row){
          //$array_message[$row->user_key] = ContentService::stringTruncate ( $row->content, 30);
          $array_message[$row->user_key] = date('H:i d/m/Y',strtotime($row->updated_at));
        } 

        $this->responseData['array_message'] = $array_message; 
      

        return $this->responseView($this->viewPart . '.index');
    }
     
    protected function responseView($view )
    {
        session_start();
        
   
            $_SESSION['user']['id'] = Auth::guard('admin')->user()->id;
            $_SESSION['user']['role'] = Auth::guard('admin')->user()->role;
            $_SESSION['user']['is_super_admin'] = Auth::guard('admin')->user()->is_super_admin;
            $_SESSION['user']['email'] = Auth::guard('admin')->user()->email;
  
        
        $this->responseData['admin_auth'] = Auth::guard('admin')->user();
        $chuadoc=0;
        $count_route['cms_message'] = $chuadoc;
        $cmsMessage = CmsMessage::select('id')->where('admin_receive_id',Auth::guard('admin')->user()->id)->whereRaw('date_at is null')->count();
        $count_route['cms_message'] = $cmsMessage;
        // dd( $count_route['cms_message']);
        $this->responseData['count_route'] = $count_route;
        $this->responseData['accessMenus'] = AdminService::getAccessMenu();
        
       

        return view($view, $this->responseData);
    }
    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
      // return redirect()->back();
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request )
    {
      $request->validate([
        'admin_receive_id' => 'required',
      ]);
      $params = $request->all();
      
      $admin_created_id = Auth::guard('admin')->user()->id;

      $admin_receive_id = $params['admin_receive_id'];

      $content = $params['content'];

      $email = Auth::guard('admin')->user()->email;
      $array_email = explode('@',$email);
      $name_email = str_replace(['.',',','_','-'],'',$array_email[0]);

      $targetDir = "uploads/".$name_email."/";
      //$allowTypes = array('jpg','png','jpeg','gif');
      if(!file_exists($targetDir)){
          if(mkdir($targetDir)){
              //echo "Tạo thư mục thành công.";
          }
      }
      
      if($_FILES['image']['name']){
        /*
        $request->validate([
            'image' => 'image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ]);
        */
        $imageName = time().'.'.$request->image->extension();  
        
        $request->image->move(public_path($targetDir), $imageName);
        
        $path_image = $targetDir.$imageName;
      }else{
        $path_image = '';
      }

      if($admin_created_id > $admin_receive_id){
        $user_key = $admin_receive_id.'_'.$admin_created_id;
      }else{
        $user_key = $admin_created_id.'_'.$admin_receive_id;
      }

      $message = new CmsMessage();
      $message -> user_key = $user_key;
      $message -> content = $content;
      $message -> media = $path_image;
      $message -> status = 'active';
      $message -> date_at = null;
      $message -> admin_created_id = $admin_created_id;
      $message -> admin_receive_id = $admin_receive_id;
      $message -> save();
      //dd($params);
      return redirect()->route($this->routeDefault . '.index')->with('admin_receive_id', $admin_receive_id);;
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\CmsMessage  $cmsMessage
     * @return \Illuminate\Http\Response
     */
    public function listMessage(Request $request )
    {
        $params = $request->all();
        $user_id = $params['id'];

        $admin_id = Auth::guard('admin')->user()->id;

        if($user_id > $admin_id){
            $user_key = $admin_id.'_'.$user_id;
        }else{
            $user_key = $user_id.'_'.$admin_id;
        } 
        

        $cmsMessage = CmsMessage::getMessageByParam(array('user_key'=>$user_key))->get();
        // if($cmsMessage->admin_receive_id == Auth::guard('admin')->user()->id){
        //   if($cmsMessage->date_at == ''){
        //       $cmsMessage->date_at = date('Y-m-d H:i:s');
        //   }
        //   $cmsMessage->updated_at = date('Y-m-d H:i:s');
        //   $cmsMessage->save();
        //  } 

        $data_post = '<div class="box-content">';
        
        if(count($cmsMessage) > 0){
            
            foreach($cmsMessage as $message){
                $avatar = $message->admin_avatar != "" ? $message->admin_avatar : '/images/noavatar.png';
                $admin_name = $message->admin_send;
                $media = $message->media != '' ? '<br><a target="_blank" href="/'.$message->media.'"><i class="fa fa-file-text-o"></i> file đính kèm</a>' : '';
                if($message->admin_created_id != $admin_id){
                  
                    $data_post .= '<div class="item anwser"> 
                    <div class="wrap"> 
                      <div class="avatar"> 
                        <a class="photo" href="#" data-desc="'.$admin_name.'" data-index="'.$message->id.'">
                        <img class="guest-avatar cms-photo ls-is-cached lazyloaded" src="'.$avatar.'" alt="'.$admin_name.'" data-photo-original-src="" data-src="'.$avatar.'"></a> 
                      </div> 
                      <div class="comment"> 
                        <div class="content"> 
                          <div class="anwser-content"> 
                            <p>'.$message->content.$media.'</p>
                          </div> 
                          <p class="text-right">
                            <span style="margin-right: 20px;"><i>'.date('H:i d/m/Y',strtotime($message->updated_at)).'</i></span>
                          </p>
                        </div> 
                      </div> 
                    </div> 
                  </div>';
                }else{
                    $data_post .= '<div class="item question"> 
                    <div class="wrap"> 
                    <div class="avatar">
                      <a class="photo" href="javascript:;" data-desc="'.$admin_name.'" data-index="'.$message->id.'">
                        <img src="'.$avatar.'" alt="'.$admin_name.'" data-photo-original-src="" class="cms-photo ls-is-cached lazyloaded" data-src="'.$avatar.'">
                      </a> 
                    </div> 
                    <div class="comment"> 
                      <div class="content"> 
                      <div class="question-content">
                        <p>'.$message->content.$media.'</p>
                      </div>  
                        <p class="text-right">
                          <span style="margin-right: 20px;"><i>'.date('H:i d/m/Y',strtotime($message->updated_at)).'</i></span> 
                        </p>
                      </div> 
                    </div> 
                    </div> 
                  </div> ';
                }
            }
        }

        $data_post .= '</div>';

        return $data_post;

    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\CmsMessage  $cmsMessage
     * @return \Illuminate\Http\Response
     */
    public function edit(CmsMessage $cmsMessage)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\CmsMessage  $cmsMessage
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, CmsMessage $cmsMessage)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\CmsMessage  $cmsMessage
     * @return \Illuminate\Http\Response
     */
    public function destroy(CmsMessage $cmsMessage)
    {
        //
    }
}
