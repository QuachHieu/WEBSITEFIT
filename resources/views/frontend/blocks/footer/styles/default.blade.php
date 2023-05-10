 
<footer class="irs-footer-field">

<div class="container">

    <div class="row animatedParent animateOnce animateOnce">

        <div class="col-md-1 hidden-sm">

            <div class="irs-footer-about">

                <a href="#">

                    <img class="irs-foooter-logo lazyload" src="{{ $web_information->image->logo_footer ?? '' }}" alt="FIT HAUI" width="100%" height="100%">

                </a>


            </div>

        </div>

        <div class="col-md-3">

            <h4 class="irs-footer-heading">Bản đồ chỉ dẫn</h4>

            <p style="margin-top: -20px;">{!! $web_information->information->site_name !!}</p>

            <div class="irs-mailbox">

                <div class="irs-footer-contact">
                @isset($web_information->source_code->map)
          {!! $web_information->source_code->map !!}
        @endisset               
    </div>

            </div>

        </div>



        <div class="col-md-4">

            <div class="irs-footer-contact">

                <h4 class="irs-footer-heading">Trụ sở chính</h4>

                <div class="irs-mailbox">

                    <p>

                        <i class="icofont icofont-map"></i>Địa chỉ: {!! $web_information->information->address !!}

                    </p>

                    <p>

                        <i class="icofont icofont-phone" aria-hidden="true"></i>Hotline: {} 

                    </p>

                    <p>

                        <i class="icofont icofont-mail"></i>Email: {!! $web_information->information->email !!}
                    </p>

                </div>

            </div>

            

        </div>

        <div class="col-md-4">

            <div class="irs-footer-contact">

                <h4 class="irs-footer-heading">Menu Footer</h4>

                <div class="irs-mailbox">

                @isset($menu)
                
                    @php
              $footer_menu = $menu->filter(function ($item, $key) {
                  return $item->menu_type == 'footer' && ($item->parent_id == null || $item->parent_id == 0);
              });
              
              $content = '';
              $col = 12 / count($footer_menu);
              foreach ($footer_menu as $main_menu) {
                  $url = $title = '';
                  $title = isset($main_menu->json_params->title->{$locale}) && $main_menu->json_params->title->{$locale} != '' ? $main_menu->json_params->title->{$locale} : $main_menu->name;

                  foreach ($menu as $item) {
                      if ($item->parent_id == $main_menu->id) {
                          $title = isset($item->json_params->title->{$locale}) && $item->json_params->title->{$locale} != '' ? $item->json_params->title->{$locale} : $item->name;
                          $url = $item->url_link;
              
                          $active = $url == url()->current() ? 'active' : '';
              
                          $content .= '<p><a style ="color:white;" href="' . $url . '">' . $title . '</a>';
                          $content .= '</p>';
                      }
                  }
              
              }
              
              echo $content;
            @endphp
            @endisset 

               

            </div>

            <div class="footer__social">

                <ul>

                    <li><a href="{{ $web_information->social->facebook }}" target="_blank" class="zalo"><img src="https://www.haui.edu.vn/dnn/web/haui/assets/images/svg/facebook.svg" alt="Facebook"></a></li>

                    <li><a href="{{ $web_information->social->youtube }}" class="zalo"><img src="https://www.haui.edu.vn/dnn/web/haui/assets/images/svg/youtube.svg" alt="Youtube"></a></li>



                    <li>

                        <a href="{{ $web_information->social->zalo }}" class="zalo"><img src="{{ asset('/themes/frontend/assets/images/svg/zalo.png')}}" alt="Zalo"></a>

                    </li>

                    <li>

                        <a href="{{ $web_information->social->twitter }}" class="tiktok"><img src="https://www.haui.edu.vn/dnn/web/haui/assets/images/svg/tiktok.svg" alt="Tiktok"></a>

                    </li>

                </ul>

            </div>

        </div>

    </div>



</div>

</div>

</footer>

<!-- Footer end -->

<section class="irs-copyright-field">

<div class="container">

    <div class="row">

        <div class="col-sm-12">

            <div class="irs-copyright">

                <p>

                    Copyright © 2023

                </p>

            </div>

        </div>

    </div>

</div>

</section>