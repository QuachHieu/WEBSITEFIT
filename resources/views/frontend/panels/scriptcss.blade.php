   <script type="text/javascript" src="{{ asset('/themes/frontend/assets/js/jquery-3.3.1.min.js')}}"></script>
   <script type="text/javascript" src="{{ asset('/themes/frontend/assets/js/jquery.cookie.js')}}"></script>
   <script type="text/javascript" src="{{ asset('/themes/frontend/assets/js/bootstrap.min.js')}}"></script>
   <script type="text/javascript" src="{{ asset('/themes/frontend/assets/js/bootstrap-dropdownhover.min.js')}}"></script>
   <script type="text/javascript" src="{{ asset('/themes/frontend/assets/js/jquery-scrolltofixed-min.js')}}"></script>
   <script type="text/javascript" src="{{ asset('/themes/frontend/assets/js/jquery.magnific-popup.min.js')}}"></script>
   <script type="text/javascript" src="{{ asset('/themes/frontend/assets/js/css3-animate-it.js')}}"></script>
   <script type="text/javascript" src="{{ asset('/themes/frontend/assets/js/bootstrap-slider.js')}}"></script>
   <script type="text/javascript" src="{{ asset('/themes/frontend/assets/js/jquery.scrollUp.js')}}"></script>
   <script type="text/javascript" src="{{ asset('/themes/frontend/assets/js/classie.js')}}"></script>
   <script type="text/javascript" src="{{ asset('/themes/frontend/assets/js/Interaction.js')}}"></script>

   <!-- Custom script for all pages -->
   <script type="text/javascript" src="{{ asset('/themes/frontend/assets/js/lazysizes.min.js')}}"></script>
   <script type="text/javascript" src="{{ asset('/themes/frontend/assets/js/script.js')}}"></script>

   <script type="text/javascript" src="{{ asset('/themes/frontend/assets/js/slick.min.js')}}"></script>
   <!-- <script type="text/javascript" src="templates/assets/js/slick.js"></script> -->
   <script type="text/javascript" src="{{ asset('/themes/frontend/assets/js/particles.min.js')}}"></script>
   <script type="text/javascript">
       window.onload = function() {
           Particles.init({
               selector: '.particles',
               color: '#75A5B7',
               maxParticles: 50,
               connectParticles: false,
               responsive: [{
                   breakpoint: 768,
                   options: {
                       maxParticles: 30
                   }
               }, {
                   breakpoint: 375,
                   options: {
                       maxParticles: 20
                   }
               }]
           });
       };
       /*================== BG Slide Animation =====================*/
       $(".category").slick({
           slidesToShow: 6,
           slidesToScroll: 1,
           arrows: true,
           autoplay: true,
           slide: 'div',
           fade: false,
           infinite: true,
           dots: false,
           responsive: [{
                   breakpoint: 980,
                   settings: {
                       slidesToShow: 4,
                       slidesToScroll: 1,
                       infinite: true,
                       vertical: false,
                       centerMode: false,
                       centerPadding: '0px',
                       dots: false,
                       arrows: true
                   }
               },
               {
                   breakpoint: 767,
                   settings: {
                       slidesToShow: 2,
                       slidesToScroll: 1,
                       infinite: true,
                       vertical: false,
                       centerMode: false,
                       centerPadding: '0px',
                       dots: false,
                       arrows: false
                   }
               },
               {
                   breakpoint: 520,
                   settings: {
                       slidesToShow: 1,
                       slidesToScroll: 1,
                       infinite: true,
                       vertical: false,
                       centerMode: false,
                       centerPadding: '0px',
                       dots: false,
                       arrows: false
                   }
               }
           ]
       });
       $("#companies-carousel").slick({
           slidesToShow: 4,
           slidesToScroll: 1,
           arrows: true,
           autoplay: true,
           slide: 'div',
           fade: false,
           infinite: true,
           dots: false,
           responsive: [{
                   breakpoint: 980,
                   settings: {
                       slidesToShow: 3,
                       slidesToScroll: 1,
                       infinite: true,
                       vertical: false,
                       centerMode: false,
                       centerPadding: '0px',
                       dots: false,
                       arrows: true
                   }
               },
               {
                   breakpoint: 767,
                   settings: {
                       slidesToShow: 2,
                       slidesToScroll: 1,
                       infinite: true,
                       vertical: false,
                       centerMode: false,
                       centerPadding: '0px',
                       dots: false,
                       arrows: true
                   }
               },
               {
                   breakpoint: 520,
                   settings: {
                       slidesToShow: 1,
                       slidesToScroll: 1,
                       infinite: true,
                       vertical: false,
                       centerMode: false,
                       centerPadding: '0px',
                       dots: false,
                       arrows: false
                   }
               }
           ]
       });
       $("#companies-carousel-1").slick({
           slidesToShow: 4,
           slidesToScroll: 1,
           arrows: true,
           autoplay: true,
           slide: 'div',
           fade: false,
           infinite: true,
           dots: false,
           responsive: [{
                   breakpoint: 980,
                   settings: {
                       slidesToShow: 3,
                       slidesToScroll: 1,
                       infinite: true,
                       vertical: false,
                       centerMode: false,
                       centerPadding: '0px',
                       dots: false,
                       arrows: true
                   }
               },
               {
                   breakpoint: 767,
                   settings: {
                       slidesToShow: 2,
                       slidesToScroll: 1,
                       infinite: true,
                       vertical: false,
                       centerMode: false,
                       centerPadding: '0px',
                       dots: false,
                       arrows: true
                   }
               },
               {
                   breakpoint: 520,
                   settings: {
                       slidesToShow: 1,
                       slidesToScroll: 1,
                       infinite: true,
                       vertical: false,
                       centerMode: false,
                       centerPadding: '0px',
                       dots: false,
                       arrows: false
                   }
               }
           ]
       });
       $("#video-carousel").slick({
           slidesToShow: 3,
           slidesToScroll: 1,
           arrows: false,
           autoplay: true,
           slide: 'div',
           fade: false,
           infinite: true,
           dots: false,
           speed: 600,
           responsive: [{
                   breakpoint: 980,
                   settings: {
                       slidesToShow: 3,
                       slidesToScroll: 1,
                       infinite: true,
                       vertical: false,
                       centerMode: false,
                       centerPadding: '0px',
                       dots: false,
                       arrows: false
                   }
               },
               {
                   breakpoint: 767,
                   settings: {
                       slidesToShow: 2,
                       slidesToScroll: 1,
                       infinite: true,
                       vertical: false,
                       centerMode: false,
                       centerPadding: '0px',
                       dots: false,
                       arrows: false
                   }
               },
               {
                   breakpoint: 520,
                   settings: {
                       slidesToShow: 1,
                       slidesToScroll: 1,
                       infinite: true,
                       vertical: false,
                       centerMode: false,
                       centerPadding: '0px',
                       dots: false,
                       arrows: false
                   }
               }
           ]
       });
       $(document).ready(function($) {
           $('.start-count').each(function() {
               var $this = $(this);
               $this.data('target', parseInt($this.html()));
               $this.data('counted', false);
               $this.html('0');
           });

           $(window).bind('scroll', function() {
               var speed = 3000;
               $('.start-count').each(function() {
                   var $this = $(this);
                   if (!$this.data('counted') && $(window).scrollTop() + $(window).height() >= $this.offset().top) {
                       $this.data('counted', true);
                       $this.animate({
                           dummy: 1
                       }, {
                           duration: speed,
                           step: function(now) {
                               var $this = $(this);
                               var val = Math.round($this.data('target') * now);
                               $this.html(val);
                               if (0 < $this.parent('.value').length) {
                                   $this.parent('.value').css('width', val + '%');
                               }
                           }
                       });
                   }
               });
           }).triggerHandler('scroll');
       });
       var oiInteraction = new _interactionCore.init(1, 0, 0);
   </script>