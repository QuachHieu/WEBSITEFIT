@extends('frontend.layouts.default')
@php
/*
$page_title = $taxonomy->title ?? ($page->title ?? $page->name);
$image_background = $taxonomy->json_params->image_background ?? ($web_information->image->background_breadcrumbs ?? '');

$title = $taxonomy->json_params->title->{$locale} ?? ($taxonomy->title ?? null);
$image = $taxonomy->json_params->image ?? null;
$seo_title = $taxonomy->json_params->seo_title ?? $title;
$seo_keyword = $taxonomy->json_params->seo_keyword ?? null;
$seo_description = $taxonomy->json_params->seo_description ?? null;
$seo_image = $image ?? null;
*/
@endphp

@section('content')
  
  
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
                
            @include('frontend.element.dangbai')

            @foreach ($posts as $item)
            @php
              $title = $item->json_params->title->{$locale} ?? $item->title;
              $brief = $item->json_params->brief->{$locale} ?? '';
              $image = $item->image_thumb != '' ? $item->image_thumb : ($item->image != '' ? $item->image : null);
              $date = date('H:i d/m/Y', strtotime($item->created_at));
              // Viet ham xu ly lay alias bai viet
              $alias_category = Str::slug($item->taxonomy_title);
              //$alias_detail = Str::slug($title);
              $url_link = route('frontend.cms.post', ['alias_detail' => $item->url_part]) . '.html';
              $taxonomy_url_link = route('frontend.cms.post_category', ['alias' => $alias_category]) . '.html?id=' . $item->taxonomy_id;
              $hienthingay = App\Http\Services\ContentService::postTime($item->aproved_date);
           @endphp
          <article class="story story--flex story--round " id="article{{ $item->id }}">
            <div class="story__meta">
                <div class="story__avatar">
                    <img src="{{ $item->avatar ?? '/images/noiavatar.png' }}" alt="{{ $item->author !='' ? $item->author : $item->fullname }}" class="img-fluid rounded-circle">
                </div>
                <div class="story__info">
                    <h3 class="story__author">{{ $item->author !='' ? $item->author : $item->fullname }}</h3>
                    <div class="story__time"><time datetime="{{ $item->updated_at.':000' }}" class="time-ago">{{$hienthingay}}</time></div>
                </div>
            </div>
            <div class="story__header">
                <h3 class="story__title">{{ $title }}</h3>
                <div class="story__summary">
                    {{ $brief }}
                    <a href="{{ $url_link }}" class="view-more">Xem thêm</a>
                    <div class="post-content d-none"></div>
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
                <a href="{{ $url_link }}#detail__footer" title="Bình luận" class="story__react comment" data-article="{{ $item->id }}"><i class="fal fa-comment"> {{ $item->comment_count }}</i><span></span></a>
                <a href="javascript:void(0)" title="Chia sẻ lên facebook" class="story__react love" data-article="{{ $item->id }}"><i class="fal fa-share"></i></a>
            </footer>

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


@endsection
