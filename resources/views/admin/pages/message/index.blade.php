@extends('admin.layouts.app')

@section('title')
  {{ $module_name }}
@endsection

@section('content-header')
  <!-- Content Header (Page header) -->
  <section class="content-header">
    <h1>
      {{ $module_name }}
    </h1>
  </section>
@endsection

@section('content')
<style>
.admin_online{ display: flex; flex: 1;align-items: center; cursor: pointer;}
.admin_online .avatar img{border-radius: 50%;}
.list_admins {max-height: 500px; overflow-y: scroll;}
.info_admin{align-items: center; flex: 1;padding-left: 10px;}
.content_message{height: 450px; width: 100%; border: 1px solid #ccc; overflow-y: scroll;margin-bottom: 10px;}

.detail__footer .input-wrap {
    display: flex;
}
.detail__footer .input-wrap .avatar {
    width: 2rem;
    height: 2rem;
    background-size: cover;
    background-position: center center;
    border-radius: 50%;
    overflow: hidden;
}
.avatarUser {
    background-image: url({{$avatar = Auth::guard('admin')->user()->avatar != "" ? Auth::guard('admin')->user()->avatar : '/images/noavatar.png'}});
}
.detail__footer .input-wrap .content_me {
    width: calc(100% - 2.5rem);
    margin-left: auto;
    position: relative;
    display: flex;
    align-items: center;
}
.detail__footer .input-wrap .content_me {
    position: relative;
    display: flex;
    align-items: center;
    padding: 0;
    border-radius: 20px;
    background-color: transparent;
}
.detail__footer .input-wrap .form-control {
    height: auto;
    padding-right: 2rem;
}
.bg-light {
    background-color: #f8f9fa !important;
}
.commentUploadImage input {
    overflow: hidden;
    opacity: 0;
    position: absolute;
    top: 0;
    width: 16px;
    height: 14px;
    cursor: pointer;
}
.fa-image:before {
    content: "\f03e";
}
.detail__footer .input-wrap .btn-send {
    width: 32px;
    text-align: center;
    color: #21A7E0;
}
.detail__footer .input-wrap .commentUploadImage {
    position: absolute;
    right: 3rem;
    top: 50%;
    transform: translateY(-50%);
}
.admin_online {width: 100%;}
</style>
  <!-- Main content -->
  <section class="content">
    {{-- Search form --}}
    <div class="box box-default">
      
        <div class="box-body">
          <div class="row">
            <form action="{{ route(Request::segment(2) . '.index') }}" method="GET">
              <div class="col-md-3">
                <div class="form-group">
                  <select name="admin_receive_id" class="form-control select2">
                    <option value="">Người dùng</option>
                    @foreach($admins as $admin)
                    <option value="{{ $admin->id}}" {{ (isset($params['admin_receive_id']) and $params['admin_receive_id'] == $admin->id) ? 'selected' : '' }}>{{$admin->name}}</option>
                    @endforeach
                  </select>
                </div>
              </div>
              <div class="col-md-1">
                <div class="form-group">
                  <div>
                    <button type="submit" class="btn btn-primary btn-sm mr-10">@lang('Submit')</button>
                  </div>
                </div>
              </div>
            </form>
          </div>
          
          <div class="row">
            <div class="col-md-4">
              <div class="list-group list_admins">
                @foreach($admins as $admin)
                @php 
                if($admin->id > Auth::guard('admin')->user()->id){
                  $user_key = Auth::guard('admin')->user()->id.'_'.$admin->id;
                }else{
                  $user_key = $admin->id.'_'.Auth::guard('admin')->user()->id;
                }
                $admin_receive = session('admin_receive_id') > 0 ? session('admin_receive_id') : '' ;
                @endphp
                <a href="#" id="admin_active_{{ $admin->id }}" onclick="checkMessage({{ $admin->id }})" class="list-group-item list-group-item-action admin_online {{ $admin_receive==$admin->id ? 'active' : '' }}">
                  <div class="avatar">
                    <img src="{{ $admin->avatar !='' ? $admin->avatar : '/images/noavatar.png' }}" style="width: 56px; height: 56px" />
                    </div>
                    <div class="info_admin" >
                      <p><b>{{ $admin->name }}</b></p>
                      <span>{{ isset($array_message[$user_key]) ? $array_message[$user_key] : '' }}</span>
                    </div>
                </a>
                @endforeach
              </div>
            </div>
            @php 
            $display = 'display: none;';
            @endphp
            <div class="col-md-8 detail__footer">
              <div class="content_message">
                <div class="box-interview interviews" data-id="1" id="content_message"> 
                  
                  @if(count($list_message) > 0)
                  @php 
                    $admin_id = Auth::guard('admin')->user()->id;
                    $display = '';
                    $data_post = '<div class="box-content">';
                    foreach($list_message as $message){
                      $avatar = $message->admin_avatar != "" ? $message->admin_avatar : '/images/noavatar.png';
                      $admin_name = $message->admin_send;
                      $media = $message->media != '' ? '<br><a target="_blank" href="/'.$message->media.'"><i class="fa fa-file-text-o"></i> file đính kèm</a>' : '';
                      if($message->admin_created_id != $admin_id){
                          $data_post .= '<div class="item anwser"> 
                          <div class="wrap"> 
                            <div class="avatar"> 
                              <a class="photo" href="#" data-desc="'.$admin_name.'" data-index="'.$message->id.'">
                              <img class="guest-avatar cms-photo ls-is-cached lazyloaded" src="'.$avatar.'" alt="'.$admin_name.'" data-photo-original-src="" data-src="'.$avatar.'"></a> 
                            </div> 
                            <div class="comment"> 
                              <div class="content"> 
                                <div class="anwser-content"> 
                                  <p>'.$message->content.$media.'</p>
                                </div> 
                                <p class="text-right">
                                  <span style="margin-right: 20px;"><i>'.date('H:i d/m/Y',strtotime($message->updated_at)).'</i></span>
                                </p>
                              </div> 
                            </div> 
                          </div> 
                        </div>';
                      }else{
                          $data_post .= '<div class="item question"> 
                          <div class="wrap"> 
                          <div class="avatar">
                            <a class="photo" href="javascript:;" data-desc="'.$admin_name.'" data-index="'.$message->id.'">
                              <img src="'.$avatar.'" alt="'.$admin_name.'" data-photo-original-src="" class="cms-photo ls-is-cached lazyloaded" data-src="'.$avatar.'">
                            </a> 
                          </div> 
                          <div class="comment"> 
                            <div class="content"> 
                            <div class="question-content">
                              <p>'.$message->content.$media.'</p>
                            </div>  
                              <p class="text-right">
                                <span style="margin-right: 20px;"><i>'.date('H:i d/m/Y',strtotime($message->updated_at)).'</i></span> 
                              </p>
                            </div> 
                          </div> 
                          </div> 
                        </div> ';
                      }
                    }
                  $data_post .= '</div>';

                  echo $data_post;
                  @endphp
                  @endif
                </div>
              </div>
              <div id="guitinnhan" style="{{ $display }}">
                <form id="form-create" accept="{{ route(Request::segment(2) . '.store') }}" method="POST" enctype="multipart/form-data" >
                  @csrf
                  <input type="hidden" id="admin_receive_id" name="admin_receive_id" value="" />
                  <div class="input-wrap">
                    <div class="avatar avatarUser"></div>
                    <div class="content_me">
                        <input type="text" id="enter_message" class="form-control" name='content' />
                        <span class="fa fa-image commentUploadImage">
                            <input type="file" name="image" accept="image/png, image/jpeg, img/gif" >
                        </span>
                        <a onclick="sendMessage()"><span class="btn-send pointer" title="Gửi tin nhắn"><i class="fa fa-paper-plane"></i></span></a>
                    </div>
                  </div>
                </form>
              </div>
            </div>
          </div>

        </div>
      
    </div>
    {{-- End search form --}}

  </section>
  
  <script>

    function sendMessage(){
      //alert('AAAAAAAAA');
      document.getElementById("form-create").submit();
    }

    function checkMessage(id){
      const collection = document.getElementsByClassName("list-group-item list-group-item-action admin_online");
      for (let i = 0; i < collection.length; i++) {
        collection[i].classList.remove("active");
      }
      document.getElementById("admin_active_"+id).classList.add("active");
      $.ajax({
        url: '{{ route("cms_message.admin") }}',
        type: 'POST',
        data: {
          _token: '{{ csrf_token() }}',
          id: id
        },
        context: document.body,
      }).done(function(data) {
        $('#content_message').html(data);
        $('#guitinnhan').attr('style','display:block');
        $('#admin_receive_id').val(id);
        $('#enter_message').focus();
      });
    }
  </script>
@endsection


