<meta charset="UTF-8">
    <meta http-equiv="x-dns-prefetch-control" content="on">
    <title>
        {{ $seo_title ?? ($page->title ?? ($web_information->information->seo_title ?? '')) }}
    </title>
    <meta name="robots" content="index, follow, max-snippet:-1, max-image-preview:large, max-video-preview:-1"/>
    <link rel="icon" href="{{ $web_information->image->favicon ?? '' }}" type="image/x-icon">
     {{-- Print SEO --}}
      @php
        $seo_title = $seo_title ?? ($page->title ?? ($web_information->information->seo_title ?? ''));
        $seo_keyword = $seo_keyword ?? ($page->keyword ?? ($web_information->information->seo_keyword ?? ''));
        $seo_description = $seo_description ?? ($page->description ?? ($web_information->information->seo_description ?? ''));
        $seo_image = $seo_image ?? ($page->json_params->og_image ?? ($web_information->image->seo_og_image ?? ''));
      @endphp
      <meta name="description" content="{{ $seo_description }}" />
      <meta name="keywords" content="{{ $seo_keyword }}" />
      <meta name="news_keywords" content="{{ $seo_keyword }}" />
      <meta property="og:image" content="{{ $seo_image }}" />
      <meta property="og:title" content="{{ $seo_title }}" />
      <meta property="og:description" content="{{ $seo_description }}" />
      <meta property="og:url" content="{{ Request::fullUrl() }}" />
      {{-- End Print SEO --}}
      <noscript>
        <style>
            .woocommerce-product-gallery {
                opacity: 1 !important
            }
        </style>
      </noscript>