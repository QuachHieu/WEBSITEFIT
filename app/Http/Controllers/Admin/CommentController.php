<?php

namespace App\Http\Controllers\Admin;

use App\Consts;
//use App\Http\Controllers\Controller;
use App\Models\Comment;
use App\Models\CmsPost;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Http\Services\ContentService;

class CommentController extends Controller
{
    public function __construct()
    {
        $this->routeDefault  = 'comment';
        $this->viewPart = 'admin.pages.comment';
        $this->responseData['module_name'] = __('Quản lý bình luận');
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        //
        if(ContentService::checkRole($this->routeDefault,'index') == 0){
            $this->responseData['module_name'] = __('Bạn không có quyền truy cập chức năng này');
            return $this->responseView($this->viewPart . '.404');
        }
        $params = $request->all();
        //dd($params);
        $this->responseData['cms_posts'] = CmsPost::select('id','title')->limit(1000)->get();
        $this->responseData['params'] = $params;
        $rows = Comment::getCommentByParam($params)->paginate(Consts::DEFAULT_PAGINATE_LIMIT);
        
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
     * @param  \App\Models\Comment  $comment
     * @return \Illuminate\Http\Response
     */
    public function show(Comment $comment)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Comment  $comment
     * @return \Illuminate\Http\Response
     */
    public function edit(Comment $comment)
    {
        //dd($comment);
        //
        if(ContentService::checkRole($this->routeDefault,'update') == 0){
            $this->responseData['module_name'] = __('Bạn không có quyền truy cập chức năng này');
            return $this->responseView($this->viewPart . '.404');
        }
        $params['id'] = $comment->id;
        $this->responseData['detail'] = Comment::getCommentByParam($params)->first();
        return $this->responseView($this->viewPart . '.edit');
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Comment  $comment
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Comment $comment)
    {
        if(ContentService::checkRole($this->routeDefault,$this->routeDefault,'update') == 0){
            $this->responseData['module_name'] = __('Bạn không có quyền truy cập chức năng này');
            return $this->responseView($this->viewPart . '.404');
        }
        $params = $request->all();
        $params['admin_updated_id'] = Auth::guard('admin')->user()->id;
        $comment->fill($params);
        $comment->save();
        
        $post_id = $comment->post_id;
        // Đếm số lượng comment được duyệt
        $count_comment = Comment::select('id')->where('post_id',$post_id)->where('status',0)->get()->count();
        //echo $count_comment;die;
        CmsPost::where('id', $post_id)
        ->update([
            'number_comment' => $count_comment
        ]);
        
        return redirect()->back()->with('successMessage', __('Successfully updated!'));
    }

    public function updateStatus(Request $request)
    {   
        if(ContentService::checkRole($this->routeDefault,'update') == 0){
            $this->responseData['module_name'] = __('Bạn không có quyền truy cập chức năng này');
            return $this->responseView($this->viewPart . '.404');
        }
        $id = $request->id;
        $status = $request->status;
        
		$comment = Comment::where('id',$id)->first();

        if($comment){
            
            $comment -> status = $status;
            $comment -> admin_updated_id = Auth::guard('admin')->user()->id;
            $comment -> save();

            $post_id = $comment ->post_id; // Id bài viết
            
            // đếm số bình luận đã được duyệt
            $count_comment = Comment::select('id')->where('post_id',$post_id)->where('status',0)->get()->count();
            // Cập nhật số lượng bình luận
            CmsPost::where('id', $post_id)->update([ 'number_comment' => $count_comment ]);

        }
        return $id;
		
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Comment  $comment
     * @return \Illuminate\Http\Response
     */
    public function destroy(Comment $comment)
    {
        if(ContentService::checkRole($this->routeDefault,'delete') == 0){
            $this->responseData['module_name'] = __('Bạn không có quyền truy cập chức năng này');
            return $this->responseView($this->viewPart . '.404');
        }
        $comment->status = 2;
        $comment->save();
        return redirect()->route($this->routeDefault . '.index')->with('successMessage', __('Bình luận đã bị khóa'));
    }
}
