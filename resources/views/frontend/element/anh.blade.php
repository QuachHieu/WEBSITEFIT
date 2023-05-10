@php
  $params['status'] = App\Consts::POST_STATUS['active'];
  $params['url_part'] = 'hinh-anh';
  
  $danhmuchinhanh = App\Http\Services\ContentService::getCmsTaxonomy($params)
  ->get();
  foreach ($danhmuchinhanh as $value) {
     $id = $value->id;
  }

  $params['status'] = App\Consts::POST_STATUS['active'];
  $params['news_position'] = '2';
  $params['is_type'] = 'post';
  if($id > 0){ $params['taxonomy_id'] = $id; }
  $hinhanh = App\Http\Services\ContentService::tinNoiBat($params)
  ->get();

@endphp
@isset($danhmuchinhanh)
@foreach ($danhmuchinhanh as $dmha)
  @php
    $title = $dmha->title;
    $url_part = $dmha->url_part;
  @endphp
<div class="homepage-cc">
    <div class="titlebar clearfix">
        <h1>
            <a href='/hinh-anh/{{ $url_part }}.html'>{{ $title }}</a>
        </h1>
    </div>
    <div class="gallerybox clearfix">
        <div class="gallerybox-i clearfix">
            <ul class="row display-flex">
                @foreach ($hinhanh as $key => $anh)
                    @php
                        $title = $anh->title;
                        $image = $anh->image;
                        $url_link = route('frontend.cms.post', ['alias_detail' => $anh->url_part]) . '.html';
                    @endphp
                        <li class="col-xs-6">
                            <div class="gallerybox-item">
                                <a class="avatar" href="{{ $url_link }}">
                                    <img src="{{ $image }}" /><i></i>
                                </a>
                                <h2>
                                    <a href="{{ $url_link }}">{{ $title }}</a>
                                </h2>
                            </div>
                            <div class="hr"></div>
                        </li>
                @endforeach
            </ul>
        </div>
    </div>
</div>
@endforeach
@endisset