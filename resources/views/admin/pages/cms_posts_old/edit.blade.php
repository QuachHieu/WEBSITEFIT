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

              <?php if(Auth::guard('admin')->user()->is_super_admin == 1){?>
                <?php if($detail->status == 'active'){ // Nếu đã duyệt hiển thị nút gỡ, cập nhật ?>
                <button type="submit" class="btn btn-danger pull-right btn-sm mg-5" id="gobaidang" name="submit" value="gobaidang">Gỡ bài</button>
                <?php }elseif($detail->status == 'waiting'){ // Nếu đang chờ duyệt thì hiển thị nút đăng, hủy, cập nhật ?>
                <button type="submit" class="btn btn-danger pull-right btn-sm mg-5" id="tuchoi" name="submit" value="tuchoi">Từ chối</button>
                <?php }elseif($detail->status == 'deactive' || $detail->status == 'lock'){ // Nếu từ chối hiển thị nút duyệt tin trạng thái chờ, cập nhật ?>
                <button type="submit" class="btn btn-warning pull-right btn-sm mg-5" id="choxuly" name="submit" value="choxuly">Chờ xử lý</button>
                <?php } ?>
              <?php } ?>
              <?php if(Auth::guard('admin')->user()->is_super_admin == 1 && $detail->status != 'active'){ ?>
                <button type="submit" class="btn btn-warning pull-right btn-sm mg-5" id="xuatban" name="submit" value="xuatban">Xuất bản</button>
              <?php } ?>
              <button type="submit" class="btn btn-primary pull-right btn-sm mg-5" id="capnhat" name="submit" value="news_edit">Cập nhật</button>
              
            </ul>

            <div class="tab-content">
              <div class="tab-pane active" id="tab_1">
                <div class="row">
                  <div class="col-md-8 col-lg-8 col-sm-12 col-xs-12">
                    <div class="form-group">
                      <div class="form-group">
                        <label>@lang('Content'): <i style="color: #f00;">("double click" vào ảnh để chọn là ảnh đại diện bài viết)</i></label>
                        <textarea name="content" class="form-control" id="content">{{ $detail->content }}</textarea>
                      </div>
                    </div>

                    <div class="form-group">
                      <label>@lang('Tin liên quan')</label>
                      <button onclick="addNewRelative()" type="button" class="btn btn-sm btn-primary">Chọn</button>
                      <ul id="list_relative">
                        @foreach($relative_posts as $relative)
                        <li id='addRow_{{ $relative->id }}'><input type='checkbox' checked onclick='removePostReative({{ $relative->id }})' name='relation[]' value='{{ $relative->id }}' id='relative_{{ $relative->id }}'/><span>{{ $relative->title }}</span></li>
                        @endforeach
                      </ul>
                    </div>

                    <div class="form-group">
                      <label>@lang('seo_title')</label>
                      <input id="txtSeoTitle" maxlength="160" name="seo_title" class="form-control" value="{{ $detail->seo_title }}"
                        onkeyup="demKytu('txtSeoTitle','remainingInput_seoTitle');" onblur="demKytu('txtSeoTitle','remainingInput_seoTitle');" onclick="demKytu('txtSeoTitle','remainingInput_seoTitle');">
                        <span id='remainingInput_seoTitle' class="note pull-right">{{ mb_strlen($detail->seo_title) }}/160</span>
                    </div>
                    <div class="form-group">
                      <label>@lang('seo_keyword')</label>
                      <input id="txtSeoKeyword" maxlength="255" name="seo_keyword" class="form-control" value="{{ $detail->seo_keyword }}"
                      onkeyup="demKytu('txtSeoKeyword','remainingInput_seoKeyword');" onblur="demKytu('txtSeoKeyword','remainingInput_seoKeyword');" onclick="demKytu('txtSeoKeyword','remainingInput_seoKeyword');">
                        <span id='remainingInput_seoKeyword' class="note pull-right">{{ mb_strlen($detail->seo_keyword) }}/255</span>
                    </div>
                    <div class="form-group">
                      <label>@lang('seo_description')</label>
                      <input id="txtSeoDescription" maxlength="255" name="seo_description" class="form-control" value="{{ $detail->seo_description }}" 
                      onkeyup="demKytu('txtSeoDescription','remainingInput_seoDescription');" onblur="demKytu('txtSeoDescription','remainingInput_seoDescription');" onclick="demKytu('txtSeoDescription','remainingInput_seoDescription');">
                        <span id='remainingInput_seoDescription' class="note pull-right">{{ mb_strlen($detail->seo_description) }}/255</span>
                    </div>
                    
                  </div>
                  <div class="col-md-4 col-lg-4 col-sm-12 col-xs-12">
                    <div class="form-group">
                      <label>@lang('Title') <small class="text-red">*</small></label>
                      <input type="text" class="form-control" maxlength="160" id="txtTitle" onchange="demKytu('txtTitle','remainingInput_text');getUrlPart('txtTitle','txtUrlPart')" onclick="demKytu('txtTitle','remainingInput_text');getUrlPart('txtTitle','txtUrlPart')" onblur="demKytu('txtTitle','remainingInput_text');getUrlPart('txtTitle','txtUrlPart')" name="title" placeholder="@lang('Title')"
                        value="{{ $detail->title }}" required>
                        <span id='remainingInput_text' class="note pull-right">{{ mb_strlen($detail->title) }}/160</span>
                    </div>
                    <div class="form-group">
                      <label>@lang('alias') <small class="text-red">*</small></label>
                      <input type="text" class="form-control" id="txtUrlPart" onchange="getUrlPart('txtUrlPart','txtUrlPart')" onclick="getUrlPart('txtUrlPart','txtUrlPart')" onblur="getUrlPart('txtTitle','txtUrlPart')" name="url_part" placeholder="@lang('Alias')"
                        value="{{ $detail->url_part }}" required>
                    </div>
                    <div class="form-group">
                      <label>@lang('Description')</label>
                      <textarea name="brief" id="description" class="form-control" maxlength="255" onkeyup="demKytu('description','remainingInput_textarea');" onblur="demKytu('description','remainingInput_textarea');" onclick="demKytu('description','remainingInput_textarea');" rows="5">{{ $detail->brief }}</textarea>
                      <span id='remainingInput_textarea' class="note pull-right">{{ mb_strlen($detail->brief) }}/255</span>
                    </div>
                    <div class="form-group">
                      <label>@lang('Danh mục') <small class="text-red">*</small></label>
                      <select name="taxonomy_id" id="taxonomy_id" class="form-control select2" style="width:100%">
                        <option value="">@lang('Please select')</option>
                        @foreach ($parents as $item)
                          @if ($item->parent_id == 0 || $item->parent_id == null)
                            <option value="{{ $item->id }}"
                              {{ if($item->id == $detail->taxonomy_id) ? 'selected' : '' }}>{{ $item->title }}</option>

                            @foreach ($parents as $sub)
                              @if ($item->id == $sub->parent_id)
                                <option value="{{ $sub->id }}"
                                  {{ if($sub->id == $detail->taxonomy_id) ? 'selected' : '' }}>- - {{ $sub->title }}
                                </option>

                                @foreach ($parents as $sub_child)
                                  @if ($sub->id == $sub_child->parent_id)
                                    <option value="{{ $sub_child->id }}"
                                      {{ if($sub_child->id == $detail->taxonomy_id) ? 'selected' : '' }}>- - - -
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
                      <label>@lang('Tag') </label>
                      <select name="cms_tag[]" id="cms_tag" multiple="multiple" class="form-control select2" style="width: 100%">
                        
                        @foreach ($parents2 as $item2)
                          @if ($item2->parent_id == 0 || $item2->parent_id == null)
                            <option value="{{ $item2->id }}" {{ in_array($item2->id,explode(",",$detail->cms_tag)) ? 'selected' : '' }}>
                              {{ $item2->title }}</option>

                            @foreach ($parents2 as $sub2)
                              @if ($item2->id == $sub2->parent_id)
                                <option value="{{ $sub2->id }}"
                                  {{ in_array($sub2->id,explode(",",$detail->cms_tag)) ? 'selected' : '' }}>- - {{ $sub2->title }}
                                </option>

                                @foreach ($parents2 as $sub_child2)
                                  @if ($sub-2>id == $sub_child2->parent_id)
                                    <option value="{{ $sub_child2->id }}"
                                      {{ in_array($sub_child2->id,explode(",",$detail->cms_tag)) ? 'selected' : '' }}>- - - -
                                      {{ $sub_child2->title }}</option>
                                  @endif
                                @endforeach
                              @endif
                            @endforeach
                          @endif
                        @endforeach
                      </select>
                    </div>
                    <div class="form-group">
                      <label>@lang('Chọn loại tin') <small class="text-red">*</small></label>
                      <select name="news_position" class="form-control" id="news_position">
                        @foreach($array_location as $key=>$position_text)
                        <option value="{{ $key }}" {{ $detail->news_position == $key ? 'selected' : '' }}>{{ $position_text }}</option>
                        @endforeach
                      </select>
                    </div>
                    <div class="form-group">
                      <label>@lang('Tác giả') <small class="text-red">*</small></label>
                      <input type="text" class="form-control" name="author" placeholder="@lang('Tác giả')"
                        value="{{ $detail->author }}">
                    </div>
                    <?php if(Auth::guard('admin')->user()->is_super_admin == 1){?>
                    <div class="form-group">
                      <label>@lang('Tiền nhuận bút') <small class="text-red">*</small></label>
                      <input type="number" class="form-control" name="nhuanbut" placeholder="@lang('Tiền nhuận bút')"
                        value="{{ $detail->nhuanbut }}">
                    </div>
                    <?php } ?>
                    <div class="form-group">
                      <label>@lang('Đường dẫn') </label>
                      <input type="text" class="form-control" name="url_coppy" placeholder="@lang('Đường dẫn')"
                        value="{{ $detail->url_coppy }}">
                    </div>
                    <div class="form-group">
                      <label>@lang('Thời gian xuất bản') </label>
                      <input class="form-control" name="aproved_date" id="aproved_date" type="datetime-local" value="<?php echo $detail->aproved_date > 0 ? date('Y-m-d\TH:i', strtotime($detail->aproved_date)) : date('Y-m-d\TH:i') ?>" />
                    </div>
                    <div class="form-group">
                      <label>@lang('Image')</label>
                      <div class="input-group">
                        <span class="input-group-btn">
                          <a data-input="image" data-preview="image-holder" onclick="openPopupImg('image')" class="btn btn-primary lfm"
                            data-type="cms-image">
                            <i class="fa fa-picture-o"></i> @lang('choose')
                          </a>
                        </span>
                        <input id="image" class="form-control" type="text" name="image"
                          placeholder="@lang('image_link')..." value="{{ $detail->image }}">
                      </div>
                      <div id="image-holder" style="margin-top:15px;max-height:100px;">
                        @if ($detail->image != '')
                          
                        @endif
                        <img id="view_image" style="height: 5rem;" src="{{ $detail->image }}">
                      </div>
                    </div>
                    <div class="form-group">
                      <label>@lang('Image thumb')</label>
                      <div class="input-group">
                        <span class="input-group-btn">
                          <a data-input="image_thumb"  onclick="openPopupImg('image_thumb')"  data-preview="image_thumb-holder" class="btn btn-primary lfm"
                            data-type="cms-image">
                            <i class="fa fa-picture-o"></i> @lang('choose')
                          </a>
                        </span>
                        <input id="image_thumb" class="form-control" type="text" name="image_thumb"
                          placeholder="@lang('image_link')..." value="{{ $detail->image_thumb }}">
                      </div>
                      <div id="image_thumb-holder" style="margin-top:15px;max-height:100px;">
                        @if ($detail->image_thumb != '')
                          
                        @endif
                        <img id="view_image_thumb" style="height: 5rem;" src="{{ $detail->image_thumb }}">
                      </div>
                    </div>
                    
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
            <?php if(Auth::guard('admin')->user()->is_super_admin == 1){?>
							<?php if($detail->status == 'active'){ // Nếu đã duyệt hiển thị nút gỡ, cập nhật ?>
							<button type="submit" class="btn btn-danger pull-right btn-sm mg-5" id="gobaidang" name="submit" value="gobaidang">Gỡ bài</button>
							<?php }elseif($detail->status == 'waiting'){ // Nếu đang chờ duyệt thì hiển thị nút đăng, hủy, cập nhật ?>
							<button type="submit" class="btn btn-danger pull-right btn-sm mg-5" id="tuchoi" name="submit" value="tuchoi">Từ chối</button>
							<?php }elseif($detail->status == 'deactive' || $detail->status == 'lock'){ // Nếu từ chối hiển thị nút duyệt tin trạng thái chờ, cập nhật ?>
							<button type="submit" class="btn btn-warning pull-right btn-sm mg-5" id="choxuly" name="submit" value="choxuly">Chờ xử lý</button>
							<?php } ?>
						<?php } ?>
            <?php if(Auth::guard('admin')->user()->is_super_admin == 1 && $detail->status != 'active'){ ?>
							<button type="submit" class="btn btn-warning pull-right btn-sm mg-5" id="xuatban" name="submit" value="xuatban">Xuất bản</button>
						<?php } ?>
						<button type="submit" class="btn btn-primary pull-right btn-sm mg-5" id="capnhat" name="submit" value="news_edit">Cập nhật</button>
						
        </div>
      </form>
    </div>
  </section>

  <div class="modal modal-login fade" id="postnewModal" tabindex="-1" role="dialog" aria-labelledby="postnewModalModalLabel" aria-hidden="true">
    
    @csrf
    <div class="modal-dialog" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="loginModalLabel">Chọn tin liên quan</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close" style="margin-top: -22px;">
            <span aria-hidden="true">×</span>
          </button>
        </div>
        <div class="modal-body">
          
          <div class="input-group">
            <input id="keyword" type="text" class="form-control" name="search" placeholder="Keyword...">
            <span class="input-group-addon" onclick="searchPost()"><i class="fa fa-search"></i></span>
          </div>

          <table class="table table-hover table-bordered">
            <thead>
              <tr>
                <th>Bài viết</th>
                <th>Chọn</th>
              </tr>
            </thead>
            <tbody id="dataTablePost">
              @foreach($cms_posts as $post)
              <tr>
                <td>{{ $post->title }}</td>
                <td>
                  <input type="checkbox" onclick="checkPostReative({{ $post->id }})" id="post_id_{{ $post->id }}" name="check_realtive[]" value="{{ $post->id }}" />
                  <input type="hidden" id="post_title_{{ $post->id }}" value="{{ $post->title }}" />
                </td>
              </tr>
              @endforeach
            </tbody>
          </table>
          <div class="text-right">
            <button type="buttom" name="btnLogin" id="btnLogin" class="btn btn-primary" class="close" data-dismiss="modal" aria-label="Close" spellcheck="false">Đóng</button>
          </div>  
        </div>
      </div>
    </div>
</div>

<div class="toast" style="display: none">
  <div class="toast-body">
    Thay đổi ảnh đại diện bài viết thành công
  </div>
</div>
@endsection

@section('script')

<script>
  
  function searchPost(){
    var keyword = $('#keyword').val();
    //alert(keyword);
		$.ajax({
		  url: '{{ route('cms_posts.post_relative') }}',
		  type: 'POST',
		  data: {
			_token: '{{ csrf_token() }}',
			keyword: keyword
		  },
		  context: document.body,
		}).done(function(data) {
		  $('#dataTablePost').html(data);
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
        toolbar: ['imageTextAlternative', '|', 'imageStyle:alignLeft', 'imageStyle:full','imageStyle:side', 'imageStyle:alignCenter'],
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
      
      
    } )
    .then( editor => {
      window.editor = editor;
      
    } )
    .catch( error => {
      console.error( 'Oops, something went wrong!' );
      console.error( 'Please, report the following error on https://github.com/ckeditor/ckeditor5/issues with the build id and the error stack trace:' );
      console.warn( 'Build id: v10wxmoi2tig-mwzdvmyjd96s' );
      console.error( error );
    } );
</script>

@endsection
