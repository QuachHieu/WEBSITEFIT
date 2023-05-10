@extends('frontend.layouts.default')

@php
$title = $detail->json_params->title->{$locale} ?? $detail->title;
$brief = $detail->json_params->brief->{$locale} ?? null;
$content = $detail->json_params->content->{$locale} ?? null;
$image = $detail->image != '' ? $detail->image : null;
$image_thumb = $detail->image_thumb != '' ? $detail->image_thumb : null;
$date = date('H:i d/m/Y', strtotime($detail->created_at));

// For taxonomy
$taxonomy_json_params = json_decode($detail->taxonomy_json_params);
$taxonomy_title = $taxonomy_json_params->title->{$locale} ?? $detail->taxonomy_title;
$image_background = $taxonomy_json_params->image_background ?? ($web_information->image->background_breadcrumbs ?? null);
$taxonomy_alias = Str::slug($taxonomy_title);
$taxonomy_url_link = route('frontend.cms.service_category', ['alias' => $taxonomy_alias]) . '.html?id=' . $detail->taxonomy_id;

$seo_title = $detail->json_params->seo_title ?? $title;
$seo_keyword = $detail->json_params->seo_keyword ?? null;
$seo_description = $detail->json_params->seo_description ?? $brief;
$seo_image = $image ?? ($image_thumb ?? null);

@endphp

@push('style')
  <style>
    #content-detail h2 {
      font-size: 30px;
    }

    #content-detail h3 {
      font-size: 24px;
    }

    #content-detail h4 {
      font-size: 18px;
    }

    #content-detail h5,
    #content-detail h6 {
      font-size: 16px;
    }

    #content-detail p {
      margin-top: 0;
      margin-bottom: 0;
    }

    #content-detail h1,
    #content-detail h2,
    #content-detail h3,
    #content-detail h4,
    #content-detail h5,
    #content-detail h6 {
      margin-top: 0;
      margin-bottom: .5rem;
    }

    #content-detail p+h2,
    #content-detail p+.h2 {
      margin-top: 1rem;
    }

    #content-detail h2+p,
    #content-detail .h2+p {
      margin-top: 1rem;
    }

    #content-detail p+h3,
    #content-detail p+.h3 {
      margin-top: 0.5rem;
    }

    #content-detail h3+p,
    #content-detail .h3+p {
      margin-top: 0.5rem;
    }

    #content-detail ul,
    #content-detail ol {
      list-style: inherit;
      padding: 0 0 0 50px;

    }

    #content-detail ul li,
    #content-detail ol li {
      display: list-item;
    }

    .posts-sm .entry-image {
      width: 75px;
    }
  </style>
@endpush

@section('content')
  {{-- Print all content by [module - route - page] without blocks content at here --}}

  <section id="page-title" class="page-title-pattern" style="background-image: url({{ $image_background }});">
    <div class="container clearfix">
      <h1>{{ $title }}</h1>
      <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="{{ route('frontend.home') }}">@lang('Home')</a></li>
        <li class="breadcrumb-item"><a href="{{ $taxonomy_url_link }}">{{ $taxonomy_title }}</a></li>
        <li class="breadcrumb-item active" aria-current="page">{{ $title }}</li>
      </ol>
    </div>
  </section>

  <section id="content">
    <div class="content-wrap">
      <div class="container clearfix">
        @if ($detail)
          <div class="row gutter-30 ">

            <div class="postcontent col-lg-12">
              <div class="single-post mb-0">
                <div class="entry clearfix">
                  <div class="entry-content mt-0">
                    <div class="content-block">
                      <div class="news-detail">
                        <div class="clear"></div>

                        <h5 class="font-weight-medium text-dark text-justify">
                          {{ $brief }}
                        </h5>
                        <div class="entry-meta">
                          <ul class="list list-inline list-inline-dashed offset-top-4">
                            <li>
                              <strong class="text-danger">
                                @lang('Price'):
                                {{ isset($detail->json_params->price) && $detail->json_params->price > 0 ? number_format($detail->json_params->price) . ' ' . $detail->json_params->price_currency : __('Contact') }}
                              </strong>
                            </li>
                            <li>
                              <a class="text-primary" href="{{ $taxonomy_url_link }}">
                                {{ $taxonomy_title }}
                              </a>
                            </li>
                          </ul>
                        </div>
                        <div class="offset-top-20 text-justify detail" id="content-detail">
                          {!! $content !!}
                        </div>
                        <div class="clear"></div>
                        <div class="pt-5">
                          <div class="fancy-title title-border">
                            <h3 class="text-uppercase">
                              @lang('Booking this service')
                            </h3>
                          </div>
                          <div class="form-result"></div>
                          <form class="mb-0" method="post" action="{{ route('frontend.order.store.service') }}"
                            id="form-booking">
                            @csrf
                            <div class="form-process">
                              <div class="css3-spinner">
                                <div class="css3-spinner-scaler"></div>
                              </div>
                            </div>
                            <div class="row">
                              <div class="col-md-12 form-group">
                                <label for="name">
                                  @lang('Fullname')
                                  <span class="text-danger">*</span>
                                </label>
                                <input type="text" id="name" name="name" class="form-control form-control-lg" value=""
                                  required autocomplete="off">
                              </div>
                              <div class="col-md-6 form-group">
                                <label for="phone">
                                  @lang('Phone')
                                  <span class="text-danger">*</span>
                                </label>
                                <input type="text" id="phone" name="phone" class="form-control form-control-lg" value=""
                                  required autocomplete="off">
                              </div>
                              <div class="col-md-6 form-group">
                                <label for="email">
                                  @lang('Email')
                                </label>
                                <input type="text" id="email" name="email" class="form-control form-control-lg" value=""
                                  autocomplete="off">
                              </div>
                              <div class="w-100"></div>
                              <div class="col-12 form-group">
                                <label for="customer_note">
                                  @lang('Content note')
                                </label>
                                <textarea class="form-control form-control-lg" id="customer_note" name="customer_note" rows="5" cols="30" autocomplete="off"></textarea>
                              </div>
                              <div class="col-12 form-group">
                                <button type="submit" class="button button-3d m-0">
                                  @lang('Submit booking')
                                </button>
                              </div>
                            </div>
                            <input type="hidden" name="item_id" value="{{ $detail->id }}">
                          </form>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </div>

            @if (count($posts) > 0)
              <div class="col-12">
                <div class="offset-top-30">
                  <h3 class="text-uppercase">@lang('Other service')</h3>
                  <hr class="text-subline">
                </div>
                <div id="posts" class="post-grid row grid-container gutter-40 clearfix" data-layout="fitRows">
                  @foreach ($posts as $item)
                    @php
                      $title = $item->json_params->title->{$locale} ?? $item->title;
                      $brief = $item->json_params->brief->{$locale} ?? '';
                      $image = $item->image != '' ? $item->image : ($item->image_thumb != '' ? $item->image_thumb : null);
                      $date = date('H:i d/m/Y', strtotime($item->created_at));
                      // Viet ham xu ly lay alias bai viet
                      $alias_category = Str::slug($item->taxonomy_title);
                      $alias_detail = Str::slug($title);
                      $url_link = route('frontend.cms.service', ['alias_category' => $alias_category, 'alias_detail' => $alias_detail]) . '.html?id=' . $item->id;
                      $taxonomy_url_link = route('frontend.cms.service_category', ['alias' => $alias_category]) . '.html?id=' . $item->taxonomy_id;
                    @endphp
                    <div class="entry col-md-4 col-sm-6 col-12">
                      <div class="grid-inner">
                        <div class="entry-image">
                          <a href="{{ $url_link }}">
                            <img src="{{ $image }}" alt="">
                          </a>
                        </div>
                        <div class="entry-title">
                          <h2>
                            <a href="{{ $url_link }}">
                              {{ $title }}
                            </a>
                          </h2>
                        </div>
                        <div class="entry-meta">
                          <ul>
                            <li>
                              <strong class="text-danger">
                                @lang('Price'):
                                {{ isset($item->json_params->price) && $item->json_params->price > 0 ? number_format($item->json_params->price) . ' ' . $item->json_params->price_currency : __('Contact') }}
                              </strong>
                            </li>
                            <li>
                              <a href="{{ $taxonomy_url_link }}">
                                <i class="icon-line-folder"></i>
                                {{ $item->taxonomy_title }}
                              </a>
                            </li>
                          </ul>
                        </div>
                        <div class="entry-content">
                          <p>
                            {{ Str::limit($brief, 100) }}
                          </p>
                        </div>
                      </div>
                    </div>
                  @endforeach
                </div>
              </div>
            @endif
          </div>
        @else
          <div class="row">
            <div class="col-12">
              <p>@lang('not_found')</p>
            </div>
          </div>
        @endif
      </div>
    </div>
  </section>

  {{-- End content --}}
@endsection
