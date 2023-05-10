<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Support\Facades\Auth;
use App\Models\Comment;
use App\Models\CmsDocument;
use App\Http\Services\AdminService;
use App\Models\CmsPost;
use Illuminate\Support\Facades\Route;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    // Part to views for Controller
    protected $viewPart;
    // Route default for Controller
    protected $routeDefault;
    // Data response to view
    protected $responseData = [];

    /**
     * Xử lý các thông tin hệ thống trước khi đổ ra view
     * @author: ThangNH
     * @created_at: 2021/10/01
     */

    protected function responseView($view )
    {
        session_start();
        
        //session_destroy();
        
        //$routeDefault = $this->routeDefault ? $this->routeDefault : '' ;
        
        //if(!isset($_SESSION['user']['id']) || $_SESSION['user']['id'] <= 0){
            $_SESSION['user']['id'] = Auth::guard('admin')->user()->id;
            $_SESSION['user']['role'] = Auth::guard('admin')->user()->role;
            $_SESSION['user']['is_super_admin'] = Auth::guard('admin')->user()->is_super_admin;
            $_SESSION['user']['email'] = Auth::guard('admin')->user()->email;
        //}
        
        $this->responseData['admin_auth'] = Auth::guard('admin')->user();
        
        //$comments = Comment::selectRaw('status, COUNT(id) as sum')->groupBy('status')->get();
        
        $cho = 0; $duyet = 0;$go =0;
        
        /*
        foreach($comments as $key){
        if($key->status == 1)
            $cho = $key->sum;
        if($key->status == 0)
            $duyet =  $key->sum;
        if($key->status == 2)
            $go =  $key->sum;
        }*/

        $tongsobinhluan['cho'] = $cho;
        $tongsobinhluan['duyet'] = $duyet;
        $tongsobinhluan['go'] = $go;
        $tongsobinhluan['tong'] =$cho + $duyet + $go;

        $count_route['comment'] = $cho;

        // Đếm tài liệu đã gửi cho bạn và chưa đọc
        $cmsDocument = CmsDocument::select('id')->where('admin_receive_id',Auth::guard('admin')->user()->id)->whereRaw('date_at is null')->count();
        $count_route['cms_document'] = $cmsDocument;
        //dd($count_route);
        $this->responseData['tongsobinhluan'] = $tongsobinhluan;
        $this->responseData['count_route'] = $count_route;
        /**
         * Get all access menu to show in the sidebar by role of current User 
        **/
        $this->responseData['accessMenus'] = AdminService::getAccessMenu();

        // App::setLocale('en');

        return view($view, $this->responseData);
    }

    protected function sendResponse($data, $message = '')
    {
        $response = [
            'data' => $data,
            'message' => $message
        ];

        return response()->json($response);
    }
}
