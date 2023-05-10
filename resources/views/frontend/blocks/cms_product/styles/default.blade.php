@if ($block)
@php
$title = $block->json_params->title->{$locale} ?? $block->title;
$brief = $block->json_params->brief->{$locale} ?? $block->brief;
$url_link_title = $block->json_params->url_link_title->{$locale} ?? $block->url_link_title;
$params['status'] = App\Consts::TAXONOMY_STATUS['active'];

$params['taxonomy'] = 'product_category';
$params['is_featured']=true;

$rows = App\Http\Services\ContentService::getCmsTaxonomy($params)
->get();
@endphp

@isset($rows)
@foreach ($rows as $list)
@php
$title_list = $list->json_params->title->{$locale} ?? $list->title;
$image_list = $list->image_thumb != '' ? $list->image_thumb : ($list->image != '' ? $list->image : null);
$alias_ = Str::slug($list->title);
$link = route('frontend.cms.product_category', ['alias' => $alias_]) . '.html?id=' . $list->id;

$paramPost['status'] = App\Consts::POST_STATUS['active'];

$paramPost['is_type']=App\Consts::POST_TYPE['product'];
$paramPost['taxonomy_id']=$list->id;

$rows2=App\Http\Services\ContentService::getCmsPost($paramPost)

->get();
@endphp
<div id="content">
        <div class="container">
<section class="product_home sec_home">
  <div class="sec_title center">
    <h2 class="title uppe bold"><a class="headline" href="{{ $link }}">{{ $title_list }}</a></h2>
  </div>
  <div class="sec_content">
    <div class="list_item_pro columns-4">
      @foreach ($rows2 as $item)
      @php
      $title_product = $item->json_params->title->{$locale} ?? $item->title;
      $brief_product = $item->json_params->brief->{$locale} ?? $item->brief;
      $image_product = $item->image_thumb ?? ($item->image ?? null);
      $date = date('H:i d/m/Y', strtotime($item->created_at));
      // Viet ham xu ly lay alias bai viet
      $alias_category = Str::slug($item->taxonomy_title);
      $alias_detail = Str::slug($title);
      $url_link = route('frontend.cms.product', ['alias_category' => $alias_category, 'alias_detail' => $alias_detail]) . '.html?id=' . $item->id;
      $taxonomy_url_link = route('frontend.cms.product_category', ['alias' => $alias_category]) . '.html?id=' . $item->taxonomy_id;
      @endphp
      <div class="item_pro center">
        <a href="{{ $url_link }}">
          <div class="img"><img data-lazyloaded="1" data-placeholder-resp="367x400" src="{{ $image_product }}" width="367" height="400" data-src="{{ $image_product }}" class="attachment-medium size-medium wp-post-image" alt="{{ $title_product }}" loading="lazy" data-srcset="{{ $image_product }}" data-sizes="(max-width: 367px) 100vw, 367px" /></div>
          <div class="info">
            <h3 class="capt">{{ $title_product }}</h3>
            <div class="additional">
              <p></p>
            </div>
            <div class="header_hotline">
              <div class="icon"><i class="fa fa-phone" aria-hidden="true"></i></div>
              <div class="value">
                <a href="tel: {{ $web_information->information->hotline }}" class="phone" data-wpmeteor-mouseover="true" data-wpmeteor-mouseout="true"> {{ $web_information->information->hotline }}</a>
              </div>
            </div>
          </div>
        </a>
      </div>
      @endforeach
    </div>
    </div>
    <div class="read_more">
      <a href="{{ $link }}">Xem thêm</a>
    </div>
  
</section>

@endforeach
@endisset
@endif