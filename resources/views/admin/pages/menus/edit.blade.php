@extends('admin.layouts.app')

@section('title')
  {{ $module_name }}
@endsection

@section('content')
  <!-- Content Header (Page header) -->
  <section class="content-header">
    <h1>
      {{ $module_name }}
      <a class="btn btn-sm btn-warning pull-right" href="{{ route(Request::segment(2) . '.create') }}">
        <i class="fa fa-plus"></i> @lang('Add')
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
      <!-- /.box-header -->

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
            <a class="btn btn-success btn-sm pull-right" href="{{ route(Request::segment(2) . '.index') }}">
              <i class="fa fa-bars"></i>
              @lang('List')
            </a>
          </ul>
          <div class="tab-content">
            <div class="tab-pane active" id="tab_1">
              <!-- form start -->
              <form role="form" action="{{ route(Request::segment(2) . '.update', $detail->id) }}" method="POST">
                @csrf
                @method('PUT')
                <div class="row">
                  <div class="col-md-3">
                    <div class="form-group">
                      <label>@lang('Title') <small class="text-red">*</small></label>
                      <input type="text" class="form-control" name="name" placeholder="@lang('Title')"
                        value="{{ $detail->name }}" required>
                    </div>
                  </div>
                  <div class="col-md-3">
                    <div class="form-group">
                      <label>@lang('Menu type') <small class="text-red">*</small></label>
                      <select name="menu_type" id="menu_type" class="form-control select2">
                        <option value="">@lang('please_chosen')</option>
                        @foreach (App\Consts::MENU_TYPE as $key => $value)
                          <option value="{{ $key }}" {{ $detail->menu_type == $value ? 'selected' : '' }}>
                            @lang($value)
                          </option>
                        @endforeach
                      </select>
                    </div>
                  </div>
                  <div class="col-md-3">
                    <div class="form-group">
                      <label>@lang('Status')</label>
                      <div class="form-control">
                        @foreach (App\Consts::STATUS as $key => $value)
                          <label>
                            <input type="radio" name="status" value="{{ $key }}"
                              {{ $detail->status == $key ? 'checked' : '' }}>
                            <small class="mr-15">{{ __($value) }}</small>
                          </label>
                        @endforeach
                      </div>
                    </div>
                  </div>
                  <div class="col-md-3">
                    <div class="form-group">
                      <label>@lang('Order')</label>
                      <input type="number" class="form-control" name="iorder" placeholder="@lang('Order')"
                        value="{{ $detail->iorder }}">
                    </div>
                  </div>
                  <div class="col-md-12">
                    <div class="form-group">
                      <label>@lang('Description')</label>
                      <textarea name="description" id="description" class="form-control" rows="3">{{ $detail->description }}</textarea>
                    </div>
                  </div>
                  <div class="col-md-12">
                    <button type="submit" class="btn btn-primary btn-sm">
                      <i class="fa fa-floppy-o"></i>
                      @lang('Save')
                    </button>
                  </div>
                </div>
              </form>
              <div class="row">
                <div class="col-md-12">
                  <hr style="border-top: dashed 2px #a94442; margin: 10px 0px;">
                </div>
                <div class="col-md-6">
                  <div class="box box-primary">
                    <div class="box-header with-border">
                      <h3 class="box-title">
                        @lang('Menu structure')
                      </h3>
                    </div>
                    <div class="box-body">
                      <div class="table-responsive">
                        <div class="dd" id="menu-sort">
                          <ol class="dd-list">
                            @foreach ($menus as $item)
                              @if ($item->parent_id == $detail->id)
                                <li class="dd-item" data-id="{{ $item->id }}">
                                  <div class="dd-handle ">
                                    {{ $item->name }}
                                    <span class="dd-nodrag pull-right">
                                      <small>({{App\Consts::STATUS[$item->status]}})</small>
                                      <a data-id="{{ $item->id }}" class="edit_menu cursor"
                                        title="@lang('Edit')">
                                        <i class="fa fa-edit fa-edit"></i>
                                      </a>
                                      <a data-id="{{ $item->id }}" class="remove_menu cursor text-danger"
                                        title="@lang('Delete')">
                                        <i class="fa fa-trash fa-edit"></i>
                                      </a>
                                    </span>
                                  </div>
                                  @if ($item->sub > 0)
                                    <ol class="dd-list">
                                      @foreach ($menus as $item_sub_1)
                                        @if ($item_sub_1->parent_id == $item->id)
                                          <li class="dd-item" data-id="{{ $item_sub_1->id }}">
                                            <div class="dd-handle ">
                                              {{ $item_sub_1->name }}
                                              <span class="dd-nodrag pull-right">
                                                <small>(@lang($item_sub_1->status))</small>
                                                <a data-id="{{ $item_sub_1->id }}" class="edit_menu cursor"
                                                  title="@lang('Edit')">
                                                  <i class="fa fa-edit fa-edit"></i>
                                                </a>
                                                <a data-id="{{ $item_sub_1->id }}"
                                                  class="remove_menu cursor text-danger" title="@lang('Delete')">
                                                  <i class="fa fa-trash fa-edit"></i>
                                                </a>
                                              </span>
                                            </div>
                                            @if ($item_sub_1->sub > 0)
                                              <ol class="dd-list">
                                                @foreach ($menus as $item_sub_2)
                                                  @if ($item_sub_2->parent_id == $item_sub_1->id)
                                                    <li class="dd-item" data-id="{{ $item_sub_2->id }}">
                                                      <div class="dd-handle">
                                                        {{ $item_sub_2->name }}
                                                        <span class="dd-nodrag pull-right">
                                                          <small>(@lang($item_sub_2->status))</small>
                                                          <a data-id="{{ $item_sub_2->id }}" class="edit_menu cursor"
                                                            title="@lang('Edit')">
                                                            <i class="fa fa-edit fa-edit"></i>
                                                          </a>
                                                          <a data-id="{{ $item_sub_2->id }}"
                                                            class="remove_menu cursor text-danger"
                                                            title="@lang('Delete')">
                                                            <i class="fa fa-trash fa-edit"></i>
                                                          </a>
                                                        </span>
                                                      </div>
                                                    </li>
                                                  @endif
                                                @endforeach
                                              </ol>
                                            @endif
                                          </li>
                                        @endif
                                      @endforeach
                                    </ol>
                                  @endif
                                </li>
                              @endif
                            @endforeach
                          </ol>
                        </div>
                      </div>
                    </div>
                    <div class="box-footer">
                      <a class="btn btn-warning btn-flat menu-sort-save btn-sm" title="@lang('Save')">
                        <i class="fa fa-floppy-o"></i>
                        @lang('Save sort')
                      </a>
                    </div>
                  </div>
                </div>
                <div class="col-md-6">
                  <div class="box box-primary">
                    <form action="{{ route(Request::segment(2) . '.store') }}" method="POST" class="form-horizontal"
                      id="form-main" enctype="multipart/form-data">
                      @csrf
                      @method('POST')
                      <div class="box-header with-border">
                        <h3 class="box-title" id="link-title">
                          @lang('Add new link to menu')
                        </h3>
                      </div>
                      <div class="box-body">
                        <div class="form-group">
                          <label for="link-parent_id" class="col-sm-3 control-label">@lang('Parent menu')</label>
                          <div class="col-sm-9">
                            <select name="parent_id" id="link-parent_id" class="form-control select2" autocomplete="off">
                              <option value="{{ $detail->id }}">== @lang('ROOT') ==</option>
                              @foreach ($menus as $item)
                                @if ($item->parent_id == $detail->id)
                                  <option value="{{ $item->id }}">- - {{ $item->name }}</option>
                                  @foreach ($menus as $sub)
                                    @if ($item->id == $sub->parent_id)
                                      <option value="{{ $sub->id }}">- - - - {{ $sub->name }}</option>
                                      @foreach ($menus as $sub_child)
                                        @if ($sub->id == $sub_child->parent_id)
                                          <option value="{{ $sub_child->id }}">- - - - - -{{ $sub_child->name }}
                                          </option>
                                        @endif
                                      @endforeach
                                    @endif
                                  @endforeach
                                @endif
                              @endforeach
                            </select>
                          </div>
                        </div>
                        <div class="form-group">
                          <label for="link-name" class="col-sm-3 control-label">
                            @lang('Title')
                            <small class="text-red">*</small>
                          </label>
                          <div class="col-sm-9">
                            <input type="text" class="form-control" id="link-name" placeholder="@lang('Title')"
                              name="name" required autocomplete="off">
                          </div>
                        </div>
                        <div class="form-group">
                          <label for="link-url_link" class="col-sm-3 control-label">
                            @lang('Url')
                            <small class="text-red">*</small>
                          </label>
                          <div class="col-sm-9">
                            <input type="text" class="form-control" id="link-url_link" placeholder="@lang('Url')"
                              name="url_link" required autocomplete="off">
                              <select id="category_link" class="form-control select2" onchange="getUrl()" autocomplete="off">
                                <option value="" selected>-Chọn danh mục-</option>
                                @foreach($category_title as $category)
                                <option value="/{{ $category->taxonomy.'/'.$category->url_part }}.html" >{{ $category->title .' ('.App\Consts::TAXONOMY[$category->taxonomy].')' }}</option>
                                @endforeach
                              </select>
                          </div>
                        </div>
                        <div class="form-group">
                          <label for="link-url_link" class="col-sm-3 control-label">
                            @lang('Icon')
                          </label>
                          <div class="col-sm-9">
                            <input type="text" class="form-control" id="link-icon" placeholder="VD: fa fa-folder"
                              name="json_params[icon]" autocomplete="off">
                          </div>
                        </div>
                        <div class="form-group">
                          <label for="link-target" class="col-sm-3 control-label">
                            @lang('Select target')
                          </label>
                          <div class="col-sm-9">
                            <select name="json_params[target]" id="link-target" class="form-control select2" autocomplete="off">
                              <option value="_self" selected>@lang('_self')</option>
                              <option value="_blank">@lang('_blank')</option>
                            </select>
                          </div>
                        </div>
                        <div class="form-group">
                          <label for="link-status" class="col-sm-3 control-label">
                            @lang('Status')
                          </label>
                          <div class="col-sm-9">
                            <div class="form-control">
                              @foreach (App\Consts::STATUS as $key => $value)
                                <label>
                                  <input type="radio" name="status" value="{{ $key }}"
                                    {{ $item->status == $key ? 'checked' : '' }}>
                                  <small class="mr-15">{{ __($value) }}</small>
                                </label>
                              @endforeach
                            </div>
                          </div>
                        </div>

                      </div>
                      <div class="box-footer">
                        <button type="submit" class="btn btn-success btn-sm submit_form">
                          @lang('Add new')
                        </button>
                        <button type="button" class="btn btn-default btn-sm pull-right reset_form">
                          @lang('Reset form')
                        </button>
                      </div>
                    </form>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>

        <div class="box-footer">

        </div>

      </div>
  </section>
@endsection

@section('style')
  <link rel="stylesheet" href="{{ asset('themes/admin/plugins/nestable/jquery.nestable.min.css') }}">
@endsection

@section('script')
  <script src="{{ asset('themes/admin/plugins/nestable/jquery.nestable.min.js') }}"></script>
  <script type="text/javascript">

    function getUrl() {

      var url_c = $('#category_link').val();
      $('#link-url_link').val(url_c);
      
      
    }

    $('#menu-sort').nestable();
    $('.menu-sort-save').click(function() {
      $('#loading').show();
      let serialize = $('#menu-sort').nestable('serialize');
      let menu = JSON.stringify(serialize);
      $.ajax({
          url: '{{ route('menus.update_sort') }}',
          type: 'POST',
          dataType: 'json',
          data: {
            _token: '{{ csrf_token() }}',
            menu: menu,
            root_id: {{ $detail->id }}
          },
        })
        .done(function(data) {
          $('#loading').hide();
          if (data.error == 0) {
            location.reload();
          } else {
            alert(data.msg);
            location.reload();
          }
        });
    });

    $('.remove_menu').click(function() {
      if (confirm("@lang('confirm_action')")) {
        let _root = $(this).closest('.dd-item');
        let id = $(this).data('id');
        $.ajax({
          method: 'post',
          url: '{{ route('menus.delete') }}',
          data: {
            id: id,
            _token: '{{ csrf_token() }}',
          },
          success: function(data) {
            if (data.error == 1) {
              alert(data.msg);
            } else {
              _root.remove();
            }
          }
        });
      }
    });
    
    var menus = @json($menus ?? []);
    $('.edit_menu').click(function() {
      $('.dd-handle').removeClass('active-item');
      let _root = $(this).closest('.dd-handle');
      let _form = $('#form-main');
      let id = $(this).data('id');
      let item = menus.find(menu => menu.id === id);
      if (!$.isEmptyObject(item)) {
        _form.find('#link-title').text("{{ __('Edit link for menu') }}");
        _form.find('.submit_form').text("{!! __('Save & update') !!}");
        _form.find('#link-parent_id').val(item.parent_id)
        _form.find('#link-name').val(item.name);
        _form.find('#link-url_link').val(item.url_link);
        if (item.json_params) {
          _form.find('#link-target').val(item.json_params.target || '_self');
          _form.find('#link-icon').val(item.json_params.icon || '');
        }
        _form.find('input[name=status][value=' + item.status + ']').prop('checked', true);
        _form.attr('action', '{{ route(Request::segment(2) . '.index') }}/' + item.id);
        _form.find('input[name=_method]').val('PUT');
        _form.find('input[name=_token]').val('{{ csrf_token() }}');
      }
      $(".select2").select2();
      _root.addClass('active-item');
    });

    $('.reset_form').click(function() {
      $('.dd-handle').removeClass('active-item');
      let _form = $('#form-main');
      _form.find('#link-title').text("{{ __('Add new link to menu') }}");
      _form.find('.submit_form').text("{!! __('Add new') !!}");
      _form.find('#link-parent_id').val({{ $detail->id }})
      _form.find('#link-name').val('');
      _form.find('#link-url_link').val('');
      _form.find('#link-target').val('_self');
      _form.find('input[name=status][value=active]').prop('checked', true);
      _form.attr('action', '{{ route(Request::segment(2) . '.store') }}');
      _form.find('input[name=_method]').val('POST');
      _form.find('input[name=_token]').val('{{ csrf_token() }}');
      $(".select2").select2();
    });
  </script>
@endsection
