@php
  $params['status'] = App\Consts::POST_STATUS['active'];
  $params['news_position'] = '2';
  
  if(Auth::guard('admin')->user()){
        $params['phanloai'] = '';
    }else {
        $params['phanloai'] = '2';
    }
  
  $tailieu = App\Http\Services\ContentService::getCmsResource($params)
  ->get();
@endphp
@isset($tailieu)
<div class="titlebar clearfix">
    <h1>
        <a href="/dm-tai-lieu">Tài liệu</a>
    </h1>
</div>
<script>
    $(document).ready(function() {
    $('#autoWidth').lightSlider({
    autoWidth:true,
    loop:true,
    onSliderLoad: function() {
        $('#autoWidth').removeClass('cS-hidden');
    } 
    });  
    });
</script>
<section class="slider">
    <ul id="autoWidth" class="cs-hidden">
         @foreach ($tailieu as $tl)
            @php
                $title = $tl->title;
                $url_link = 'tai-lieu/'.$tl->url_part.'.html';
                $image = $tl->image;
                $click = $tl->click;
            @endphp
                <li class="item-a">
                    <div class="box">
                        <div class="slide-img">
                            <img alt="{{ $title }}" src="{{ $image }}">
                            <div class="overlay">
                                <a href="{{ $url_link }}" class="buy-btn">Chi Tiết</a>    
                            </div>
                        </div>
                        <div class="detail-box">
                            <div class="type">
                                <a href="{{ $url_link }}"><p class="text">{{ $title }}</p></a>
                                <p style="text-align: center;font-weight:700;color:red" class="group inner list-group-item-text">Lượt tải : {{ $click }}</p>
                            </div>
                        </div>
                    </div>      
               </li>
        @endforeach
    </ul>
</section>
<div class="hr"></div>
@endisset
<style type="text/css">
    body{
    margin:0px;
    padding: 0px;
}
a{
    text-decoration:none;
}
.box{
    width:220px;
    box-shadow: 2px 2px 30px rgba(0,0,0,0.2);
    overflow: hidden;
    margin:5px;
}
.slide-img{
    height: 260px;
    position:relative;
}
.slide-img img{
    width:100%;
    height: 100%;
    object-fit: cover;
    box-sizing: border-box;
}

.type{
    display: flex;
    flex-direction: column;
}
.type a{
    color:#222222;
    margin: 5px 0px;
    font-weight: 700;
    letter-spacing: 0.5px;
    padding-right: 8px;
}
.type span{
    color:rgba(26,26,26,0.5);
}
.price{
    color: #333333;
    font-weight: 600;
    font-size: 1.1rem;
    font-family: poppins;
    letter-spacing: 0.5px;
}
.overlay{
    position: absolute;
    left: 50%;
    top: 50%;
    transform: translate(-50%,-50%);
    width:100%;
    height: 100%;
    background-color: rgba(0,0,0,0.6);
    display: flex;
    justify-content: center;
    align-items: center;
}
.buy-btn{
    width:160px;
    height: 40px;
    display: flex;
    justify-content: center;
    align-items: center;
    background-color: #FFFFFF;
    color:#252525;
    font-weight: 700;
    letter-spacing: 1px;
    font-family: calibri;
    border-radius: 20px;
    box-shadow: 2px 2px 30px rgba(0,0,0,0.2);
}
.buy-btn:hover{
    color:#FFFFFF;
    background-color: #197B30;
    transition: all ease 0.3s;
}
.overlay{
    visibility: hidden;
}
.slide-img:hover .overlay{
    visibility: visible;
    animation:fade 0.5s;
}
 
@keyframes fade{
    0%{
        opacity: 0;
    }
    100%{
        opacity: 1;
    }
}
.slider{
    width:100%;
    display: flex;
    justify-content: center;
    align-items: center;
}
</style>
        <!-- 
<div class="titlebar clearfix">
    <h1>
        <a href="/tai-lieu.html">Tài liệu giáo viên</a>
    </h1>
</div>
<script>
    $(document).ready(function() {
    $('#autoWidth1').lightSlider({
    autoWidth:true,
    loop:true,
    onSliderLoad: function() {
        $('#autoWidth1').removeClass('cS-hidden');
    } 
    });  
    });
</script>
<section class="slider">
            <div class="titlebar clearfix">
            <h1>
                <a class="open-popup-link2">(ĐĂNG NHẬP ĐỂ XEM TÀI LIỆU)</a>
            </h1>
        </div>
        
</section>

<div class="hr"></div>

 -->