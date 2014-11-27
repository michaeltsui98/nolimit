﻿$.fn.extend({
    mousewheel: function (Func) {
        return this.each(function () {
            var _self = this;
            _self.D = 0;
            if ($.browser.msie || $.browser.safari) {
                _self.onmousewheel = function () { _self.D = event.wheelDelta; event.returnValue = false; Func && Func.call(_self); };
            } else {
                _self.addEventListener("DOMMouseScroll", function (e) {
                    _self.D = e.detail > 0 ? -1 : 1;
                    e.preventDefault();
                    Func && Func.call(_self);
                }, false);
            }
        });
    }
});
$.fn.extend({
    jscroll: function (j) {
        var color = "#d1d1d1";
        return this.each(function () {
            j = j || {};
            j.Bar = j.Bar || {};
            j.Btn = j.Btn || {};
            j.Bar.Bg = j.Bar.Bg || {};
            j.Bar.Bd = j.Bar.Bd || {};
            j.Btn.uBg = j.Btn.uBg || {};
            j.Btn.dBg = j.Btn.dBg || {};
            var jun = {
                W: "10px",
                BgUrl: "",
                Bg: "#efefef",
                Bar: {
                    Pos: "up",
                    Bd: { Out: "#b5b5b5", Hover: "#ccc" },
                    Bg: { Out: "#fff", Hover: "#fff", Focus: color }
                },
                Btn: {
                    btn: true,
                    uBg: { Out: "#ccc", Hover: "#fff", Focus: color },
                    dBg: { Out: "#ccc", Hover: "#fff", Focus: color }
                },
                Fn: function () { }
            };
            j.W = j.W || jun.W;
            j.BgUrl = j.BgUrl || jun.BgUrl;
            j.Bg = j.Bg || jun.Bg;
            j.Bar.Pos = j.Bar.Pos || jun.Bar.Pos;
            j.Bar.Bd.Out = j.Bar.Bd.Out || jun.Bar.Bd.Out;
            j.Bar.Bd.Hover = j.Bar.Bd.Hover || jun.Bar.Bd.Hover;
            j.Bar.Bg.Out = j.Bar.Bg.Out || jun.Bar.Bg.Out;
            j.Bar.Bg.Hover = j.Bar.Bg.Hover || jun.Bar.Bg.Hover;
            j.Bar.Bg.Focus = j.Bar.Bg.Focus || jun.Bar.Bg.Focus;
            j.Btn.btn = j.Btn.btn != undefined ? j.Btn.btn : jun.Btn.btn;
            j.Btn.uBg.Out = j.Btn.uBg.Out || jun.Btn.uBg.Out;
            j.Btn.uBg.Hover = j.Btn.uBg.Hover || jun.Btn.uBg.Hover;
            j.Btn.uBg.Focus = j.Btn.uBg.Focus || jun.Btn.uBg.Focus;
            j.Btn.dBg.Out = j.Btn.dBg.Out || jun.Btn.dBg.Out;
            j.Btn.dBg.Hover = j.Btn.dBg.Hover || jun.Btn.dBg.Hover;
            j.Btn.dBg.Focus = j.Btn.dBg.Focus || jun.Btn.dBg.Focus;
            j.Fn = j.Fn || jun.Fn;
            var _self = this;
            var Stime, Sp = 0, Isup = 0;
            $(_self).css({ overflow: "hidden", position: "relative" });
            var dw = $(_self).width(), dh = $(_self).height() - 1;
            var sw = j.W ? parseInt(j.W) : 21;
            var sl = dw - sw;
            var bw = 0;
            if ($(_self).children(".jscroll-c").height() == null) {
                $(_self).wrapInner("<div class='jscroll-c' style='top:0px;z-index:inherit;zoom:1;position:relative'></div>");
                $(_self).children(".jscroll-c").prepend("<div style='height:0px;overflow:hidden'></div>");
                $(_self).append("<div class='jscroll-e' unselectable='on' style=' height:100%;top:0px;right:0;-moz-user-select:none;position:absolute;overflow:hidden;z-index:10000;border-radius:8px;padding:0;display:none;'><div class='jscroll-h'  unselectable='on' style='background:green;position:absolute;left:0;-moz-user-select:none;border:1px solid;border-radius:8px;padding:0;'></div></div>");//<div class='jscroll-u' style='position:absolute;top:0px;width:100%;left:0;background:blue;overflow:hidden'></div><div class='jscroll-d' style='position:absolute;bottom:0px;width:100%;left:0;background:blue;overflow:hidden'></div>
            }
            var jscrollc = $(_self).children(".jscroll-c");
            var jscrolle = $(_self).children(".jscroll-e");
            var jscrollh = jscrolle.children(".jscroll-h");
            var scrollH = false;
            //var jscrollu = jscrolle.children(".jscroll-u");
            //var jscrolld = jscrolle.children(".jscroll-d");
            if ($.browser.msie) {
                document.execCommand("BackgroundImageCache", false, true);
            }
            jscrollc.css({ "padding-right": sw });
            jscrolle.css({ width: sw, background: j.Bg, "background-image": j.BgUrl });
            jscrollh.css({ top: bw, background: j.Bar.Bg.Out, "background-image": j.BgUrl, "border-color": j.Bar.Bd.Out, width: sw - 2 });
            //jscrollu.css({ height: bw, background: j.Btn.uBg.Out, "background-image": j.BgUrl });
            //jscrolld.css({ height: bw, background: j.Btn.dBg.Out, "background-image": j.BgUrl });
            jscrollh.hover(function () {
                if (Isup == 0) {
                    $(this).css({ background: j.Bar.Bg.Hover, "background-image": j.BgUrl, "border-color": j.Bar.Bd.Hover });
                }
            },
            function () {
                if (Isup == 0) {
                    $(this).css({ background: j.Bar.Bg.Out, "background-image": j.BgUrl, "border-color": j.Bar.Bd.Out });
                }
            });
            //jscrollu.hover(function () { if (Isup == 0) $(this).css({ background: j.Btn.uBg.Hover, "background-image": j.BgUrl }) }, function () { if (Isup == 0) $(this).css({ background: j.Btn.uBg.Out, "background-image": j.BgUrl }) })
            //jscrolld.hover(function () { if (Isup == 0) $(this).css({ background: j.Btn.dBg.Hover, "background-image": j.BgUrl }) }, function () { if (Isup == 0) $(this).css({ background: j.Btn.dBg.Out, "background-image": j.BgUrl }) })
            var sch = jscrollc.height();
            var sh = (dh - 2 * bw) * dh / sch;
            if (sh < 10) {
                sh = 10;
            }
            var wh = sh / 6;
            var curT = 0, allowS = false;
            jscrollh.height(sh);
            if (sch <= dh) {
                jscrollc.css({ padding: 0 }); jscrolle.css({ display: "none" })
            }
            else {
                allowS = true;
            }
            if (j.Bar.Pos != "up") {
                curT = dh - sh - bw;
                setT();
            }
            jscrollh.bind("mousedown", function (e) {
                scrollH = true;
                j['Fn'] && j['Fn'].call(_self);
                Isup = 1;
                jscrollh.css({ background: j.Bar.Bg.Focus, "background-image": j.BgUrl });
                var pageY = e.pageY, t = parseInt($(this).css("top"));
                $(document).mousemove(function (e2) {
                    if (scrollH) {
                        curT = t + e2.pageY - pageY;
                        setT();
                    }
                });
                $(document).mouseup(function () {
                    Isup = 0;
                    jscrollh.css({ background: j.Bar.Bg.Out, "background-image": j.BgUrl, "border-color": j.Bar.Bd.Out });
                    scrollH = false;
                    //$(document).unbind();
                });
                return false;
            });
            _self.timeSetT = function (d) {
                var self = this;
                if (d == "u") { curT -= wh; } else { curT += wh; }
                setT();
                Sp += 2;
                //var t = 500 - Sp * 50;
                //if (t <= 0) { t = 0 };
                Stime = setTimeout(function () {
                    self.timeSetT(d);
                }, 100);
            };
            jscrolle.bind("mousedown", function (e) {
                j['Fn'] && j['Fn'].call(_self);
                curT = curT + e.pageY - jscrollh.offset().top - sh / 2;
                asetT();
                return false;
            });
            function asetT() {
                if (curT < bw) { curT = bw; }
                if (curT > dh - sh - bw) { curT = dh - sh - bw; }
                jscrollh.stop().animate({ top: curT }, 100);
                var scT = -((curT - bw) * sch / (dh - 2 * bw));
                jscrollc.stop().animate({ top: scT }, 1000);
            }
            function setT() {
                if (curT < bw) { curT = bw; }
                if (curT > dh - sh - bw) { curT = dh - sh - bw; }
                jscrollh.css({ top: curT });
                var scT = -((curT - bw) * sch / (dh - 2 * bw));
                jscrollc.css({ top: scT });
            }
            $(_self).mousewheel(function () {
                if (allowS != true) return;
                j['Fn'] && j['Fn'].call(_self);
                if (this.D > 0) { curT -= wh; } else { curT += wh; };
                setT();
            });
            $(_self).mouseover(function () {
                $(this).find("div.jscroll-e").css("display", "block");
            });
            $(_self).mouseout(function () {
                $(this).find("div.jscroll-e").css("display", "none");
            });
        });
    }
});