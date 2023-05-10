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
      <div class="col-md-6">
        @if($check_member == 1 && $check_time == 0)
        <form role="form" action="{{ route('live_reporting.create_comment') }}" method="POST">
          @csrf
          <input type="hidden" name="live_id" id="live_id" value="{{ $detail->id }}" />
          <div class="form-group">
            <div class="form-group">
              <label>Nội dung bình luận</label>
              <textarea name="content" class="form-control" id="content"></textarea>
            </div>
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
              <table class="table table-hover table-bordered">
                  <thead>
                    <tr>
                      <th style="width: 100px">@lang('Avatar')</th>
                      <th style="width: 150px">@lang('Người viết')</th>
                      <th>@lang('Nội dung bình luận')</th>
                    </tr>
                  </thead>
                  <tbody>
                      @foreach($liveReportingDetail as $liveDetail)
                      <tr>
                          <td><img src="{{ $liveDetail->avatar !='' ? $liveDetail->avatar : '/images/noavatar.png' }}" alt="{{ $liveDetail->admin_name }}" style="width: 80px" /></td>
                          <td><b>{{ $liveDetail->admin_name }}</b>
                            <p><i>{{ date('H:i d/m/Y', strtotime($liveDetail->created_at)) }}</i></p>
                            @if($check_manage == 1)
                            <select style="max-width: 120px" class="form-control" id="live_reporting_detail_{{ $liveDetail->id }}" onchange="updateStatusLive({{ $liveDetail->id }})">
                              @foreach(APP\Consts::LIVE_STATUS as $key=>$status_live)
                              <option value="{{ $key }}" {{ $liveDetail->status == $key ? 'selected' : '' }}>{{ $status_live }}</option>
                              @endforeach
                            </select>
                            <img id="ic-loading_{{ $liveDetail->id }}" style="display: none;vertical-align: middle;" src="/images/load.gif" width="20px">
                            @endif
                          </td>
                          <td>{!! $liveDetail->content !!}</td>
                      </tr>
                      @endforeach
                  </tbody>
              </table>
            </div>
          </div>
        </div>
    </div>

    
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
      /*
      let container = editor.ui.view.editable.element;
      if (container) {
        container.addEventListener('mousedown', onEditorMouseDown);
      }*/
    } ) .catch( error => {
      console.error( 'Oops, something went wrong!' );
      console.error( 'Please, report the following error on https://github.com/ckeditor/ckeditor5/issues with the build id and the error stack trace:' );
      console.warn( 'Build id: v10wxmoi2tig-mwzdvmyjd96s' );
      console.error( error );
    } );
    @if($check_manage == 1)
    function updateStatusLive(id){
      var status = $('#live_reporting_detail_'+id).val();
      $('#ic-loading_'+id).show();
      
      $.ajax({
        url: '{{ route('live_reporting.update_live') }}',
        type: 'POST',
        data: {
        _token: '{{ csrf_token() }}',
        status: status,
        id: id,
        },
        context: document.body,
      }).done(function(data) {
        $('#ic-loading_'+id).hide();
        $('#live_reporting_detail_'+id).attr('readonly',true)
        window.location.reload();
      });
    }
    @if($check_time == 0)
    
    setTimeout(function () {
      location.reload();
    }, 30000);
    @endif
    @endif
  </script>
  <style>
    div.ck-editor__editable {
      height: 350px !important;
      overflow: scroll;
    }
  </style>
@endsection