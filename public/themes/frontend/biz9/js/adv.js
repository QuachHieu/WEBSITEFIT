"use strict";
var widthWindows = $(window).width(), heightWindows = $(window).height() - 30;
class Adv {
    BindAdvImage(name, url, imgSource, imgClass) {
        if (imgClass !== undefined) {
            return "<div class='item-adv'> <a target='_blank' href='" + url + "' ><img alt='Quảng cáo' class='" + imgClass + "' src='" + imgSource + "' /></a></div>";
        } else {
            return "<div class='item-adv'> <a target='_blank' href='" + url + "' ><img alt='Quảng cáo' src='" + imgSource + "' /></a></div>";
        }

    };
    CommentCode(html) {
        if (html === undefined || html === "") return "";
        return "<!--" + html + "-->";
    }
    /**
     * Hàm để hiển thị quảng cáo
     */

    /*
    Load() {
        $.getJSON("/ajax/adv.ashx?time=" + Math.random(), function (data) {
            var adv = new Adv();
            $.each(data, function (key, item) {
                //check quảng cáo theo chuyên mục            
                if (item.CatList !== "" && ((typeof IsContent === 'undefined'))) {
                    if (("," + item.CatList.toString() + ",").indexOf("," + ZoneAdv.toString() + ",") < 0) return true;
                }
                switch (item.Position) {
                    case 5:
                        adv.DisplayItem(item, "#advRight1", "img-fluid");
                        break;
                    case 4:
                        adv.DisplayItem(item, "#advRight2", "img-fluid");
                        break;
                    case 6:
                        adv.DisplayItem(item, "#advHeaderTop", "img-fluid");
                        break;
                    case 1003:
                        adv.DisplayItem(item, "#advAfterHot", "img-fluid");
                        break;
                    case 1004:
                        adv.DisplayItem(item, "#advPost5", "img-fluid");
                        break;
                    case 1005:
                        adv.DisplayItem(item, "#advPost10", "img-fluid");
                        break;
                }
            });
        });
    }
    */

    /**
     *Hiển thị các item quảng cáo
     * @param {any} item
     * @param {any} container
     */
    DisplayItem(item, container, imgClass) {
        var adv = new Adv();
        if (item.AdvType !== 1) {
            //1 là kiểu quảng cáo dạng ảnh
            $(container).html($(container).html() + adv.CommentCode(item.PName + ":" + item.Name) + "<div class='item-adv'>" + item.Content + "</div>").removeClass('d-none');
        } else {
            //2 là kiểu quảng cáo dạng html/javascript
            $(container).html($(container).html() + adv.CommentCode(item.PName + ":" + item.Name) + adv.BindAdvImage(item.Name, item.Url, item.Avatar, imgClass)).removeClass('d-none');
        }

        if ($(container + " .item-adv").length > 1) {
            $(container).height(item.Height).css("overflow", "hidden");
            $(container + " .item-adv").hide();
            $(container + " .item-adv:first-child").show();
            setInterval(function () {
                $(container + ' > .item-adv:first').fadeOut(1000).next().fadeIn(1500).end().appendTo(container);
            }, item.NextTime * 1000);
        }
    }
}

$(function () {
    var adv = new Adv();
    adv.Load();
});