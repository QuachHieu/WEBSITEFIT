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
                      <label>@lang('Service category') <small class="text-red">*</small></label>
                      <select name="taxonomy_id" id="taxonomy_id" class="form-control select2" style="width:100%">
                        <option value="">@lang('Please select')</option>
                        @foreach ($parents as $item)
                          @if ($item->parent_id == 0 || $item->parent_id == null)
                            <option value="{{ $item->id }}"
                              {{ $detail->taxonomy_id == $item->id ? 'selected' : '' }}>{{ $item->title }}</option>

                            @foreach ($parents as $sub)
                              @if ($item->id == $sub->parent_id)
                                <option value="{{ $sub->id }}"
                                  {{ $detail->taxonomy_id == $sub->id ? 'selected' : '' }}>- - {{ $sub->title }}
                                </option>

                                @foreach ($parents as $sub_child)
                                  @if ($sub->id == $sub_child->parent_id)
                                    <option value="{{ $sub_child->id }}"
                                      {{ $detail->taxonomy_id == $sub_child->id ? 'selected' : '' }}>- - - -
                                      {{ $sub_child->title }}</option>
                                  @endif
                                @endforeach
                              @endif
                            @endforeach
                          @endif
                        @endforeach
                      </select>

                    </div>

                    <div class="form-group">
                      <label>@lang('Title') <small class="text-red">*</small></label>
                      <input type="text" class="form-control" name="title" placeholder="@lang('Title')"
                        value="{{ $detail->title }}" required>
                    </div>

                    <div class="form-group">
                      <div class="row">
                        <div class="col-xs-6">
                          <label>@lang('Price')</label>
                          <input type="text" class="form-control" name="json_params[price]"
                            placeholder="@lang('Price')"
                            value="{{ $detail->json_params->price ?? old('json_params[price]') }}">
                        </div>
                        <div class="col-xs-6">
                          <label>@lang('Price Curreny')</label>
                          <input type="text" class="form-control" name="json_params[price_currency]"
                            placeholder="@lang('Price Curreny')"
                            value="{{ $detail->json_params->price_currency ?? old('json_params[price_currency]') }}">
                        </div>
                      </div>
                    </div>
                    <div class="form-group">
                      <label>@lang('Image')</label>
                      <div class="input-group">
                        <span class="input-group-btn">
                          <a data-input="image" data-preview="image-holder" class="btn btn-primary lfm"
                            data-type="cms-image">
                            <i class="fa fa-picture-o"></i> @lang('choose')
                          </a>
                        </span>
                        <input id="image" class="form-control" type="text" name="image"
                          placeholder="@lang('image_link')..." value="{{ $detail->image }}">
                      </div>
                      <div id="image-holder" style="margin-top:15px;max-height:100px;">
                        @if ($detail->image != '')
                          <img style="height: 5rem;" src="{{ $detail->image }}">
                        @endif
                      </div>
                    </div>

                  </div>

                  <div class="col-md-6">

                    <div class="form-group">
                      <label>@lang('Is featured')</label>
                      <div class="form-control">
                        <label>
                          <input type="radio" name="is_featured" value="1"
                            {{ $detail->is_featured == '1' ? 'checked' : '' }}>
                          <small>@lang('true')</small>
                        </label>
                        <label>
                          <input type="radio" name="is_featured" value="0" class="ml-15"
                            {{ $detail->is_featured == '0' ? 'checked' : '' }}>
                          <small>@lang('false')</small>
                        </label>
                      </div>
                    </div>

                    <div class="form-group">
                      <label>@lang('Order')</label>
                      <input type="number" class="form-control" name="iorder" placeholder="@lang('Order')"
                        value="{{ $detail->iorder }}">
                    </div>
                    <div class="form-group">
                      <label>@lang('Status')</label>
                      <div class="form-control">
                        <label>
                          <input type="radio" name="status" value="active"
                            {{ $detail->status == 'active' ? 'checked' : '' }}>
                          <small>@lang('active')</small>
                        </label>
                        <label>
                          <input type="radio" name="status" value="deactive"
                            {{ $detail->status == 'deactive' ? 'checked' : '' }} class="ml-15">
                          <small>@lang('deactive')</small>
                        </label>
                      </div>
                    </div>

                    <div class="form-group">
                      <label>@lang('Image thumb')</label>
                      <div class="input-group">
                        <span class="input-group-btn">
                          <a data-input="image_thumb" data-preview="image_thumb-holder" class="btn btn-primary lfm"
                            data-type="cms-image">
                            <i class="fa fa-picture-o"></i> @lang('choose')
                          </a>
                        </span>
                        <input id="image_thumb" class="form-control" type="text" name="image_thumb"
                          placeholder="@lang('image_link')..." value="{{ $detail->image_thumb }}">
                      </div>
                      <div id="image_thumb-holder" style="margin-top:15px;max-height:100px;">
                        @if ($detail->image_thumb != '')
                          <img style="height: 5rem;" src="{{ $detail->image_thumb }}">
                        @endif
                      </div>
                    </div>

                  </div>
                  <div class="col-md-12">
                    <hr style="border-top: dashed 2px #a94442; margin: 10px 0px;">
                  </div>

                  <div class="col-md-12">
                    <div class="form-group">
                      <label>@lang('Description')</label>
                      <textarea name="json_params[brief][vi]" class="form-control" rows="5">{{ isset($detail->json_params->brief->vi) ? $detail->json_params->brief->vi : old('json_params[brief][vi]') }}</textarea>
                    </div>
                  </div>

                  <div class="col-md-12">
                    <div class="form-group">
                      <div class="form-group">
                        <label>@lang('Content')</label>
                        <textarea name="json_params[content][vi]" class="form-control" id="content_vi">{{ isset($detail->json_params->content->vi) ? $detail->json_params->content->vi : old('json_params[content][vi]') }}</textarea>
                      </div>
                    </div>
                  </div>
                  <div class="col-md-12">
                    <hr style="border-top: dashed 2px #a94442; margin: 10px 0px;">
                  </div>
                  <div class="col-md-12">
                    <div class="form-group">
                      <label>@lang('seo_title')</label>
                      <input name="json_params[seo_title]" class="form-control"
                        value="{{ $detail->json_params->seo_title ?? old('json_params[seo_title]') }}">
                    </div>
                  </div>
                  <div class="col-md-12">
                    <div class="form-group">
                      <label>@lang('seo_keyword')</label>
                      <input name="json_params[seo_keyword]" class="form-control"
                        value="{{ $detail->json_params->seo_keyword ?? old('json_params[seo_keyword]') }}">
                    </div>
                  </div>
                  <div class="col-md-12">
                    <div class="form-group">
                      <label>@lang('seo_description')</label>
                      <input name="json_params[seo_description]" class="form-control"
                        value="{{ $detail->json_params->seo_description ?? old('json_params[seo_description]') }}">
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
