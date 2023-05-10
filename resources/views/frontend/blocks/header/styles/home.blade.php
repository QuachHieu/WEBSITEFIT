<header class="irs-main-header">

    <div class="irs-header-nav scrollingto-fixed">

        <div class="container">

            <div class="row">

                <div class="col-md-12 col-sm-12">

                    <nav class="navbar navbar-default irs-navbar">

                        <div class="container-fluid" style="padding-right: 0px">

                            <!-- Brand and toggle get grouped for better mobile display -->

                            <div class="navbar-header">

                                <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1" aria-expanded="false">

                                    <span class="sr-only">Toggle navigation</span>

                                    <span class="icon-bar"></span>

                                    <span class="icon-bar"></span>

                                    <span class="icon-bar"></span>

                                </button>

                                <a class="navbar-brand" href="{{ route('frontend.home') }}">

                                    <img style="width: 150px;height: 65px" src="{{ $web_information->image->logo_header_dark ?? '' }}" alt="CLB FIT HAUI" width="100%" height="100%" class="lazyload">

                                </a>

                            </div>

                            <!-- Collect the nav links, forms, and other content for toggling -->

                            <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">

                                <ul class="nav navbar-nav navbar-right" data-hover="dropdown" data-animations="flipInY">



                                    <li class="active">

                                        <a href="{{ route('frontend.home') }}"><i class="icofont icofont-home" aria-hidden="true" style="font-size: 20px"></i></a>

                                    </li>
                                    
                                    @isset($menu)
                      @php
                      $main_menu = $menu->first(function ($item, $key) {
                          return $item->menu_type == 'header' && ($item->parent_id == null || $item->parent_id == 0);
                      });
                      
                      $content = '';
                      foreach ($menu as $item) { 
                          $url = $title = '';
                          if ($item->parent_id == $main_menu->id) {
                              $title = isset($item->json_params->title->{$locale}) && $item->json_params->title->{$locale} != '' ? $item->json_params->title->{$locale} : $item->name;
                              $url = $item->url_link;
                              $active = $url == url()->current() ? 'active' : '';
                              $content .= '<li class="' . $active . '"><a href="' . $url . '">' . $title . '</a>'; 
                              if ($item->sub > 0) {
                            $content .= '<ul class="dropdown-menu">';
                            foreach ($menu as $item_sub) {
                                $url = $title = '';
                                if ($item_sub->parent_id == $item->id) {
                                    $title = isset($item_sub->json_params->title->{$locale}) && $item_sub->json_params->title->{$locale} != '' ? $item_sub->json_params->title->{$locale} : $item_sub->name;
                                    $url = $item_sub->url_link;
                
                                    $content .= '<li class="dropdown"><a href="' . $url . '">' . $title . '</a>';
                
                                    if ($item_sub->sub > 0) {
                                        $content .= '<ul class="dropdown-menu">';
                                        foreach ($menu as $item_sub_2) {
                                            $url = $title = '';
                                            if ($item_sub_2->parent_id == $item_sub->id) {
                                                $title = isset($item_sub_2->json_params->title->{$locale}) && $item_sub_2->json_params->title->{$locale} != '' ? $item_sub_2->json_params->title->{$locale} : $item_sub_2->name;
                                                $url = $item_sub_2->url_link;
                
                                                $content .= '<li><a href="' . $url . '">' . $title . '</a></li>';
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
            @endisset


                            
                                    <style>
                                        /*body {font-family: Arial, Helvetica, sans-serif;}*/



                                        /* Full-width input fields */

                                        input[type=text],
                                        input[type=password] {

                                            width: 35%;

                                            padding: 12px 20px;

                                            margin: 8px 0;

                                            display: inline-block;

                                            border: 1px solid #ccc;

                                            box-sizing: border-box;

                                        }



                                        /* Set a style for all buttons */

                                        button {

                                            background-color: #04AA6D;

                                            color: white;

                                            padding: 14px 20px;

                                            margin: 8px 0;

                                            border: none;

                                            cursor: pointer;

                                            width: 35%;

                                        }



                                        button:hover {

                                            opacity: 0.8;

                                        }



                                        /* Extra styles for the cancel button */

                                        .cancelbtn {

                                            width: auto;

                                            padding: 10px 18px;

                                            background-color: #f44336;

                                        }



                                        /* Center the image and position the close button */

                                        .imgcontainer {

                                            text-align: center;

                                            margin: 24px 0 12px 0;

                                            position: relative;

                                        }



                                        img.avatar {

                                            width: 15%;

                                            border-radius: 50%;

                                        }



                                        .container1 {

                                            padding: 16px;

                                        }



                                        span.psw {

                                            float: right;

                                            padding-top: 16px;

                                        }



                                        /* The Modal (background) */

                                        .modal {



                                            display: none;
                                            /* Hidden by default */

                                            position: fixed;
                                            /* Stay in place */

                                            z-index: 1;
                                            /* Sit on top */

                                            left: 0;

                                            top: 0;

                                            width: 100%;
                                            /* Full width */

                                            height: 100%;
                                            /* Full height */

                                            overflow: auto;
                                            /* Enable scroll if needed */

                                            background-color: rgb(0, 0, 0);
                                            /* Fallback color */

                                            background-color: rgba(0, 0, 0, 0.4);
                                            /* Black w/ opacity */

                                            padding-top: 60px;

                                        }



                                        /* Modal Content/Box */

                                        .modal-content {

                                            background-color: #fefefe;

                                            margin: 5% auto 15% auto;
                                            /* 5% from the top, 15% from the bottom and centered */

                                            border: 1px solid #888;

                                            width: 550px;

                                            height: 550px;
                                            /* Could be more or less, depending on screen size */

                                        }



                                        /* The Close Button (x) */

                                        .close {

                                            position: absolute;

                                            right: 25px;

                                            top: 0;

                                            color: #000;

                                            font-size: 35px;

                                            font-weight: bold;

                                        }



                                        .close:hover,

                                        .close:focus {

                                            color: red;

                                            cursor: pointer;

                                        }



                                        /* Add Zoom Animation */

                                        .animate {

                                            -webkit-animation: animatezoom 0.6s;

                                            animation: animatezoom 0.6s
                                        }



                                        @-webkit-keyframes animatezoom {

                                            from {
                                                -webkit-transform: scale(0)
                                            }

                                            to {
                                                -webkit-transform: scale(1)
                                            }

                                        }



                                        @keyframes animatezoom {

                                            from {
                                                transform: scale(0)
                                            }

                                            to {
                                                transform: scale(1)
                                            }

                                        }



                                        /* Change styles for span and cancel button on extra small screens */

                                        @media screen and (max-width: 300px) {

                                            span.psw {

                                                display: block;

                                                float: none;

                                            }

                                            .cancelbtn {

                                                width: 100%;

                                            }

                                        }
                                    </style>

                                   


                            

                                </ul>



                            </div>

                            <!-- /.navbar-collapse -->

                        </div>

                        <!-- /.container-fluid -->

                    </nav>

                </div>

            </div>

        </div>

    </div>

</header>