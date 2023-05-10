@extends('admin.layouts.app')

@section('title')
  {{ $module_name }}
@endsection

@section('content')

  <!-- Content Header (Page header) -->
  <section class="content-header">
    <h1>
      {{ $module_name }}
      <a class="btn btn-sm btn-warning pull-right" href="{{ route(Request::segment(2) . '.create') }}"><i
          class="fa fa-plus"></i> @lang('Add')</a>
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
      
      <!-- /.box-header -->
      <!-- form start -->
      <form role="form" action="{{ route(Request::segment(2) . '.store') }}" method="POST">
        @csrf
        <div class="box-body">
          <!-- Custom Tabs -->
          <div class="nav-tabs-custom">
            <ul class="nav nav-tabs">
              <li class="active">
                <a href="#tab_1" data-toggle="tab">
                  <h5>Gửi tài liệu <span class="text-danger">*</span></h5>
                </a>
              </li>

              <button type="submit" class="btn btn-primary btn-sm pull-right">
                <i class="fa fa-floppy-o"></i>
                @lang('Save')
              </button>
            </ul>

            <div class="tab-content">
              <div class="tab-pane active" id="tab_1">
                <div class="row">
                  <div class="col-md-6">
                    <div class="form-group">
                      <label>@lang('Title') <small class="text-red">*</small></label>
                      <input type="text" class="form-control" name="title" placeholder="@lang('Title') *"
                        value="{{ old('title') }}" required>
                    </div>
                    <div class="form-group">
                      <div class="form-group">
                        <label>@lang('Content')  <small class="text-red">*</small></label>
                        <textarea name="content" class="form-control" id="content">{{ old('content') }}</textarea>
                      </div>
                    </div>
                  </div>

                  <div class="col-md-6">
                    <div class="form-group">
                      <label>@lang('Phòng ban')</label>
                      <select class="form-control select2" onchange="loadMemberDepartment()" id="check_department">
                        <option>-Chọn tất cả phòng ban-</option>
                        @foreach($departments as $department)
                        <option value="{{ $department->id }}">{{ $department->title }}</option>
                        @endforeach
                      </select>
                    </div>
                    <section class="tbl-header table_scroll">
                      <div class="container_table custom-scroll table-responsive no-margin" style="max-height: 600px; overflow-y: scroll;">
                        <div id="dt_basic_wrapper" class="dataTables_wrapper form-inline"></div>
                        <div id="ic-loading" style="display: none;">
                          <i class="fa fa-spinner fa-2x fa-spin text-success" style="padding: 3px;"></i>Đang tải...
                        </div>
                        <table class="table table-striped table-bordered table-hover no-footer" width="100%" style="border: 1px solid #ccc !important">
                          <thead>
                            <tr>
                              <th class="col-xs-4">
                                <input class="checkbox check-all" type="checkbox" value="0" ><span>Phòng ban</span>
                              </th>
                              <th class="col-xs-8">
                                <input class="checkbox check-all-relative" type="checkbox"  value="0" ><span>Người nhận</span>
                              </th>
                            </tr>
                          </thead>
                          <tbody id="load-ajax" >
                            @foreach($departments as $department)
                            <tr>
                              <td class="">
                                <input class="_check_all checkbox check_class" id="check_class_{{ $department->id }}" type="checkbox" name="department[]" value="{{ $department->id }}"> <span>{{ $department->title }}</span>
                              </td>
                              <td class="">
                                <?php foreach($admins as $admin){ 
                                  if($admin->department_id == $department->id){
                                  ?>
                                  <input class="checkbox _check_all _check_relative _check_all_{{ $department->id }}" type="checkbox" name="user_receive[]" value="{{ $admin->id }}" data-value="{{ $department->id }}"> <span>{{ $admin->name }}</span><br>
                                <?php } } ?>
                              </td>
                            </tr>
                            @endforeach
                          </tbody>
                        </table>
                      </div>
                    </section>
                  </div>
                  <div class="col-md-12">
                    <hr style="border-top: dashed 2px #a94442; margin: 10px 0px;">
                  </div>
                </div>

              </div>

            </div><!-- /.tab-content -->
          </div><!-- nav-tabs-custom -->

        </div>
        <!-- /.box-body -->

        <div class="box-footer">
          <a class="btn btn-success btn-sm" href="{{ route(Request::segment(2) . '.index') }}">
            <i class="fa fa-bars"></i> @lang('List')
          </a>
          <button type="submit" class="btn btn-primary pull-right btn-sm"><i class="fa fa-floppy-o"></i>
            @lang('Save')</button>
        </div>
      </form>
    </div>
  </section>
  
  @section('script')

  <style>
    div.ck-editor__editable {
      height: 300px !important;
      overflow: scroll;
    }
    .checkbox, .radio{
      display: unset !important;
    }
  </style>
<script>
  $(document).ready(function() {
  
    $('.check-all').on('change', function() {
      
      if($(".check-all:checked").val() == 0){
        $('._check_all').prop('checked',true);
        $('.check-all-relative').prop('checked',true);
      }else{
        $('._check_all').prop('checked',false);
        $('.check-all-relative').prop('checked',false);
      }
    });
  
    $('.check_class').on('change', function() {
      var class_id = $(this).val();
      if($("#check_class_"+class_id+":checked").val() == class_id){
        $('._check_all_'+class_id).prop('checked',true);
      }else{
        $('._check_all_'+class_id).prop('checked',false);
      }
      $('.check-all-relative').prop('checked',false);
    });
  
    $('._check_all').on('change', function() {
      var class_id = $(this).attr('data-value');
      $('.check-all').attr('disabled', false);
      $('.check-all').prop('checked',false);
      $('#check_class_'+class_id).prop('checked',false);
    });
  
    $('.btn-psadmin').attr('disabled', 'disabled');
    
    $('.checkbox').on('change', function() {
      if($(".checkbox:checked").val() >= 0){
        $('.btn-psadmin').attr('disabled', false);
      }else{
        $('.btn-psadmin').attr('disabled', 'disabled');
      }
    });
    
    $('.check-all-relative').on('change', function() {
      if($(".check-all-relative:checked").val() == 0){
        $('._check_relative').prop('checked',true);
      }else{
        $('._check_relative').prop('checked',false);
      }
      $('.check-all').prop('checked',false);
      $('.check_class').prop('checked',false);
    });
  
    $('._check_relative').on('change', function() {
      $('.check-all').prop('checked',false);
      $('.check-all-relative').prop('checked',false);
    });
  });
</script>

  <script>
    function loadMemberDepartment(){
      var dep = $('#check_department').val();
      //alert(dep);
      $.ajax({
        url: '{{ route('admins.load_member') }}',
        type: 'POST',
        data: {
          _token: '{{ csrf_token() }}',
          dep: dep
        },
        context: document.body,
      }).done(function(data) {
        
        $('#load-ajax').html(data);
      });
      

    }


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
  </script>
  @endsection
@endsection
