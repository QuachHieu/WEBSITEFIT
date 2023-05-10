@extends('admin.layouts.app')

@section('title')
{{ $module_name }}
@endsection

@section('content-header')
<!-- Content Header (Page header) -->
<section class="content-header">
  <h1>
    {{ $module_name }}
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
          
          <div class="col-md-4">
            <div class="form-group">
              <label>Từ khóa</label>
              <input type="text" class="form-control" name="keyword" placeholder="@lang('Nhập từ khóa bài viết')"
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
      <h3 class="box-title">@lang('Danh sách bình luận')</h3>
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
            <th>@lang('STT')</th>
            <th>@lang('Bài viết')</th>
            <th>@lang('Người thao tác')</th>
            <th>@lang('Ngày thao tác')</th>
            <th>@lang('Action')</th>
          </tr>
        </thead>
        <tbody>
          @foreach ($rows as $stt=>$row)
            <tr class="valign-middle">
              <td class="text-center">
                {{ $stt+1 }}
              </td>
              <td>
                <a href="{{ route(Request::segment(2) . '.edit', $row->id) }}"> {{ $row->title }}</a>
              </td>
              <td>
                {{ $row->admins_name }}
              </td>
              <td>
                {{$row->updated_at}}
              </td>
              <td>
                {{$row->action}}
              </td>
            </tr>
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
@endsection