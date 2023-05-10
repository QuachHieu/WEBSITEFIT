@extends('frontend.layouts.default')

@php
$page_title = $taxonomy->title ?? ($page->title ?? $page->name);
$image_background = $taxonomy->json_params->image_background ?? ($web_information->image->background_breadcrumbs ?? '');
@endphp

@section('content')
  {{-- Print all content by [module - route - page] without blocks content at here --}}



  <main class="site-content">
    <div id="fb-root" class=" fb_reset"><div style="position: absolute; top: -10000px; width: 0px; height: 0px;"><div></div></div></div>
    <script async="" defer="" crossorigin="anonymous" src="https://connect.facebook.net/vi_VN/sdk.js#xfbml=1&amp;version=v8.0&amp;appId=625475154576703&amp;autoLogAppEvents=1" nonce="09KWpjhx"></script>

    <div class="site-wrap">
        <!-- left columnn -->
                
        <aside class="column-nav">
        <h1 class="logo" title="Diễn đàn kinh tế">
        <a href="/" title="Diễn đàn kinh tế">
            <img src="{{ $web_information->image->logo_header_light ?? '' }}" alt="" class="img-fluid"></a>
        </h1>
        
        <ul class="nav flex-column">
            @isset($menu)
            @php
            $main_menu = $menu->first(function ($item, $key) {
            return $item->menu_type == 'header' && ($item->parent_id == null || $item->parent_id == 0);
            });
            $content_menu = '';
            foreach ($menu as $item) {
            $url = $title = '';
            if ($item->parent_id == $main_menu->id) {
                $title = isset($item->json_params->title->{$locale}) && $item->json_params->title->{$locale} != '' ? $item->json_params->title->{$locale} : $item->name;
                $url = $item->url_link;
                $active = $url == url()->current() ? 'current' : '';
                $icon= $item->json_params->icon ?? '';
                $content_menu .= '<li class="nav-item ' . $active . '"><a class="nav-link" href="' . $url . '"><i class="'.$icon.'"></i>' . $title . '</a></li>';
            }
            }
            echo $content_menu;
            @endphp
            @endisset
            <li class="nav-item">
            <div class="form-group search-wrap my-3 px-1">
                <input type="text" class="form-control textFind" id="txtFind" placeholder="Tìm kiếm" spellcheck="false">
                <a href="javascript:void(0)" title="Tìm kiếm" class="fal fa-search"></a>
            </div>
            </li>
            <li class="nav-item"><a class="nav-link" href="javascript:void(0)" onclick="Member.RequireLogin();" title="Đăng nhập"><i class="fad fa-sign-in"></i>Đăng nhập/Đăng ký</a></li>
        </ul>
        
        </aside>

        <!-- column content -->
        <div class="column-content">
        
        @include('frontend.element.adsheader')

        <div class="column-wrap">
            <div class="column-main" id="column-main"><div class="wrap">
                
            <section class="box box--composer">
                <div class="box__content">
                <div class="composer-wrap">
                    <div class="avatar">
                    <img src="/themes/frontend/biz9/images/user.png" alt="" class="img-fluid rounded-circle">
                    </div>
                    <h3 class="name">
                    <p></p>
                    </h3>
                    <div class="input">
                    <div class="form-group">
                        <input id="mainComposer" type="text" class="form-control" placeholder="Viết bài chia sẻ, đặt câu hỏi, Share link" spellcheck="false">
                    </div>
                    </div>
                    <div class="input is-full">
                    <div class="form-group editor">
                        
                        <div contenteditable="true" id="editor" class="form-control auto-size" spellcheck="false" style="height: 36px; resize: none;">
                        <p>&nbsp;</p>
                        </div>
                    </div>
                    <div class="form-group">
                        <a href="javascript:void(0)" id="btnUploadImage" class="btn btn-warning btn-sm rounded-pill btnUploadImage"><i class="fal fa-upload mr-2"></i>
                        <input type="file" class="form-control-file" id="inputImage" accept="image/png, image/jpeg, img/gif" onchange="Images.UploadImage(this)" multiple="multiple" spellcheck="false">
                        Tải ảnh </a>
                        
                        <div class="float-right d-none" id="loading-container">
                        <img src="https://static.nguoimuanha.vn/images/common/loading.svg" height="30">
                        </div>
                    </div>
                    <input type="hidden" id="hdGalleryImages" name="hdGalleryImages" spellcheck="false">
                    <ul class="gallery" id="gallery-images"></ul>
                    <div class="crawler d-none" id="scrape-article">
                        
                    </div>
                    <input type="hidden" id="hdScrapeArticle" name="hdScrapeArticle" spellcheck="false">
                    <div class="my-2">
                        <button type="button" id="btnPost" class="btn btn-primary btn-sm w-100">Đăng</button>                    
                    </div>
                    </div>
                    <div class="tool">
                    <a href="#" class="tool-btn"><i class="fal fa-image"></i></a>
                    <a href="#" class="tool-btn btn-close"><i class="fal fa-times"></i></a>
                    </div>
                </div>
                <div class="composer-mask"></div>
                </div>
            </section>

            @foreach ($posts as $item)
            @php
              $title = $item->json_params->title->{$locale} ?? $item->title;
              $brief = $item->json_params->brief->{$locale} ?? '';
              $image = $item->image_thumb != '' ? $item->image_thumb : ($item->image != '' ? $item->image : null);
              $date = date('H:i d/m/Y', strtotime($item->created_at));
              // Viet ham xu ly lay alias bai viet
              $alias_category = Str::slug($item->taxonomy_title);
              $alias_detail = Str::slug($title);
              $url_link = route('frontend.cms.post', ['alias_category' => $alias_category, 'alias_detail' => $alias_detail]) . '.html?id=' . $item->id;
              $taxonomy_url_link = route('frontend.cms.post_category', ['alias' => $alias_category]) . '.html?id=' . $item->taxonomy_id;
            @endphp
          <article class="story story--flex story--round " id="article{{ $item->id }}">
                <div class="story__meta">
                    <div class="story__avatar">
                        <img src="{{ $image }}" alt="{{ $item->author !='' ? $item->author : $item->fullname }}" class="img-fluid rounded-circle">
                    </div>
                    <div class="story__info">
                        <h3 class="story__author">{{ $item->author !='' ? $item->author : $item->fullname }}</h3>
                        <div class="story__time"><time datetime="{{ $item->updated_at.':000' }}" class="time-ago"></time></div>
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
                        <div class="fb-like fb_iframe_widget" data-href="{{ $url_link }}" data-width="" data-layout="button_count" data-action="like" data-size="small" data-share="true" fb-xfbml-state="rendered" fb-iframe-plugin-query="action=like&amp;app_id=625475154576703&amp;container_width=0&amp;href=https%3A%2F%2Fnguoimuanha.vn%2Fphan-khuc-bds-sieu-re-duoi-300tr--thi-truong-lon-con-bo-ngo-60893.html&amp;layout=button_count&amp;locale=vi_VN&amp;sdk=joey&amp;share=true&amp;size=small&amp;width="><span style="vertical-align: top; width: 0px; height: 0px; overflow: hidden;"><iframe name="f1efc6442c90b9" width="1000px" height="1000px" data-testid="fb:like Facebook Social Plugin" title="fb:like Facebook Social Plugin" frameborder="0" allowtransparency="true" allowfullscreen="true" scrolling="no" allow="encrypted-media" src="https://www.facebook.com/v4.0/plugins/like.php?action=like&amp;app_id=625475154576703&amp;channel=https%3A%2F%2Fstaticxx.facebook.com%2Fx%2Fconnect%2Fxd_arbiter%2F%3Fversion%3D46%23cb%3Df3e94987a1366fc%26domain%3Dnguoimuanha.vn%26is_canvas%3Dfalse%26origin%3D{{ $url_link }}&amp;layout=button_count&amp;locale=vi_VN&amp;sdk=joey&amp;share=true&amp;size=small&amp;width=" style="border: none; visibility: visible; width: 0px; height: 0px;"></iframe></span></div>
                    </div>
                    <a href="javascript:void(0)" title="Bình luận" class="story__react comment" data-article="{{ $item->id }}"><i class="fal fa-comment"></i><span></span></a>
                    <a href="javascript:void(0)" title="Lưu bài viết" class="story__react love" data-article="{{ $item->id }}"><i class="fal fa-heart"></i></a>
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
                            <span class="btn-send pointer" title="Gửi bình luận"><i class="fas fa-paper-plane"></i></span>
                        </div>
                    </div>
                </div>
                <div class="story__extend">
                    <div class="dropdown">
                        <a class="" href="#" role="button" id="dropdownMenuLink1" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"><i class="far fa-ellipsis-h"></i></a>
                        <div class="dropdown-menu dropdown-menu-right moreActionArticle" aria-labelledby="dropdownMenuLink1" data-user="377" data-article="60893"><a class="dropdown-item aNotiArticle" href="javascript:void(0)" onclick="FollowingArticles.Follow('{{ $item->id }}')"><i class="fal fa-bell mr-2"></i>Thông báo khi có bình luận</a><a class="dropdown-item aFollow" href="javascript:void(0)" onclick="Following.Follow(377)"> <i class="fal fa-user-plus mr-2"></i>Theo dõi tác giả</a>
                            
                            <a class="dropdown-item getLinkArticle" href="javascript:void(0)" data-toggle="modal" data-url="{{ $url_link }}"><i class="fal fa-link mr-2"></i>Lấy link bài viết</a>

                            <a class="dropdown-item text-danger reportArticle" href="javascript:void(0)" data-toggle="modal" data-target="#modalReport" data-article="{{ $item->id }}"><i class="fal fa-exclamation-square mr-2"></i>Báo cáo bài viết</a>
                        </div>
                    </div>
                </div>
            </article>
            @endforeach

            {{ $posts->withQueryString()->links('frontend.pagination.default') }}
          </div>
          </div>
                
            @include('frontend.element.sidebar')    
            
            </div>
        </div>
    </div>

    <div class="toast hide" id="toast" data-delay="5000">
        <div class="toast-header">
            <strong class="mr-auto">Thông báo</strong>
            <small></small>
            <button type="button" class="ml-2 mb-1 close" data-dismiss="toast" aria-label="Close">
                <span aria-hidden="true">×</span>
            </button>
        </div>
        <div class="toast-body">
            <div class="toast-avatar" id="toast-avatar"></div>
            <div class="toast-content">
                <div class="text"><span class="name" id="toast-username"></span>vừa bình luận bài viết <a class="text-link" id="toast-link"></a></div>
            </div>
        </div>
    </div>
</main>

  {{-- End content --}}
@endsection
