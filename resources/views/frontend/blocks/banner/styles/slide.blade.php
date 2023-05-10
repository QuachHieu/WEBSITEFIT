@if ($block)
@php
// Filter all blocks by parent_id
$block_childs = $blocks->filter(function ($item, $key) use ($block) {
return $item->parent_id == $block->id;
});
@endphp
<section class="irs-main-slider">



  <div id="myCarousel" class="carousel fade-carousel slide" data-ride="carousel">

    <div class="carousel-inner">
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
       
      @if($item->iorder==1)
      <div class="item slides active">

        <a href="{{ $url_link }}" title="">

          <img src="{{ $image }}" alt="slide" width="100%" height="100%" />

        </a>

      </div>
      @else
      <div class="item slides ">

                         <a href="{{ $url_link }}" title="">

                                <img src="{{ $image }}" alt="slide" width="100%" height="100%"/>

                        </a> 

      </div>
      @endif

      @endforeach
      @endif

      <!-- <div class="item slides ">

                         <a href="{{ $url_link }}" title="">

                                <img src="{{ $image }}" alt="slide" width="100%" height="100%"/>

                        </a> 

                    </div> -->




    </div>

    <a class="left carousel-control hidden-xs" href="{{ $url_link }}#myCarousel" data-slide="prev">

      <span class="glyphicon glyphicon-chevron-left icofont icofont-arrow-left"></span>

      <span class="sr-only">Previous</span>

    </a>

    <a class="right carousel-control hidden-xs" href="{{ $url_link }}#myCarousel" data-slide="next">

      <span class="glyphicon glyphicon-chevron-right icofont icofont-arrow-right"></span>

      <span class="sr-only">Next</span>

    </a>

  </div>



</section>


@endif