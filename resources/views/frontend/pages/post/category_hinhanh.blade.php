@php
    $page_title = $taxonomy->title ?? ($page->title ?? $page->name);
@endphp
<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml">
<meta http-equiv="content-type" content="text/html;charset=utf-8" />
<head>

    @include('frontend.panels.metaindex')

    @include('frontend.panels.styleindex')

</head>
<body>

    {{-- logo, tìm kiếm, đăng kí đăng nhập --}}
    @include('frontend.element.header')

    {{-- menu done--}}
    @include('frontend.element.menu')
    
    <style type="text/css">
        .gallery-item .avatar {
            background: url(../themes/frontend/cdts/images/logo.png) no-repeat center center;
        }
    </style>
    <div class="contentpage clearfix">
            <div class="contentpage-c">
                <div class="titlebar clearfix">
                    <h1>
                        <a href="#">Thư viện Ảnh</a>
                    </h1>
                </div>
                <div class="gallerylist clearfix">
                    <div class="row display-flex">
                        @foreach ($posts as $item)
                        @php
                          $title = $item->json_params->title->{$locale} ?? $item->title;
                          $image = $item->image_thumb != '' ? $item->image_thumb : ($item->image != '' ? $item->image : null);
                          $url_link = route('frontend.cms.post', ['alias_detail' => $item->url_part]) . '.html';
                        @endphp
                        <div class="col-xs-6 col-sm-3 col-lg-2 gallery-item">
                            <a class="avatar" href="{{ $url_link }}">
                                <img alt="{{ $title }}" src="{{ $image }}" />
                            </a>
                            <h4>
                                <a href="{{ $url_link }}"
                                    title="{{ $title }}">{{ $title }}</a>
                            </h4>
                        </div>
                        @endforeach
                    </div>
                </div>
                <div class="datapager"></div>
                <div class="hr"></div>
            </div>
        </div> 
   
    @include('frontend.element.footer')

    </div>

</body>

</html>
  
