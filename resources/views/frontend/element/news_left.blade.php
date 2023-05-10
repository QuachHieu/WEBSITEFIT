@php

  $params['status'] = App\Consts::POST_STATUS['active'];
  $params['taxonomy_id'] = '41';
  $params['news_position'] = '1';
  $params['is_type'] = 'post';
  $tintuc = App\Http\Services\ContentService::tinNoiBat($params)
  ->get();

  $params['status'] = App\Consts::POST_STATUS['active'];
  $params['taxonomy_id'] = '44';
  $params['news_position'] = '1';
  $params['is_type'] = 'post';
  $thongbao = App\Http\Services\ContentService::tinNoiBat($params)
  ->get();
@endphp
<div class="zonepage-r">
    <div class="titlebar clearfix">
        <h1>
            <span>TIN TỨC MỚI</span>
        </h1>
    </div>
    <div class="lateststory clearfix">
        <div class="lateststory-i clearfix">
            <ul>
                @foreach ($tintuc as $tt)
                    @php
                        $title = $tt->title;
                        $url_link = route('frontend.cms.post', ['alias_detail' => $tt->url_part]) . '.html';
                        $image = $tt->image;
                    @endphp
                    <li>
                        <a class="avatar" href="{{ $url_link }}">
                            <img src="{{ $image }}" />
                        </a>
                        <a href="{{ $url_link }}">{{ $title }}</a>
                    </li>
                    <li class="sep"></li>
                @endforeach
            </ul>
        </div>
    </div>

    <div class="hr"></div>
    <div class="titlebar clearfix">
        <h1>
            <span>THÔNG BÁO</span>
        </h1>
    </div>
    <div class="topstory clearfix">
        <div class="topstory-i clearfix">
            <ul>
                 @foreach ($thongbao as $tb)
                    @php
                        $title = $tb->title;
                        $url_link = route('frontend.cms.post', ['alias_detail' => $tb->url_part]) . '.html';
                        $image = $tb->image;
                    @endphp
                    <li>                        
                        <a href="{{ $url_link }}">{{ $title }}</a>                         
                    </li>
                    <li class="sep"></li>
                @endforeach    
            </ul>
        </div>
    </div>
    <div class="hr"></div>
</div>