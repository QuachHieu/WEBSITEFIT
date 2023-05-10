@if ($block)
@php
// Filter all blocks by parent_id
$block_childs = $blocks->filter(function ($item, $key) use ($block) {
return $item->parent_id == $block->id;
});
@endphp
<section class="irs-blog-field" style="padding: 0 0 30px 0;">
  <div class="container">
  <div class="row">
      <div class="col-md-12">
        <div class="irs-section-title" style="text-align: center;">
          <h2>
            <a href="https://khcn.haui.edu.vn/"><span>Liên kết tài trợ CLB</span></a>
          </h2>
        </div>
      </div>

      <div class="col-lg-12">
        <div class="top-company-sec">
          <div class="row" id="companies-carousel-1">
          @if ($block_childs)
      @foreach ($block_childs as $item)
      @php
      $title = $item->json_params->title->{$locale} ?? $item->title;
      $brief = $item->json_params->brief->{$locale} ?? $item->brief;
      $image = $item->image != '' ? $item->image : url('assets/images/slide-01.jpg');
      $url_link = $item->url_link != '' ? $item->url_link : '';
      $url_link_title = $item->json_params->url_link_title->{$locale} ?? $item->url_link_title;
      $icon = $item->icon != '' ? $item->icon : '';
      $style = $item->json_params->style ?? '';
      @endphp
            <div class="col-lg-3">
              <div class="top-compnay">
                <img src="{{ $image }}" alt="{{ $title }}" onerror="this.src='{{ $image }}'">
               
              </div><!-- Top Company -->
            </div>
           
            @endforeach
      @endif
          </div>
        </div>
      </div>

  </div>
  </div>
</section>

@endif 
</div>
</div>
</main>