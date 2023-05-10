@php
//$post_json_params = json_decode($detail->json_params);
//dd($post_json_params->content->{$locale});
$id = $detail->id;
$alias = $detail->url_part;
$title_detail = $detail->title;
$brief_detail = $detail->brief;
$content = $detail->content;
$click = $detail->click;
$tenfile = explode('/',$detail->file);
$image = $detail->image != '' ? $detail->image : null;
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

    <div class="zonepage clearfix">
        <div class="zonepage-l">
            <div class="zonebar clearfix">
                <h1>
                    <a href="#">{{ $title_detail }}</a>
                </h1>
            </div>
            <div class="storydetail clearfix">
                <div class="story_tools clearfix">
                    <span class="story_date"><i class="fa fa-calendar text-thm1"> </i>
                        {{ $date }}                      
                    </span>
                </div>
                <div class="story_head clearfix">
                    <span class="story_headline">{{ $brief_detail }}</span>
                </div>
                <div class="story_content clearfix">
                    <div class="story_body">
                        {{ $content }}
                    </div>
                </div>
                <div class="story_tags clearfix">
                    <span class="story_teaser">Tải tài liệu tại đây : 
                        <a style="color:blue" onclick="countclick('{{ $id }}')" type="button" href="javascript:;">
                        {{ array_pop($tenfile) }}</a>
                    </span>
                    <span style="float: right;" class="story_teaser">Lượt tải : {{ $click }}</span>
                </div>
                <div id="result"> </div>
            </div>

            <div class="hr"></div>

            <script>
                function countclick(id) {
                    var f = "?id="+ id;
                    var _url = "/countclick" + f;
                    $.ajax({
                        url: _url,
                        data: f,
                        cache: false,
                        context: document.body,
                        success: function(data) {
                            if(data.trim() != ''){
                                window.location.href = data;
                            }else if(data.trim() == ''){
                                alert("Tài liệu cần đăng nhập để tải");
                            }
                        }
                    });
                }
            </script>

            <script type="text/javascript">
                function popupWindow(url, width, height) {
                    var left = (screen.availWidth - width) / 2;
                    var top = (screen.availHeight - height) / 2;

                    var winDef =
                        'status=yes,resizable=yes,scrollbars=no,toolbar=no,location=no,fullscreen=no,titlebar=yes,height='
                        .concat(height).concat(',').concat('width=').concat(width).concat(',');
                    winDef = winDef.concat('top=' + top + ',left=' + left);
                    open(url, '_blank', winDef);
                }

                function print(url) {
                    popupWindow(url, 700, 450);
                }
            </script>

            <script type="text/javascript">
                $('img', '.story_body').css('height', 'auto').css('max-width', '100%');
            </script>

            <div class="hr"></div>

            <script type="text/javascript">
                function SR_selectPageIndex(index) {
                    $('#PM28_hddPageIndex').val(index);
                    __doPostBack('PM28$btnRefresh', '');
                }
            </script>
        </div>

        @include('frontend.element.news_left')

        </div>

    @include('frontend.element.footer')

    </div>

</body>

</html>
