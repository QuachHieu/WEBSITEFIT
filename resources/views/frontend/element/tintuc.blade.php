
@php


$params['status'] = App\Consts::POST_STATUS['active'];
$params['is_featured'] = true;
$params['is_type'] = App\Consts::POST_TYPE['post'];
$params['limit']=6;
$rows = App\Http\Services\ContentService::getCmsPost($params)->get();
@endphp

<section class="irs-blog-field" style="background: url(../dnn/web/haui/assets/images/bg/parallax3.jpg) 50% -54.0531px repeat scroll transparent; padding: 20px 0;">

  <div class="container">

    <div class="row">

      <div class="col-md-8 event-left">

        <div class="col-md-12">

          <div class="irs-section-title">

            <h2>

              <a href="#">

                <span>Tin Tức</span>

              </a>

            </h2>

          </div>

        </div>

      @isset($rows)

      @foreach ($rows as $key => $item)
      @php
      $title = $item->json_params->title->{$locale} ?? $item->title;
      $brief = $item->json_params->brief->{$locale} ?? $item->brief;
      $image = $item->image_thumb != '' ? $item->image_thumb : ($item->image != '' ? $item->image : null);
      $date = date('H:i d/m/Y', strtotime($item->created_at));
      // Viet ham xu ly lay slug
      $alias_category = Str::slug($item->taxonomy_title);
      $alias_detail = Str::slug($title);
      $url_link = route('frontend.cms.post', ['alias_category' => $alias_category, 'alias_detail' => $alias_detail]) . '.html?id=' . $item->id;

      @endphp
      @if($key==0)
      <div class="col-md-6">

        <div class="irs-blog-single-col">

          <div class="irs-blog-col">

            <div id="index-img">

              <a href="{{ $url_link }}" title="{{ $title }}">

                <img data-src="{{ $image }}" alt="notice" onerror="this.src='{{ $image }}'" width="100%" height="100%" class="lazyload">

              </a>

            </div>

            <div class="irs-courses-content">

              <h4>

                <a href="{{ $url_link }}" title="{{ $title }}">{{ $title }}</a>

              </h4>

              <div class="line-clamp" style="overflow:hidden;">

                <p>

                  {{ Str::limit($brief, 100) }}

                </p>

              </div>

            </div>

          </div>

        </div>

      </div>
      @else
      <div class="col-md-6">

        <div class="irs-blog-single-col">

          <div class="irs-side-bar">

            <div class="irs-post">



              <div class="irs-post-item" style="padding-left: 110px;">

                <a href="{{ $url_link }}" title="{{ $title }}">

                  <img data-src="{{ $image }}" alt="{{ $image }}" onerror="this.src='{{ $image }}'" width="100%" height="100%" class="lazyload">

                </a>

                <a href="{{ $url_link }}" title="{{ $title }}">

                  <div class="event-title-module line-clamp">

                    <p>{{ $title }}</p>

                  </div>

                </a>

              </div>


            </div>

          </div>

        </div>

      </div>
      @endif
      @endforeach
      </div>
      
      @endisset
      @php

      $params['status'] = App\Consts::POST_STATUS['active'];
      $params['is_featured'] = true;
      $params['taxonomy_id']= '21';
      
      $rows2 = App\Http\Services\ContentService::getCmsPost($params)->get();
      @endphp
      <div class="col-md-4 event-hightlight">

        <div class="event-title-highlight">

          Thông báo

        </div>



        <ul>

          @foreach ($rows2 as $postnoti)
          @php
          $title_tb = $postnoti->json_params->title_tb->{$locale} ?? $postnoti->title;
          $brief_tb = $postnoti->json_params->brief_tb->{$locale} ?? $postnoti->brief;
          $image_tb = $postnoti->image_thumb != '' ? $postnoti->image_thumb : ($postnoti->image_tb != '' ? $postnoti->image_tb : null);
          $date = date('H:i d/m/Y', strtotime($item->created_at));
          // Viet ham xu ly lay slug
          $alias_category = Str::slug($postnoti->taxonomy_title);
          $alias_detail = Str::slug($title_tb);
          $url_link_tb = route('frontend.cms.post', ['alias_category' => $alias_category, 'alias_detail' => $alias_detail]) . '.html?id=' . $postnoti->id;

          @endphp
          <li> <a href="{{ $url_link_tb }}" title="{{ $title_tb }}">
              <div class="event-title-module line-clamp">
                <p><span style="color:#0d4e96;">» </span>{{ $title_tb }}</p>
              </div>
            </a></li>



          @endforeach

        </ul>

        <!-- <a href="#" class="pull-right" style="color:#0d4e96;">» Xem tất cả</a> -->



      </div>

    </div>


  </div>
  </div>
</section>
