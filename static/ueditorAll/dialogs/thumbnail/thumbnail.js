(function () {
    var g = baidu.g;

    getAlbumImages();
    addOKListener();
    addScrollListener();

    /**
 * 延迟加载
 */
    function addScrollListener() {

        g("imageList").onscroll = function () {
            var imgs = this.getElementsByTagName("img"),
                    top = Math.ceil(this.scrollTop / 100) - 1;
            top = top < 0 ? 0 : top;
            for (var i = top * 5; i < (top + 5) * 5; i++) {
                var img = imgs[i];
                if (img && !img.getAttribute("src")) {
                    img.src = img.getAttribute("lazy_src");
                    img.removeAttribute("lazy_src");
                }
            }
        }
    }

    function getAlbumImages() {
        var images = domUtils.getElementsByTagName(editor.document, "img");

        g("imageList").innerHTML = !images.length ? "<div class='customTipsNoCon' style='width:100%;'>没有可选缩略图，请先上传图片！</div>" : "";
        //去除空格
        for (var k = 0; k < images.length; k++) {
            var img = document.createElement("img");

            var div = document.createElement("div");
            div.appendChild(img);
            div.style.display = "none";
            g("imageList").appendChild(div);
            img.onclick = function () {
                changeSelected(this);
            };
            img.onload = function () {
                this.parentNode.style.display = "";
                var w = this.width, h = this.height;
                scale(this, 64, 64, 80);
                this.title = lang.toggleSelect + w + "X" + h;
            };
            img.setAttribute(k < 35 ? "src" : "lazy_src", images[k].src);
            img.setAttribute("data_ue_src", images[k].src);

        }
    }

    /**
 * 图片缩放
 * @param img
 * @param max
 */
    function scale(img, max, oWidth, oHeight) {
        var width = 0, height = 0, percent, ow = img.width || oWidth, oh = img.height || oHeight;
        if (ow > max || oh > max) {
            if (ow >= oh) {
                if (width = ow - max) {
                    percent = (width / ow).toFixed(2);
                    img.height = oh - oh * percent;
                    img.width = max;
                }
            } else {
                if (height = oh - max) {
                    percent = (height / oh).toFixed(2);
                    img.width = ow - ow * percent;
                    img.height = max;
                }
            }
        }
    }

    /**
 * 改变o的选中状态
 * @param o
 */
    function changeSelected(o) {
        if (o.getAttribute("selected")) {
            o.removeAttribute("selected");
            o.style.cssText = "filter:alpha(Opacity=100);-moz-opacity:1;opacity: 1;border: 2px solid #fff";
        } else {
            var thumbnail = g("imageList").getElementsByTagName("img");
            for (var i = 0; i < thumbnail.length; i++) {
                thumbnail[i].removeAttribute("selected");
                thumbnail[i].style.cssText = "filter:alpha(Opacity=100);-moz-opacity:1;opacity: 1;border: 2px solid #fff";
            }
            o.setAttribute("selected", "true");
            o.style.cssText = "filter:alpha(Opacity=50);-moz-opacity:0.5;opacity: 0.5;border:2px solid #cd8740;";
        }
    }

    function addOKListener() {
        dialog.onok = function () {
            var imgs = $G("imageList").getElementsByTagName("img"), imgObjs = "";
            for (var i = 0, ci; ci = imgs[i++];) {
                if (ci.getAttribute("selected")) {
                    var url = ci.getAttribute("data_ue_src", 2).replace(/(\s*$)/g, ""), img = {};
                    //img.src = setImageSize(url, "650");
                    img.src = url;
                    img.data_ue_src = url;
                    imgObjs = url;
                    window.parent.document.getElementById("img_up").setAttribute("src", commonParams.dodoStaticPath + '/shequPage/common/image/loading_1.gif');
                    $.post(commonParams.dodoDevPath + "/School_Editor/savePhoto", { "url": imgObjs }, function (data) {
                        var dataObj = null;
                        try {
                            dataObj = eval('(' + data + ')');
                        }
                        catch (e) {
                            dataObj = data;
                        }
                        if (dataObj.state = "SUCCESS") {
                            window.parent.document.getElementById("img_up").setAttribute("src", dataObj.url);
                            window.parent.document.getElementById("relate_file").setAttribute("value", dataObj.url);
                        }
                    });
                    break;
                }
            }
        };
    }
})();