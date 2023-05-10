@extends('frontend.layouts.default')
@php
//$post_json_params = json_decode($detail->json_params);
//dd($post_json_params->content->{$locale});
$title_detail = $detail->title;
$brief_detail = $detail->brief;
$content = $detail->content;
$image = $detail->image != '' ? $detail->image : null;
$image_thumb = $detail->image_thumb != '' ? $detail->image_thumb : null;
$date = date('H:i d/m/Y', strtotime($detail->created_at));

// For taxonomy
$taxonomy_json_params = json_decode($detail->taxonomy_json_params);
$taxonomy_title = $detail->taxonomy_title ?? $detail->taxonomy_title;
$image_background = $taxonomy_json_params->image_background ?? ($web_information->image->background_breadcrumbs ?? null);
$taxonomy_alias = $detail->taxonomy_url_part !='' ? $detail->taxonomy_url_part : Str::slug($taxonomy_title);
$alias_detail = $detail->url_part !='' ? $detail->url_part :Str::slug($title_detail);
$taxonomy_url_link = route('frontend.cms.post_category', ['alias' => $taxonomy_alias]) . '.html?id=' . $detail->taxonomy_id;
$url_link = route('frontend.cms.post', ['alias_category' => $taxonomy_alias, 'alias_detail' => $alias_detail]) . '.html?id=' . $detail->id;

$seo_title = $detail->json_params->seo_title ?? $title_detail;
$seo_keyword = $detail->json_params->seo_keyword ?? null;
$seo_description = $detail->json_params->seo_description ?? $brief_detail;
$seo_image = $image ?? ($image_thumb ?? null);
//echo "AAAAAAAAAAAA".$content;die;

if(Auth::guard('web')->check()) {
    $hanhdong = '';
    $sendcomment = 'onclick=sentComment()';
    $avatar = Auth::guard('web')->user()->avatar;
    if($avatar==''){
        $avatar = '/themes/frontend/biz9/images/user.png';
    }
}else{
    $hanhdong = 'onclick=dangnhap()';
    $avatar = '/themes/frontend/biz9/images/user.png';
    $sendcomment = 'onclick=dangnhap()';
}

@endphp

@section('content')

{{-- session('successMessage') ?? 'aaaa' --}}

@if (session('successMessage'))
<div id="snackbar">{{ session('successMessage') }}</div>
<script>
    $(document).ready(function() {
    var x = document.getElementById("snackbar");
    x.className = "show";
    setTimeout(function(){ x.className = x.className.replace("show", ""); }, 5000);
    });
</script>
@endif

<main class="site-content">
    <div id="fb-root" class=" fb_reset"><div style="position: absolute; top: -10000px; width: 0px; height: 0px;"><div></div></div></div>
    <script async="" defer="" crossorigin="anonymous" src="https://connect.facebook.net/vi_VN/sdk.js#xfbml=1&amp;version=v8.0&amp;appId=625475154576703&amp;autoLogAppEvents=1" nonce="09KWpjhx"></script>

    <div class="site-wrap">
        <!-- left columnn -->
        
        @include('frontend.element.menuleft')

        <!-- column content -->
        <div class="column-content">
        
        @include('frontend.element.adsheader')

        <div class="column-wrap">
            <div class="column-main" id="column-main"><div class="wrap">
                
            {{-- @include('frontend.element.dangbai') --}}

            <article class="detail-wrap">
              <header class="detail__header">
                  <div class="detail__meta">
                      <div class="detail__avatar">
                          <img src="{{ $detail->avatar ?? '/images/noiavatar.png' }}" alt="{{ $detail->author !='' ? $detail->author : $detail->fullname }}" class="img-fluid rounded-circle">
                      </div>
                      <div class="detail__info">
                          <h3 class="detail__author">{{ $detail->author !='' ? $detail->author : $detail->fullname }}</h3>
                          <div class="detail__time"><time datetime="{{ $detail->created_at.':000' }}" class="time-ago">{{ App\Http\Services\ContentService::postTime($detail->aproved_date) }}</time></div>
                      </div>
                      <div class="fb_share" style="flex: 1;float: right;text-align: right;">
                        <div class="fb-like fb_iframe_widget" data-href="{{ $url_link }}" data-width="" data-layout="button_count" data-action="like" data-size="small" data-share="true" fb-xfbml-state="rendered" fb-iframe-plugin-query="action=like&amp;app_id=625475154576703&amp;container_width=0&amp;href={{ $url_link }}&amp;layout=button_count&amp;locale=vi_VN&amp;sdk=joey&amp;share=true&amp;size=small&amp;width=">
                        </div>
                      </div>
                  </div>
                  
                  <h1 class="detail__title">{{ $title_detail }}</h1>
                  <h2 class="detail__summary">{{ $brief_detail }}</h2>
              </header>
                            
              <div class="detail__content mt-4">
                  {!! $content !!}
              </div>
              
              <footer class="detail__footer" id="detail__footer">
                  <div class="detail__react">
                      <a href="javascript:void(0)" title="Bình luận" class="item story__react comment" data-article="{{ $detail->id }}"><i class="fal fa-comment"></i><span>{{ $detail->comment_count }}</span></a>
                      <a href="javascript:void(0)" title="Chia sẻ lên facebook" class="item story__react love" data-article="{{ $detail->id }}"><i class="fal fa-share"></i></a>
            
                    </div>
                  <section class="zone mt-3">
                      <div class="zone__header">
                          <h2 class="zone__title"><span>Bình luận</span></h2>
                      </div>
                        <div class="zone__content">
                            <form id="portComment" action="{{ route('frontend.comment.store') }}" method="POST" enctype="multipart/form-data" >
                                @csrf
                                <div class="story__comment" data-count-comment="{{ $detail->comment_count }}" style="display: block;">
                                    <div class="comment-listing" id="post{{ $detail->id }}">
                                        @foreach($comments as $comment)
                                        <div class="comment__item comment-parent" id="{{ $comment->id }}">
                                            <div class="avatar"><img src="{{ $comment->avatar }}" alt="{{ $comment->comment_name }}" class="img-fluid rounded-circle"></div>
                                            <div class="content">
                                                <h3 class="name">{{ $comment->comment_name }} <time class="time-ago" datetime="{{ $comment->created_at }}">{{ date('H:i d/m/Y',strtotime($comment->created_at)) }}</time></h3>
                                                <div class="text">
                                                    @php
                                                        if($comment->image_comment !=""){
                                                            echo "<img src=".$comment->image_comment." />";
                                                        }
                                                    @endphp
                                                    {{ $comment->content }}&nbsp;
                                                </div>       
                                            </div>
                                            <div class="comment-react hidden">
                                                <a href="javascript:void(0)" title="Thích" class="text-link like"><i class="far fa-thumbs-up"></i> <span id="likeNumber_e3eda16e-96a3-43b4-b625-8b98188c7dc6">6</span></a>
                                                <a href="javascript:void(0)" title="Trả lời" class="text-link reply"><i class="fal fa-reply"></i></a>
                                                <a href="javascript:void(0)" title="Chia sẻ lên facebook" class="text-link share"><i class="fal fa-share"></i></a>
                                                <a href="javascript:void(0)" title="Báo cáo bình luận không phù hợp" class="text-link report"><i class="fal fa-flag"> </i></a>
                                            </div>
                                        </div>
                                        @endforeach
                                    </div>
                                    <div class="input-wrap">
                                        <div class="avatar avatarUser"></div>
                                        <div class="content">
                                            <div contenteditable="true" id="input" {{ $hanhdong }} draggable="true" class="form-control bg-light editor inputComment auto-size" spellcheck="false" data-id="post{{ $detail->id }}"></div>
                                            <input type="hidden" name="content" id="content_comment" >
                                            <input type="hidden" name="post_id" id="post_id" value="{{ $detail->id }}">
                                            <input type="hidden" name="url_link" id="url_link" value="{{ $url_link }}">
                                            <span class="fal fa-image commentUploadImage">
                                                <input type="file" name="image" {{ $hanhdong }} accept="image/png, image/jpeg, img/gif" >
                                            </span>
                                            <a {{ $sendcomment }}><span class="btn-send pointer" title="Gửi bình luận"><i class="fas fa-paper-plane"></i></span></a>
                                        </div>
                                    </div>
                                </div>
                            </form>
                      </div>
                  </section>
              </footer>
          </article>
            <script>
                function sentComment(){
                    var myElement = document.getElementById("input");
                    var myform = document.getElementById("portComment");
                    var content =  myElement.innerHTML ;
                    $('#content_comment').val(content);

                    myform.submit();
                    
                }
            </script>   
            
            @foreach ($posts as $item)
            @php
              $title = $item->json_params->title->{$locale} ?? $item->title;
              $brief = $item->json_params->brief->{$locale} ?? '';
              $image = $item->image_thumb != '' ? $item->image_thumb : ($item->image != '' ? $item->image : null);
              $date = date('H:i d/m/Y', strtotime($item->created_at));
              // Viet ham xu ly lay alias bai viet
              //$alias_category = Str::slug($item->taxonomy_title);
              //$alias_detail = Str::slug($title);
              $alias_category = $detail->taxonomy_url_part !='' ? $detail->taxonomy_url_part : Str::slug($item->taxonomy_title);
              $alias_detail = $detail->url_part !='' ? $detail->url_part :Str::slug($title);
              $url_link = route('frontend.cms.post', ['alias_category' => $alias_category, 'alias_detail' => $alias_detail]) . '.html?id=' . $item->id;
              $taxonomy_url_link = route('frontend.cms.post_category', ['alias' => $alias_category]) . '.html?id=' . $item->taxonomy_id;
              $hienthingay = App\Http\Services\ContentService::postTime($detail->aproved_date);
              @endphp
          <article class="story story--flex story--round " id="article{{ $item->id }}">
                <div class="story__meta">
                    <div class="story__avatar">
                        <img src="{{ $image }}" alt="{{ $item->author !='' ? $item->author : $item->fullname }}" class="img-fluid rounded-circle">
                    </div>
                    <div class="story__info">
                        <h3 class="story__author">{{ $item->author !='' ? $item->author : $item->fullname }}</h3>
                        <div class="story__time"><time datetime="{{ $item->updated_at.':000' }}" class="time-ago">{{ $hienthingay }}</time></div>
                    </div>
                </div>
                <div class="story__header">
                    <h3 class="story__title">{{ $title }}</h3>
                    <div class="story__summary">
                        {{ $brief }}
                        <a href="{{ $url_link }}" class="view-more">Xem thêm</a>
                        <div class="post-content d-none">
                            
                        </div>
                    </div>
                </div>
                <div class="story__images lightgallery">
                    
                  <div data-src="{{ $image }}" class="item">
                      <img src="{{ $image }}" alt="{{ $title }}" class="img-fluid" title="{{ $title }}">
                  </div>
                        
                </div>
                <footer class="story__footer">
                    <div class="story__react share">
                        <div class="fb-like fb_iframe_widget" data-href="{{ $url_link }}" data-width="" data-layout="button_count" data-action="like" data-size="small" data-share="true" fb-xfbml-state="rendered" fb-iframe-plugin-query="action=like&amp;app_id=625475154576703&amp;container_width=0&amp;href={{ $url_link }}&amp;layout=button_count&amp;locale=vi_VN&amp;sdk=joey&amp;share=true&amp;size=small&amp;width=">
                            
                        </div>
                    </div>
                    <a href="{{ $url_link }}#detail__footer" title="Bình luận" class="story__react comment" data-article="{{ $item->id }}"><i class="fal fa-comment"></i><span></span></a>
                    <a href="javascript:void(0)" title="Chia sẻ lên facebook" class="story__react love" data-article="{{ $item->id }}"><i class="fal fa-share"></i></a>
                </footer>

                <div class="story__comment" data-count-comment="0" >
                  <div class="comment-listing" id="post{{ $item->id }}" data-url="{{ $url_link }}"></div>
                    <div class="input-wrap">
                        <div class="avatar avatarUser"></div>
                        <div class="content">
                            <div contenteditable="true" draggable="true" class="form-control bg-light editor inputComment auto-size" spellcheck="false" data-id="post{{ $item->id }}"></div>
                            <span class="fal fa-image commentUploadImage">
                                <input type="file" accept="image/png, image/jpeg, img/gif" onchange="Images.UploadImage(this,$(this).parent().prev())">
                            </span>
                            <span class="btn-send pointer"  title="Gửi bình luận"><i class="fas fa-paper-plane"></i></span>
                        </div>
                    </div>
                </div>
                <div class="story__extend hidden">
                    <div class="dropdown">
                        <a class="" href="#" role="button" id="dropdownMenuLink1" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"><i class="far fa-ellipsis-h"></i></a>
                        <div class="dropdown-menu dropdown-menu-right moreActionArticle" aria-labelledby="dropdownMenuLink1" data-user="377" data-article="{{ $item->id }}">
                            <a class="dropdown-item aNotiArticle" href="javascript:void(0)" onclick="FollowingArticles.Follow('{{ $item->id }}')"><i class="fal fa-bell mr-2"></i>Thông báo khi có bình luận</a>
                            <a class="dropdown-item aFollow" href="javascript:void(0)" onclick="Following.Follow(377)"> <i class="fal fa-user-plus mr-2"></i>Theo dõi tác giả</a>
                            <a class="dropdown-item getLinkArticle" href="javascript:void(0)" data-toggle="modal" data-url="{{ $url_link }}"><i class="fal fa-link mr-2"></i>Lấy link bài viết</a>
                            <a class="dropdown-item text-danger reportArticle" href="javascript:void(0)" data-toggle="modal" data-target="#modalReport" data-article="{{ $item->id }}"><i class="fal fa-exclamation-square mr-2"></i>Báo cáo bài viết</a>
                        </div>
                    </div>
                </div>
            </article>
            @endforeach
          </div>
          
            </div>
                
            @include('frontend.element.sidebar')    
            
            </div>
        </div>
    </div>
    
</main>



  {{-- End content --}}
@endsection
