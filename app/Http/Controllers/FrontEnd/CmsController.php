<?php

namespace App\Http\Controllers\FrontEnd;

use App\Consts;
use App\Http\Services\ContentService;
use App\Models\CmsPost;
use App\Models\CmsTaxonomy;
use App\Models\Admin;
use App\Models\CmsResource;
use App\Models\CmsMessage;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Auth;

class CmsController extends Controller
{   
    public function sound(Request $request){
        $admin_id = Auth::guard('admin')->user()->id;
      
        $wait_mess=CmsMessage::where('admin_receive_id',$admin_id)->whereRaw('date_at is null')->get();
        dd($wait_mess);
        
        if($wait_mess!='') echo 0;
    }
    public function countmess(Request $request){

       // $user_id = request('id_list') ?? '';

        $admin_id = Auth::guard('admin')->user()->id;
      
        $wait_mess=CmsMessage::where('admin_receive_id',$admin_id)->whereRaw('date_at is null')->get();
        // $wait_mess=CmsMessage::where('admin_receive_id',$admin_id)->get();
       
        $arr_admin = array();
        foreach ($wait_mess as $key_mess) {
            if(isset($arr_admin[$key_mess->admin_created_id])){
                $arr_admin[$key_mess->admin_created_id] = $arr_admin[$key_mess->admin_created_id] + 1;
            }else{
                $arr_admin[$key_mess->admin_created_id] = 1;
            }
        }
       // dd($arr_admin);
        $str_count='';
        foreach ($arr_admin as $key => $key_mess1) {
            $str_count.=$key.'_'.$key_mess1.';';
        }
        
     
        echo $str_count;
       
       
        
        
    }
    
    public function listchat(Request $request){

        $user_id = request('id_list') ?? '';

        $admin_id = request('id_user') ?? '';

        $name_list = request('name_list') ?? '';

        if($user_id > $admin_id){
            $user_key = $admin_id.'_'.$user_id;
        }else{
            $user_key = $user_id.'_'.$admin_id;
        }

        $cmsMessage = CmsMessage::getMessageByParam(array('user_key'=>$user_key))->get();

        foreach($cmsMessage as $check_message)
           {
            if($check_message->admin_receive_id == Auth::guard('admin')->user()->id){
                //dd($check_message->date_at);
                if($check_message->date_at == ''){
                    $check_message->date_at = date('Y-m-d H:i:s');
                }
                $check_message->updated_at = date('Y-m-d H:i:s');
                $check_message->save();
            } 
           }

        if(count($cmsMessage) > 0){ 

            ?>

            <div class="chatbox">
                <div class="chatbox-top">
                  <div class="chat-partner-name">
                    <span class="status online"></span>
                    <a href="#"><?php echo $name_list; ?></a>
                  </div>
                  <div class="chatbox-icons">
                    <a href="javascript:void(0);"><i class="fa fa-minus"></i></a>
                    <a href="javascript:void(0);"><i class="fa fa-close"></i></a>       
                  </div>      
                </div>
                
                <div class="chat-messages">
                    <!-- đổ dữ liệu gửi tin nhắn -->
                    <div id="loadchat">
                        
                    </div>
                   
                </div>
                <!-- <button class="btn" onClick="scrollToEnd('.chat-messages')">Goto Last Line</button> -->
                
                <input type="hidden" id="admin_id" value="<?=$admin_id?>">
                <input type="hidden" id="user_id" value="<?=$user_id?>">
                <div class="chat-input-holder" style="padding-bottom: 5px;">
                    <span class="input-group-btn" style="padding-left: 5px;">
                      <a data-input="file" onclick="openPopupImg('file')" data-preview="file-holder" class="lfm" data-type="cms-file" style="cursor:pointer;">
                        <i class="fa fa-file"></i> File: 
                      </a>
                    </span>
                    <input id="file" class="form-control" type="text" name="file" placeholder="Đường dẫn file..." style="border: none;padding-left: 5px;">
                </div>
                <div class="chat-input-holder">
                  <textarea class="chat-input" id="content"></textarea>
                  <input type="submit" value="Gửi" class="message-send" onclick="sendMessage()"/>
                </div>
            </div>

        <?php } else { ?>

            <div class="chatbox">
                <div class="chatbox-top">
                  <div class="chat-partner-name">
                    <span class="status online"></span>
                    <a href="#"><?php echo $name_list; ?></a>
                  </div>
                  <div class="chatbox-icons">
                    <a href="javascript:void(0);"><i class="fa fa-minus"></i></a>
                    <a href="javascript:void(0);"><i class="fa fa-close"></i></a>       
                  </div>      
                </div>
                
                <div class="chat-messages">
                    <!--Tin nhắn rỗng-->
                    <div id="loadchat">
                        
                    </div>
                   
                </div>
                <!-- <button class="btn" onClick="scrollToEnd('.chat-messages')">Goto Last Line</button> -->
                    <input type="hidden" id="admin_id" value="<?=$admin_id?>">
                    <input type="hidden" id="user_id" value="<?=$user_id?>">
                    <div class="chat-input-holder" style="padding-bottom: 5px;">
                        <span class="input-group-btn" style="padding-left: 5px;">
                          <a data-input="file" onclick="openPopupImg('file')" data-preview="file-holder" class="lfm"
                            data-type="cms-file" style="cursor:pointer;">
                            <i class="fa fa-file"></i> File: 
                          </a>
                        </span>
                        <input id="file" class="form-control" type="text" name="file" placeholder="Đường dẫn file..." style="border: none;padding-left: 5px;">
                    </div>
                    <div class="chat-input-holder">
                      <textarea class="chat-input" id="content"></textarea>
                      <input type="submit" value="Gửi" class="message-send" onclick="sendMessage()"/>
                    </div>
              </div>
    <?php  } ?>

        <script src="/public/themes/admin/js/custom.js"></script>
        <script src="/public/themes/admin/editor/ckfinder/ckfinder.js"></script>
       
        <script type="text/javascript">
            function loadchat() {
                var admin_id = $("#admin_id").val();
                var user_id = $("#user_id").val();

                var f = "?user_id="+ user_id + "&admin_id=" + admin_id;
                var _url = "/loadchat" + f;

                $.ajax({
                    type: "GET",
                    url: _url,
                    data: f,
                    cache: false,
                    context: document.body,
                    success: function(data) {
                        // alert(data);
                        $("#loadchat").html(data);
                    }
                }); 
            }
            $(document).ready(function() {
                setInterval(loadchat, 500);
            });          
            loadchat();
        </script>

        <script type="text/javascript">
            function sendMessage() {

                document.getElementById("chatlist").style.display = "block";

                var media = $("#file").val();
                var admin_id = $("#admin_id").val();
                var user_id = $("#user_id").val();
                var content = $("#content").val();

                var f = "?user_id="+ user_id + "&content=" +content+ "&admin_id=" + admin_id + "&media=" +media;
                var _url = "/sendmessage" + f;

                $.ajax({
                    type: "GET",
                    url: _url,
                    data: f,
                    cache: false,
                    context: document.body,
                    success: function(data) {
                        // $("#loadchat").html(data);
                        $("#content").val('');
                        $("#file").val('');
                    }
                });
                
            }
           
 
        </script>
        <!-- <script type="text/javascript">
            let scrollToEnd = ( elem ) => {
            let el = document.querySelector(elem);
            el.scrollTop = el.scrollHeight;
            }
            scrollToEnd('.chat-messages') 
        </script> -->
        <script type="text/javascript">
           var elem;
           elem = document.querySelector('.chat-messages');
           elem.scrollTop=elem.scrollHeight;
        </script>
        <script type="text/javascript">
          $(function(){

          $('.fa-minus').click(function(){    
            $(this).closest('.chatbox').toggleClass('chatbox-min');
          });

          $('.min').click(function(){    
            $(this).closest('.chatboxx').toggleClass('chatbox-min');
          });

          $('.fa-close').click(function(){
            $(this).closest('.chatbox').hide();
          });
        });
        </script>

    <?php }

    public function loadchat(Request $request){

        $user_id = request('user_id') ?? '';

        $admin_id = request('admin_id') ?? '';

        if($admin_id > $user_id){
            $user_key = $user_id.'_'.$admin_id;
        }else{
            $user_key = $admin_id.'_'.$user_id;
        }
        
        $cmsMessage = CmsMessage::getMessageByParam(array('user_key'=>$user_key))->get();
        
        // dd($cmsMessage);
        $data = '';

        foreach($cmsMessage as $message){
            $date = date('H:i d/m/Y',strtotime($message->date_at));
            $tenfile = explode('/',$message->media);
            $media = $message->media != '' ? '<a target="_blank" href="'.$message->media.'"><i class="fa fa-file-text-o"></i>'.array_pop($tenfile).'</a>' : '';

            if($message->admin_created_id == $admin_id){
                
                $data .= '
                <div class="message-box-holder">
                    <div class="message-box">
                      '.nl2br($message->content).'<br>'.$media.'
                    </div>
                </div>
                ';

            } else {
                $data .= '
                <div class="message-box-holder">';

                if($message->date_at != ''){
                    $data .= '
                    <div class="message-sender">
                       '.$date.'
                    </div>';
                }
                $data .= '<div class="message-box message-partner">
                        '.nl2br($message->content).'<br>'.$media.'
                    </div>
                </div>
                ';
            }
        }
        return $data;

    }

    public function sendmessage(Request $request){

        $media = request('media') ?? '';

        $user_id = request('user_id') ?? '';

        $admin_id = request('admin_id') ?? '';

        $content = request('content') ?? '';

        if($admin_id > $user_id){
            $user_key = $user_id.'_'.$admin_id;
        }else{
            $user_key = $admin_id.'_'.$user_id;
        }
        $message = new CmsMessage();
        $message -> user_key = $user_key;
        $message -> content = $content;
        $message -> media = $media;
        $message -> status = 'active';
        $message -> date_at = null;
        $message -> admin_created_id = $admin_id;
        $message -> admin_receive_id = $user_id;
        $message -> save();

        echo 1;
        
        // $tenfile = explode('/',$media);

        // $media = $media != '' ? '<a target="_blank" href="'.$media.'"><i class="fa fa-file-text-o"></i>'.array_pop($tenfile).'</a>' : '';

        // $data = '';

        // $data .='
        // <div class="message-box-holder">
        //     <div class="message-box">
        //       '.nl2br($content).'<br>'.$media.'
        //     </div>
        // </div>
        // ';

        // return $data;

    }

    public function countclick(Request $request)
    {
        $id = request('id') ?? '';

        $count = CmsResource::where('id',$id)->first();

        if($count){

            if($count->phanloai == 2){

                if(Auth::guard('admin')->user()){

                    $click_new = $count->click + 1;
                    $count->click = $click_new;
                    $count->save();
                    echo $count->file;
                
                } else {
                    echo '';
                }

            }else{

                $click_new = $count->click + 1;
                $count->click = $click_new;
                $count->save();
                echo $count->file;
            }
            
        }
    }

    public function viewMore()
    {

        $txt_post = session('post_id');
        
        //$articles = request('articles') ?? '';
        $trang = request('trang') ?? '';
        $banghibandau = request('row') ?? '';
        $limit = request('limit') ?? '';

        $locale = 'vi';

        $paramPost['status']='active';
        $paramPost['limit']=$limit;
        $paramPost['is_type'] = Consts::POST_TYPE['post'];
        $paramPost['order_by']= array( 'news_position'=>'desc', 'iorder' => 'asc', 'aproved_date'=>'desc' );
        
        $article_id = $txt_post;

        $dsTinTuc = ContentService::getCmsPostLoading($paramPost,trim($txt_post,','))->get();
        $data_post = '';
        foreach($dsTinTuc as $item){
            $title = $item->json_params->title->{$locale} ?? $item->title;
            $brief = $item->json_params->brief->{$locale} ?? $item->brief;
            $image = $item->image_thumb != '' ? $item->image_thumb : ($item->image != '' ? $item->image : null);
            $date = date('H:i d/m/Y', strtotime($item->created_at));
            // Viet ham xu ly lay alias bai viet
            $alias_detail = $item->url_part ? $item->url_part : Str::slug($title);
            
            $url_link = route('frontend.cms.post', ['alias_detail' => $alias_detail]) . '.html';
            $article_id.=$item->id.',';
            $author = $item->author !='' ? $item->author : $item->fullname;
            $hienthingay = ContentService::postTime($item->aproved_date);
            $avatar = $item->avatar !='' ? $item->avatar : '/images/noiavatar.png';
            $data_post .=' 
            <article class="story story--flex story--round " id="article'.$item->id.'">
                <div class="story__meta">
                    <div class="story__avatar">
                        <img src="'.$avatar .'" alt="'.$author.'" class="img-fluid rounded-circle">
                    </div>
                    <div class="story__info">
                        <h3 class="story__author">'.$author.'</h3>
                        <div class="story__time"><time datetime="'.$item->updated_at.'" class="time-ago">'.$hienthingay.'</time></div>
                    </div>
                </div>
                <div class="story__header">
                    <h3 class="story__title">'.$title.'</h3>
                    <div class="story__summary">
                        '.$brief.'
                        <a href="'.$url_link.'" class="view-more">Xem thêm</a>
                        <div class="post-content d-none"></div>
                    </div>
                </div>
                <div class="story__images lightgallery">
                    
                    <div data-src="'.$image.'" class="item">
                        <img src="'.$image.'" alt="'.$title.'" class="img-fluid" title="'.$title.'">
                    </div>
                        
                </div>
                <footer class="story__footer">
                    <div class="story__react share">
                        <div class="fb-like fb_iframe_widget" data-href="'.$url_link.'" data-width="" data-layout="button_count" data-action="like" data-size="small" data-share="true" fb-xfbml-state="rendered" fb-iframe-plugin-query="action=like&amp;app_id=625475154576703&amp;container_width=0&amp;href=https%3A%2F%2Fnguoimuanha.vn%2Fgia-bds-se-tiep-tuc-tang-60883.html&amp;layout=button_count&amp;locale=vi_VN&amp;sdk=joey&amp;share=true&amp;size=small&amp;width="><span style="vertical-align: bottom; width: 150px; height: 28px;"><iframe name="f1f94d842c23d74" width="1000px" height="1000px" data-testid="fb:like Facebook Social Plugin" title="fb:like Facebook Social Plugin" frameborder="0" allowtransparency="true" allowfullscreen="true" scrolling="no" allow="encrypted-media" src="https://www.facebook.com/v4.0/plugins/like.php?action=like&amp;app_id=625475154576703&amp;channel=https%3A%2F%2Fstaticxx.facebook.com%2Fx%2Fconnect%2Fxd_arbiter%2F%3Fversion%3D46%23cb%3Df2eda2a027ba0d8%26domain%3Dnguoimuanha.vn%26is_canvas%3Dfalse%26origin%3Dhttps%253A%252F%252Fnguoimuanha.vn%252Ff102ff38a076bb8%26relation%3Dparent.parent&amp;container_width=0&amp;href='.$url_link.'&amp;layout=button_count&amp;locale=vi_VN&amp;sdk=joey&amp;share=true&amp;size=small&amp;width=" style="border: none; visibility: visible; width: 150px; height: 28px;" class=""></iframe></span></div>
                    </div>
                    <a href="'.$url_link.'#detail__footer" title="Bình luận" class="story__react comment" data-article="'.$item->id.'"><i class="fal fa-comment"></i><span></span></a>
                    <a href="javascript:void(0)" title="Chia sẻ lên facebook" class="story__react love" data-article="'.$item->id.'"><i class="fal fa-share"></i></a>
                </footer>

                <div class="story__comment" data-count-comment="0" >
                    <div class="comment-listing" id="post'.$item->id.'" data-url="'.$url_link.'"></div>
                    <div class="input-wrap">
                        <div class="avatar avatarUser"></div>
                        <div class="content">
                            <div contenteditable="true" draggable="true" class="form-control bg-light editor inputComment auto-size" spellcheck="false" data-id="post'.$item->id.'"></div>
                            <span class="fal fa-image commentUploadImage">
                                <input type="file" accept="image/png, image/jpeg, img/gif" onchange="Images.UploadImage(this,$(this).parent().prev())">
                            </span>
                            <span class="btn-send pointer" title="Gửi bình luận"><i class="fas fa-paper-plane"></i></span>
                        </div>
                    </div>
                </div>

                <div class="story__extend">
                    <div class="dropdown">
                        <a class="" href="#" role="button" id="dropdownMenuLink1" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"><i class="far fa-ellipsis-h"></i></a>
                        <div class="dropdown-menu dropdown-menu-right moreActionArticle" aria-labelledby="dropdownMenuLink1" data-user="377" data-article="'.$item->id.'">
                            <a class="dropdown-item aNotiArticle" href="javascript:void(0)" onclick="FollowingArticles.Follow('.$item->id.')"><i class="fal fa-bell mr-2"></i>Thông báo khi có bình luận</a>
                            <a class="dropdown-item aFollow" href="javascript:void(0)" onclick="Following.Follow(377)"> <i class="fal fa-user-plus mr-2"></i>Theo dõi tác giả</a>
                            
                            <a class="dropdown-item getLinkArticle" href="javascript:void(0)" data-toggle="modal" data-url="'.$url_link.'"><i class="fal fa-link mr-2"></i>Lấy link bài viết</a>

                            <a class="dropdown-item text-danger reportArticle" href="javascript:void(0)" data-toggle="modal" data-target="#modalReport" data-article="'.$item->id.'"><i class="fal fa-exclamation-square mr-2"></i>Báo cáo bài viết</a>
                        </div>
                    </div>
                </div>
            </article>';
        }
        session(['post_id'=>$article_id]);
        return $data_post;
        
    }

    public function postCategory($alias = null, Request $request)
    {
        // $id = $request->get('id')  ?? null;
        
        if ($alias != "" ) {
            $params['url_part'] = str_replace('.html','',$alias);
            //dd($params['url_part']);
            // echo 'AAAAAAAA'.$id;die;
            //$params['id'] = $id;
            $params['status'] = Consts::TAXONOMY_STATUS['active'];
            $params['taxonomy'] = Consts::CATEGORY['post_category'];
            $taxonomy = ContentService::getCmsTaxonomy($params)->first();
            if ($taxonomy) {
                $id=$taxonomy->id;
                $this->responseData['taxonomy'] = $taxonomy;
                if ($taxonomy->sub_taxonomy_id != null) {
                    $str_taxonomy_id = $id . ',' . $taxonomy->sub_taxonomy_id;
                    $paramPost['taxonomy_id'] = array_map('intval', explode(',', $str_taxonomy_id));
                } else {
                    $paramPost['taxonomy_id'] = $id;
                }
                $paramPost['status'] = Consts::POST_STATUS['active'];
                $paramPost['is_type'] = Consts::POST_TYPE['post'];
                $this->responseData['posts'] = ContentService::getCmsPost($paramPost)->paginate(Consts::POST_PAGINATE_LIMIT);
                // dd($this->responseData['posts']);
                return $this->responseView('frontend.pages.post.category');
            } else {
                return redirect()->back()->with('errorMessage', __('not_found'));
            }
        } else {
            $paramPost['status'] = Consts::POST_STATUS['active'];
            $paramPost['is_type'] = Consts::POST_TYPE['post'];
            $this->responseData['posts'] = ContentService::getCmsPost($paramPost)->paginate(Consts::POST_PAGINATE_LIMIT);
        }

        return $this->responseView('frontend.pages.post.default');
    }

    public function postHinhanh($alias = null, Request $request)
    {
        
        if ($alias != "" ) {
            $params['url_part'] = str_replace('.html','',$alias);
            $params['status'] = Consts::TAXONOMY_STATUS['active'];
            $params['taxonomy'] = 'hinh-anh';
            $taxonomy = ContentService::getCmsTaxonomy($params)->first();
            if ($taxonomy) {
                $id=$taxonomy->id;
                $this->responseData['taxonomy'] = $taxonomy;
                if ($taxonomy->sub_taxonomy_id != null) {
                    $str_taxonomy_id = $id . ',' . $taxonomy->sub_taxonomy_id;
                    $paramPost['taxonomy_id'] = array_map('intval', explode(',', $str_taxonomy_id));
                } else {
                    $paramPost['taxonomy_id'] = $id;
                }
                $paramPost['status'] = Consts::POST_STATUS['active'];
                $paramPost['is_type'] = Consts::POST_TYPE['post'];
                $this->responseData['posts'] = ContentService::getCmsPost($paramPost)->paginate(Consts::POST_PAGINATE_LIMIT);

                return $this->responseView('frontend.pages.post.category_hinhanh');
            } else {
                return redirect()->back()->with('errorMessage', __('not_found'));
            }
        } else {
            $paramPost['status'] = Consts::POST_STATUS['active'];
            $paramPost['is_type'] = Consts::POST_TYPE['post'];
            $this->responseData['posts'] = ContentService::getCmsPost($paramPost)->paginate(Consts::POST_PAGINATE_LIMIT);
        }

        return $this->responseView('frontend.pages.post.default');
    }


    // Danh mục video
    public function postVideo($alias = null, Request $request)
    {
        // $id = $request->get('id')  ?? null;
        
        if ($alias != "" ) {
            $params['url_part'] = str_replace('.html','',$alias);
            //dd($params['url_part']);
            // echo 'AAAAAAAA'.$id;die;
            //$params['id'] = $id;
            $params['status'] = Consts::TAXONOMY_STATUS['active'];
            $params['taxonomy'] = 'video';
            $taxonomy = ContentService::getCmsTaxonomy($params)->first();
            if ($taxonomy) {
                $id=$taxonomy->id;
                $this->responseData['taxonomy'] = $taxonomy;
                if ($taxonomy->sub_taxonomy_id != null) {
                    $str_taxonomy_id = $id . ',' . $taxonomy->sub_taxonomy_id;
                    $paramPost['taxonomy_id'] = array_map('intval', explode(',', $str_taxonomy_id));
                } else {
                    $paramPost['taxonomy_id'] = $id;
                }
                $paramPost['status'] = Consts::POST_STATUS['active'];
                $paramPost['is_type'] = Consts::POST_TYPE['post'];
                $this->responseData['posts'] = ContentService::getCmsPost($paramPost)->paginate(Consts::POST_PAGINATE_LIMIT);
                // dd($this->responseData['posts']);

                return $this->responseView('frontend.pages.post.category_video');
            } else {
                return redirect()->back()->with('errorMessage', __('not_found'));
            }
        } else {
            $paramPost['status'] = Consts::POST_STATUS['active'];
            $paramPost['is_type'] = Consts::POST_TYPE['post'];
            $this->responseData['posts'] = ContentService::getCmsPost($paramPost)->paginate(Consts::POST_PAGINATE_LIMIT);
        }

        return $this->responseView('frontend.pages.post.default');
    }
    //chi tiết video
    public function videoDetail($alias_detail = null, Request $request)
    {
        
        if ($alias_detail != '') {
            $params['url_part'] = str_replace('.html','',$alias_detail);
            //$params['url_part'] = $alias_detail;
            $params['status'] = Consts::POST_STATUS['active'];
            $params['aproved_date'] = date('Y-m-d H:i:s');

            $detail = ContentService::getCmsPost($params)->first();
            //dd($detail);
            if ($detail) {

                $id = $detail->id;
                $detail->number_view = $detail->number_view + 1;
                
                $detail->save();

                $this->responseData['detail'] = $detail;
                
                $id = $detail->id;
                $params_relative['id'] = $id;
                $params_relative['taxonomy_id'] = $detail->taxonomy_id;
                $params_relative['status'] = Consts::POST_STATUS['active'];
                $params_relative['is_type'] = Consts::POST_TYPE['post'];
                
                $this->responseData['posts'] = ContentService::getCmsPostRelative($params_relative)->limit(Consts::DEFAULT_RELATIVE_LIMIT)->get();

                $this->responseData['comments'] = ContentService::getComment(['post_id'=>$id,'status'=>0])->get();

                return $this->responseView('frontend.pages.post.detail_video');
                
            }
        }

        return redirect()->back()->with('errorMessage', __('not_found'));
    }


    public function addnew(Request $request)
    {
        $params = $request->all();

        $request->validate([
            'title' => 'required|max:255',
            'content' => 'required|max:7000',
        ]);

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
            $path_image = null;
        }

        $tb_cms_posts = new CmsPost();
        $tb_cms_posts->is_type = 'post';
        $tb_cms_posts->title = $params['title'];
        $tb_cms_posts->brief = $params['title'];
        $tb_cms_posts->content = $params['content'];
        $tb_cms_posts->image = $path_image;
        $tb_cms_posts->status = 'waiting';
        $tb_cms_posts->user_id = Auth::guard('web')->user()->id;
        $tb_cms_posts->save();
        
        //return redirect('/')->with('successMessage', 'Thêm mới tin thành công! Tin của bạn đang được chờ duyệt');

        $this->responseData['successMessage']  =  __('Thêm mới tin thành công! Tin của bạn đang được chờ duyệt.');
        return $this->responseView('frontend.pages.home.index');

    }

    public function search($alias = null, Request $request)
    {
        $keyword = $request->get('keyword')  ?? null;

        $paramPost['status'] = Consts::POST_STATUS['active'];
        $paramPost['is_type'] = Consts::POST_TYPE['post'];
        $paramPost['keyword'] = $keyword;
        $this->responseData['posts'] = ContentService::getCmsPost($paramPost)->paginate(Consts::POST_PAGINATE_LIMIT);

        return $this->responseView('frontend.pages.post.category');
    }

    public function cmstag($alias = null, Request $request)
    {
        $id = $request->get('id')  ?? null;
        //echo 'AAAAAAAA'.$id;die;
        if ($id > 0) {
            //echo 'AAAAAAAA'.$id;die;
            $params['id'] = $id;
            $params['status'] = Consts::TAXONOMY_STATUS['active'];
            $params['taxonomy'] = Consts::CATEGORY['post_tag'];
            $taxonomy = ContentService::getCmsTaxonomy($params)->first();
            if ($taxonomy) {
                $this->responseData['taxonomy'] = $taxonomy;
                if ($taxonomy->sub_taxonomy_id != null) {
                    $str_taxonomy_id = $id . ',' . $taxonomy->sub_taxonomy_id;
                    $paramPost['taxonomy_id'] = array_map('intval', explode(',', $str_taxonomy_id));
                } else {
                    $paramPost['taxonomy_id'] = $id;
                }
                $paramPost['status'] = Consts::POST_STATUS['active'];
                $paramPost['is_type'] = Consts::POST_TYPE['post'];
                $this->responseData['posts'] = ContentService::getCmsPostTag($paramPost)->paginate(Consts::POST_PAGINATE_LIMIT);
                return $this->responseView('frontend.pages.post.category');
            } else {
                return redirect()->back()->with('errorMessage', __('not_found'));
            }
        } else {
            $paramPost['status'] = Consts::POST_STATUS['active'];
            $paramPost['is_type'] = Consts::POST_TYPE['post'];
            $this->responseData['posts'] = ContentService::getCmsPost($paramPost)->paginate(Consts::POST_PAGINATE_LIMIT);
        }

        return $this->responseView('frontend.pages.post.default');
    }

    public function post($alias_detail = null, Request $request)
    {

        //$id = $request->get('id')  ?? null;
        
        if ($alias_detail != '') {
            $params['url_part'] = str_replace('.html','',$alias_detail);
            //$params['url_part'] = $alias_detail;
            $params['status'] = Consts::POST_STATUS['active'];
            $params['is_type'] = Consts::POST_TYPE['post'];
            $params['aproved_date'] = date('Y-m-d H:i:s');

            $detail = ContentService::getCmsPost($params)->first();
            //dd($detail);
            if ($detail) {
                //dd($alias_detail);
                $id = $detail->id;
                $detail->number_view = $detail->number_view + 1;
                
                $detail->save();

                $this->responseData['detail'] = $detail;
                
				$id = $detail->id;
				$params_relative['id'] = $id;
				$params_relative['taxonomy_id'] = $detail->taxonomy_id;
				$params_relative['status'] = Consts::POST_STATUS['active'];
				$params_relative['is_type'] = Consts::POST_TYPE['post'];
				
                $this->responseData['posts'] = ContentService::getCmsPostRelative($params_relative)->limit(Consts::DEFAULT_RELATIVE_LIMIT)->get();

                $this->responseData['comments'] = ContentService::getComment(['post_id'=>$id,'status'=>0])->get();

                return $this->responseView('frontend.pages.post.detail');
            }
        }

        return redirect()->back()->with('errorMessage', __('not_found'));
    }

    public function postIntroduction($alias = null, Request $request)
    {
        //$id = $request->get('id')  ?? null;
        //echo $id ;die;
        if ($alias !="") {
            $params['url_part'] = str_replace('.html','',$alias);
            //$params['id'] = $id;
            $params['status'] = Consts::POST_STATUS['active'];
            $params['is_type'] = Consts::POST_TYPE['intro'];
            $detail = ContentService::getCmsPost($params)->first();
            if ($detail) {
                $id = $detail->id;
                $detail->view_ngay = $detail->view_ngay + 1;
                $detail->view_tuan = $detail->view_tuan + 1;
                $detail->view_thang = $detail->view_thang + 1;
                $detail->save();
                
                $this->responseData['detail'] = $detail;
                
                return $this->responseView('frontend.pages.post.intro');
            }
        }

        return redirect()->back()->with('errorMessage', __('not_found'));
    }

    public function serviceCategory($alias = null, Request $request)
    {
        $id = $request->get('id')  ?? null;
        if ($id > 0) {
            $params['id'] = $id;
            $params['status'] = Consts::TAXONOMY_STATUS['active'];
            $params['taxonomy'] = Consts::TAXONOMY['service_category'];
            $taxonomy = ContentService::getCmsTaxonomy($params)->first();
            if ($taxonomy) {
                $this->responseData['taxonomy'] = $taxonomy;
                $paramPost['taxonomy_id'] = $id;
                $paramPost['status'] = Consts::POST_STATUS['active'];
                $paramPost['is_type'] = Consts::POST_TYPE['service'];
                $this->responseData['posts'] = ContentService::getCmsPost($paramPost)->paginate(Consts::POST_PAGINATE_LIMIT);
                return $this->responseView('frontend.pages.service.category');
            } else {
                return redirect()->back()->with('errorMessage', __('not_found'));
            }
        } else {
            $paramPost['status'] = Consts::POST_STATUS['active'];
            $paramPost['is_type'] = Consts::POST_TYPE['service'];
            $this->responseData['posts'] = ContentService::getCmsPost($paramPost)->paginate(Consts::POST_PAGINATE_LIMIT);
        }

        return $this->responseView('frontend.pages.service.default');
    }

    public function service($alias_category = null, $alias_detail = null, Request $request)
    {
        $id = $request->get('id')  ?? null;
        if ($id > 0) {
            $params['id'] = $id;
            $params['status'] = Consts::POST_STATUS['active'];
            $params['is_type'] = Consts::POST_TYPE['service'];
            $detail = ContentService::getCmsPost($params)->first();
            if ($detail) {
                $detail->count_visited = $detail->count_visited + 1;
                $detail->save();
                $this->responseData['detail'] = $detail;
                $params['id'] = null;
                $params['different_id'] = $detail->id;
                $this->responseData['posts'] = ContentService::getCmsPost($params)->limit(Consts::DEFAULT_OTHER_LIMIT)->get();

                return $this->responseView('frontend.pages.service.detail');
            }
        }

        return redirect()->back()->with('errorMessage', __('not_found'));
    }

    public function productCategory($alias = null, Request $request)
    {
        $id = $request->get('id')  ?? null;
        if ($id > 0) {
            $params['id'] = $id;
            $params['status'] = Consts::TAXONOMY_STATUS['active'];
            $params['taxonomy'] = Consts::TAXONOMY['product_category'];
            $taxonomy = ContentService::getCmsTaxonomy($params)->first();
            if ($taxonomy) {
                $this->responseData['taxonomy'] = $taxonomy;
                $paramPost['taxonomy_id'] = $id;
                $paramPost['status'] = Consts::POST_STATUS['active'];
                $paramPost['is_type'] = Consts::POST_TYPE['product'];
                $this->responseData['posts'] = ContentService::getCmsPost($paramPost)->paginate(Consts::POST_PAGINATE_LIMIT);
                return $this->responseView('frontend.pages.product.category');
            } else {
                return redirect()->back()->with('errorMessage', __('not_found'));
            }
        } else {
            $paramPost['status'] = Consts::POST_STATUS['active'];
            $paramPost['is_type'] = Consts::POST_TYPE['product'];
            $this->responseData['posts'] = ContentService::getCmsPost($paramPost)->paginate(Consts::POST_PAGINATE_LIMIT);
        }

        return $this->responseView('frontend.pages.product.default');
    }

    public function product($alias_category = null, $alias_detail = null, Request $request)
    {
        $id = $request->get('id')  ?? null;
        if ($id > 0) {
            $params['id'] = $id;
            $params['status'] = Consts::POST_STATUS['active'];
            $params['is_type'] = Consts::POST_TYPE['product'];
            $detail = ContentService::getCmsPost($params)->first();
            if ($detail) {
                $detail->count_visited = $detail->count_visited + 1;
                $detail->save();
                $this->responseData['detail'] = $detail;
                $params['id'] = null;
                $params['different_id'] = $detail->id;
                $this->responseData['posts'] = ContentService::getCmsPost($params)->limit(Consts::DEFAULT_OTHER_LIMIT)->get();

                return $this->responseView('frontend.pages.product.detail');
            }
        }

        return redirect()->back()->with('errorMessage', __('not_found'));
    }

    public function doctorList(Request $request)
    {
        $paramPost['status'] = Consts::POST_STATUS['active'];
        $paramPost['is_type'] = Consts::POST_TYPE['doctor'];
        $this->responseData['posts'] = ContentService::getCmsPost($paramPost)->paginate(Consts::POST_PAGINATE_LIMIT);

        return $this->responseView('frontend.pages.doctor.default');
    }

    public function doctor($alias = null, $id = null, Request $request)
    {
        $id = $request->get('id')  ?? $id;
        if ($id > 0) {
            $params['id'] = $id;
            $params['status'] = Consts::POST_STATUS['active'];
            $params['is_type'] = Consts::POST_TYPE['doctor'];
            $detail = ContentService::getCmsPost($params)->first();
            if ($detail) {
                $detail->count_visited = $detail->count_visited + 1;
                $detail->save();
                $this->responseData['detail'] = $detail;
                $params['id'] = null;
                $params['different_id'] = $detail->id;
                $this->responseData['posts'] = ContentService::getCmsPost($params)->limit(Consts::DOCTOR_OTHER_LIMIT)->get();

                return $this->responseView('frontend.pages.doctor.detail');
            }
        }

        return redirect()->back()->with('errorMessage', __('not_found'));
    }

    public function galleryCategory($alias = null, $id = null, Request $request)
    {

        $paramPost['status'] = Consts::POST_STATUS['active'];
        $paramPost['is_type'] = Consts::POST_TYPE['gallery'];
        $this->responseData['posts'] = ContentService::getCmsPost($paramPost)->paginate(Consts::POST_PAGINATE_LIMIT);

        return $this->responseView('pages.gallery.default');
    }

    public function gallery($alias = null, $id = null, Request $request)
    {
        $id = $request->get('id')  ?? $id;
        if ($id > 0) {
            $params['id'] = $id;
            $params['status'] = Consts::POST_STATUS['active'];
            $params['is_type'] = Consts::POST_TYPE['gallery'];
            $detail = ContentService::getCmsPost($params)->first();
            if ($detail) {
                $detail->count_visited = $detail->count_visited + 1;
                $detail->save();
                $this->responseData['detail'] = $detail;
                return $this->responseView('pages.gallery.detail');
            }
        }

        return redirect()->back()->with('errorMessage', __('not_found'));
    }


    public function department($alias = null, Request $request)
    {
        $id = $request->get('id')  ?? null;
        if ($id > 0) {
            $params['id'] = $id;
            $params['status'] = Consts::TAXONOMY_STATUS['active'];
            $params['taxonomy'] = Consts::TAXONOMY['department'];
            $taxonomy = ContentService::getCmsTaxonomy($params)->first();
            if ($taxonomy) {
                $this->responseData['detail'] = $taxonomy;

                $params['id'] = null;
                $params['different_id'] = $taxonomy->id;
                $this->responseData['posts'] = ContentService::getCmsTaxonomy($params)->limit(Consts::DEPARTMENT_OTHER_LIMIT)->get();


                return $this->responseView('frontend.pages.department.detail');
            } else {
                return redirect()->back()->with('errorMessage', __('not_found'));
            }
        } else {
            $paramPost['status'] = Consts::TAXONOMY_STATUS['active'];
            $paramPost['taxonomy'] = Consts::TAXONOMY['department'];
            $this->responseData['posts'] = ContentService::getCmsTaxonomy($paramPost)->paginate(Consts::POST_PAGINATE_LIMIT);
        }

        return $this->responseView('frontend.pages.department.default');
    }

    public function resourceCategory($alias = null, Request $request)
    {   
        $keyword = $request->get('keyword')  ?? null;
        $paramPost['keyword'] = $keyword;

        if(Auth::guard('admin')->user() != ''){
            $paramPost['phanloai'] = '';
        }else {
            $paramPost['phanloai'] = '2';
        }

        $params['status'] = Consts::TAXONOMY_STATUS['active'];
        $params['taxonomy'] = Consts::CATEGORY['resource'];
        $this->responseData['taxonomy'] = ContentService::getCmsTaxonomy($params)->get();

        if ($alias != "" ) {

            $params['url_part'] = str_replace('.html','',$alias);
            $params['status'] = Consts::TAXONOMY_STATUS['active'];
            $params['taxonomy'] = Consts::CATEGORY['resource'];
            $taxonomyfirst = ContentService::getCmsTaxonomy($params)->first();

            if ($taxonomyfirst) {
                $id=$taxonomyfirst->id;
                $this->responseData['taxonomyfirst'] = $taxonomyfirst;
                if ($taxonomyfirst->sub_taxonomy_id != null) {
                    $str_taxonomy_id = $id . ',' . $taxonomyfirst->sub_taxonomy_id;
                    $paramPost['taxonomy_id'] = array_map('intval', explode(',', $str_taxonomy_id));
                } else {
                    $paramPost['taxonomy_id'] = $id;
                }
                $paramPost['status'] = Consts::POST_STATUS['active'];



                $this->responseData['posts'] = ContentService::getCmsResource($paramPost)->paginate(Consts::POST_PAGINATE_LIMIT);
                    
                return $this->responseView('frontend.pages.resource.category');

            } else {

                return redirect()->back()->with('errorMessage', __('not_found'));
            }

        } else {

            $paramPost['status'] = Consts::POST_STATUS['active'];

            $this->responseData['posts'] = ContentService::getCmsResource($paramPost)->paginate(Consts::POST_PAGINATE_LIMIT);
        }

        return $this->responseView('frontend.pages.resource.default');


    }

    public function resource($alias_detail = null, Request $request)
    {


        if ($alias_detail != '') {

            $params['url_part'] = str_replace('.html','',$alias_detail);
            $params['status'] = Consts::POST_STATUS['active'];
            $params['aproved_date'] = date('Y-m-d H:i:s');

            $detail = ContentService::getCmsResource($params)->first();
            if ($detail) {

                $id = $detail->id;
                $detail->view = $detail->view + 1;
                
                $detail->save();

                $this->responseData['detail'] = $detail;
                
                // $id = $detail->id;
                // $params_relative['id'] = $id;
                // $params_relative['taxonomy_id'] = $detail->taxonomy_id;
                // $params_relative['status'] = Consts::POST_STATUS['active'];
                
                // $this->responseData['posts'] = ContentService::getCmsPostRelative($params_relative)->limit(Consts::DEFAULT_RELATIVE_LIMIT)->get();

                // $this->responseData['comments'] = ContentService::getComment(['post_id'=>$id,'status'=>0])->get();

                return $this->responseView('frontend.pages.resource.detail');
            }
        }

        return redirect()->back()->with('errorMessage', __('not_found'));
    }
}
