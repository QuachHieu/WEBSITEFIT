(function($) {     
    $.fn.extend({
    center: function () {
        return this.each(function() {                 
        var top = ($(window).height() - $(this).outerHeight()) / 2 + $(window).scrollTop();                 
        var left = ($(window).width() - $(this).outerWidth()) / 2 + $(window).scrollLeft();  
        $(this).show();               
        $(this).css({position:'absolute', margin:0, top: (top > 0 ? top : 0)+'px', left: (left > 0 ? left : 0)+'px'});             
        });
    }
});
})(jQuery); 