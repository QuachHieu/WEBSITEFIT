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

    <div class="zonepage clearfix">
        <div class="zonepage-l">
            <div class="zonebar clearfix">
                <h1>
                    <a href="#">{{ $page_title }}</a>
                </h1>
            </div>
            <div class="zoneteaser clearfix">
                <ul>
                    @foreach ($posts as $item)
                    @php
                      $title = $item->json_params->title->{$locale} ?? $item->title;
                      $brief = $item->json_params->brief->{$locale} ?? $item->brief;
                      $image = $item->image_thumb != '' ? $item->image_thumb : ($item->image != '' ? $item->image : null);
                      $date = date('H:i d/m/Y', strtotime($item->created_at));
                      // Viet ham xu ly lay alias bai viet
                      $alias_category = Str::slug($item->taxonomy_title);
                      //$alias_detail = Str::slug($title);
                      $url_link = route('frontend.cms.post', ['alias_detail' => $item->url_part]) . '.html';
                      $taxonomy_url_link = route('frontend.cms.post_category', ['alias' => $alias_category]) . '.html?id=' . $item->taxonomy_id;
                      $hienthingay = App\Http\Services\ContentService::postTime($item->aproved_date);
                   @endphp
                    <li>
                        <h4>
                            <a href="{{ $url_link }}">{{ $title }}</a>
                        </h4>
                        <a class="avatar" href="{{ $url_link }}">
                            <img src="{{ $image }}" />
                        </a>

                        <p>
                            {{ $brief }}
                        </p>
                        <div class="viewmore"><i class="fa fa-calendar text-thm1"> </i>
                            {{ $date }}                      
                        </div>
                        <div class="viewmore">
                            <a href="{{ $url_link }}">Xem
                                chi tiết</a>
                        </div>
                        
                    </li>
                    <li class="sep"></li>
                    @endforeach
                </ul>
            </div>
            <div class="datapager"></div>
            <div class="hr"></div>
        </div>

        @include('frontend.element.news_left')

    </div>

    {{-- ảnh --}}
    @include('frontend.element.footer')

    </div>

</body>

</html>
  
