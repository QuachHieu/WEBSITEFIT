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
          class="fa fa-plus"></i>
        @lang('add_new')</a>
    </h1>
  </section>

  <!-- Main content -->
  <section class="content">
    @if (session('successMessage'))
      <div class="alert alert-success alert-dismissible">
        <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
        {{ session('successMessage') }}
      </div>
    @endif

    @if (session('errorMessage'))
      <div class="alert alert-warning alert-dismissible">
        <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
        {{ session('errorMessage') }}
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
        <h3 class="box-title">@lang('update_form')</h3>
      </div>
      <!-- /.box-header -->
      <!-- form start -->
      <form role="form" action="{{ route(Request::segment(2) . '.update', $admin->id) }}" method="POST">
        @csrf
        @method('PUT')
        <div class="box-body">
          <div class="col-md-6">
            <div class="form-group">
              <label>@lang('email') <small class="text-red">*</small></label>
              <input type="email" class="form-control" name="email" value="{{ $admin->email }}" required>
            </div>
            <div class="form-group">
              <label>@lang('name') <small class="text-red">*</small></label>
              <input type="text" class="form-control" name="name" value="{{ $admin->name }}" required>
            </div>

            <div class="form-group">
              <label>@lang('password') <small class="text-muted"><i>(Để trống nếu không muốn đổi mật khẩu
                    mới)</i></small></label>
              <input type="password" class="form-control" name="password_new" placeholder="Mật khẩu ít nhất 8 ký tự"
                value="" autocomplete="new-password">
            </div>

            <div class="form-group">
              <label>@lang('status')</label>
              <div class="form-control">
                <label>
                  <input type="radio" name="status" value="active" {{ $admin->status == 'active' ? 'checked' : '' }}>
                  <small>@lang('active')</small>
                </label>
                <label>
                  <input type="radio" name="status" value="deactive" class="ml-15"
                    {{ $admin->status == 'deactive' ? 'checked' : '' }}>
                  <small>@lang('deactive')</small>
                </label>
              </div>
            </div>

            <div class="form-group">
			  <label>Ảnh đại diện tác giả</label>
			  <div class="input-group">
				<span class="input-group-btn">
				  <a data-input="avatar" onclick="openPopupImg('avatar')" data-preview="image-holder" class="btn btn-primary lfm"
					data-type="cms-image">
					<i class="fa fa-picture-o"></i> @lang('choose')
				  </a>
				</span>
				<input id="avatar" class="form-control" type="text" name="avatar" value="{{ $admin->avatar }}"
				  placeholder="@lang('image_link')...">
			  </div>
			  <div id="image-holder" style="margin-top:15px;max-height:100px;">
				@if ($admin->avatar != '')
				<img id="view_avatar" style="height: 5rem;" src="{{ $admin->avatar }}">
				@else
				<img id="view_avatar" style="height: 5rem;" src="">
				@endif
			  </div>
			</div>

          </div>
          <div class="col-md-6">
            
          <!-- <div class="form-group">
              <label>@lang('Department') <small class="text-red">*</small></label>
              <select name="json_information[department]" id="department" class="form-control select2" required>
                <option value="">@lang('please_chosen')</option>
                @foreach ($Departments as $dp)
                  <option value="{{ $dp->id }}" {{ $admin->json_information->department == $dp->id ? 'selected' : '' }}>
                    {{ $dp->title }}
                  </option>
                @endforeach
              </select>
           </div>

           <div class="form-group">
              <label>@lang('Degree') <small class="text-red">*</small></label>
              <select name="json_information[degree]" id="degree" class="form-control select2" required>
                <option value="">@lang('please_chosen')</option>
                @foreach ($Degree as $dg)
                  <option value="{{ $dg->id }}" {{ $admin->json_information->degree == $dg->id ? 'selected' : '' }}>
                    {{ $dg->title }}
                  </option>
                @endforeach
              </select>
           </div> 

           <div class="form-group">
              <label>@lang('Duty') <small class="text-red">*</small></label>
              <select name="json_information[duty]" id="duty" class="form-control select2" required>
                <option value="">@lang('please_chosen')</option>
                @foreach ($Duty as $dt)
                  <option value="{{ $dt->id }}" {{ $admin->json_information->duty == $dt->id ? 'selected' : '' }}>
                    {{ $dt->title }}
                  </option>
                @endforeach
              </select>
           </div> -->

            <div class="form-group">
              <label>@lang('role') <small class="text-red">*</small></label>
              <select name="role" id="role" class="form-control select2" required>
                <option value="">@lang('please_chosen')</option>
                @foreach ($roles as $item)
                  <option value="{{ $item->id }}" {{ $admin->role == $item->id ? 'selected' : '' }}>
                    {{ $item->name }}
                  </option>
                @endforeach
              </select>
            </div>

           
			
          </div>

        </div>
        <!-- /.box-body -->

        <div class="box-footer">
          <a class="btn btn-sm btn-success" href="{{ route(Request::segment(2) . '.index') }}">
            <i class="fa fa-bars"></i> @lang('list')
          </a>
          <button type="submit" class="btn btn-primary btn-sm pull-right"><i class="fa fa-floppy-o"></i>
            @lang('save')</button>
        </div>
      </form>
    </div>
  </section>
@endsection
