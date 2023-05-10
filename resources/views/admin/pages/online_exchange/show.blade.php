@extends('admin.layouts.app')

@section('title')
{{ $module_name }}
@endsection

@section('content')
<!-- Content Header (Page header) -->
<section class="content-header">
  <h1>
    {{ $module_name }}
    <a class="btn btn-success btn-sm pull-right" href="{{ route(Request::segment(2) . '.index') }}">
        <i class="fa fa-bars"></i> @lang('List')
      </a>
  </h1>
</section>

<!-- Main content -->
<section class="content">
  @if (session('errorMessage'))
  <div class="alert alert-warning alert-dismissible">
    <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
    {{ session('errorMessage') }}
  </div>
  @endif
  @if (session('successMessage'))
  <div class="alert alert-success alert-dismissible">
    <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
    {{ session('successMessage') }}
  </div>
  @endif

  @if ($errors->any())
  <div class="alert alert-danger alert-dismissible">
    <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>

    @foreach ($errors->all() as $error)
    <p>{{ $error }}</p>
    @endforeach

  </div>
  @endif

  <div class="box box-primary">
    <div class="box-header with-border">
      <h3 class="box-title">@lang('Chi tiết bình luận trực tiếp')</h3>
    </div>
    <!-- /.box-header -->
    <!-- form start -->
    
      <div class="box-body">
        <div class="{{ $check_member == 1 ? 'col-md-6' : 'col-md-12' }}">
            <h3>{{ $detail->title }}</h3>
            <p>Thời gian: <i>{{ date('H:i d/m/Y',strtotime($detail->start_date))}} - {{ date('H:i d/m/Y',strtotime($detail->end_date))}}</i> </p>
            {!! $detail->content !!}
        </div>
        <div class="col-md-6" id="enter_comment" style="display:none">
          @if($check_member == 1)
          <form role="form" action="{{ route('online_exchange.create_comment') }}" method="POST">
            @csrf
            <input type="hidden" name="exchange_id" id="exchange_id" value="{{ $detail->id }}" />
            <input type="hidden" name="parent_id" id="parent_id" value="" />
            <h5>Bạn đang trả lời câu hỏi: </h5>
            <p><b id="reply_comment"></b></p>
            <div class="form-group">
                <label>Chọn chuyên gia trả lời <small class="text-red">*</small></label>
                <select name="experts_id" id="experts_id" class="form-control select2" required style="width: 100%">
                  <option value="">-Chọn chuyên gia-</option>
                  @foreach($experts as $expert)
                    <option value="{{ $expert->id }}">{{ $expert->title }}</option>
                  @endforeach
                </select>
            </div>
            <div class="form-group">
                <label>Nội dung bình luận <small class="text-red">*</small></label>
                <textarea name="content" class="form-control" id="content"></textarea>
            </div>
            <button type="submit" class="btn btn-primary pull-right btn-sm">
                <i class="fa fa-floppy-o"></i>
                @lang('Save')
            </button>
          </form>
          @endif
        </div>
      </div>
    
    <div class="nav-tabs-custom">
        <ul class="nav nav-tabs">
          <li class="{{ $task == 'active' ? 'active' : ''}}">
            <a href="?task=active">
              <h5>Đã duyệt</h5>
            </a>
          </li>
          <li class="{{ $task == 'waiting' ? 'active' : ''}}">
            <a href="?task=waiting">
              <h5>Chờ duyệt</h5>
            </a>
          </li>
          <li class="{{ $task == 'deactive' ? 'active' : ''}}">
            <a href="?task=deactive">
              <h5>Từ chối</h5>
            </a>
          </li>
        </ul>
        
        <div class="tab-content">
          <div class="tab-pane active" id="active">
            <div class="">
              @if($task!='active')
              <table class="table table-hover table-bordered">
                <thead>
                  <tr>
                    <th style="width: 100px">@lang('Avatar')</th>
                    <th style="width: 150px">@lang('Người viết')</th>
                    <th style="">@lang('Bài viết')</th>
                    <th>@lang('Nội dung bình luận')</th>
                  </tr>
                </thead>
                <tbody>
                    @foreach($OnlineExchangeDetail as $liveDetail)
                      @php
                        if($liveDetail->experts_id == ''){
                          $avatar = '/images/noavatar.png';
                          $author = $liveDetail->author;
                          $type_comment = '';
                          $live_title = $liveDetail->live_title;
                        }else{
                          $avatar = $liveDetail->experts_image !='' ? $liveDetail->experts_image : '/images/noavatar.png';
                          $author = $liveDetail->experts_name;
                          $type_comment = '<br> Trả lời BL: <b>'.$liveDetail->reply_comment.'<b>';
                          $live_title = 'Bài viết: <b>'. $liveDetail->live_title.'<b>';
                        }
                      @endphp
                      <tr id="stt_{{ $liveDetail->id }}">
                          <td><img src="{{ $avatar }}" alt="{{ $author }}" style="width: 80px" /></td>
                          <td><b>{{ $author }}</b>
                            <p><i>{{ date('H:i d/m/Y', strtotime($liveDetail->created_at)) }}</i></p>
                            @if($check_manage == 1)
                            <a href="javascript:;" onclick="UpdateStatusComment({{ $liveDetail->id }},'active')"><i class="fa fa-eye"></i> Duyệt bình luận</a> <img id="ic-loading_{{ $liveDetail->id }}" style="display: none;vertical-align: middle;" src="/images/load.gif" width="20px">
                            @endif
                          </td>
                          <td>
                             {{ $live_title }}</b>
                            {{$type_comment}}
                          </td>
                          <td>{!! $liveDetail->content !!}</td>
                      </tr>
                    @endforeach
                </tbody>
              </table>
              @else
              <div class="box-interview interviews" data-id="{{ $detail->id }}"> 
                
                <div class="box-content"> 
                  @foreach($OnlineExchangeDetail as $liveDetail)
                  @if($liveDetail->parent_id =='')
                 <div class="item question"> 
                  <div class="wrap"> 
                   <div class="avatar"> 
                    <input type="hidden" id='comment_content_{{ $liveDetail->id }}' value="{{ $liveDetail->content }}" />
                    <a class="photo" href="javascript:;" onclick="ReplyComment({{ $liveDetail->id }})" data-desc="{{ $liveDetail->author }}" data-index="2">
                      <img src="/images/reply.jpg" alt="{{ $liveDetail->author }}" data-photo-original-src="" class="cms-photo ls-is-cached lazyloaded" data-src="/images/reply.jpg">
                    </a> 
                    </div> 
                   <div class="comment"> 
                    <div class="content"> 
                     <p class="heading"> Bạn {{ $liveDetail->author }} hỏi: </p> 
                     <div class="question-content">
                      {{ $liveDetail->content }}
                     </div>  
                      <p class="text-right">
                        <span style="margin-right: 20px;"><i>{{ date('d/m/Y H:i',strtotime($liveDetail->created_at)) }}</i></span> <a href="javascript:;" onclick="UpdateStatusComment({{ $liveDetail->id }},'deactive')"><i class="fa fa-eye-slash"></i> Ẩn bình luận</a> <img id="ic-loading_{{ $liveDetail->id }}" style="display: none;vertical-align: middle;" src="/images/load.gif" width="20px"></p>
                    </div> 
                   </div> 
                  </div> 
                 </div> 
                 @foreach ($OnlineExchangeDetail as $sub)
                  @if ($liveDetail->id == $sub->parent_id)
                 <div class="item anwser"> 
                  <div class="wrap"> 
                   <div class="avatar"> 
                    <a class="photo" href="#" data-desc="{{ $sub->experts_name }}" data-index="3"><img class="guest-avatar cms-photo ls-is-cached lazyloaded" src="{{ $sub->experts_image }}" alt="{{ $sub->experts_name }}" data-photo-original-src="" data-src="{{ $sub->experts_image }}"></a> 
                   </div> 
                   <div class="comment"> 
                    <div class="content"> 
                     <p class="heading">{{ $sub->experts_name }}</p> 
                     <div class="anwser-content"> 
                      {!! $sub->content !!}
                     </div> 
                     <p class="text-right">
                      <span style="margin-right: 20px;"><i>{{ date('d/m/Y H:i',strtotime($sub->created_at)) }}</i></span>
                      <a href="javascript:;" onclick="UpdateStatusComment({{ $sub->id }},'deactive')"><i class="fa fa-eye-slash"></i> Ẩn bình luận</a> <img id="ic-loading_{{ $sub->id }}" style="display: none;vertical-align: middle;" src="/images/load.gif" width="20px"></p>
                    </div> 
                   </div> 
                  </div> 
                 </div>
                 @endif
                @endforeach
                 @endif
                @endforeach

                </div> 
                </div> 
                @endif
              </div>
            </div>
          </div>
        </div>
    </div>

<style>

.tab-content{
  height: 500px;
  overflow-y: scroll;
}

img{max-width: 100%;}
.box-interview .item {
    margin-top: 32px;
}
.box-interview .item .wrap {
    display: flex;
}
.item.question .avatar {
    order: 1;
    margin-left: 36px;
    background: #ccc;
    margin-right: 0;
}
.box-interview .item .avatar {
    width: 50px;
    height: 50px;
    border-radius: 50%;
    padding: 25px;
    position: relative;
    background: #ccc;
    margin-right: 36px;
    margin-top: 13px;
    overflow: hidden;
}
.box-interview .item .avatar img {
    top: 0;
    right: 0;
    display: block;
    position: absolute;
    width: 100%;
    height: 100%;
}
.box-interview .comment{
  flex: auto;
}

.box-interview .content {
    background: #fff3cd;
    padding: 15px 30px 15px;
    border-radius: 0 32px 32px 32px;
    position: relative;
}
.item.question .content {
    border-radius: 32px 0 32px 32px;
    background: #d1e7dd;
    position: relative;
}
.item.question .content::before {
    position: absolute;
    content: "";
    right: -10px;
    left: auto;
    top: 30px;
    width: 0;
    height: 0;
    border-top: 10px solid transparent;
    border-bottom: 10px solid transparent;
    border-left: 10px solid #d1e7dd;
    border-right: 0;
}
.box-interview .content::before {
    position: absolute;
    content: "";
    left: -10px;
    top: 30px;
    width: 0;
    height: 0;
    border-top: 10px solid transparent;
    border-bottom: 10px solid transparent;
    border-right: 10px solid #fff3cd;
}
.box-interview .time {
    font-style: normal;
    font-weight: 400;
    font-size: 12px;
    line-height: 14px;
    color: #adb5bd;
    display: block;
    text-align: right;
    margin-top: 10px;
}
.box-interview .content .heading {
    font-style: normal;
    font-weight: 700;
    font-size: 17px;
    line-height: 27px;
    color: #212529;
    text-transform: capitalize;
    text-align: left;
    padding: 0;
    padding-bottom: 8px;
    border-bottom: 1px solid;
    border-color: rgba(0,0,0,.1);
}

</style>
    
  </div>
</section>
@endsection
@section('script')
  <script>
    ClassicEditor.create( document.querySelector( '#content' ), {
      toolbar: {
        items: [
          'CKFinder',"|",
          'heading',
          'bold',
          'link',
          'italic',
          '|',
          'blockQuote',
          'alignment:left', 'alignment:right', 'alignment:center', 'alignment:justify',
          'insertTable',
          'undo',
          'redo',
          'LinkImage',
          'bulletedList',
          'numberedList',
          'mediaEmbed',
          'fontBackgroundColor',
          'fontColor',
          'fontSize',
          'fontFamily'
        ]
      },
      language: 'vi',
      image: {
        toolbar: ['imageTextAlternative', '|', 'imageStyle:alignLeft', 'imageStyle:full','imageStyle:side', 'imageStyle:alignCenter','linkImage'],
        styles: [
            'full',
            'side',
            'alignCenter',
            'alignLeft',
            'alignRight'
        ]
      },
      table: {
        contentToolbar: [
          'tableColumn',
          'tableRow',
          'mergeTableCells'
        ]
      },
      licenseKey: '',
      
      
    } ) .then( editor => {
      window.editor = editor;
      
    } ) .catch( error => {
      console.error( 'Oops, something went wrong!' );
      console.error( 'Please, report the following error on https://github.com/ckeditor/ckeditor5/issues with the build id and the error stack trace:' );
      console.warn( 'Build id: v10wxmoi2tig-mwzdvmyjd96s' );
      console.error( error );
    } );
    @if($check_manage == 1)
    
    function ReplyComment(id){
      var content = $('#comment_content_'+id).val();
      $('#reply_comment').html(content);
      $('#parent_id').val(id);
      $('#enter_comment').attr('style','display:block');
      //document.body.scrollTop = 0;
      //document.documentElement.scrollTop = 0;
      $('html, body').animate({scrollTop:0}, 'slow');
    }

    function UpdateStatusComment(id,status){
      //var status = $('#live_reporting_detail_'+id).val();
      $('#ic-loading_'+id).show();
      
      $.ajax({
        url: '{{ route('online_exchange.status_comment') }}',
        type: 'POST',
        data: {
        _token: '{{ csrf_token() }}',
        status: status,
        id: id,
        },
        context: document.body,
      }).done(function(data) {
        $('#ic-loading_'+id).hide();
        window.location.reload();
      });
    }

    @endif

    @if($check_time == 1)
    setTimeout(function () {
      location.reload();
    }, 100000);
    @endif

  </script>
  <style>
    div.ck-editor__editable {
      height: 350px !important;
      overflow: scroll;
    }
  </style>
@endsection