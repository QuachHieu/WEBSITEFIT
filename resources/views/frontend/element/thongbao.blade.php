@php
  $params['status'] = App\Consts::POST_STATUS['active'];
  $params['vitri'] = 'trangchu_phai';
  
  $danhmuctin = App\Http\Services\ContentService::getCmsTaxonomy($params)
  ->get();

  $params['status'] = App\Consts::POST_STATUS['active'];
  $params['news_position'] = '2';
  $tintuc = App\Http\Services\ContentService::tinNoiBat($params)
  ->get();
@endphp
@isset($danhmuctin)
@foreach ($danhmuctin as $danhmuc)
  @php
    $title = $danhmuc->title;
    $taxonomy_url_link = route('frontend.cms.post_category', ['alias' => $danhmuc->url_part]) . '.html?id=' . $danhmuc->id;
    $iddm = $danhmuc->id;
  @endphp
<div class="homepage-tr">
    <div class="titlebar clearfix">
        <h1>
            <a href="{{ $taxonomy_url_link }}">{{ $title }}
                <a class="viewall"
                href="{{ $taxonomy_url_link }}"></a>
        </h1>
    </div>
    <div class="homeheadline clearfix">
        <div class="homeheadline-i clearfix">
            <ul class="clearfix">
                @isset($tintuc)
                    @foreach ($tintuc as $tin)
                        @if($tin->taxonomy_id == $iddm)
                            @php
                            $title = $tin->title;
                            $url_link = route('frontend.cms.post', ['alias_detail' => $tin->url_part]) . '.html';
                            @endphp
                            <li>
                                <a href="{{ $url_link }}">{{ $title }}</a>
                            </li>
                            <li class="sep"></li>
                        @endif
                    @endforeach
                @endisset
            </ul>
        </div>
    </div>
    <div class="hr"></div>
</div>
</div>
@endforeach
@endisset