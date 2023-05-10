

/*
var documentHeight = $(document).height();
let currentLatestPage = 1;
let allowAppend = true;
$(window).scroll(function (event) {
    var scroll = $(window).scrollTop();
    if ((scroll + 1000 > this.documentHeight) && allowAppend === true) {
        allowAppend = false;
        currentLatestPage += 1;
        $.get("/ajax/latestnews.aspx", { trang: currentLatestPage },
            function (data) {
                $("#column-main .wrap").append(data);
                var content = document.createElement("div");
                content.innerHTML = data;
                let articles = $(content).find('script')[0].innerText.replace("var arrayAppendArticles = '", "").replace("';", "");
                allowAppend = true;
                documentHeight = $(document).height();
                if (Client.UserID === "" || Client.UserID === "0") return false;
                $.getJSON("/ajax/article.ashx?type=fillter&articles=" + articles, function (res) {
                    if (res.Status === true) {
                        var saved = JSON.parse(res.Content);
                        for (var i = 0; i < saved.length; i++) {
                            var item = saved[i];
                            $(".story__react.love[data-article='" + item.NewsID + "']").addClass("is-checked").attr("data-id", item.ID);
                        }

                    } else {
                        alertify.notify(res.Content, 'error', 3);
                    }
                });
            }
        );
    }
});
$(document).ready(function () {
    
});
*/
