@php
    $seo_title = $seo_title ?? ($page->title ?? ($web_information->information->seo_title ?? ''));
@endphp

<!DOCTYPE html>
<html lang="vi" class="no-js">

    @include('frontend.panels.head')

<body>
  
    {{-- logo, đăng kí đăng nhập --}}
    @include('frontend.element.header')

        
    {{-- slide done--}}
    @include('frontend.element.slide')

   

        {{-- tin tức --}}
        @include('frontend.element.tintuc')
    
 
        
    

          {{-- video --}}
          @include('frontend.element.video')
          </div>
</div>
</main>
        
        @include('frontend.element.footer')
       
         
   





    @include('frontend.panels.scriptcss')
    
</body>

</html>

 