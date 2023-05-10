
@php


$params['status'] = App\Consts::POST_STATUS['active'];
$params['is_featured'] = true;
$params['is_type'] = App\Consts::POST_TYPE['resource'];

$rows = App\Http\Services\ContentService::getCmsPost($params)
->limit(5)
->get();

@endphp
<section class="irs-blog-field">
  <div class="container">

    <div class="row">

      <div class="col-md-8 col-md-offset-2">

        <div class="irs-section-title" style="text-align: center;">

          <h2>

            <a href="#"><span>Thư Viện Video</span></a>

          </h2>

        </div>

      </div>

    </div>

    
    <div class="row animatedParent animateOnce">

<div class="container">

    <div class="row">

    @isset($rows)
    @foreach ($rows as $item)
                @if ($loop->index >= 0)
                  @php
                    $title = $item->json_params->title->{$locale} ?? $item->title;
                    $brief = $item->json_params->brief->{$locale} ?? $item->brief;
                    $image = $item->image_thumb != '' ? $item->image_thumb : ($item->image != '' ? $item->image : null);
                    
                    $videos = $item->json_params->gallery_video ?? '';
					$i=0;$videolink = $video_title = "";
					foreach($videos as $vd){
						$i++;
						if($i==1){
							$videolink = $vd->source;
							$video_title = $vd->title;
						}
					}
					
					$ytarray=explode("/", $videolink);
					$ytendstring=end($ytarray);
					$ytendarray=explode("?v=", $ytendstring);
					$ytendstring=end($ytendarray);
					$ytendarray=explode("&", $ytendstring);
					$ytcode=$ytendarray[0];
					
                    $date = date('H:i d/m/Y', strtotime($item->created_at));
                    // Viet ham xu ly lay slug
                    $alias_category = Str::slug($item->taxonomy_title);
                    $alias_detail = Str::slug($title);
                    $url_link = route('frontend.cms.resource', ['alias_category' => $alias_category, 'alias_detail' => $alias_detail]) . '.html?id=' . $item->id;
                  @endphp
                <div class="col-md-3">

                    <div class="irs-courses-col animated fadeInLeftShort slow delay-250">

                        <div class="irs-courses-img" id="index-img">

                          

                            <iframe width="100%" height="220" src="https://www.youtube.com/embed/{{ $ytcode }}" title="{{ $video_title }}" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>

                            

                        </div>

                      

                    </div>

                </div>
                @endif
              @endforeach 
                

    @endisset


    </div>

</div>

</div>

  </div>
</section>

