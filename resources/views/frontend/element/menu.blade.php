@include('frontend.element.style_menu')
{{--  <div class="header-navbar">
    <div class="navbarr clearfix">
<ul class="nav">
  <li>
    <a href="#">Servicios<span class="down">&#9660;</span></a>
    <ul>
      <li>
        <a href="#">Diseno web<span class="down">&#9660;</span></a>
        <ul>
          <li><a href="#">Submenu 1<span class="down">&#9660;</span></a></li>
        </ul>
      </li>
    </ul>
  </li>
</ul>
</div>
</div>
</div>
--}}
 @isset($menu)
<div class="header-navbar">
    <div class="navbarr clearfix">
        <ul class="nav">
            @php
              $main_menu = $menu->first(function ($item, $key) {
                  return $item->menu_type == 'header' && ($item->parent_id == null || $item->parent_id == 0);
              });
              
              $content = '';
              //cấp 1
              foreach ($menu as $item) {
                  $url = $title = '';
                  if ($item->parent_id == $main_menu->id) {
                      $title = isset($item->json_params->title->{$locale}) && $item->json_params->title->{$locale} != '' ? $item->json_params->title->{$locale} : $item->name;
                      $url = $item->url_link;
                      $active = $url == url()->current() ? 'current' : '';
              
                      $content .= '<li><a class="newa" href="' . $url . '">' . $title . '<span class="down">&#9660;</span></a>';

                      //cấp 2
                      if ($item->sub > 0) {
                          $content .= '<ul>';
                          foreach ($menu as $item_sub) {
                              $url = $title = '';
                              if ($item_sub->parent_id == $item->id) {
                                  $title = isset($item_sub->json_params->title->{$locale}) && $item_sub->json_params->title->{$locale} != '' ? $item_sub->json_params->title->{$locale} : $item_sub->name;
                                  $url = $item_sub->url_link;
                                  $content .= '<li><a class="newa" href="' . $url . '">'.$title.'<span class="down">&#9660;</span></a>';

                                  //cấp 3
                                  if ($item_sub->sub > 0) {
                                      $content .= '<ul>';
                                      foreach ($menu as $item_sub_2) {
                                          $url = $title = '';
                                          if ($item_sub_2->parent_id == $item_sub->id) {
                                              $title = isset($item_sub_2->json_params->title->{$locale}) && $item_sub_2->json_params->title->{$locale} != '' ? $item_sub_2->json_params->title->{$locale} : $item_sub_2->name;
                                              $url = $item_sub_2->url_link;
              
                                              $content .= '<li><a class="newa" href="' . $url . '">' . $title . '</a><span class="down">&#9660;</span></li>';
                                          }
                                      }
                                      $content .= '</ul>';
                                  }
                                  $content .= '</li>';
                              }
                          }
                          $content .= '</ul>';
                      }
                      $content .= '</li>';
                  }
              }
              echo $content;
            @endphp
        </ul>
    </div>
</div>        
</div>
@endisset

