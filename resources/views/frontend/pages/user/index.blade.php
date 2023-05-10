@extends('frontend.layouts.default')

@section('content')
<main class="site-content">
    <div id="fb-root" class=" fb_reset">
      <div style="position: absolute; top: -10000px; width: 0px; height: 0px;">
        <div></div></div></div>
      <script async="" defer="" crossorigin="anonymous" src="https://connect.facebook.net/vi_VN/sdk.js#xfbml=1&amp;version=v8.0&amp;appId=625475154576703&amp;autoLogAppEvents=1" nonce="09KWpjhx"></script>

      <div class="site-wrap">
          <!-- left columnn -->
        @include('frontend.element.menuleft')

          <!-- column content -->
        <div class="column-content">
          
          @include('frontend.element.adsheader')

          <div class="column-wrap">
            <div class="column-main" id="column-main"><div class="wrap">   
              <style>
                  .navSettings .active{background-color:#FFF; padding:inherit 6px;}
              </style>
              <ul class="nav justify-content-center navSettings">
                  <li class="nav-item">
                      </li>
                  <li class="nav-item">
                      <a class="nav-link active" href="{{ route('frontend.user.index') }}" title="Thông tin cơ bản"><i class="fal fa-info"></i> Thông tin cơ bản</a>
                  </li>        
                <li class="nav-item">
                      <a class="nav-link" href="#" title="Đổi mật khẩu"><i class="fal fa-key"></i> Đổi mật khẩu</a>
                  </li>
              </ul>

              <p></p>
              <div id="ContentPlaceHolder1_divUserInfo">
                <form role="form" action="{{ route('frontend.user.update') }}" method="POST" enctype="multipart/form-data">
                  @csrf
                  <div class="form-group">
                      <label>Email</label>
                      <input name="email" type="text" value="{{ Auth::guard('web')->user()->email }}" id="txtEmail" disabled="disabled" class="aspNetDisabled form-control rounded border-primary" autocomplete="off" spellcheck="false">
                  </div>
                  
                  <div class="form-group">
                      <label>Họ tên</label>
                      <input type="text" class="form-control rounded border-primary" name="name" id="txtName" autocomplete="off" value="{{ Auth::guard('web')->user()->name }}" spellcheck="false">
                  </div>
                  <div class="form-group">
                      <label>Điện thoại</label>
                      <input type="tel" class="form-control rounded border-primary" name="phone" id="phone" value="{{ Auth::guard('web')->user()->phone }}" autocomplete="off" spellcheck="false">
                  </div>
                  <div class="form-group">
                    <label>Giới tính</label>
                    <select name="sex" id="ddlSex">
                      <option {{ Auth::guard('web')->user()->sex == 0 ? 'selected' : '' }}  value="0">Nam</option>
                      <option {{ Auth::guard('web')->user()->sex == 1 ? 'selected' : '' }} value="1">Nữ</option>
                      <option {{ Auth::guard('web')->user()->sex == 2 ? 'selected' : '' }} value="2">Khác</option>
                    </select>
                  </div>
                  <div class="form-group">            
                      <input type="date" class="form-control rounded border-primary" name="birthday" id="birthday" value="{{ Auth::guard('web')->user()->birthday }}" autocomplete="off" required="" placeholder="Ngày sinh: ngày/tháng/năm" spellcheck="false">
                  </div>        
                  
                  <div class="form-group avatar">
                    <label>Ảnh đại diện</label>
                    <input type="hidden" id="avatar" name="avatar" value="{{ Auth::guard('web')->user()->avatar }}" spellcheck="false">
                    <input type="file" name="image" accept="image/jpg,image/png,image/jpeg" onchange="Images.UploadImage(this,'.listAvatar')" spellcheck="false">
                    <div class="listAvatar"></div>
                  </div>
                  <hr>
                  <div class="form-group">
                      <input type="submit" name="btnUpdate" value="Cập nhật" id="btnUpdate" class="btn btn-primary" spellcheck="false">
                  </div>
                </form>
              </div>
              <script>
                  function Bind() {
                      if ($(".listAvatar img").length > 0) {
                          $("#hdAvatar").val($(".listAvatar img").attr('src'));
                      }
                      return true;
                  }
                  
              </script>
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
