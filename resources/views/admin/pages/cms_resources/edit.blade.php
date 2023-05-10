@extends('admin.layouts.app')

@section('title')
  {{ $module_name }}
@endsection
@section('style')
  <style>
    .gallery-image {
      cursor: pointer;
    }

    .btn-action {
      position: absolute;
      top: 40%;
      display: none;
      width: calc(100% - 30px);
      text-align: center;
    }

    .img-width {
      width: 100%;
    }

    .mr-5 {
      margin-right: 5px;
    }

    .gallery-image:hover {
      opacity: 0.75;
    }

    .gallery-image img {
      border: 1px dashed #CDCDCD;
    }

    .gallery-image {
      height: 200px;
      overflow: hidden;
    }

  </style>
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
    @php
    $array_location = APP\Consts::POST_POSITION;
    @endphp
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
                  <div class="col-md-3">
                    <div class="form-group">
                      <label>@lang('Resource category') <small class="text-red">*</small></label>
                      <select name="taxonomy_id" id="taxonomy_id" class="form-control select2">
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
                                      {{ $detail->taxonomy_id == $sub_child->id ? 'selected' : '' }}>
                                      - - - -
                                      {{ $sub_child->title }}</option>
                                  @endif
                                @endforeach
                              @endif
                            @endforeach
                          @endif
                        @endforeach
                      </select>
                    </div>
                  </div>
                  <div class="col-md-3">
                    <div class="form-group">
                      <label>@lang('Phân loại')</label>
                      <div class="form-control">
                        <label>
                          <input type="radio" name="phanloai" value="1"
                            {{ $detail->phanloai == '1' ? 'checked' : '' }}>
                          <small>@lang('Công khai')</small>
                        </label>
                        <label>
                          <input type="radio" name="phanloai" value="2"
                            {{ $detail->phanloai == '2' ? 'checked' : '' }} class="ml-15">
                          <small>@lang('Không công khai')</small>
                        </label>
                      </div>
                    </div>
                  </div>
                  <div class="col-md-6">
                    <div class="form-group">
                      <label>@lang('Title') <small class="text-red">*</small></label>
                        <input type="text" class="form-control" id="txtTitle" onchange="demKytu('txtTitle','remainingInput_text');getUrlPart('txtTitle','txtUrlPart')" onclick="demKytu('txtTitle','remainingInput_text');getUrlPart('txtTitle','txtUrlPart')" onblur="demKytu('txtTitle','remainingInput_text');getUrlPart('txtTitle','txtUrlPart')" name="title" placeholder="@lang('Title')"
                        value="{{ $detail->title }}" required>
                    </div>
                    <div class="form-group hidden">
                      <label>@lang('alias') <small class="text-red">*</small></label>
                      <input type="text" class="form-control" id="txtUrlPart" onchange="getUrlPart('txtUrlPart','txtUrlPart')" onclick="getUrlPart('txtUrlPart','txtUrlPart')" onblur="getUrlPart('txtTitle','txtUrlPart')" name="url_part" placeholder="@lang('Alias')"
                        value="{{ $detail->url_part }}">
                    </div>
                  </div>
                  <div class="col-md-4">
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
                  </div>
               
                  <div class="col-md-4">
                    <div class="form-group">
                      <label>@lang('Order')</label>
                      <input type="number" class="form-control" name="iorder" placeholder="@lang('Order')"
                        value="{{ $detail->iorder }}">
                    </div>
                  </div>
                  <div class="col-md-4">
                    <label>@lang('Chọn loại tin') <small class="text-red">*</small></label>
                    <select name="news_position" class="form-control" id="news_position">
                      @foreach($array_location as $key=>$position_text)
                      <option value="{{ $key }}" {{ $detail->news_position == $key ? 'selected' : '' }}>{{ $position_text }}</option>
                      @endforeach
                    </select>
                  </div>
                  <div class="col-md-6">
                    <div class="form-group">
                      <label>@lang('Image')</label>
                      <div class="input-group">
                        <span class="input-group-btn">
                          <a data-input="image" data-preview="image-holder" class="btn btn-primary lfm"
                            data-type="cms-image" onclick="openPopupImg('image')">
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
                      <label>@lang('File')</label>
                      <div class="input-group">
                        <span class="input-group-btn">
                          <a data-input="file" onclick="openPopupImg('file')" data-preview="image-holder" class="btn btn-primary lfm"
                            data-type="cms-image">
                            <i class="fa fa-picture-o"></i> @lang('choose')
                          </a>
                        </span>
                        <input id="file" class="form-control" type="text" name="file"
                          placeholder="@lang('image_link')..." value="{{ $detail->file }}">
                      </div>
                     
                    </div>
                  </div>
                  <div class="col-md-12">
                    <div class="form-group">
                      <label>@lang('Description')</label>
                      <textarea name="brief" class="form-control"
                        rows="3">{{ isset($detail->brief) ? $detail->brief : old('brief') }}</textarea>
                    </div>
                  </div>
                  <div class="col-md-12">
                    <div class="form-group">
                      <label>@lang('Content')</label>
                      <textarea name="content" class="form-control"
                        id="content">{{ isset($detail->content) ? $detail->content : old('content') }}</textarea>
                    </div>
                  </div>
                  {{-- <div class="col-md-12">
                    <hr style="border-top: dashed 2px #a94442; margin: 10px 0px;">
                  </div>
                  <div class="col-md-12">
                    <div class="form-group">
                      <label>@lang('Video list')</label>
                      <input class="btn btn-warning btn-sm add-gallery-video" data-toggle="tooltip"
                        title="Nhấn để chọn thêm video" type="button" value="Thêm video" />
                    </div>
                    <div class="list-gallery-video">
                      @isset($detail->json_params->gallery_video)
                        @foreach ($detail->json_params->gallery_video as $key => $item)
                          <div class="row gallery-video border-bottom">
                            <div class="col-md-6 col-xs-12 py-2 ">
                              <input type="text" name="json_params[gallery_video][{{ $key }}][title]"
                                class="form-control" id="gallery_video_title_{{ $key }}"
                                placeholder="Tiêu đề, giới thiệu ngắn..." value="{{ $item->title ?? '' }}">
                            </div>
                            <div class="col-md-5 col-xs-10 py-2 ">
                              <div class="input-group">
                                <span class="input-group-btn">
                                  <a data-input="gallery_video_source_{{ $key }}" class="btn btn-primary video">
                                    <i class="fa fa-file-video-o"></i> @lang('choose')
                                  </a>
                                </span>
                                <input id="gallery_video_source_{{ $key }}" class="form-control" type="text"
                                  name="json_params[gallery_video][{{ $key }}][source]"
                                  placeholder="Link video..." value="{{ $item->source ?? '' }}" required>
                              </div>
                            </div>
                            <div class="col-md-1 col-xs-2 py-2 ">
                              <span class="btn btn-sm btn-danger btn-remove" data-toggle="tooltip"
                                title="@lang('delete')">
                                <i class="fa fa-trash"></i>
                              </span>
                            </div>
                          </div>
                        @endforeach
                      @endisset
                    </div>
                  </div>

                  <div class="col-md-12">
                    <hr style="border-top: dashed 2px #a94442; margin: 10px 0px;">
                  </div> --}}
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
            </div>
          </div>
        </div>

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
  <script>
    $('.video').filemanager('video', {
      prefix: route_prefix
    });
    $('.audio').filemanager('audio', {
      prefix: route_prefix
    });

    $(document).ready(function() {
      var no_image_link = '{{ url('img/no_image.jpg') }}';

      $('.add-gallery-image').click(function(event) {
        let keyRandom = new Date().getTime();
        let elementParent = $('.list-gallery-image');
        let elementAppend =
          '<div class="col-lg-2 col-md-3 col-sm-4 mb-1 gallery-image my-15">';
        elementAppend += '<img class="img-width"';
        elementAppend += 'src="' + no_image_link + '">';
        elementAppend += '<input type="text" name="json_params[gallery_image][' + keyRandom +
          ']" class="hidden" id="gallery_image_' + keyRandom +
          '">';
        elementAppend += '<div class="btn-action">';
        elementAppend += '<span class="btn btn-sm btn-success btn-upload lfm mr-5" data-input="gallery_image_' +
          keyRandom +
          '">';
        elementAppend += '<i class="fa fa-upload"></i>';
        elementAppend += '</span>';
        elementAppend += '<span class="btn btn-sm btn-danger btn-remove">';
        elementAppend += '<i class="fa fa-trash"></i>';
        elementAppend += '</span>';
        elementAppend += '</div>';
        elementParent.append(elementAppend);

        $('.lfm').filemanager('image', {
          prefix: route_prefix
        });
      });
      // Change image for img tag gallery-image
      $('.list-gallery-image').on('change', 'input', function() {
        let _root = $(this).closest('.gallery-image');
        var img_path = $(this).val();
        _root.find('img').attr('src', img_path);
      });

      // Delete image
      $('.list-gallery-image').on('click', '.btn-remove', function() {
        // if (confirm("@lang('confirm_action')")) {
        let _root = $(this).closest('.gallery-image');
        _root.remove();
        // }
      });

      $('.list-gallery-image').on('mouseover', '.gallery-image', function(e) {
        $(this).find('.btn-action').show();
      });
      $('.list-gallery-image').on('mouseout', '.gallery-image', function(e) {
        $(this).find('.btn-action').hide();
      });

      // Xử lý video input
      $('.add-gallery-video').click(function(event) {
        let keyRandom = new Date().getTime();
        let elementParent = $('.list-gallery-video');
        let elementAppend = '';
        elementAppend += '<div class="row gallery-video border-bottom">';
        elementAppend += '<div class="col-md-6 col-xs-12 py-2 ">';
        elementAppend += '<input type="text" name="json_params[gallery_video][' + keyRandom +
          '][title]" class="form-control" id="gallery_video_title_' + keyRandom +
          '" placeholder="Tiêu đề, giới thiệu ngắn...">';
        elementAppend += '</div>';
        elementAppend += '<div class="col-md-5 col-xs-10 py-2 ">';
        elementAppend += '<div class="input-group">';
        elementAppend += '<span class="input-group-btn">';
        elementAppend += '<a data-input="gallery_video_source_' + keyRandom + '" class="btn btn-primary video">';
        elementAppend += '<i class="fa fa-file-video-o"></i> ';
        elementAppend += '@lang('choose')';
        elementAppend += '</a>';
        elementAppend += '</span>';
        elementAppend += '<input id="gallery_video_source_' + keyRandom +
          '" class="form-control" type="text" name = "json_params[gallery_video][' + keyRandom +
          '][source]" placeholder = "Link video..." required>';
        elementAppend += '</div>';
        elementAppend += '</div>';
        elementAppend += '<div class="col-md-1 col-xs-2 py-2 ">';
        elementAppend +=
          '<span class="btn btn-sm btn-danger btn-remove" data-toggle="tooltip" title="@lang('delete')">';
        elementAppend += '<i class="fa fa-trash"></i>';
        elementAppend += '</span>';
        elementAppend += '</div>';
        elementAppend += '</div>';
        elementParent.append(elementAppend);

        $('.video').filemanager('video', {
          prefix: route_prefix
        });
      });
      // Remove
      $('.list-gallery-video').on('click', '.btn-remove', function() {
        let _root = $(this).closest('.gallery-video');
        _root.remove();
      });

      // Xử lý audio input
      $('.add-gallery-audio').click(function(event) {
        let keyRandom = new Date().getTime();
        let elementParent = $('.list-gallery-audio');
        let elementAppend = '';
        elementAppend += '<div class="row gallery-audio border-bottom">';
        elementAppend += '<div class="col-md-6 col-xs-12 py-2 ">';
        elementAppend += '<input type="text" name="json_params[gallery_audio][' + keyRandom +
          '][title]" class="form-control" id="gallery_audio_title_' + keyRandom +
          '" placeholder="Tiêu đề, giới thiệu ngắn...">';
        elementAppend += '</div>';
        elementAppend += '<div class="col-md-5 col-xs-10 py-2 ">';
        elementAppend += '<div class="input-group">';
        elementAppend += '<span class="input-group-btn">';
        elementAppend += '<a data-input="gallery_audio_source_' + keyRandom + '" class="btn btn-primary audio">';
        elementAppend += '<i class="fa fa-file-audio-o"></i> ';
        elementAppend += '@lang('choose')';
        elementAppend += '</a>';
        elementAppend += '</span>';
        elementAppend += '<input id="gallery_audio_source_' + keyRandom +
          '" class="form-control" type="text" name = "json_params[gallery_audio][' + keyRandom +
          '][source]" placeholder = "Link audio..." required>';
        elementAppend += '</div>';
        elementAppend += '</div>';
        elementAppend += '<div class="col-md-1 col-xs-2 py-2 ">';
        elementAppend +=
          '<span class="btn btn-sm btn-danger btn-remove" data-toggle="tooltip" title="@lang('delete')">';
        elementAppend += '<i class="fa fa-trash"></i>';
        elementAppend += '</span>';
        elementAppend += '</div>';
        elementAppend += '</div>';
        elementParent.append(elementAppend);

        $('.audio').filemanager('audio', {
          prefix: route_prefix
        });
      });
      // Remove
      $('.list-gallery-audio').on('click', '.btn-remove', function() {
        let _root = $(this).closest('.gallery-audio');
        _root.remove();
      });

    });
  </script>
@endsection
