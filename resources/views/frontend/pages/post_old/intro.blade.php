@extends('frontend.layouts.default')

@php
$title_detail = $detail->json_params->title->{$locale} ?? $detail->title;
$brief_detail = $detail->json_params->brief->{$locale} ?? null;
$content = $detail->json_params->content->{$locale} ?? null;
$image = $detail->image != '' ? $detail->image : null;
$image_thumb = $detail->image_thumb != '' ? $detail->image_thumb : null;
$date = date('H:i d/m/Y', strtotime($detail->created_at));

// For taxonomy
$taxonomy_json_params = json_decode($detail->taxonomy_json_params);
$taxonomy_title = $taxonomy_json_params->title->{$locale} ?? $detail->taxonomy_title;
$image_background = $taxonomy_json_params->image_background ?? ($web_information->image->background_breadcrumbs ?? null);
$taxonomy_alias = Str::slug($taxonomy_title);
$alias_detail = Str::slug($title_detail);
$taxonomy_url_link = route('frontend.cms.post_category', ['alias' => $taxonomy_alias]) . '.html?id=' . $detail->taxonomy_id;
$url_link = route('frontend.cms.post', ['alias_category' => $taxonomy_alias, 'alias_detail' => $alias_detail]) . '.html?id=' . $detail->id;

$seo_title = $detail->json_params->seo_title ?? $title_detail;
$seo_keyword = $detail->json_params->seo_keyword ?? null;
$seo_description = $detail->json_params->seo_description ?? $brief_detail;
$seo_image = $image ?? ($image_thumb ?? null);
//echo "AAAAAAAAAAAA".$content;die;

@endphp


@section('content')

<main class="site-content">
    
    <div class="site-wrap">
        <!-- left columnn -->
        
        @include('frontend.element.menuleft')

        <!-- column content -->
        <div class="column-content">
            
            @include('frontend.element.adsheader')

            <div class="column-wrap">
                <div class="column-main" id="column-main"><div class="wrap">
                    
                    @include('frontend.element.dangbai')

                    <article class="detail-wrap">
                    <header class="detail__header">
                        
                        <h1 class="detail__title">{{ $title_detail }}</h1>
                        <h2 class="detail__summary">{{ $brief_detail }}</h2>
                    </header>
                                    
                    <div class="detail__content mt-4">
                        {!! $content !!}
                    </div>
                    
                    </article>
                    
                </div>
            
            </div>
                    
            @include('frontend.element.sidebar')    
            
        </div>
    </div>
    </div>
    
</main>



  {{-- End content --}}
@endsection
