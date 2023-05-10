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
      <div class="container">
        @if (session('cart'))
          <table class="table cart mb-5">
            <thead>
              <tr>
                <th class="cart-product-remove">&nbsp;</th>
                <th class="cart-product-thumbnail">&nbsp;</th>
                <th class="cart-product-name">@lang('Product')</th>
                <th class="cart-product-price">@lang('Price') </th>
                <th class="cart-product-quantity">@lang('Quantity')</th>
                <th class="cart-product-subtotal">@lang('Total')</th>
              </tr>
            </thead>
            <tbody>
              @php $total = 0 @endphp
              @foreach (session('cart') as $id => $details)
                @php
                  $total += $details['price'] * $details['quantity'];
                  $alias_detail = Str::slug($details['title']);
                  $url_link = route('frontend.cms.product', ['alias_category' => 'chi-tiet', 'alias_detail' => $alias_detail]) . '.html?id=' . $id;
                @endphp
                <tr class="cart_item" data-id="{{ $id }}">
                  <td class="cart-product-remove">
                    <a href="javascript:void(0)" class="remove remove-from-cart" title="@lang('Remove this item')">
                      <i class="icon-trash2"></i>
                    </a>
                  </td>
                  <td class="cart-product-thumbnail">
                    <a href="{{ $url_link }}">
                      <img width="64" height="64" src="{{ $details['image_thumb'] ?? $details['image'] }}"
                        alt="{{ $details['title'] }}">
                    </a>
                  </td>
                  <td class="cart-product-name">
                    <a href="{{ $url_link }}">{{ $details['title'] }}</a>
                  </td>
                  <td class="cart-product-price">
                    <span class="amount">
                      {{ isset($details['price']) && $details['price'] > 0 ? number_format($details['price']) : __('Contact') }}
                    </span>
                  </td>
                  <td class="cart-product-quantity">
                    <div class="quantity">
                      <input type="button" value="-" class="minus">
                      <input type="text" name="quantity" value="{{ $details['quantity'] }}" autocomplete="off"
                        class="qty update-cart" />
                      <input type="button" value="+" class="plus">
                    </div>
                  </td>
                  <td class="cart-product-subtotal">
                    <span class="amount">{{ number_format($details['price'] * $details['quantity']) }}</span>
                  </td>
                </tr>
              @endforeach
              <tr class="cart_item">
                <td colspan="5">
                  <div class="row justify-content-between">
                    <div class="col-lg-12">
                      <a href="{{ url()->current() }}" class="button button-3d m-0">@lang('Update Cart')</a>
                    </div>
                  </div>
                </td>
                <td class="cart-product-subtotal">
                  <span class="amount text-danger">
                    <strong>
                      {{ number_format($total) }}
                    </strong>
                  </span>
                </td>
              </tr>
            </tbody>
          </table>
          <div class="row col-mb-30">
            <div class="col-lg-12">
              <h4 class="text-uppercase">@lang('Submit Order Cart')</h4>
              <form class="row" method="POST" action="{{ route('frontend.order.store.product') }}">
                @csrf
                <div class="col-md-4 form-group">
                  <input type="text" class="sm-form-control" placeholder="@lang('Fullname') *" id="name"
                    name="name" autocomplete="off" required />
                </div>
                <div class="col-md-4 form-group">
                  <input type="email" class="sm-form-control" placeholder="@lang('Email')" id="email"
                    name="email" autocomplete="off" />
                </div>
                <div class="col-md-4 form-group">
                  <input type="text" class="sm-form-control" placeholder="@lang('Phone') *" id="phone"
                    name="phone" autocomplete="off" required />
                </div>
                <div class="col-md-12 form-group">
                  <input type="text" class="sm-form-control" placeholder="@lang('Address')" id="address"
                    name="address" autocomplete="off" />
                </div>

                <div class="col-md-12 form-group">
                  <textarea class="sm-form-control" id="customer_note" name="customer_note" rows="5" cols="30"
                    placeholder="@lang('Content note')" autocomplete="off"></textarea>
                </div>
                <div class="col-12 form-group">
                  <button type="submit" class="button button-3d m-0">@lang('Submit Order')</button>
                </div>
              </form>
            </div>
          </div>
        @else
          <div class="row">
            <div class="col-lg-12">
              <div class="style-msg alertmsg">
                <div class="sb-msg">
                  <i class="icon-warning-sign"></i>
                  <strong>@lang('Warning!')</strong>
                  @lang('Cart is empty!')
                </div>
              </div>
            </div>
          </div>
        @endif
      </div>
    </div>
  </section>

  {{-- End content --}}
@endsection
