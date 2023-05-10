@extends('frontend.layouts.default')

@php
$page_title = $taxonomy->title ?? ($page->title ?? ($page->name ?? ''));
$image_background = $taxonomy->json_params->image_background ?? ($web_information->image->background_breadcrumbs ?? '');
@endphp

@section('content')
  {{-- Print all content by [module - route - page] without blocks content at here --}}

  <section id="page-title" class="page-title-pattern" style="background-image: url({{ $image_background }});">
    <div class="container clearfix">
      <h1>{{ $page_title }}</h1>
      <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="{{ route('frontend.home') }}">@lang('Home')</a></li>
        <li class="breadcrumb-item active" aria-current="page">{{ $page->name ?? '' }}</li>
      </ol>
    </div>
  </section>

  <section id="content">
    <div class="content-wrap">
      <div class="container clearfix">
        <div class="row align-items-stretch col-mb-30 mb-0">
          <div class="col-lg-6">
            <div class="fancy-title title-border">
              <h3>
                {!! $web_information->information->site_name ?? '' !!}
              </h3>
            </div>
            <div class="form-result"></div>
            <form class="mb-0" method="post" action="{{ route('frontend.contact.store') }}" id="form-booking">
              @csrf
              <div class="form-process">
                <div class="css3-spinner">
                  <div class="css3-spinner-scaler"></div>
                </div>
              </div>
              <div class="row">
                <div class="col-md-6 form-group">
                  <label for="name">
                    @lang('Fullname')
                    <span class="text-danger">*</span>
                  </label>
                  <input type="text" id="name" name="name" class="form-control form-control-lg" value="" required
                    autocomplete="off">
                </div>
                <div class="col-md-6 form-group">
                  <label for="phone">
                    @lang('Phone')
                    <span class="text-danger">*</span>
                  </label>
                  <input type="text" id="phone" name="phone" class="form-control form-control-lg" value="" required
                    autocomplete="off">
                </div>
                <div class="w-100"></div>
                <div class="col-md-6 form-group">
                  <label for="email">
                    @lang('Email')
                  </label>
                  <input type="text" id="email" name="email" class="form-control form-control-lg" value=""
                    autocomplete="off">
                </div>
                <div class="col-md-6 form-group">
                  <label for="department_id">
                    @lang('Department')
                    <span class="text-danger">*</span>
                  </label>
                  <select id="department_id" name="json_params[department_id]" class="form-control select2" required
                    autocomplete="off" style="width: 100%;">
                    <option value="">@lang('Please select')</option>
                    @isset($departments)
                      @foreach ($departments as $item)
                        @php
                          $title = $item->json_params->title->{$locale} ?? $item->title;
                          $brief = $item->json_params->brief->{$locale} ?? $item->brief;
                        @endphp
                        <option value="{{ $item->id }}">{{ $title }}</option>
                      @endforeach
                    @endisset
                  </select>
                </div>
                <div class="w-100"></div>
                <div class="col-12 form-group">
                  <label for="content">
                    @lang('Content note')
                  </label>
                  <textarea class="form-control form-control-lg" id="content" name="content" rows="5" cols="30" autocomplete="off"></textarea>
                </div>
                <div class="col-12 form-group">
                  <button type="submit" class="button button-3d m-0">
                    @lang('Send message')
                  </button>
                </div>
              </div>
              <input type="hidden" name="is_type" value="contact">
            </form>
          </div>

          <div class="col-lg-6 min-vh-50">
            @isset($web_information->source_code->map)
              {!! $web_information->source_code->map !!}
            @endisset
          </div>
        </div>

        <div class="row col-mb-50">
          <div class="col-sm-6 col-lg-3">
            <div class="feature-box fbox-center fbox-bg fbox-plain">
              <div class="fbox-icon">
                <i class="icon-map-marker2"></i>
              </div>
              <div class="fbox-content">
                <h3>
                  @lang('Address')
                  <span class="subtitle">
                    {!! $web_information->information->address ?? '' !!}
                  </span>
                </h3>
              </div>
            </div>
          </div>
          <div class="col-sm-6 col-lg-3">
            <div class="feature-box fbox-center fbox-bg fbox-plain">
              <div class="fbox-icon">
                <i class="icon-phone3"></i>
              </div>
              <div class="fbox-content">
                <h3>
                  @lang('Phone')
                  <span class="subtitle">
                    <br>
                    {!! $web_information->information->phone ?? '' !!}
                  </span>
                </h3>
              </div>
            </div>
          </div>

          <div class="col-sm-6 col-lg-3">
            <div class="feature-box fbox-center fbox-bg fbox-plain">
              <div class="fbox-icon">
                <i class="icon-line-phone-call"></i>
              </div>
              <div class="fbox-content">
                <h3>
                  @lang('Hotline')
                  <span class="subtitle">
                    <br>
                    {!! $web_information->information->hotline ?? '' !!}
                  </span>
                </h3>
              </div>
            </div>
          </div>

          <div class="col-sm-6 col-lg-3">
            <div class="feature-box fbox-center fbox-bg fbox-plain">
              <div class="fbox-icon">
                <i class="icon-email3"></i>
              </div>
              <div class="fbox-content">
                <h3>
                  @lang('Email')
                  <span class="subtitle">
                    <br>
                    {!! $web_information->information->email ?? '' !!}
                  </span>
                </h3>
              </div>
            </div>
          </div>
        </div>

      </div>
    </div>
  </section>

  {{-- End content --}}
@endsection
