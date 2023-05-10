@extends('admin.layouts.app')

@section('title')
  {{ $module_name }}
@endsection

@section('content-header')
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
@endsection

@section('content')

  <!-- Main content -->
  <section class="content">

    <div class="box">
      <div class="box-header">
        <h3 class="box-title">@lang('Popups list')</h3>
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
            @lang('not_found')
          </div>
        @else
          <table class="table table-hover table-bordered">
            <thead>
              <tr>
                <th>@lang('Title')</th>
                <th>@lang('Banner')</th>
                <th>@lang('Start time')</th>
                <th>@lang('End time')</th>
                <th>@lang('Time duration')</th>
                <th>@lang('Created at')</th>
                <th>@lang('Status')</th>
                <th>@lang('Action')</th>
              </tr>
            </thead>
            <tbody>

              @foreach ($rows as $row)
                <form action="{{ route(Request::segment(2) . '.destroy', $row->id) }}" method="POST"
                  onsubmit="return confirm('@lang('confirm_action')')">
                  <tr class="valign-middle">
                    <td>
                      {{ $row->title }}
                    </td>
                    <td>
                      <img src="{{ $row->image ?? url('themes/admin/img/no_image.jpg') }}" width="150px">
                    </td>
                    <td>
                      {{ isset($row->start_time) ? \Carbon\Carbon::parse($row->start_time)->format('d/m/Y') : '' }}
                    </td>
                    <td>
                      {{ isset($row->end_time) ? \Carbon\Carbon::parse($row->end_time)->format('d/m/Y') : '' }}
                    </td>
                    <td>
                      {{ $row->duration }}
                    </td>
                    <td>
                      {{ $row->created_at }}
                    </td>
                    <td>
                      @lang($row->status)
                    </td>
                    <td>
                      <a class="btn btn-sm btn-warning" data-toggle="tooltip" title="@lang('Update')"
                        data-original-title="@lang('Update')"
                        href="{{ route(Request::segment(2) . '.edit', $row->id) }}">
                        <i class="fa fa-pencil-square-o"></i>
                      </a>
                      @csrf
                      @method('DELETE')
                      <button class="btn btn-sm btn-danger" type="submit" data-toggle="tooltip"
                        title="@lang('Delete')" data-original-title="@lang('Delete')">
                        <i class="fa fa-trash"></i>
                      </button>
                    </td>
                  </tr>
                </form>
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
@endsection
