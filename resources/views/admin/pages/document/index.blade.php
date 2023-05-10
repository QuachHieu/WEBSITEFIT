@extends('admin.layouts.app')

@section('title')
  {{ $module_name }}
@endsection

@section('content-header')
  <!-- Content Header (Page header) -->
  <section class="content-header">
    <h1>
      {{ $module_name }}
      <a class="btn btn-sm btn-warning pull-right" href="{{ route(Request::segment(2) . '.create') }}"><i
          class="fa fa-plus"></i> @lang('Add')</a>
    </h1>
  </section>
@endsection

@section('content')

  <!-- Main content -->
  <section class="content">
    {{-- Search form --}}
    <div class="box box-default">
      <form action="{{ route(Request::segment(2) . '.index') }}" method="GET">
        <input type="hidden" name="task" value="{{ $_REQUEST['task'] }}" />
        <div class="box-body">
          <div class="row">
            <div class="col-md-3">
              <div class="form-group">
                <label>@lang('Department') </label>
                <select name="department_id" class="form-control select2">
                  <option value="">-Chọn phòng ban-</option>
                  @foreach($departments as $department)
                  <option value="{{ $department->id}}" {{ (isset($params['department_id']) and $params['department_id'] == $department->id) ? 'selected' : '' }}>{{$department->title}}</option>
                  @endforeach
                </select>
              </div>
            </div>
            <div class="col-md-3">
              <div class="form-group">
                <label>@lang('Admin') </label>
                <select name="admin_receive_id" class="form-control select2">
                  <option value="">{{ $array_lable[0] }}</option>
                  @foreach($admins as $admin)
                  <option value="{{ $admin->id}}" {{ (isset($params['admin_receive_id']) and $params['admin_receive_id'] == $admin->id) ? 'selected' : '' }}>{{$admin->name}}</option>
                  @endforeach
                </select>
              </div>
            </div>
            <div class="col-md-3">
              <div class="form-group">
                <label>@lang('Keyword') </label>
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
        @php 
        $pr = '';
        $pr .= isset($params['keyword']) ? '&keyword='.$params['keyword'] : '';
        $pr .= isset($params['admin_created_id']) ? '&admin_created_id='.$params['admin_created_id'] : '';
        $pr .= isset($params['admin_receive_id']) ? '&admin_receive_id='.$params['admin_receive_id'] : '';
        $pr .= isset($params['department_id']) ? '&department_id='.$params['department_id'] : '';
        @endphp
        <ul class="nav nav-tabs">
          <li class="<?php if($_REQUEST['task'] == 'send') echo 'active'; ?>">
            <a href="{{ route(Request::segment(2) . '.index') }}?task=send{{ $pr }}" >
              <h5>Thư đã gửi</h5>
            </a>
          </li>
          <li class="<?php if($_REQUEST['task'] == 'receive') echo 'active'; ?>">
            <a href="{{ route(Request::segment(2) . '.index') }}?task=receive{{ $pr }}" >
              <h5>Thư đã nhận</h5>
            </a>
          </li>
        </ul>
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
            @lang('not_found')
          </div>
        @else
        
          <table class="table table-hover table-bordered">
            <thead>
              <tr>
                <th>{{ $array_lable[1] }}</th>
                <th>Tiêu đề</th>
                <th>Nội dung</th>
                <th>Đã đọc</th>
                <th>Thời gian gửi</th>
                <th>@lang('Action')</th>
              </tr>
            </thead>
            <tbody>

              @foreach ($rows as $row)
                  <tr class="valign-middle">
                    <td>
                      {{ $_REQUEST['task'] == 'send' ? $row->user_receive : $row->user_send  }}
                    </td>
                    <td>
                      <a href="javascript:;" style="font-size: 14px;" data-toggle="modal" data-target="#showCNTT" onClick="showCNTT('{{ route(Request::segment(2) . '.show', $row->id) }}')" >{{ $row->title }}</a>
                    </td>
                    <td>
                      {!! App\Http\Services\ContentService::stringTruncate ( $row->content, 200) !!}
                    </td>
                    <td>
                      {{ $row->date_at!='' ? date('H:i d/m/Y',strtotime($row->updated_at)) : '' }}
                    </td>
                    <td>
                      {{ date('H:i d/m/Y',strtotime($row->created_at)) }}
                    </td>
                    <td>
                      <button class="btn btn-warning" data-toggle="modal" data-target="#showCNTT" onClick="showCNTT('{{ route(Request::segment(2) . '.show', $row->id) }}')" ><i class="fa fa-eye"></i> Chi tiết </button>
                    </td>
                  </tr>
              @endforeach
            </tbody>
          </table>
        @endif
      </div>

      <div class="box-footer clearfix">
        <div class="row">
          <div class="col-sm-5">
            Tìm thấy {{ $rows->total() }} kết quả
          </div>
          <div class="col-sm-7">
            {{ $rows->withQueryString()->links('admin.pagination.default') }}
          </div>
        </div>
      </div>

    </div>
  </section>
  @if (count($rows) > 0)
  <div class="modal fade" id="showCNTT" data-backdrop="false" role="dialog" aria-hidden="true" style="display: none;">
    <div class="modal-dialog modal-wide">
    
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">×</span></button>
          <h4 class="modal-title">Chi tiết tài liệu gửi</h4>
        </div>
        <div class="modal-body modal-scroll" id="xemTaiLieu">
          
        </div>
        <div class="modal-footer">
          <div class="form-group">
            <div class="col-md-12 text-center">
              <button type="button" class="btn btn-default " data-dismiss="modal">Đóng lại</button>
            </div>
          </div>
        </div>
      </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
  </div>
  <script>
    function showCNTT(url_detail) {
      $.ajax({
        url: url_detail,
        type: 'GET',
        data: {
          _token: '{{ csrf_token() }}',
          task: '{{ $_REQUEST["task"] }}'
        },
        context: document.body,
      }).done(function(data) {
        $('#xemTaiLieu').html(data);
      });
    }
    
  </script>
  @endif
@endsection


