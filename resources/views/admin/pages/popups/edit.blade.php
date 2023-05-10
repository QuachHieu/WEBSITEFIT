@extends('admin.layouts.app')
@section('title')
  {{ $module_name }}
@endsection
@section('style')
  <style>
    .checkbox_list {
      min-height: 300px;
    }

    .checkbox_list li {
      border-bottom: 1px dashed;
    }
  </style>
@endsection
@section('content')
  <!-- Content Header (Page header) -->
  <section class="content-header">
    <h1>
      {{ $module_name }}
      <a class="btn btn-sm btn-warning pull-right" href="{{ route(Request::segment(2) . '.create') }}">
        <i class="fa fa-plus"></i>
        @lang('Add')
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
        <h3 class="box-title">@lang('Update form')</h3>
      </div>
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
                  <h5>
                    @lang('General information')
                    <span class="text-danger">*</span>
                  </h5>
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
                  <div class="col-md-12">
                    <div class="form-group">
                      <label>@lang('Title')</label>
                      <small class="text-red">*</small>
                      <input type="text" class="form-control" name="title" placeholder="@lang('Title')"
                        value="{{ old('title') ?? $detail->title }}" required>
                    </div>
                  </div>

                  <div class="col-md-12">
                    <div class="form-group">
                      <label>@lang('Banner')</label>
                      <i class="fa fa-coffee text-red" aria-hidden="true"></i>
                      <div class="input-group">
                        <span class="input-group-btn">
                          <a data-input="image" data-preview="image-holder" data-type="cms-image"
                            class="btn btn-primary lfm">
                            <i class="fa fa-picture-o"></i> @lang('choose')
                          </a>
                        </span>
                        <input id="image" class="form-control" type="text" name="image"
                          placeholder="@lang('image_link')..." value="{{ $detail->image ?? old('image') }}">
                      </div>
                      <div id="image-holder" style="margin-top:15px;max-height:150px;">
                        @if (isset($detail->image) && $detail->image != '')
                          <img style="height: 10rem;" src="{{ $detail->image }}">
                        @endif
                      </div>
                    </div>
                  </div>
                  <div class="col-md-12">
                    <div class="form-group">
                      <label>@lang('Content Page')</label>
                      <textarea type="text" class="form-control" name="content" id="content">{{ old('content') ?? $detail->content }}</textarea>
                    </div>
                  </div>

                  <div class="col-md-3">
                    <div class="form-group">
                      <label>@lang('Start date')</label>
                      <span class="text-red">*</span>
                      <input type="text" class="form-control datepicker" name="start_time"
                        placeholder="@lang('dd/mm/yyyy')"
                        value="{{ isset($detail->start_time) ? \Carbon\Carbon::parse($detail->start_time)->format('d/m/Y') : old('start_time') }}"
                        required>
                    </div>
                  </div>
                  <div class="col-md-3">
                    <div class="form-group">
                      <label>@lang('End date')</label>
                      <span class="text-red">*</span>
                      <input type="text" class="form-control datepicker" name="end_time"
                        placeholder="@lang('dd/mm/yyyy')"
                        value="{{ isset($detail->end_time) ? \Carbon\Carbon::parse($detail->end_time)->format('d/m/Y') : old('end_time') }}"
                        required>
                    </div>
                  </div>
                  <div class="col-md-3">
                    <div class="form-group">
                      <label>@lang('Time duration')</label>
                      <input type="text" class="form-control" name="duration" min="0"
                        placeholder="@lang('Number of seconds')" value="{{ old('duration') ?? $detail->duration }}">
                    </div>
                  </div>

                  <div class="col-md-3">
                    <div class="form-group">
                      <label>@lang('Status')</label>
                      <div class="form-control">
                        @foreach (App\Consts::STATUS as $key => $value)
                          <label>
                            <input type="radio" name="status" value="{{ $value }}"
                              {{ $detail->status == $value ? 'checked' : '' }}>
                            <small class="mr-15">{{ __($value) }}</small>
                          </label>
                        @endforeach
                      </div>
                    </div>
                  </div>
                </div>
                <div class="row">
                  <div class="col-md-12">
                    <hr style="border-top: dashed 2px #a94442; margin: 10px 0px;">
                  </div>
                  <div class="col-md-12">
                    <h3>
                      @lang('Setting Block Content')
                    </h3>
                  </div>
                  <div class="col-md-6">
                    <h4 class="mb-15">
                      @lang('Selected Blocks')
                    </h4>
                    <ul class="checkbox_list" id="block_selected">
                      @foreach ($page_selected as $item)
                        <li class="cursor">
                          <input name="json_params[page][]" type="checkbox" value="{{ $item->id }}"
                            class="mr-15 block_item cursor" id="block_content_{{ $item->id }}" checked
                            autocomplete="off">
                          <label class="cursor" for="block_content_{{ $item->id }}">
                            <strong>{{ __($item->name) }}</strong>
                          </label>
                        </li>
                      @endforeach
                    </ul>
                  </div>
                  <div class="col-md-6">
                    <h4 class="mb-15">
                      @lang('Available Blocks')
                    </h4>
                    <ul class="checkbox_list" id="block_available">
                      @foreach ($pages as $item)
                        @if (!in_array($item->id, $detail->json_params->page ?? []))
                          <li class="cursor">
                            <input name="json_params[page][]" type="checkbox" value="{{ $item->id }}"
                              class="mr-15 block_item cursor" id="block_content_{{ $item->id }}"
                              autocomplete="off">
                            <label class="cursor" for="block_content_{{ $item->id }}">
                              <strong>{{ __($item->name) }}</strong>
                            </label>
                          </li>
                        @endif
                      @endforeach
                    </ul>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
        <div class="box-footer">
          <a class="btn btn-sm btn-success" href="{{ route(Request::segment(2) . '.index') }}">
            <i class="fa fa-bars"></i>
            @lang('List')
          </a>
          <button type="submit" class="btn btn-primary btn-sm pull-right">
            <i class="fa fa-floppy-o"></i>
            @lang('Save')
          </button>
        </div>
      </form>
    </div>
  </section>
@endsection
@section('script')
  <script>
    CKEDITOR.replace('content', ck_options);
    // Change to filter
    $(document).ready(function() {

      // Checked and unchecked block item event
      $(document).on('click', '.block_item', function() {
        let ischecked = $(this).is(':checked');
        let _root = $(this).closest('li');
        console.log(_root);
        let _targetHTML;

        if (ischecked) {
          _targetHTML = $("#block_selected");
        } else {
          _targetHTML = $("#block_available");
        }
        _targetHTML.append(_root);
      });
    });
  </script>
@endsection
