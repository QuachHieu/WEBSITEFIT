$(function () {
    setTimeout(Init, 1000);
});
function Init() {
    if (!allowLoging()) return false;
    var ismobile = (/Android|webOS|iPhone|iPad|iPod|BlackBerry|IEMobile|Opera Mini/i.test(navigator.userAgent));
    var url = window.location.pathname;
    var urlFull = window.location.href;
    var urlRefer = document.referrer;
    var resolution = window.screen.availWidth + "x" + window.screen.availHeight;
    var form_data = new FormData();
    form_data.append('ismobile', ismobile);
    form_data.append('url', url);
    form_data.append('urlFull', urlFull);
    form_data.append('urlRefer', urlRefer);
    form_data.append('resolution', resolution);
    form_data.append('pagetype', pagetype);
    form_data.append('site', site);
    form_data.append('ObjectID', ObjectID);
    form_data.append('UserID', getUserID());

    var xhr = new XMLHttpRequest();
    xhr.open('POST', AddressWebLoging +  'log.ashx', true);
    xhr.send(form_data);
}
function allowLoging() {
    var cookieName = "ul_" + site.toString() + pagetype.toString() + ObjectID.toString();
    var value = getCookie(cookieName);
    if (value === undefined) {
        createCookie(cookieName, "true"); return true;
    } else {
        return false;
    }
}
function getUserID() {
    var cookieName = "imf.log.cname";
    var user = getCookie(cookieName);
    if (user === undefined) {
        user = guid();
        setCookie(cookieName, user, 3000);
    }
    return user;
}

function guid() {
    function s4() {
        return Math.floor((1 + Math.random()) * 0x10000).toString(16).substring(1);
    }
    return s4() + s4() + '-' + s4() + '-' + s4() + '-' + s4() + '-' + s4() + s4() + s4();
}

function setCookie(c_name, value, exdays) {
    var exdate = new Date();
    exdate.setDate(exdate.getDate() + exdays);
    var c_value = escape(value) + ((exdays === null) ? "" : ";path=/; expires=" + exdate.toUTCString());
    document.cookie = c_name + "=" + c_value;
}
function createCookie(name, value) {
    var date = new Date();
    date.setTime(date.getTime() + (30 * 1000 * 60));
    var expires = "; expires=" + date.toGMTString();
    document.cookie = name + "=" + value + expires + "; path=/";
}
function getCookie(c_name) {
    var i, x, y, ARRcookies = document.cookie.split(";");
    for (i = 0; i < ARRcookies.length; i++) {
        x = ARRcookies[i].substr(0, ARRcookies[i].indexOf("="));
        y = ARRcookies[i].substr(ARRcookies[i].indexOf("=") + 1);
        x = x.replace(/^\s+|\s+$/g, "");
        if (x === c_name) {
            return unescape(y);
        }
    }
}
function deleteCookie(c_name) {
    setCookie(c_name, "", -1);
}

