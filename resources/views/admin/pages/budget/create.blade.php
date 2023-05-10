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
      <h3 class="box-title">@lang('Create form')</h3>
    </div>
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
                    <input type="text" class="form-control" name="title" placeholder="Tên loại quỹ" value="{{ old('title') }}" required>
                  </div>
                </div>
                <div class="col-md-4">
                  <div class="form-group">
                    <label>@lang('Status')</label>
                    <div class="form-control">
                      <label>
                        <input type="radio" name="status" value="active" checked="">
                        <small>@lang('active')</small>
                      </label>
                      <label>
                        <input type="radio" name="status" value="deactive" class="ml-15">
                        <small>@lang('deactive')</small>
                      </label>
                    </div>
                  </div>
                </div>

                <div class="col-md-4">
                  <div class="form-group">
                    <label>@lang('Order')</label>
                    <input type="number" class="form-control" name="iorder" placeholder="@lang('Order')" value="{{ old('iorder') }}">
                  </div>
                </div>

                <div class="col-md-4">
                  <div class="form-group">
                    <label>@lang('Số tiền(đơn vị VNĐ)')</label>
                    <input type="text" class="form-control" name="fund" placeholder="@lang('Số tiền thu mỗi người')" value="{{ old('fund') }}">
                  </div>
                </div>
            

                <div class="col-md-12">
                  <hr style="border-top: dashed 2px #a94442; margin: 10px 0px;">
                </div>

             

                <div class="col-md-12">
                  <div class="form-group">
                    <label>@lang('Ghi chú')</label>
                    <textarea name="brief" class="form-control" rows="5">{{ old('brief') }}</textarea>
                  </div>
                </div>

                <div class="col-md-12">
                  <hr style="border-top: dashed 2px #a94442; margin: 10px 0px;">
                </div>

                <div class="col-md-12">
                  <div class="form-group">
                    <label>@lang('Danh sách nộp quỹ')</label>
                    <div class="row">
                      @if (count($members) == 0)
                      <div class="col-12">
                        @lang('not_found')
                      </div>
                      @else
                      @foreach ($members as $mem)

                      <div class="col-md-4">
                        <ul class="checkbox_list">
                          <li>
                            <input name="json_params[members][]" type="checkbox" value="{{ $mem->id }}" id="json_params_members_{{ $mem->id }}" class="mr-15">
                            <label for="json_params_members_{{ $mem->id }}"><strong>{{ $mem->name }}</strong></label>
                          </li>

                        </ul>
                      </div>


                      @endforeach
                      @endif

                    </div>
                  </div>
                </div>

                <div class="col-md-12">
                  <hr style="border-top: dashed 2px #a94442; margin: 10px 0px;">
                </div>
                <div class="col-md-12">
                  <div class="form-group">
                    <label>@lang('seo_title')</label>
                    <input name="json_params[seo_title]" class="form-control" value="{{ old('json_params[seo_title]') }}">
                  </div>
                </div>
                <div class="col-md-12">
                  <div class="form-group">
                    <label>@lang('seo_keyword')</label>
                    <input name="json_params[seo_keyword]" class="form-control" value="{{ old('json_params[seo_keyword]') }}">
                  </div>
                </div>
                <div class="col-md-12">
                  <div class="form-group">
                    <label>@lang('seo_description')</label>
                    <input name="json_params[seo_description]" class="form-control" value="{{ old('json_params[seo_description]') }}">
                  </div>
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
  CKEDITOR.replace('content_vi', ck_options);
</script>
@endsection