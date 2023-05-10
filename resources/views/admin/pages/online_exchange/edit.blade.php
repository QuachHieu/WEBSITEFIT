@extends('admin.layouts.app')

@section('title')
{{ $module_name }}
@endsection

@section('content')
<!-- Content Header (Page header) -->
<section class="content-header">
  <h1>
    {{ $module_name }}
    <a class="btn btn-sm btn-warning pull-right" href="{{ route(Request::segment(2).'.create') }}"><i
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
        <div class="col-md-6">

          <div class="form-group">
            <label>@lang('Danh mục') <small class="text-red">*</small></label>
            <select name="taxonomy_id" id="taxonomy_id" class="form-control select2" style="width: 100%">
              
              @foreach ($parents as $item)
                @if ($item->parent_id == 0 || $item->parent_id == null)
                  <option value="{{ $item->id }}" {{ $detail->taxonomy_id == $item->id ? 'selected' : '' }}>
                    {{ $item->title }}</option>

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
            <label>@lang('Title')  <small class="text-red">*</small></label>
            <input type="text" class="form-control input_text" id="txtTitle" onchange="demKytu('txtTitle','remainingInput_text');getUrlPart('txtTitle','txtUrlPart')" onkeyup="demKytu('txtTitle','remainingInput_text');getUrlPart('txtTitle','txtUrlPart')" onblur="demKytu('txtTitle','remainingInput_text');getUrlPart('txtTitle','txtUrlPart')" maxlength="160" name="title" placeholder="@lang('Title')"
              value="{{ $detail->title }}" required>
              <span id='remainingInput_text' class="note pull-right">{{ strlen($detail->title) }}/160</span>
          </div>
          <div class="form-group">
            <label>@lang('Mô tả')</label>
            <textarea name="brief" id="description" maxlength="255" onkeyup="demKytu('description','remainingInput_textarea');" onblur="demKytu('description','remainingInput_textarea');" onclick="demKytu('description','remainingInput_textarea');"  class="form-control" rows="4">{{ $detail->brief }}</textarea>
            <span id='remainingInput_textarea' class="note pull-right">{{ strlen($detail->brief) }}/255</span>
          </div>
          
          <div class="form-group">
            <label>Thời gian bắt đầu  <small class="text-red">*</small></label>
            <input type="datetime-local" class="form-control" name="start_date" placeholder="Thời gian bắt đầu"
              value="{{ strftime('%Y-%m-%dT%H:%M', strtotime($detail->start_date)) }}" required>
          </div>
          
          <div class="form-group">
            <label>Thời gian Kết thúc  <small class="text-red">*</small></label>
            <input type="datetime-local" class="form-control" name="end_date" placeholder="Thời gian kết thúc"
              value="{{ strftime('%Y-%m-%dT%H:%M', strtotime($detail->end_date)) }}" required>
          </div>
          
          <div class="form-group">
            <label>@lang('Người viết bài')</label>
            <select  class="form-control select2" multiple="multiple" name="member[]" id="member">
              @foreach($admins as $admin)
              <option value="{{ $admin->id }}" {{ in_array($admin->id,explode(",",$detail->member)) ? 'selected' : '' }}>{{ $admin->name }}</option>
              @endforeach
            </select>
          </div>

          <div class="form-group">
            <label>@lang('Người kiểm duyệt')</label>
            <select  class="form-control select2" multiple="multiple" name="manage[]" id="manage">
              @foreach($admins as $admin)
              <option value="{{ $admin->id }}" {{ in_array($admin->id,explode(",",$detail->manage)) ? 'selected' : '' }}>{{ $admin->name }}</option>
              @endforeach
            </select>
          </div>
          
          <div class="form-group">
            <label>@lang('Mời chuyên gia')</label>
            <select  class="form-control select2" multiple="multiple" name="experts[]" id="manage">
              @foreach($experts as $expert)
              <option value="{{ $expert->id }}" {{ in_array($expert->id,explode(",",$detail->experts)) ? 'selected' : '' }}>{{ $expert->title }}</option>
              @endforeach
            </select>
          </div>

        </div>

        <div class="col-md-6">
          <div class="form-group">
            <label>@lang('status')</label>
            <div class="form-control">
              <label>
                <input type="radio" name="status" value="waiting" {{ $detail->status == "waiting" ? "checked" : "" }} >
                <small>Chờ duyệt</small>
              </label>
              <label>
                <input type="radio" name="status" value="active" {{ $detail->status == "active" ? "checked" : "" }} class="ml-15">
                <small>Hoạt động</small>
              </label>
              <label>
                <input type="radio" name="status" value="deactive" {{ $detail->status == "deactive" ? "checked" : "" }} class="ml-15">
                <small>Không HĐ</small>
              </label>
            </div>
          </div>
          <div class="form-group">
            <label>@lang('Alias')  <small class="text-red">*</small></label>
            <input type="text" class="form-control" id="txtUrlPart" onchange="getUrlPart('txtUrlPart','txtUrlPart')" onkeyup="getUrlPart('txtUrlPart','txtUrlPart')" onblur="getUrlPart('txtUrlPart','txtUrlPart')" maxlength="255" name="url_part" placeholder="@lang('Alias')"
              value="{{ $detail->url_part }}" required>
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
              <input id="image" class="form-control" type="text" name="image" value="{{ $detail->image }}" 
                placeholder="@lang('image_link')...">
            </div>
            <div id="image-holder" style="margin-top:15px;max-height:100px;">
              @if ($detail->image != '')
                <img style="height: 5rem;" src="{{ $detail->image }}">
              @endif
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
              <input id="image_thumb" class="form-control" type="text" name="image_thumb" value="{{ $detail->image_thumb }}"
                placeholder="@lang('image_link')...">
            </div>
            <div id="image_thumb-holder" style="margin-top:15px;max-height:100px;">
              @if ($detail->image_thumb != '')
                <img style="height: 5rem;" src="{{ $detail->image_thumb }}">
              @endif
            </div>
          </div>
          <div class="form-group">
            <label>@lang('seo_title')</label>
            <input name="json_params[seo_title]" class="form-control" maxlength="255" id="seo_title" onkeyup="demKytu('seo_title','input_seo_title');"
              value="{{ $detail->json_params->seo_title }}">
              <span id='input_seo_title' class="note pull-right">{{ strlen($detail->json_params->seo_title) }}/255</span>
          </div>
          <div class="form-group">
            <label>@lang('seo_keyword')</label>
            <input name="json_params[seo_keyword]" class="form-control" maxlength="255" id="seo_keyword" onkeyup="demKytu('seo_keyword','input_seo_keyword');"
              value="{{ $detail->json_params->seo_keyword }}">
              <span id='input_seo_keyword' class="note pull-right">{{ strlen($detail->json_params->seo_keyword) }}/255</span>
          </div>
          <div class="form-group">
            <label>@lang('seo_description')</label>
            <input name="json_params[seo_description]" class="form-control" maxlength="255" id="seo_description" onkeyup="demKytu('seo_description','input_seo_description');"
              value="{{ $detail->json_params->seo_description }}">
              <span id='input_seo_description' class="note pull-right">{{ strlen($detail->json_params->seo_description) }}/255</span>
          </div>
        </div>
        <div class="col-md-12">
          <div class="form-group">
            <div class="form-group">
              <label>@lang('Content')</label>
              <textarea name="content" class="form-control" id="content">{{ $detail->content }}</textarea>
            </div>
          </div>
        </div>
      </div>
      <div class="box-footer">
        <a class="btn btn-success btn-sm" href="{{ route(Request::segment(2) . '.index') }}">
          <i class="fa fa-bars"></i> @lang('List')
        </a>
        <button type="submit" class="btn btn-primary pull-right btn-sm">
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

  </script>
  <style>
    div.ck-editor__editable {
      height: 350px !important;
      overflow: scroll;
    }
  </style>
@endsection