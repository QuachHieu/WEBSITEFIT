<?php

namespace App\Http\Controllers\FrontEnd;

use Exception;
use App\Consts;
use App\Http\Services\ContentService;
use App\Http\Services\PageBuilderService;
use App\Models\Admin;
//use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

class UsersController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        // echo "SSSSSSSSSSS";die;
        if( Auth::guard('web')->check() ){
            return $this->responseView('frontend.pages.user.index');
        }else{
            
            return $this->responseView('frontend.pages.home');
        }
        
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Admin  $admin
     * @return \Illuminate\Http\Response
     */
    public function show(Admin $admin)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Admin  $admin
     * @return \Illuminate\Http\Response
     */
    public function edit(Admin $admin)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Admin  $admin
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request)
    {

        $params = $request->all();
        $targetDir = "member/hinhanh".Auth::guard('web')->user()->id."/";
        //$allowTypes = array('jpg','png','jpeg','gif');
        if(!file_exists($targetDir)){
            if(mkdir($targetDir)){
                //echo "Tạo thư mục thành công.";
            }
        }
        
        if($_FILES['image']['name']){
            $request->validate([
                'image' => 'image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            ]);
    
            $imageName = time().'.'.$request->image->extension();  
    
            $request->image->move(public_path($targetDir), $imageName);
            
            $path_image = $targetDir.$imageName;
        }else{
            $path_image = $params['avatar'];
        }
        
        /**/
        
        Auth::guard('web')->user()->avatar = '/'.$path_image;
        Auth::guard('web')->user()->sex = $params['sex'];
        Auth::guard('web')->user()->birthday = $params['birthday'];
        Auth::guard('web')->user()->phone = $params['phone'];
        
        Auth::guard('web')->user()->save();

        /*
        Auth::guard('web')->user()->save();
        */
        //print_r($params);die;
        return $this->responseView('frontend.pages.user.index');
        
    }

    public function register(Request $request)
    {
        $params = $request->all();

        $user_register = new User;
        $user_register -> name = $params['FullName'];
        $user_register -> email = $params['TxtEmail'];
        $user_register -> password = bcrypt($params['Password']);
        $user_register -> sex = $params['Sex'];
        $user_register -> birthday = $params['DateOfBirth'];
        $user_register -> phone = $params['Phone'];
        $user_register -> status = 'active';
        $user_register -> save();
        
        return redirect()->route('frontend.home');
        
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Admin  $admin
     * @return \Illuminate\Http\Response
     */
    public function destroy(Admin $admin)
    {
        //
    }
}
