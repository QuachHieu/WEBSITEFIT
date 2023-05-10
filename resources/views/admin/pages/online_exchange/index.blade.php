@extends('admin.layouts.app')

@section('title')
{{ $module_name }}
@endsection

@section('content-header')
<!-- Content Header (Page header) -->
<section class="content-header">
  <h1>
    {{ $module_name }}
    <a class="btn btn-sm btn-warning pull-right" href="{{ route(Request::segment(2).'.create') }}"><i
        class="fa fa-plus"></i> @lang('Add')</a>
  </h1>
</section>
@endsection

@section('content')

<!-- Main content -->
<section class="content">
  {{-- Search form --}}
  <div class="box box-default">

    <div class="box-header with-border">
      <h3 class="box-title">@lang('Filter')</h3>
      <div class="box-tools pull-right">
        <button class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
      </div>
    </div>
    <form action="{{ route(Request::segment(2) . '.index') }}" method="GET">
      <div class="box-body">
        <div class="row">
          
          <div class="col-md-2">
            <div class="form-group">
              <label>Trạng thái</label>
              <select name="status" id="status" class="form-control select2" style="width: 100%;">
                <option value="">@lang('Please select')</option>
                @foreach (App\Consts::LIVE_STATUS as $key => $value)
                  <option value="{{ $key }}"
                    {{ isset($params['status']) && $key == $params['status'] ? 'selected' : '' }}>
                    {{ __($value) }}</option>
                @endforeach
              </select>
            </div>
          </div>
          
          <div class="col-md-6">
            <div class="form-group">
              <label>Từ khóa</label>
              <input type="text" class="form-control" name="keyword" placeholder="@lang('keyword_note')"
                value="{{ isset($params['keyword']) ? $params['keyword'] : '' }}">
            </div>
          </div>

          <div class="col-md-2">
            <div class="form-group">
              <label>@lang('Filter')</label>
              <div>
                <button type="submit" class="btn btn-primary btn-sm mr-10">@lang('Submit')</button>
                <a class="btn btn-default btn-sm" href="{{ route(Request::segment(2) . '.index') }}">
                  @lang('Reset')
                </a>
              </div>
            </div>
          </div>

        </div>
      </div>
    </form>
  </div>
  {{-- End search form --}}

  <div class="box">
    <div class="box-header">
      <h3 class="box-title">@lang('Danh sách tường thuật trực tiếp')</h3>
    </div>

    <div class="box-body table-responsive">
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
      @if (count($rows) == 0)
      <div class="alert alert-warning alert-dismissible">
        <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
        @lang('No record found')
      </div>
      @else
      <table class="table table-hover table-bordered">
        <thead>
          <tr>
            <th>@lang('Tiêu đề')</th>
            <th>@lang('Danh mục')</th>
            <th>@lang('Bắt đầu')</th>
            <th>@lang('Kết thúc')</th>
            <th>@lang('Trạng thái')</th>
            <th>@lang('Updated at')</th>
            <th style="min-width: 80px">@lang('Action')</th>
          </tr>
        </thead>
        <tbody>
          @foreach ($rows as $row)

          <form action="{{ route(Request::segment(2).'.destroy', $row->id) }}" method="POST"
            onsubmit="return confirm('@lang('confirm_action')')">
            <tr class="valign-middle">
              <td>
                {{ $row->title }}
              </td>
              <td>
                {{ $row->taxonomy_title }}
              </td>
              <td>
                {{ $row->start_date }}
              </td>
              <td>
                {{ $row->end_date }}
              </td>
              <td>
                {{ APP\Consts::LIVE_STATUS[$row->status] }}
              </td>
              <td>
                {{$row->updated_at}}
              </td>
              <td class="text-center">
                <a class="btn btn-xs btn-primary" data-toggle="tooltip" title="@lang('Detail')"
                  data-original-title="@lang('Detail')" href="{{ route(Request::segment(2).'.show', $row->id) }}" style="margin-right: 5px;">
                  <i class="fa fa-comment"></i>
                </a>
                <a class="btn btn-xs btn-warning" data-toggle="tooltip" title="@lang('Update')"
                  data-original-title="@lang('update')" href="{{ route(Request::segment(2).'.edit', $row->id) }}" style="margin-right: 5px;">
                  <i class="fa fa-pencil-square-o"></i>
                </a>
                @csrf
                @method('DELETE')
                <button class="btn btn-xs btn-danger" type="submit" data-toggle="tooltip" title="@lang('Delete')"
                  data-original-title="@lang('delete')" >
                  <i class="fa fa-ban"></i>
                </button>
              </td>
            </tr>
          </form>
          @endforeach
        </tbody>
      </table>
      @endif
    </div>

    @if ($rows->hasPages())
    <div class="box-footer clearfix">
      {{ $rows->withQueryString()->links('admin.pagination.default') }}
    </div>
    @endif

  </div>
</section>
<script type="text/javascript">
  /*
  function updateStatusComment(id){
    var status = $('#apcept_comment_'+id).val();
    $('#ic-loading_'+id).show();
    $.ajax({
      url: '{{ route('cms_posts.update_comment_status') }}',
      type: 'POST',
      data: {
      _token: '{{ csrf_token() }}',
      status: status,
      id: id,
      },
      context: document.body,
    }).done(function(data) {
      $('#ic-loading_'+id).hide();
    });
  }
  */
</script>

@endsection