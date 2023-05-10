@extends('admin.layouts.app')
@section('title')
{{ $module_name }}
@endsection

@section('content')
<!-- Content Header (Page header) -->
<section class="content-header">
  <h1>
    {{ $module_name }}
    
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
      <div class="box-body">
        <!-- Custom Tabs -->
        <div class="nav-tabs-custom">
          <ul class="nav nav-tabs">
            <li class="active">
              <a href="#tab_1" data-toggle="tab">
                <h5>Thông tin chính <span class="text-danger">*</span></h5>
              </a>
            </li>
          </ul>
          <div class="tab-content">
            <div class="tab-pane active" id="tab_1">
              <div class="row">
                
                <div class="col-md-6">
                  <h4 class="modal-title" style="border-botto: 1px solid;"><b style="border-bottom: 1px solid;">Sau</b></h4>
                  <div class="details">
                    <div class="details__header">
                      <h1 class="details__headline"> <?= $detail->title ?></h1>
                      <img src="<?=$detail->image?>" style ="width:100%; vertical-align:middle" alt="">
                      <div class="details__meta">
                        <div class="meta">
                          <span class="time">
                          <i class="spr spr__clock"></i>
                          {{ date('H:i d/m/Y',strtotime($detail->created_at)) }}
                          </span>
                        </div>
                      </div>
                      <div class="details__summary"><?= $detail->brief ?></div>
                    </div>
                    <div class="details__content">
                    <?= $detail->content ?>
                    </div>
                  </div>
                </div>
				
                <div class="col-md-6">
                  <h4 class="modal-title" style="border-botto: 1px solid;"><b style="border-bottom: 1px solid;">Trước</b></h4>
                  <div class="details">
					@if($history_relative)
                    <div class="details__header">
					
                      <h1 class="details__headline"> <?= $history_relative->title ?></h1>
                      <img src="<?=$history_relative->image?>" style ="width:100%; vertical-align:middle" alt="">
                      <div class="details__meta">
                        <div class="meta">
                          <span class="time">
                          <i class="spr spr__clock"></i>
                          {{ date('H:i d/m/Y',strtotime($history_relative->created_at)) }}
                          </span>
                        </div>
                      </div>
                      <div class="details__summary"><?= $history_relative->brief ?></div>
                    </div>
                    <div class="details__content">
                    <?= $history_relative->content ?>
                    </div>
					@endif
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
      </div>
  </div>
</section>
@endsection