@php
  $params['status'] = App\Consts::POST_STATUS['active'];
  $params['block_code'] = 'slide';
  
  $slide = App\Http\Services\PageBuilderService::getBlockContent($params)->get();

@endphp
@isset($slide)
<section class="irs-main-slider">



  <div id="myCarousel" class="carousel fade-carousel slide" data-ride="carousel">

    <div class="carousel-inner">
    @foreach ($slide as $item)
        @php
          $title = $item->json_params->title->{$locale} ?? $item->title;
          $url_link = $item->url_link != '' ? $item->url_link : '';
          $image = $item->image_thumb != '' ? $item->image_thumb : ($item->image != '' ? $item->image : null);
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


@endisset