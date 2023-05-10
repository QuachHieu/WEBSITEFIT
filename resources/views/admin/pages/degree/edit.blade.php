@extends('admin.layouts.app')

@section('title')
{{ $module_name }}
@endsection

@section('content')
<!-- Content Header (Page header) -->
<section class="content-header">
  <h1>
    {{ $module_name }}
    <a class="btn btn-sm btn-warning pull-right" href="{{ route(Request::segment(2) . '.create') }}"><i class="fa fa-plus"></i> @lang('Add')</a>
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
      <h3 class="box-title">@lang('Update form')</h3>
    </div>
    <!-- /.box-header -->
    <!-- form start -->
    <form role="form" action="{{ route(Request::segment(2) . '.update', $detail->id) }}" method="POST">
      @csrf
      @method('PUT')
      <div class="box-body">
        <!-- Custom Tabs -->
        <div class="nav-tabs-custom">
          <ul class="nav nav-tabs">
            <li class="active">
              <a href="#tab_1" data-toggle="tab">
                <h5>Thông tin chính <span class="text-danger">*</span></h5>
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
                    <input type="text" class="form-control" name="title" id="txtTitle" onchange="getUrlPart('txtTitle','txtUrlPart')" onclick="getUrlPart('txtTitle','txtUrlPart')" onblur="getUrlPart('txtTitle','txtUrlPart')" placeholder="@lang('Title')" value="{{ $detail->title }}" required>
                  </div>

                  <div class="form-group">
                    <label>@lang('Order')</label>
                    <input type="number" class="form-control" name="iorder" placeholder="@lang('iorder')" value="{{ $detail->iorder }}">
                  </div>



                </div>

                <div class="col-md-6">
              
                  <div class="form-group">
                    <label>@lang('Status')</label>
                    <div class="form-control">
                      <label>
                        <input type="radio" name="status" value="active" {{ $detail->status == 'active' ? 'checked' : '' }}>
                        <small>@lang('active')</small>
                      </label>
                      <label>
                        <input type="radio" name="status" value="deactive" {{ $detail->status == 'deactive' ? 'checked' : '' }} class="ml-15">
                        <small>@lang('deactive')</small>
                      </label>
                    </div>
                  </div>
                </div>
                <div class="col-md-12">
                    <div class="form-group">
                      <label>@lang('Note')</label>
                      <textarea name="note" class="form-control" rows="5">{{ $detail->note ?? old('note') }}</textarea>
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
@endsection

@section('script')
<script>
  ClassicEditor.create(document.querySelector('#content_vi'), {
      toolbar: {
        items: [
          'CKFinder', "|",
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
        toolbar: ['imageTextAlternative', '|', 'imageStyle:alignLeft', 'imageStyle:full', 'imageStyle:side', 'imageStyle:alignCenter'],
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


    })
    .then(editor => {
      window.editor = editor;

    })
    .catch(error => {
      console.error('Oops, something went wrong!');
      console.error('Please, report the following error on https://github.com/ckeditor/ckeditor5/issues with the build id and the error stack trace:');
      console.warn('Build id: v10wxmoi2tig-mwzdvmyjd96s');
      console.error(error);
    });

  $(document).ready(function() {
    var taxonomys = @json($taxonomys ?? null);

    // Change to filter type by name taxonomy
    $(document).on('change', '#taxonomy', function() {
      let _value = $(this).val();
      let _html = $('#parent_id');
      let _list = taxonomys.filter(function(e, i) {
        return ((e.parent_id == 0 || e.parent_id == null) && e.taxonomy == _value);
      });
      let _content = '<option value="">== @lang('
      ROOT ') ==</option>';
      if (_list) {
        _list.forEach(element => {
          _content += '<option value="' + element.id + '"> ' + element.title + ' </option>';
          let _child = taxonomys.filter(function(e, i) {
            return ((e.parent_id == element.id) && e.taxonomy == _value);
          });
          if (_child) {
            _child.forEach(element => {
              _content += '<option value="' + element.id + '">- - ' + element.title + ' </option>';
            });
          }
        });
        _html.html(_content);

        $('#parent_id').select2();
      }
    });

  });
</script>
@endsection