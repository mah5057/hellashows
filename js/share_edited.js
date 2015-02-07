! function(e) {
    if ("object" == typeof exports && "undefined" != typeof module) module.exports = e();
    else if ("function" == typeof define && define.amd) define([], e);
    else {
        var f;
        "undefined" != typeof window ? f = window : "undefined" != typeof global ? f = global : "undefined" != typeof self && (f = self), f.Share = e()
    }
}(function() {
    var define, module, exports;

    function getStyles(config) {
        return "" + config.selector + "{width:92px;height:20px;-webkit-touch-callout:none;-khtml-user-select:none;-webkit-user-select:none;-moz-user-select:none;-ms-user-select:none;user-select:none}" + config.selector + " [class*=entypo-]:before{font-family:entypo,sans-serif}" + config.selector + " label{font-size:26px;cursor:pointer;margin:0;padding:5px 26px;border-radius:0px;background:#444;color:#282828;-webkit-transition:all .3s ease;transition:all .3s ease}" + config.selector + " label:hover{color:#fafafa}" + config.selector + " label span{text-transform:uppercase;font-size:.9em;font-weight:400;-webkit-font-smoothing:antialiased;padding-left:6px}" + config.selector + " .social{opacity:0;-webkit-transition:all .4s ease;transition:all .4s ease;margin-left:-15px;visibility:hidden}" + config.selector + " .social.top{-webkit-transform-origin:0 0;-ms-transform-origin:0 0;transform-origin:0 0;margin-top:-80px}" + config.selector + " .social.bottom{-webkit-transform-origin:0 0;-ms-transform-origin:0 0;transform-origin:0 0;margin-top:5px}" + config.selector + " .social.middle{margin-top:-42px}" + config.selector + " .social.middle.right{-webkit-transform-origin:5% 50%;-ms-transform-origin:5% 50%;transform-origin:5% 50%;margin-left:105px}" + config.selector + " .social.middle.left{-webkit-transform-origin:5% 50%;-ms-transform-origin:5% 50%;transform-origin:5% 50%}" + config.selector + " .social.right{margin-left:14px}" + config.selector + " .social.load{-webkit-transition:none!important;transition:none!important}" + config.selector + " .social.networks-1{width:162px}" + config.selector + " .social.networks-1.center," + config.selector + " .social.networks-1.left{margin-left:14px}" + config.selector + " .social.networks-1.middle.left{margin-left:-70px}" + config.selector + " .social.networks-1 ul{width:162px}" + config.selector + " .social.networks-2{width:120px}" + config.selector + " .social.networks-2.center{margin-left:-13px}" + config.selector + " .social.networks-2.left{margin-left:-44px}" + config.selector + " .social.networks-2.middle.left{margin-left:-130px}" + config.selector + " .social.networks-2 ul{width:120px}" + config.selector + " .social.networks-3{width:180px}" + config.selector + " .social.networks-3.center{margin-left:-45px}" + config.selector + " .social.networks-3.left{margin-left:-102px}" + config.selector + " .social.networks-3.middle.left{margin-left:-190px}" + config.selector + " .social.networks-3 ul{width:180px}" + config.selector + " .social.networks-4{width:240px}" + config.selector + " .social.networks-4.center{margin-left:-75px}" + config.selector + " .social.networks-4.left{margin-left:162px}" + config.selector + " .social.networks-4.middle.left{margin-left:-250px}" + config.selector + " .social.networks-4 ul{width:240px}" + config.selector + " .social.networks-5{width:810px}" + config.selector + " .social.networks-5.center{margin-left:-105px}" + config.selector + " .social.networks-5.left{margin-left:-225px}" + config.selector + " .social.networks-5.middle.left{margin-left:-320px}" + config.selector + " .social.networks-5 ul{width:810px}" + config.selector + " .social.active{opacity:1;-webkit-transition:all .4s ease;transition:all .4s ease;visibility:visible}" + config.selector + " .social.active.top{-webkit-transform:scale(1) translateY(-10px);-ms-transform:scale(1) translateY(-10px);transform:scale(1) translateY(-10px)}" + config.selector + " .social.active.bottom{-webkit-transform:scale(1) translateY(15px);-ms-transform:scale(1) translateY(15px);transform:scale(1) translateY(15px)}" + config.selector + " .social.active.middle.right{-webkit-transform:scale(1) translateX(45px);-ms-transform:scale(1) translateX(45px);transform:scale(1) translateX(45px)}" + config.selector + " .social.active.middle.left{-webkit-transform:scale(1) translateX(-10px);-ms-transform:scale(1) translateX(-10px);transform:scale(1) translateX(-10px)}" + config.selector + " .social ul{position:relative;left:0;right:0;height:46px;color:#fafafa;margin:auto;padding:0;list-style:none}" + config.selector + " .social ul li{font-size:20px;cursor:pointer;width:162px;margin:0;padding:10px 0px 19px 0px;text-align:center;float:left;display:none;height:22px;position:relative;top:-2.5px;z-index:2;-webkit-box-sizing:content-box;-moz-box-sizing:content-box;box-sizing:content-box;-webkit-transition:all .3s ease;transition:all .3s ease}" + config.selector + " .social ul li:hover{color:rgba(0,0,0,.5)}" + config.selector + " .social li[class*=facebook]{background:#3b5998;display:" + config.networks.facebook.display + "}" + config.selector + " .social li[class*=twitter]{background:#6cdfea;display:" + config.networks.twitter.display + "}" + config.selector + " .social li[class*=gplus]{background:#e34429;display:" + config.networks.google_plus.display + "}" + config.selector + " .social li[class*=pinterest]{background:#c5282f;display:" + config.networks.pinterest.display + "}" + config.selector + " .social li[class*=paper-plane]{background:#42c5b0;display:" + config.networks.email.display + "}"
    };
    var ShareUtils;
    "classList" in document.documentElement || !Object.defineProperty || "undefined" == typeof HTMLElement || Object.defineProperty(HTMLElement.prototype, "classList", {
        get: function() {
            var t, e, o;
            return o = function(t) {
                return function(o) {
                    var n, i;
                    n = e.className.split(/\s+/), i = n.indexOf(o), t(n, i, o), e.className = n.join(" ")
                }
            }, e = this, t = {
                add: o(function(t, e, o) {~
                    e || t.push(o)
                }),
                remove: o(function(t, e) {~
                    e && t.splice(e, 1)
                }),
                toggle: o(function(t, e, o) {~
                    e ? t.splice(e, 1) : t.push(o)
                }),
                contains: function(t) {
                    return !!~e.className.split(/\s+/).indexOf(t)
                },
                item: function(t) {
                    return e.className.split(/\s+/)[t] || null
                }
            }, Object.defineProperty(t, "length", {
                get: function() {
                    return e.className.split(/\s+/).length
                }
            }), t
        }
    }), String.prototype.to_rfc3986 = function() {
        var t;
        return t = encodeURIComponent(this), t.replace(/[!'()*]/g, function(t) {
            return "%" + t.charCodeAt(0).toString(16)
        })
    }, ShareUtils = function() {
        function t() {}
        return t.prototype.extend = function(t, e, o) {
            var n, i;
            for (i in e) n = void 0 !== t[i], n && "object" == typeof e[i] ? this.extend(t[i], e[i], o) : (o || !n) && (t[i] = e[i])
        }, t.prototype.hide = function(t) {
            return t.style.display = "none"
        }, t.prototype.show = function(t) {
            return t.style.display = "block"
        }, t.prototype.has_class = function(t, e) {
            return t.classList.contains(e)
        }, t.prototype.add_class = function(t, e) {
            return t.classList.add(e)
        }, t.prototype.remove_class = function(t, e) {
            return t.classList.remove(e)
        }, t.prototype.is_encoded = function(t) {
            return t = t.to_rfc3986(), decodeURIComponent(t) !== t
        }, t.prototype.encode = function(t) {
            return "undefined" == typeof t || this.is_encoded(t) ? t : t.to_rfc3986()
        }, t.prototype.popup = function(t, e) {
            var o, n, i, r;
            return null == e && (e = {}), n = {
                width: 500,
                height: 350
            }, n.top = screen.height / 2 - n.height / 2, n.left = screen.width / 2 - n.width / 2, i = function() {
                var t;
                t = [];
                for (o in e) r = e[o], t.push("" + o + "=" + this.encode(r));
                return t
            }.call(this).join("&"), i && (i = "?" + i), window.open(t + i, "targetWindow", "toolbar=no,location=no,status=no,menubar=no,scrollbars=yes,resizable=yes,left=" + n.left + ",top=" + n.top + ",width=" + n.width + ",height=" + n.height)
        }, t
    }();
    var Share, __hasProp = {}.hasOwnProperty,
        __extends = function(t, e) {
            function o() {
                this.constructor = t
            }
            for (var n in e) __hasProp.call(e, n) && (t[n] = e[n]);
            return o.prototype = e.prototype, t.prototype = new o, t.__super__ = e.prototype, t
        };
    Share = function(t) {
        function e(t, e) {
            return this.element = t, this.el = {
                head: document.getElementsByTagName("head")[0],
                body: document.getElementsByTagName("body")[0]
            }, this.config = {
                enabled_networks: 0,
                protocol: -1 === ["http", "https"].indexOf(window.location.href.split(":")[0]) ? "https://" : "//",
                url: window.location.href,
                caption: null,
                title: this.default_title(),
                image: this.default_image(),
                description: this.default_description(),
                ui: {
                    flyout: "top center",
                    button_text: "Share",
                    button_font: !0,
                    icon_font: !0
                },
                networks: {
                    google_plus: {
                        enabled: !0,
                        url: null
                    },
                    twitter: {
                        enabled: !0,
                        url: null,
                        description: null
                    },
                    facebook: {
                        enabled: !0,
                        load_sdk: !0,
                        url: null,
                        app_id: null,
                        title: null,
                        caption: null,
                        description: null,
                        image: null
                    },
                    pinterest: {
                        enabled: !0,
                        url: null,
                        image: null,
                        description: null
                    },
                    email: {
                        enabled: !0,
                        title: null,
                        description: null
                    }
                }
            }, this.setup(t, e), this
        }
        return __extends(e, t), e.prototype.setup = function(t, e) {
            var o, n, i, r, s;
            for (i = document.querySelectorAll(t), this.extend(this.config, e, !0), this.set_global_configuration(), this.normalize_network_configuration(), this.config.ui.icon_font && this.inject_icons(), this.config.ui.button_font, this.config.networks.facebook.enabled && this.config.networks.facebook.load_sdk && this.inject_facebook_sdk(), o = r = 0, s = i.length; s > r; o = ++r) n = i[o], this.setup_instance(t, o)
        }, e.prototype.setup_instance = function(t, e) {
            var o, n, i, r, s, c, l, a, p = this;
            for (n = document.querySelectorAll(t)[e], this.hide(n), this.add_class(n, "sharer-" + e), n = document.querySelectorAll(t)[e], this.inject_css(n), this.inject_html(n), this.show(n), i = n.getElementsByTagName("label")[0], o = n.getElementsByClassName("social")[0], s = n.getElementsByTagName("li"), this.add_class(o, "networks-" + this.config.enabled_networks), i.addEventListener("click", function() {
                return p.event_toggle(o)
            }), p = this, a = [], e = c = 0, l = s.length; l > c; e = ++c) r = s[e], a.push(r.addEventListener("click", function() {
                return p.event_network(n, this), p.event_close(o)
            }));
            return a
        }, e.prototype.event_toggle = function(t) {
            return this.has_class(t, "active") ? this.event_close(t) : this.event_open(t)
        }, e.prototype.event_open = function(t) {
            return this.has_class(t, "load") && this.remove_class(t, "load"), this.add_class(t, "active")
        }, e.prototype.event_close = function(t) {
            return this.remove_class(t, "active")
        }, e.prototype.event_network = function(t, e) {
            var o;
            return o = e.getAttribute("data-network"), this.hook("before", o, t), this["network_" + o](), this.hook("after", o, t)
        }, e.prototype.open = function() {
            return this["public"]("open")
        }, e.prototype.close = function() {
            return this["public"]("close")
        }, e.prototype.toggle = function() {
            return this["public"]("toggle")
        }, e.prototype["public"] = function(t) {
            var e, o, n, i, r, s, c;
            for (s = document.querySelectorAll(this.element), c = [], o = i = 0, r = s.length; r > i; o = ++i) n = s[o], e = n.getElementsByClassName("social")[0], c.push(this["event_" + t](e));
            return c
        }, e.prototype.network_facebook = function() {
            return this.config.networks.facebook.load_sdk ? window.FB ? FB.ui({
                method: "feed",
                name: this.config.networks.facebook.title,
                link: this.config.networks.facebook.url,
                picture: this.config.networks.facebook.image,
                caption: this.config.networks.facebook.caption,
                description: this.config.networks.facebook.description
            }) : console.error("The Facebook JS SDK hasn't loaded yet.") : this.popup("https://www.facebook.com/sharer/sharer.php", {
                u: this.config.networks.facebook.url
            })
        }, e.prototype.network_twitter = function() {
            return this.popup("https://twitter.com/intent/tweet", {
                text: this.config.networks.twitter.description,
                url: this.config.networks.twitter.url
            })
        }, e.prototype.network_google_plus = function() {
            return this.popup("https://plus.google.com/share", {
                url: this.config.networks.google_plus.url
            })
        }, e.prototype.network_pinterest = function() {
            return this.popup("https://www.pinterest.com/pin/create/button", {
                url: this.config.networks.pinterest.url,
                media: this.config.networks.pinterest.image,
                description: this.config.networks.pinterest.description
            })
        }, e.prototype.network_email = function() {
            return this.popup("mailto:", {
                subject: this.config.networks.email.title,
                body: this.config.networks.email.description
            })
        }, e.prototype.inject_icons = function() {
            return this.inject_stylesheet("https://www.sharebutton.co/fonts/v2/entypo.min.css")
        }, e.prototype.inject_fonts = function() {
            return this.inject_stylesheet("http://fonts.googleapis.com/css?family=Oswald:400&text=" + this.config.ui.button_text)
        }, e.prototype.inject_stylesheet = function(t) {
            var e;
            return this.el.head.querySelector('link[href="' + t + '"]') ? void 0 : (e = document.createElement("link"), e.setAttribute("rel", "stylesheet"), e.setAttribute("href", t), this.el.head.appendChild(e))
        }, e.prototype.inject_css = function(t) {
            var e, o, n, i;
            return n = "." + t.getAttribute("class").split(" ").join("."), this.el.head.querySelector("meta[name='sharer" + n + "']") ? void 0 : (this.config.selector = n, e = getStyles(this.config), i = document.createElement("style"), i.type = "text/css", i.styleSheet ? i.styleSheet.cssText = e : i.appendChild(document.createTextNode(e)), this.el.head.appendChild(i), delete this.config.selector, o = document.createElement("meta"), o.setAttribute("name", "sharer" + n), this.el.head.appendChild(o))
        }, e.prototype.inject_html = function(t) {
            return t.innerHTML = "<label class='entypo-export'><span>" + this.config.ui.button_text + "</span></label><div class='social load " + this.config.ui.flyout + "'><ul><li class='entypo-pinterest' data-network='pinterest'></li><li class='entypo-twitter' data-network='twitter'></li><li class='entypo-facebook' data-network='facebook'></li><li class='entypo-gplus' data-network='google_plus'></li><li class='entypo-paper-plane' data-network='email'></li></ul></div>"
        }, e.prototype.inject_facebook_sdk = function() {
            var t, e;
            return window.FB || !this.config.networks.facebook.app_id || this.el.body.querySelector("#fb-root") ? void 0 : (e = document.createElement("script"), e.text = "window.fbAsyncInit=function(){FB.init({appId:'" + this.config.networks.facebook.app_id + "',status:true,xfbml:true})};(function(e,t,n){var r,i=e.getElementsByTagName(t)[0];if(e.getElementById(n)){return}r=e.createElement(t);r.id=n;r.src='" + this.config.protocol + "connect.facebook.net/en_US/all.js';i.parentNode.insertBefore(r,i)})(document,'script','facebook-jssdk')", t = document.createElement("div"), t.id = "fb-root", this.el.body.appendChild(t), this.el.body.appendChild(e))
        }, e.prototype.hook = function(t, e, o) {
            var n, i;
            n = this.config.networks[e][t], "function" == typeof n && (i = n.call(this.config.networks[e], o), void 0 !== i && (i = this.normalize_filter_config_updates(i), this.extend(this.config.networks[e], i, !0), this.normalize_network_configuration()))
        }, e.prototype.default_title = function() {
            var t;
            return (t = document.querySelector('meta[property="og:title"]') || document.querySelector('meta[name="twitter:title"]')) ? t.getAttribute("content") : (t = document.querySelector("title")) ? t.innerText : void 0
        }, e.prototype.default_image = function() {
            var t;
            return (t = document.querySelector('meta[property="og:image"]') || document.querySelector('meta[name="twitter:image"]')) ? t.getAttribute("content") : void 0
        }, e.prototype.default_description = function() {
            var t;
            return (t = document.querySelector('meta[property="og:description"]') || document.querySelector('meta[name="twitter:description"]') || document.querySelector('meta[name="description"]')) ? t.getAttribute("content") : ""
        }, e.prototype.set_global_configuration = function() {
            var t, e, o, n, i, r;
            i = this.config.networks, r = [];
            for (e in i) {
                n = i[e];
                for (o in n) null == this.config.networks[e][o] && (this.config.networks[e][o] = this.config[o]);
                this.config.networks[e].enabled ? (t = "block", this.config.enabled_networks += 1) : t = "none", r.push(this.config.networks[e].display = t)
            }
            return r
        }, e.prototype.normalize_network_configuration = function() {
            return this.config.networks.facebook.app_id || (this.config.networks.facebook.load_sdk = !1), this.is_encoded(this.config.networks.twitter.description) || (this.config.networks.twitter.description = encodeURIComponent(this.config.networks.twitter.description)), "number" == typeof this.config.networks.facebook.app_id ? this.config.networks.facebook.app_id = this.config.networks.facebook.app_id.toString() : void 0
        }, e.prototype.normalize_filter_config_updates = function(t) {
            return this.config.networks.facebook.app_id !== t.app_id && (console.warn("You are unable to change the Facebook app_id after the button has been initialized. Please update your Facebook filters accordingly."), delete t.app_id), this.config.networks.facebook.load_sdk !== t.load_sdk && (console.warn("You are unable to change the Facebook load_sdk option after the button has been initialized. Please update your Facebook filters accordingly."), delete t.app_id), t
        }, e
    }(ShareUtils);
    return Share;
});
