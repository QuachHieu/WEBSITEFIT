@php
    $title_detail = $detail->title;
    $brief_detail = $detail->brief;
    $content = $detail->content;
    $image = $detail->image != '' ? $detail->image : null;
    $file = $detail->file != '' ? $detail->file : null;
    $image_thumb = $detail->image_thumb != '' ? $detail->image_thumb : null;
    $date = date('H:i d/m/Y', strtotime($detail->created_at));
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
        .video-item .avatar {
            background: url(../themes/frontend/cdts/images/logo.png) no-repeat center center;
        }
    </style>

   <div class="contentpage clearfix">
                
                <div class="contentpage-c">


                <div class="titlebar clearfix">
                    <h1>
                        <a href="#">Thư viện Video</a>
                    </h1>
                </div>
                <div class="videocont clearfix">
                    
                    <div class="row">
                        <div class="col-md-8">

                            <div class="videodetail-player clearfix">
                                <video id="videoJS" class="video-js vjs-default-skin vjs-16-9" controls
                                    autoplay="true" preload="auto" width="100%" height="100%"
                                    poster="/"
                                    data-setup='{}'>
                                    <source src="{{ $file }}" type="video/mp4">
                                    <p class="vjs-no-js">
                                        <a href="{{ $file }}" target="_blank">{{ $title_detail }}</a></p>
                                </video>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="videodetail clearfix">
                                <h1>
                                    {{ $title_detail }}                               
                                </h1>
                                <div class="videodetail-i clearfix">
                                    <p>{{ $brief_detail }}</p>
                                    <!-- <h5>
                                        <span>Đã xem:
                                            6247</span>
                                    </h5> -->
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="hr"></div>

                <div class="videolist clearfix">
                    <div class="row display-flex">
                        @foreach($posts as $item)
                            @php
                                $title = $item->json_params->title->{$locale} ?? $item->title;
                                $image = $item->image_thumb != '' ? $item->image_thumb : ($item->image != '' ? $item->image : null);
                                $url_link = route('frontend.cms.post_video', ['alias' => $item->url_part]) . '.html';
                            @endphp
                            <div class="col-xs-6 col-sm-3 col-lg-2 video-item">
                                <a class="avatar" href="{{ $url_link }}" title="{{ $title }}">
                                    <img alt="{{ $title }}" src="{{ $image }}" />
                                </a>
                                <h4>
                                    <a href="{{ $url_link }}" title="{{ $title }}">{{ $title }}</a>
                                </h4>
                            </div>
                        @endforeach
                    </div>
                </div>
                
                <!-- <div class="datapager">
                    <span class="active">1</span>
                    <a href="video2679.html?page=1">2</a>
                    <a href="video4658.html?page=2">3</a>
                    <a href="video9ba9.html?page=3">4</a>
                    <a href="videofdb0.html?page=4">5</a>
                    <a href="videoaf4d.html?page=5">6</a>
                    <a href="videoc575.html?page=6">7</a>
                    <a href="video235c.html?page=7">8</a>
                    <a href="videofdfa.html?page=8">9</a>
                    <a href="video0b08.html?page=9">10</a>
                    <a class="next" href="video1448.html?page=10">Tiếp</a>
                </div> -->




                <div class="hr"></div>

        </div>

    </div>
   
    @include('frontend.element.footer')

    </div>

</body>

</html>
  
