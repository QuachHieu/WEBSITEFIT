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
        <i class="fa fa-plus"></i> @lang('Add')
      </a>
    </h1>
  </section>
@endsection

@section('content')

  <!-- Main content -->
  <section class="content">
    <div class="box">
      <div class="box-header">
        <h3 class="box-title">@lang('Taxonomy list')</h3>
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
                <th>@lang('Taxonomy')</th>
                <th>@lang('Link')</th>
                <th>@lang('Is featured')</th>
                <th>@lang('Vị trí hiển thị')</th>
                <th>@lang('Order')</th>
                <th>@lang('Updated at')</th>
                <th>@lang('Status')</th>
                <th>@lang('Action')</th>
              </tr>
            </thead>
            <tbody>
              @if ($rows)
                @foreach ($rows as $row)
                  @if ($row->parent_id == 0 || $row->parent_id == null)
                    <form action="{{ route(Request::segment(2) . '.destroy', $row->id) }}" method="POST"
                      onsubmit="return confirm('@lang('confirm_action')')">
                      <tr class="valign-middle">
                        <td>
                          <strong style="font-size: 14px;">{{ $row->title }}</strong>
                        </td>
                        <td>
                          {{ __(App\Consts::TAXONOMY[$row->taxonomy] ?? '') }}
                        </td>
                        @php
                          $url_mapping = url('');
                          $url_mapping .= '/' . $row->taxonomy . '/';
                          $url_mapping .= $row->url_part;
                          $url_mapping .= '.html';
                        @endphp
                        <td>
                          <a target="_new" href="{{ $url_mapping }}" data-toggle="tooltip" title="@lang('Link')"
                            data-original-title="@lang('Link')">
                            <span class="btn btn-flat btn-sm btn-info">
                              <i class="fa fa-external-link"></i>
                            </span>
                          </a>
                        </td>
                        <td>
                          @lang(App\Consts::TITLE_BOOLEAN[$row->is_featured])
                        </td>
                        <td>
                          @php  
                            if($row->vitri == 'trangchu_trai'){
                              echo 'Trang chủ bên trái';
                            } elseif($row->vitri == 'trangchu_phai') {
                              echo 'Trang chủ bên phải';
                            } else{
                              echo 'None';
                            }
                          @endphp
                        </td>
                        <td>
                          {{ $row->iorder }}
                        </td>
                        <td>
                          {{ $row->updated_at }}
                        </td>
                        <td>
                          @lang($row->status)
                        </td>
                        <td>
                          <a class="btn btn-sm btn-warning" data-toggle="tooltip" title="@lang('update')"
                            data-original-title="@lang('update')"
                            href="{{ route(Request::segment(2) . '.edit', $row->id) }}">
                            <i class="fa fa-pencil-square-o"></i>
                          </a>
                          @csrf
                          @method('DELETE')
                          <button class="btn btn-sm btn-danger" type="submit" data-toggle="tooltip"
                            title="@lang('delete')" data-original-title="@lang('delete')">
                            <i class="fa fa-trash"></i>
                          </button>
                        </td>
                      </tr>
                    </form>

                    @foreach ($rows as $sub)
                      @if ($sub->parent_id == $row->id)
                        <form action="{{ route(Request::segment(2) . '.destroy', $sub->id) }}" method="POST"
                          onsubmit="return confirm('@lang('confirm_action')')">
                          <tr class="valign-middle bg-gray-light">

                            <td>
                              - - - - {{ $sub->title }}
                            </td>
                            <td>
                              {{ __(App\Consts::TAXONOMY[$sub->taxonomy]) }}
                            </td>
                            @php
                              $url_mapping = url('');
                              // $url_mapping .= '/' . App\Consts::ROUTE_PREFIX_TAXONOMY[$sub->taxonomy] . '/';
                              $url_mapping .= Str::slug($sub->title);
                              $url_mapping .= '.html?id=' . $sub->id;
                            @endphp
                            <td>
                              <a target="_new" href="{{ $url_mapping }}" data-toggle="tooltip"
                                title="@lang('Link')" data-original-title="@lang('Link')">
                                <span class="btn btn-flat btn-sm btn-info">
                                  <i class="fa fa-external-link"></i>
                                </span>
                              </a>
                            </td>
                            {{-- <td>
                              {{ $url_mapping }}
                            </td> --}}
                            <td>
                              @php  
                                if($row->vitri == 'trangchu_trai'){
                                  echo 'Trang chủ bên trái';
                                } elseif($row->vitri == 'trangchu_phai') {
                                  echo 'Trang chủ bên phải';
                                } else{
                                  echo 'None';
                                }
                              @endphp
                            </td>
                            <td>
                              @lang(App\Consts::TITLE_BOOLEAN[$sub->is_featured])
                            </td>
                            <td>
                              {{ $sub->iorder != '' ? '- - - - ' . $sub->iorder : '' }}
                            </td>
                            <td>
                              {{ $sub->updated_at }}
                            </td>
                            <td>
                              @lang($sub->status)
                            </td>
                            <td>
                              <a class="btn btn-sm btn-warning" data-toggle="tooltip" title="@lang('update')"
                                data-original-title="@lang('update')"
                                href="{{ route(Request::segment(2) . '.edit', $sub->id) }}">
                                <i class="fa fa-pencil-square-o"></i>
                              </a>
                              @csrf
                              @method('DELETE')
                              <button class="btn btn-sm btn-danger" type="submit" data-toggle="tooltip"
                                title="@lang('delete')" data-original-title="@lang('delete')">
                                <i class="fa fa-trash"></i>
                              </button>
                            </td>
                          </tr>
                        </form>

                        @foreach ($rows as $sub_child)
                          @if ($sub_child->parent_id == $sub->id)
                            <form action="{{ route(Request::segment(2) . '.destroy', $sub_child->id) }}" method="POST"
                              onsubmit="return confirm('@lang('confirm_action')')">
                              <tr class="valign-middle bg-gray-light">
                                <td>
                                  - - - - - - {{ $sub_child->title }}
                                </td>
                                <td>
                                  {{ __(App\Consts::TAXONOMY[$sub_child->taxonomy]) }}
                                </td>
                                @php
                                  $url_mapping = url('');
                                  // $url_mapping .= '/' . App\Consts::ROUTE_PREFIX_TAXONOMY[$sub_child->taxonomy] . '/';
                                  $url_mapping .= Str::slug($sub_child->title);
                                  $url_mapping .= '.html?id=' . $sub_child->id;
                                @endphp
                                <td>
                                  <a target="_new" href="{{ $url_mapping }}" data-toggle="tooltip"
                                    title="@lang('Link')" data-original-title="@lang('Link')">
                                    <span class="btn btn-flat btn-sm btn-info">
                                      <i class="fa fa-external-link"></i>
                                    </span>
                                  </a>
                                </td>
                               {{--  <td>
                                  {{ $url_mapping }}
                                </td> --}}
                                <td>
                                  @php  
                                    if($row->vitri == 'trangchu_trai'){
                                      echo 'Trang chủ bên trái';
                                    } elseif($row->vitri == 'trangchu_phai') {
                                      echo 'Trang chủ bên phải';
                                    } else{
                                      echo 'None';
                                    }
                                  @endphp
                                </td>
                                <td>
                                  @lang(App\Consts::TITLE_BOOLEAN[$sub_child->is_featured])
                                </td>
                                <td>
                                  {{ $sub_child->iorder != '' ? '- - - - - - ' . $sub_child->iorder : '' }}
                                </td>
                                <td>
                                  {{ $sub_child->updated_at }}
                                </td>
                                <td>
                                  @lang($sub_child->status)
                                </td>
                                <td>
                                  <a class="btn btn-sm btn-warning" data-toggle="tooltip" title="@lang('update')"
                                    data-original-title="@lang('update')"
                                    href="{{ route(Request::segment(2) . '.edit', $sub_child->id) }}">
                                    <i class="fa fa-pencil-square-o"></i>
                                  </a>
                                  @csrf
                                  @method('DELETE')
                                  <button class="btn btn-sm btn-danger" type="submit" data-toggle="tooltip"
                                    title="@lang('delete')" data-original-title="@lang('delete')">
                                    <i class="fa fa-trash"></i>
                                  </button>
                                </td>
                              </tr>
                            </form>
                            @foreach ($rows as $sub_three)
                              @if ($sub_three->parent_id == $sub_child->id)
                                <form action="{{ route(Request::segment(2) . '.destroy', $sub_three->id) }}" method="POST"
                                  onsubmit="return confirm('@lang('confirm_action')')">
                                  <tr class="valign-middle bg-gray-light">
                                    <td>
                                      - - - - - - - -{{ $sub_three->title }}
                                    </td>
                                    <td>
                                      {{ __(App\Consts::TAXONOMY[$sub_three->taxonomy]) }}
                                    </td>
                                    @php
                                      $url_mapping = url('');
                                      // $url_mapping .= '/' . App\Consts::ROUTE_PREFIX_TAXONOMY[$sub_three->taxonomy] . '/';
                                      $url_mapping .= Str::slug($sub_three->title);
                                      $url_mapping .= '.html?id=' . $sub_three->id;
                                    @endphp
                                    <td>
                                      <a target="_new" href="{{ $url_mapping }}" data-toggle="tooltip"
                                        title="@lang('Link')" data-original-title="@lang('Link')">
                                        <span class="btn btn-flat btn-sm btn-info">
                                          <i class="fa fa-external-link"></i>
                                        </span>
                                      </a>
                                    </td>
                                    {{-- <td>
                                      {{ $url_mapping }}
                                    </td> --}}
                                    <td>
                                      @php  
                                        if($row->vitri == 'trangchu_trai'){
                                          echo 'Trang chủ bên trái';
                                        } elseif($row->vitri == 'trangchu_phai') {
                                          echo 'Trang chủ bên phải';
                                        } else{
                                          echo 'None';
                                        }
                                      @endphp
                                    </td>
                                    <td>
                                      @lang(App\Consts::TITLE_BOOLEAN[$sub_three->is_featured])
                                    </td>
                                    <td>
                                      {{ $sub_three->iorder != '' ? '- - - - - - - - ' . $sub_three->iorder : '' }}
                                    </td>
                                    <td>
                                      {{ $sub_three->updated_at }}
                                    </td>
                                    <td>
                                      @lang($sub_three->status)
                                    </td>
                                    <td>
                                      <a class="btn btn-sm btn-warning" data-toggle="tooltip" title="@lang('update')"
                                        data-original-title="@lang('update')"
                                        href="{{ route(Request::segment(2) . '.edit', $sub_three->id) }}">
                                        <i class="fa fa-pencil-square-o"></i>
                                      </a>
                                      @csrf
                                      @method('DELETE')
                                      <button class="btn btn-sm btn-danger" type="submit" data-toggle="tooltip"
                                        title="@lang('delete')" data-original-title="@lang('delete')">
                                        <i class="fa fa-trash"></i>
                                      </button>
                                    </td>
                                  </tr>
                                </form>
                                @foreach ($rows as $sub_four)
                                @if ($sub_four->parent_id == $sub_three->id)
                                  <form action="{{ route(Request::segment(2) . '.destroy', $sub_four->id) }}" method="POST"
                                    onsubmit="return confirm('@lang('confirm_action')')">
                                    <tr class="valign-middle bg-gray-light">
                                      <td>
                                        - - - - - - - - - -{{ $sub_four->title }}
                                      </td>
                                      <td>
                                        {{ __(App\Consts::TAXONOMY[$sub_four->taxonomy]) }}
                                      </td>
                                      @php
                                        $url_mapping = url('');
                                        // $url_mapping .= '/' . App\Consts::ROUTE_PREFIX_TAXONOMY[$sub_three->taxonomy] . '/';
                                        $url_mapping .= Str::slug($sub_four->title);
                                        $url_mapping .= '.html?id=' . $sub_four->id;
                                      @endphp
                                      <td>
                                        <a target="_new" href="{{ $url_mapping }}" data-toggle="tooltip"
                                          title="@lang('Link')" data-original-title="@lang('Link')">
                                          <span class="btn btn-flat btn-sm btn-info">
                                            <i class="fa fa-external-link"></i>
                                          </span>
                                        </a>
                                      </td>
                                      {{-- <td>
                                        {{ $url_mapping }}
                                      </td> --}}
                                      <td>
                                        @php  
                                          if($row->vitri == 'trangchu_trai'){
                                            echo 'Trang chủ bên trái';
                                          } elseif($row->vitri == 'trangchu_phai') {
                                            echo 'Trang chủ bên phải';
                                          } else{
                                            echo 'None';
                                          }
                                        @endphp
                                      </td>
                                      <td>
                                        @lang(App\Consts::TITLE_BOOLEAN[$sub_four->is_featured])
                                      </td>
                                      <td>
                                        {{ $sub_four->iorder != '' ? '- - - - - - - - - -' . $sub_four->iorder : '' }}
                                      </td>
                                      <td>
                                        {{ $sub_four->updated_at }}
                                      </td>
                                      <td>
                                        @lang($sub_four->status)
                                      </td>
                                      <td>
                                        <a class="btn btn-sm btn-warning" data-toggle="tooltip" title="@lang('update')"
                                          data-original-title="@lang('update')"
                                          href="{{ route(Request::segment(2) . '.edit', $sub_four->id) }}">
                                          <i class="fa fa-pencil-square-o"></i>
                                        </a>
                                        @csrf
                                        @method('DELETE')
                                        <button class="btn btn-sm btn-danger" type="submit" data-toggle="tooltip"
                                          title="@lang('delete')" data-original-title="@lang('delete')">
                                          <i class="fa fa-trash"></i>
                                        </button>
                                      </td>
                                    </tr>
                                  </form>
                                @endif
                              @endforeach
                              @endif
                            @endforeach
                          @endif
                        @endforeach
                      @endif
                    @endforeach
                  @endif
                @endforeach
              @endif
            </tbody>
          </table>
        @endif
      </div>
    </div>
  </section>
@endsection
