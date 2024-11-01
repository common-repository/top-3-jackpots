/**
 * Front-End JavaScript
 * 
 * @package Top3Jackpots
 * @since Top3Jackpots 1.0.0
 */

jQuery(document).ready(function($){

    $(".top3jps-for-gtm-trigger").click(function(e){
        e.preventDefault();
        var userId = $(this).attr("data-id");
        var lottery = $(this).attr("data-lottery");
        window.open(window.top3jpsRef + lottery + "?ref=" + userId);
        $.ajax(window.top3jpsCountClicks, {
            method: "POST",
            data: { top3jps_accept_click: true }
        });
        return false;
    });

    if(!window.top3jpsIsHP){

        var top = $('#main').offset().top - ( $( '#header' ).height() + 40);
        var top3jpsHeight = $( '#top3jps-container' ).height() + 100;
        var offset =  20;
        var doing = false;
        var closedByUser = false;

        $("#top3jps-footer-closer").click(function(){
            $("#top3jps-footer-container").css("display", "none");
            closedByUser = true;
        });

        var st = jQuery(this).scrollTop();
        if(window.innerWidth < 992 && st > offset && $("#top3jps-footer-container").attr("data-ishidden") != "true"){
            $("#top3jps-footer-container").css("display", "block");
        } else {
            $("#top3jps-footer-container").css("display", "none");
        }

        jQuery(window).scroll(function(){

            if(closedByUser){
                return;
            }

            var st = jQuery(this).scrollTop();

            if(window.innerWidth < 992 && st > offset && $("#top3jps-footer-container").attr("data-ishidden") != "true"){
                $("#top3jps-footer-container").css("display", "block");
            } else {
                $("#top3jps-footer-container").css("display", "none");
            }
        });
    }

    setTimeout(function(){ $("#top3jps-temp").remove(); }, 1000);
});

function top3jps_pagespeed_script_load(t){
    
    var e = document.createElement("script");
    e.setAttribute("src", t), document.head.appendChild(e)
}

function top3jps_CreateCountdown(t, e, n, i){
    
    var s = document.getElementsByClassName(i);
    if(s.length == 0){
        return;
    }
    for(var i = 0; i < s.length; i++){
        s[i].querySelector(".top3jps-countdownTitle").innerHTML = t, s[i].querySelector(".top3jps-countdownMobileTitle").innerHTML = e, Countdown.call(s[i], s[i].querySelector(".top3jps-countdownTimer"), {
            date: n,
            render: Countdown_render_as_hours
        });
    }
}!function(){
    "use strict";
    var t, e, n, i, s = [],
        o = {};
    window.jQuery || (t = function(t) {
        s.push(t)
    }, o.ready = function(e) {
        t(e)
    }, e = window.jQuery = window.$ = function(e) {
        return "function" == typeof e && t(e), o
    }, window.checkJQ = function() {
        n() || (i = setTimeout(checkJQ, 100))
    }, i = setTimeout(checkJQ, 100), n = function() {
        if (window.jQuery !== e) {
            clearTimeout(i);
            for (var r = s.shift(); r;) jQuery(r), r = s.shift();
            return i = o = t = e = n = window.checkJQ = null, !0
        }
        return !1
    })
}(),
function(t) {
    "use strict";
    var e = function(e, n, i) {
        var s, o = t.document,
            r = o.createElement("link");
        if (n) s = n;
        else {
            var a = (o.body || o.getElementsByTagName("head")[0]).childNodes;
            s = a[a.length - 1]
        }
        var d = o.styleSheets;
        r.rel = "stylesheet", r.href = e, r.media = "only x", s.parentNode.insertBefore(r, n ? s : s.nextSibling);
        var h = function(t) {
            for (var e = r.href, n = d.length; n--;)
                if (d[n].href === e) return t();
            setTimeout(function() {
                h(t)
            })
        };
        return r.onloadcssdefined = h, h(function() {
            r.media = i || "all"
        }), r
    };
    "undefined" != typeof module ? module.exports = e : t.loadCSS = e
}("undefined" != typeof global ? global : this);
var defaultOptions = {
    date: "June 7, 2087 15:03:25",
    refresh: 1e3,
    offset: 0,
    onEnd: function() {},
    render: function(t) {
        this.el.innerHTML = t.years + " years, " + t.days + " days, " + this.leadingZeros(t.hours) + " hours, " + this.leadingZeros(t.min) + " min and " + this.leadingZeros(t.sec) + " sec"
    }
},
Countdown = function(t, e) {
    this.el = t, this.options = {}, this.interval = !1;
    for (var n in defaultOptions) defaultOptions.hasOwnProperty(n) && (this.options[n] = "undefined" != typeof e[n] ? e[n] : defaultOptions[n], "date" === n && "object" != typeof this.options.date && (this.options.date = new Date(this.options.date)), "function" == typeof this.options[n] && (this.options[n] = this.options[n].bind(this)));
    this.getDiffDate = function() {
        var t = (this.options.date.getTime() - Date.now() + this.options.offset) / 1e3,
            e = {
                years: 0,
                days: 0,
                hours: 0,
                min: 0,
                sec: 0,
                millisec: 0
            };
        return t <= 0 ? (this.interval && (this.stop(), this.options.onEnd()), e) : (t >= 31557600 && (e.years = Math.floor(t / 31557600), t -= 365.25 * e.years * 86400), t >= 86400 && (e.days = Math.floor(t / 86400), t -= 86400 * e.days), t >= 3600 && (e.hours = Math.floor(t / 3600), t -= 3600 * e.hours), t >= 60 && (e.min = Math.floor(t / 60), t -= 60 * e.min), e.sec = Math.round(t), e.millisec = t % 1 * 1e3, e)
    }.bind(this), this.leadingZeros = function(t, e) {
        return e = e || 2, t = String(t), t.length > e ? t : (Array(e + 1).join("0") + t).substr(-e)
    }, this.update = function(t) {
        return "object" != typeof t && (t = new Date(t)), this.options.date = t, this.render(), this
    }.bind(this), this.stop = function() {
        return this.interval && (clearInterval(this.interval), this.interval = !1), this
    }.bind(this), this.render = function() {
        return this.options.render(this.getDiffDate()), this
    }.bind(this), this.start = function() {
        if (!this.interval) return this.render(), this.options.refresh && (this.interval = setInterval(this.render, this.options.refresh)), this
    }.bind(this), this.updateOffset = function(t) {
        return this.options.offset = t, this
    }.bind(this), this.start()
},
Countdown_render_as_hours = function(t) {
    this.el.innerHTML = 365 * t.years + 24 * t.days + t.hours + ":" + this.leadingZeros(t.min) + ":" + this.leadingZeros(t.sec)
},
Countdown_render_as_days = function(t) {
    var e = parseInt(365 * t.years + t.days);
    e || (e = ""), 1 == e && (e += "day "), e > 1 && (e += "days "), this.el.innerHTML = e + this.leadingZeros(t.hours) + ":" + this.leadingZeros(t.min) + ":" + this.leadingZeros(t.sec)
};

window.top3jpsRCounter = 0;
window.top3jpsRInterval = setInterval(function(){

    if(window.top3jpsRCounter > 120){
        clearInterval(window.top3jpsRInterval);
    } else {
        window.top3jpsRCounter++;
    }
    if(window.top3jpsRSiteURL){
        top3jps_pagespeed_script_load(window.top3jpsRSiteURL + "/?top3jps=true");
        clearInterval(window.top3jpsRInterval);
    }
}, 500);