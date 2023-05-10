
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
    .zonebarr h1 {
        clear: both;
        display: block;
        padding: 0 0 9px;
        border-bottom: #00A3E4 1px solid;
    }
    .zonebarr h1 a, .zonebarr h1 span {
        font-size: 24px;
        color: #00A3E4;
        font-weight: 400;
        text-transform: uppercase;
        font-family: calibri;
    }
    </style>
    <div class="zonepage clearfix">
            <div class="zonepage">
                <div class="zonebarr clearfix">
                    <h1>
                        <a href="#">Tài Liệu</a>
                    </h1>
                    <div style="width:24%;float:left">
                        <div class="menu">
                            <div class="title"><strong>Danh Mục</strong></span>
                                <div class="arrow"></div>
                            </div>
                            <nav class="navv" role="navigation">
                            @php
                                function showCategories($categories, $parent_id = 0, $char = '')
                                {
                                    // BƯỚC 1: LẤY DANH SÁCH CATE CON
                                    $cate_child = array();
                                    foreach ($categories as $key => $item)
                                    {
                                        // Nếu là chuyên mục con thì hiển thị
                                        if ($item['parent_id'] == $parent_id)
                                        {
                                            $cate_child[] = $item;
                                            unset($categories[$key]);
                                        }
                                    }
                                    
                                    // BƯỚC 2: HIỂN THỊ DANH SÁCH CHUYÊN MỤC CON NẾU CÓ
                                    if ($cate_child)
                                    {
                                        echo '<ul class="toggle">';
                                        foreach ($cate_child as $key => $item)
                                        {

                                            $title = $item->title;
                                            $url_link = route('frontend.cms.resource_category', ['alias' => $item->url_part]) . '.html';

                                            echo '<li>';

                                                if($item->sub > 0){

                                                    echo '<input id="'.$char.'item-'.$key.'" type="checkbox" hidden />';
                                                    echo '<label for="'.$char.'item-'.$key.'"><span class="fa fa-angle-right"></span>'.$title.'</label>';
                                                    echo '<ul class="'.$char.'item-list">';
                                                         
                                                        // Tiếp tục đệ quy để tìm chuyên mục con của chuyên mục đang lặp
                                                        showCategories($categories, $item['id'], $char.'sub-');

                                                    echo '</ul>';

                                                } else {
                                                    $active = '';
                                                    if(strpos($_SERVER["REQUEST_URI"],$item->url_part) != ''){
                                                        $active = 'active';
                                                    }
                                                    echo '<a class="'.$active.'" href="'.$url_link.'">'.$title.'</a>';
                                                }

                                            echo '</li>';
                                        }

                                        echo '</ul>';
                                    }
                                }
                                showCategories($taxonomy, $parent = 0, $sub="");
                            @endphp
                        </nav> 
                        @include('frontend.element.style_danhmuctailieu')
                        </div>
                    </div>
                    <div style="width:75%;float:right">

                        <div class="container2">
                            <div class="well well-sm">
                                <strong>{{ $taxonomyfirst->title }}</strong>
                            </div>
                            <div id="products" class="row list-group">
                                @foreach($posts as $item)
                                @php
                                    $url_link = 'tai-lieu/'.$item->url_part.'.html';
                                    $title = $item->title;
                                    $image = $item->image;
                                    $click = $item->click != '' ? $item->click : 0;
                                @endphp
                                <div class="item  col-xs-3 col-lg-3">
                                    <div class="thumbnail">
                                        <a href="{{ $url_link }}">
                                            <img style="width:195px;height: 270px;object-fit: cover;" class="group list-group-image" src="{{ $image }}" alt="" />
                                        </a>
                                        <div class="caption">
                                            <h4 style="text-align: center;" class="group inner list-group-item-heading">
                                                <a href="{{ $url_link }}">
                                                    {{ $title }}</a>
                                            </h4>
                                            <p style="text-align: center;font-weight:700;color:red" class="group inner list-group-item-text">
                                                Lượt tải : {{ $click }}
                                            </p>

                                        </div>
                                    </div>
                                </div>
                                @endforeach
                                <style>
                                    .null{
                                        padding-left: 20px;
                                        font-weight: 700;
                                        font-size: 24px;
                                    }
                                    .p:hover {
                                        background: #71a2b6 !important; 
                                        -webkit-transform: scale(1.05);
                                        color: #fff;
                                        box-shadow: 0 0 30px -10px #000
                                    }
                                </style>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="datapager"></div>

    @include('frontend.element.style_tailieu')

    </div>

    @include('frontend.element.footer')

    </div>

</body>

</html>