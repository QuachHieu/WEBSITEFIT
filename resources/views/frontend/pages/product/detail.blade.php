@extends('frontend.layouts.default')

@php
$title = $detail->json_params->title->{$locale} ?? $detail->title;
$brief = $detail->json_params->brief->{$locale} ?? null;
$content = $detail->json_params->content->{$locale} ?? null;
$image = $detail->image != '' ? $detail->image : null;
$image_thumb = $detail->image_thumb != '' ? $detail->image_thumb : null;
$date = date('H:i d/m/Y', strtotime($detail->created_at));

$str_alias = '.html?id=';
// For taxonomy
$taxonomy_json_params = json_decode($detail->taxonomy_json_params);
$taxonomy_title = $taxonomy_json_params->title->{$locale} ?? $detail->taxonomy_title;
$image_background = $taxonomy_json_params->image_background ?? ($web_information->image->background_breadcrumbs ?? null);
$taxonomy_alias = Str::slug($taxonomy_title);
$taxonomy_url_link = route('frontend.cms.product_category', ['alias' => $taxonomy_alias]) . $str_alias . $detail->taxonomy_id;

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

            <div class="postcontent col-lg-9">

              <div class="single-product">
                <div class="product">
                  <div class="row gutter-40">

                    <div class="col-md-6">
                      <div class="product-image">
                        <div class="fslider" data-pagi="false" data-arrows="false" data-thumbs="true">
                          <div class="flexslider">
                            <div class="slider-wrap" data-lightbox="gallery">
                              <div class="slide" data-thumb="{{ $image_thumb }}">
                                <a href="{{ $image }}" title="{{ $title }}" data-lightbox="gallery-item">
                                  <img src="{{ $image }}" alt="{{ $title }}">
                                </a>
                              </div>
                            </div>
                          </div>
                        </div>
                      </div>
                    </div>

                    <div class="col-md-6 product-desc">

                      <div class="d-flex align-items-center justify-content-between">
                        <div class="product-price ">
                          <ins class="text-danger">
                            @lang('Price'):
                            {{ isset($detail->json_params->price) && $detail->json_params->price > 0 ? number_format($detail->json_params->price) . ' ' . $detail->json_params->price_currency : __('Contact') }}
                          </ins>
                        </div>
                      </div>

                      <div class="line"></div>

                      <form class="cart mb-0 d-flex justify-content-between align-items-center">
                        <div class="quantity clearfix">
                          <input type="button" value="-" class="minus">
                          <input type="number" step="1" min="1" name="quantity" value="1"
                            id="quantity" title="Qty" class="qty" />
                          <input type="button" value="+" class="plus">
                        </div>
                        <button type="button" class="add-to-cart button m-0 add-to-cart" data-id="{{ $detail->id }}">
                          @lang('Add to cart')
                        </button>
                      </form>

                      <div class="line"></div>

                      <p>
                        {{ $brief }}
                      </p>

                    </div>

                    <div class="w-100"></div>

                    <div class="col-12 mt-3">
                      <div class="tabs clearfix mb-0" id="tab-1">
                        <div class="tab-container">
                          <div class="tab-content clearfix" id="tabs-1">
                            {!! $content !!}
                          </div>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
              </div>

              <div class="line"></div>
              @if (count($posts) > 0)
                <div class="w-100">

                  <h4>@lang('Related Products')</h4>

                  <div class="owl-carousel product-carousel carousel-widget" data-margin="30" data-pagi="false"
                    data-autoplay="5000" data-items-xs="1" data-items-md="2" data-items-lg="3" data-items-xl="3">

                    @foreach ($posts as $item)
                      @php
                        $title = $item->json_params->title->{$locale} ?? $item->title;
                        $brief = $item->json_params->brief->{$locale} ?? $item->brief;
                        $image = $item->image_thumb ?? ($item->image ?? null);
                        $date = date('H:i d/m/Y', strtotime($item->created_at));
                        // Viet ham xu ly lay alias bai viet
                        $alias_category = Str::slug($item->taxonomy_title);
                        $alias_detail = Str::slug($title);
                        $url_link = route('frontend.cms.product', ['alias_category' => $alias_category, 'alias_detail' => $alias_detail]) . $str_alias . $item->id;
                        $taxonomy_url_link = route('frontend.cms.product_category', ['alias' => $alias_category]) . $str_alias . $item->taxonomy_id;
                      @endphp
                      <div class="oc-item">
                        <div class="product">
                          <div class="product-image">
                            <a href="{{ $url_link }}">
                              <img src="{{ $image }}" alt="{{ $title }}">
                            </a>
                            <div class="bg-overlay">
                              <div class="bg-overlay-content align-items-end justify-content-between"
                                data-hover-animate="fadeIn" data-hover-speed="400">
                                <a href="javascript:void(0);" class="btn btn-dark me-2 add-to-cart"
                                  data-id="{{ $item->id }}" data-quantity="1" title="@lang('Add to cart')">
                                  <i class="icon-shopping-cart"></i>
                                </a>
                                <a href="{{ $url_link }}" class="btn btn-dark" title="@lang('View detail')">
                                  <i class="icon-line-expand"></i>
                                </a>
                              </div>
                              <div class="bg-overlay-bg bg-transparent"></div>
                            </div>
                          </div>
                          <div class="product-desc">
                            <div class="product-title">
                              <h3><a href="{{ $url_link }}">{{ Str::limit($title, 30) }}</a></h3>
                            </div>
                            <div class="product-price text-danger">
                              @lang('Price'):
                              {{ isset($item->json_params->price) && $item->json_params->price > 0 ? number_format($item->json_params->price) . ' ' . $item->json_params->price_currency : __('Contact') }}
                            </div>
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

            <div class="sidebar col-lg-3">
              @include('frontend.components.sidebar.product')
            </div>

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
