// init function
var isMobile, isTablet, isDesktop;
var position = $(window).scrollTop();

$(function() {
    /*check device width*/
    bsContainerWidth = $(".site-content").find('.site-wrap').outerWidth();
    if (bsContainerWidth < 768) {
        console.log("mobile");
        isMobile = true;
    } else if (bsContainerWidth < 960) {
        console.log("tablet");
        isTablet = true;
    } else {
        console.log("desktop");
        isDesktop = true;
    }

    /*pin header */
    $('.site-content .column-main').wrapInner("<div class='wrap'></div>");
    $('.site-content .column-sidebar').wrapInner("<div class='wrap'></div>");

    window.onscroll = function() { windowScroll() };
    window.addEventListener("resize", function() {
        if ($(".site-content .column-sidebar").length > 0) {
            windowScroll();
        }
    });

    // button click
    $('#site-header .btn-expand').on('click', btnExpandClick);
    $('#site-header .btn-search').on('click', btnSearchClick);
    $('#site-header .btn-user').on('click', btnUserClick);
    $(".button-close").on('click', siteMaskClick);
    $("#site-mask").on('click', siteMaskClick);
    $('.site-footer .btn-sm').on('click', function(e) {
        e.preventDefault();
        e.stopPropagation();
        $('.site-footer__info').toggle('fast');
    });
    if (isMobile || isTablet) {
        var headerHeight = $('.site-header').height();
        $(window).scroll(function() {
            var scroll = $(window).scrollTop();
            if (scroll > position) {
                $('.site-header').removeClass('is-pinned');
                $('.site-header').removeAttr('style');
            } else {
                $('.site-header').addClass('is-pinned');
                $('.site-header').height(headerHeight);
                if (scroll === 0) {
                    $('.site-header').removeClass('is-pinned');
                    $('.site-header').removeAttr('style');
                }
            }
            position = scroll;
        });
    }

    // sticky
    if (isDesktop == true) {
        // $('#qcDemo').stickyfloat({ duration: 0 });
        stickyads(".column-main", ".column-sidebar", "#sticky", "#qcDemo", 10);
    }
});

/*customise function*/
// scoll down & pin site header
function windowScroll() {
    if (document.body.scrollTop > $('.site-header').outerHeight() || document.documentElement.scrollTop > $('.site-header').outerHeight()) {
        $("#btnGotop").fadeIn('slow');
    } else {
        $("#btnGotop").fadeOut('slow');
    }
}

function btnExpandClick(e) {
    e.preventDefault();
    e.stopPropagation();
    expandNav();
}

function expandNav() {
    $('#site-header .nav').toggle();
    $('#wrap-menu').addClass('is-active');
    openSiteMask();
}

function btnExpandClick(e) {
    e.preventDefault();
    e.stopPropagation();
    expandNav();
}

function expandSearch() {
    $('#wrap-search').addClass('is-active');
    $("#searchInput").focus();
    openSiteMask();
}

function btnSearchClick(e) {
    e.preventDefault();
    e.stopPropagation();
    expandSearch();
}

function btnUserClick(e) {
    e.preventDefault();
    e.stopPropagation();
    expandUserwrap();
}

function expandUserwrap() {
    $('#wrap-user').addClass('is-active');
    openSiteMask();
}

function openSiteMask(e) {
    $("#site-mask").addClass('is-active');
    $('body').css('overflow', 'hidden');
}

function removeSiteMask(e) {
    $("#site-mask").removeClass('is-active');
    $('body').removeAttr('style');
}

function siteMaskClick(e) {
    e.preventDefault();
    e.stopPropagation();
    removeSiteMask();
    $("div[id*='wrap-']").removeClass('is-active');
}


/*sticky ads*/
function stickyads(contentbox, sidebarbox, sidebartop, sticky, top) {
    $(window).scroll(function() {
        var contentHeight = $(contentbox).height();
        var sidebarWidth = $(sidebarbox).width();
        var sidebarHeight = $(sidebarbox).height();
        if (sidebarHeight <= contentHeight) {
            var curPos = $(window).scrollTop();
            var stickyTop = curPos - top;
            if ($(sidebartop).length > 0) {
                stickyTop = curPos - $(sidebartop).offset().top - $(sidebartop).height();
            }
            var stickyBottom = curPos - $(contentbox).offset().top - $(contentbox).height() + $(sticky).outerHeight() + top;
            if (stickyTop > 0) {
                $(sticky).css('position', "fixed");
                $(sticky).css("top", top + "px");
                $(sticky).css("margin", "0");
                $(sticky).css("width", sidebarWidth + "px");
            } else {
                $(sticky).removeAttr("style");
                $(sticky).css("top", "0px");
            }
            if (stickyBottom > 0) $(sticky).css("top", -1 * stickyBottom);
        }
    });
}

// Normalize Carousel Heights - pass in Bootstrap Carousel items.
$.fn.matchHeight = function() {

    var items = $(this), //grab all slides
        heights = [], //create empty array to store height values
        tallest; //create variable to make note of the tallest slide

    var normalizeHeights = function() {

        items.each(function() { //add heights to array
            heights.push($(this).height());
        });
        tallest = Math.max.apply(null, heights); //cache largest value
        items.each(function() {
            $(this).css('min-height', tallest + 'px');
        });
    };

    normalizeHeights();

    $(window).on('resize orientationchange', function() {
        //reset vars
        tallest = 0;
        heights.length = 0;

        items.each(function() {
            $(this).css('min-height', '0'); //reset min-height
        });
        normalizeHeights(); //run it again 
    });

};

jQuery(function($) {
    $(window).on('load', function() {
        if ($(".overlay-listing").length > 0) {
            $('.overlay-listing .story').matchHeight();
        }
    });
});