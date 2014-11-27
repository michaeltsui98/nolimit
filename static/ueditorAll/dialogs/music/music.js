/**
 * Created by JetBrains PhpStorm.
 * User: taoqili
 * Date: 12-2-20
 * Time: 上午11:19
 * To change this template use File | Settings | File Templates.
 */
var video = {};
var music = {};
(function () {
    video.init = function () {
        switchTab("videoTab");
        //createAlignButton(["videoFloat"]);
        //createAlignButton(["videoUploadFloat"]);
        //addUrlChangeListener($G("videoUrl"));
        addOkListener();
        //addSearchListener();

        //编辑视频时初始化相关信息
        (function () {
            var img = editor.selection.getRange().getClosedNode(), url;
            if (img && img.className == "edui-faked-video") {
                $G("videoUrl").value = url = img.getAttribute("_url");
                $G("videoWidth").value = img.width;
                $G("videoHeight").value = img.height;
                var align = domUtils.getComputedStyle(img, "float"),
                    parentAlign = domUtils.getComputedStyle(img.parentNode, "text-align");
                updateAlignButton(parentAlign === "center" ? "center" : align);
            }
            createPreviewVideo(url);
        })();
    };
    /**
     * 监听确认和取消两个按钮事件，用户执行插入或者清空正在播放的视频实例操作
     */
    function addOkListener() {
        dialog.onok = function () {
            //$G("preview").innerHTML = "";
            var currentTab = findFocus("tabHeads", "tabSrc");
            switch (currentTab) {
                case "video":
                    return music.exec();
                    //return insertSingle();
                    break;
                case "videoSearch":
                    return insertSearch("searchList");
                    break;
                case "videoUpload":
                    if (videoParams.fid) {
                        setFileShare(videoParams.fid);
                    }
                    return insertUpload();
                    break;
            }
        };
        dialog.oncancel = function () {
            $G("preview").innerHTML = "";
        };
    }

    function selectTxt(node) {
        if (node.select) {
            node.select();
        } else {
            var r = node.createTextRange && node.createTextRange();
            r.select();
        }
    }

    /**
     * 依据传入的align值更新按钮信息
     * @param align
     */
    function updateAlignButton(align) {
        var aligns = $G("videoFloat").children;
        for (var i = 0, ci; ci = aligns[i++];) {
            if (ci.getAttribute("name") == align) {
                if (ci.className != "focus") {
                    ci.className = "focus";
                }
            } else {
                if (ci.className == "focus") {
                    ci.className = "";
                }
            }
        }
    }

    /**
     * 将单个视频信息插入编辑器中
     */
    function insertSingle() {
        var width = $G("videoWidth"),
            height = $G("videoHeight"),
            url = $G('videoUrl').value,
            align = findFocus("videoFloat", "name");
        if (!url) return false;
        if (!checkNum([width, height])) return false;
        editor.execCommand('music', {
            url: convert_url(url),
            width: width.value,
            height: height.value,
            align: align
        }, false);
    }

    /**
     * 将元素id下的所有代表视频的图片插入编辑器中
     * @param id
     */
    function insertSearch(id) {
        var videoObjs = [];
        var checkId = $("ul[check='yes']");
        for (var i = 0; i < checkId.length; i++) {
            var fileId = checkId[i].getAttribute("file_id");
            if (checkId[i].getAttribute("is_share") > 0) {
                videoObjs.push({
                    url: editor.options.viewVideo + "/disk_id/" + videoParams.diskId + "/fid/" + fileId,
                    width: 250,
                    height: 50,
                    align: "none"
                });
                //function shareFile(data) {
                //    if (data) {
                //        videoObjs.push({
                //            url: "http://dev.dodoedu.com/Disk/viewVideo/disk_id/" + videoParams.diskId + "/fid/" + checkId,
                //            width: 420,
                //            height: 280,
                //            align: "none"
                //        });
                //    }
                //    else {
                //        alert("error");
                //    }
                //}
                //AjaxForJson(commonParams.dodoDevPath + "/Disk/attachFileShare", "fid=" + fileId + "&disk_id=" + videoParams.diskId, shareFile, null);
            }
            else {
                if (checkId[i].getAttribute("file_id")) {
                    setFileShare(checkId[i].getAttribute("file_id"));
                }
                videoObjs.push({
                    url: editor.options.viewVideo + "/disk_id/" + videoParams.diskId + "/fid/" + fileId,
                    width: 250,
                    height: 50,
                    align: "none"
                });
            }
        }
        editor.execCommand('music', videoObjs, true);
    }

    /**
    * 将本地上传视频信息插入编辑器中
    */
    function insertUpload() {
        var width = 250,
            height = 50,
            //url = $G('videoUrl').value,
            url = filesList[0],
            align = "none";
        if (!url) return false;
        if (!checkNum([width, height])) return false;
        var musicObjs = new Array();
        for (var i = 0; i < filesList.length; i++) {
            musicObjs.push({
                url: convert_url(filesList[i]),
                width: 250,
                height: 50,
                align: "none"
            })
        }
        editor.execCommand('music', musicObjs, true);
    }

    /**
     * 找到id下具有focus类的节点并返回该节点下的某个属性
     * @param id
     * @param returnProperty
     */
    function findFocus(id, returnProperty) {
        var tabs = $G(id).children,
                property;
        for (var i = 0, ci; ci = tabs[i++];) {
            if (ci.className == "focus") {
                property = ci.getAttribute(returnProperty);
                break;
            }
        }
        return property;
    }
    function convert_url(s) {
        return s.replace(/http:\/\/www\.tudou\.com\/programs\/view\/([\w\-]+)\/?/i, "http://www.tudou.com/v/$1")
            .replace(/http:\/\/www\.youtube\.com\/watch\?v=([\w\-]+)/i, "http://www.youtube.com/v/$1")
            .replace(/http:\/\/v\.youku\.com\/v_show\/id_([\w\-=]+)\.html/i, "http://player.youku.com/player.php/sid/$1")
            .replace(/http:\/\/www\.56\.com\/u\d+\/v_([\w\-]+)\.html/i, "http://player.56.com/v_$1.swf")
            .replace(/http:\/\/www.56.com\/w\d+\/play_album\-aid\-\d+_vid\-([^.]+)\.html/i, "http://player.56.com/v_$1.swf")
            .replace(/http:\/\/v\.ku6\.com\/.+\/([^.]+)\.html/i, "http://player.ku6.com/refer/$1/v.swf");
    }

    /**
      * 检测传入的所有input框中输入的长宽是否是正数
      * @param nodes input框集合，
      */
    function checkNum(nodes) {
        for (var i = 0, ci; ci = nodes[i++];) {
            var value = ci.value;
            if (!isNumber(value) && value) {
                alert(lang.numError);
                ci.value = "";
                ci.focus();
                return false;
            }
        }
        return true;
    }

    /**
     * 数字判断
     * @param value
     */
    function isNumber(value) {
        return /(0|^[1-9]\d*$)/.test(value);
    }

    /**
     * tab切换
     * @param tabParentId
     * @param keepFocus   当此值为真时，切换按钮上会保留focus的样式
     */
    function switchTab(tabParentId, keepFocus) {
        var tabElements = $G(tabParentId).children,
                tabHeads = tabElements[0].children,
                tabBodys = tabElements[1].children,
                maskVideoIframe = $G("maskVideoIframe");
        for (var i = 0, length = tabHeads.length; i < length; i++) {
            var head = tabHeads[i];
            domUtils.on(head, "click", function () {
                //head样式更改
                for (var k = 0, len = tabHeads.length; k < len; k++) {
                    if (!keepFocus) tabHeads[k].className = "";
                }
                this.className = "focus";
                //body显隐
                var tabSrc = this.getAttribute("tabSrc");
                for (var j = 0, length = tabBodys.length; j < length; j++) {
                    var body = tabBodys[j],
                        id = body.getAttribute("id");

                    if (id == tabSrc) {
                        body.style.zIndex = "200";
                        if (id == "video") {
                            maskVideoIframe.style.display = "";
                            music = new Music;
                            //selectTxt($G("videoUrl"));
                        }
                        if (id == "videoSearch") {
                            maskVideoIframe.style.display = "";
                            getVideoFiles();
                        }
                        if (id == "videoUpload") {
                            maskVideoIframe.style.display = "none";
                            //initUploadFlash();
                        }

                    } else {
                        body.style.zIndex = "1";
                    }
                }
            });
        }
    }
    /**
      * 创建图片浮动选择按钮
      * @param ids
      */
    function createAlignButton(ids) {
        for (var i = 0, ci; ci = ids[i++];) {
            var floatContainer = $G(ci),
                    nameMaps = { "none": lang['default'], "left": lang.floatLeft, "right": lang.floatRight, "center": lang.block };
            for (var j in nameMaps) {
                var div = document.createElement("div");
                div.setAttribute("name", j);
                if (j == "none") div.className = "focus";
                div.style.cssText = "background:url(images/" + j + "_focus.jpg);";
                div.setAttribute("title", nameMaps[j]);
                floatContainer.appendChild(div);
            }
            switchSelect(ci);
        }
    }

    /**
     * 选择切换
     * @param selectParentId
     */
    function switchSelect(selectParentId) {
        var selects = $G(selectParentId).children;
        for (var i = 0, ci; ci = selects[i++];) {
            domUtils.on(ci, "click", function () {
                for (var j = 0, cj; cj = selects[j++];) {
                    cj.className = "";
                    cj.removeAttribute && cj.removeAttribute("class");
                }
                this.className = "focus";
            })
        }
    }

    /**
     * 监听url改变事件
     * @param url
     */
    function addUrlChangeListener(url) {
        if (browser.ie) {
            url.onpropertychange = function () {
                createPreviewVideo(this.value);
            }
        } else {
            url.addEventListener("input", function () {
                createPreviewVideo(this.value);
            }, false);
        }
    }

    /**
     * 根据url生成视频预览
     * @param url
     */
    function createPreviewVideo(url) {

        if (!url) return;
        var matches = url.match(/youtu.be\/(\w+)$/) || url.match(/youtube\.com\/watch\?v=(\w+)/) || url.match(/youtube.com\/v\/(\w+)/),
            youku = url.match(/youku\.com\/v_show\/id_(\w+)/),
            youkuPlay = /player\.youku\.com/ig.test(url);
        if (!youkuPlay) {
            if (matches) {
                url = "https://www.youtube.com/v/" + matches[1] + "?version=3&feature=player_embedded";
            } else if (youku) {
                url = "http://player.youku.com/player.php/sid/" + youku[1] + "/v.swf"
            } else if (!endWith(url, [".swf", ".flv", ".wmv"])) {
                $G("preview").innerHTML = lang.urlError;
                return;
            }
        } else {
            url = url.replace(/\?f=.*/, "");
        }
        $G("preview").innerHTML = '<embed type="application/x-shockwave-flash" pluginspage="http://www.macromedia.com/go/getflashplayer"' +
        ' src="' + url + '"' +
        ' width="' + 420 + '"' +
        ' height="' + 280 + '"' +
        ' wmode="transparent" play="true" loop="false" menu="false" allowscriptaccess="never" allowfullscreen="true" ></embed>';
    }

    /**
     * 末尾字符检测
     * @param str
     * @param endStrArr
     */
    function endWith(str, endStrArr) {
        for (var i = 0, len = endStrArr.length; i < len; i++) {
            var tmp = endStrArr[i];
            if (str.length - tmp.length < 0) return false;

            if (str.substring(str.length - tmp.length) == tmp) {
                return true;
            }
        }
        return false;
    }

    /**
     * ajax获取视频信息
     */
    function getMovie() {
        var keywordInput = $G("videoSearchTxt");
        if (!keywordInput.getAttribute("hasClick") || !keywordInput.value) {
            selectTxt(keywordInput);
            return;
        }
        $G("searchList").innerHTML = lang.loading;
        var keyword = keywordInput.value,
                type = $G("videoType").value,
            str = "";
        ajax.request(editor.options.getMovieUrl, {
            searchKey: keyword,
            videoType: type,
            onsuccess: function (xhr) {
                try {
                    var info = eval("(" + xhr.responseText + ")");
                } catch (e) {
                    return;
                }

                var videos = info.multiPageResult.results;
                var html = ["<table width='530'>"];
                for (var i = 0, ci; ci = videos[i++];) {
                    html.push(
                        "<tr>" +
                            "<td><img title='" + lang.clickToSelect + "' ue_video_url='" + ci.outerPlayerUrl + "' alt='" + ci.tags + "' width='106' height='80' src='" + ci.picUrl + "' /> </td>" +
                            "<td>" +
                                "<p><a target='_blank' title='" + lang.goToSource + "' href='" + ci.itemUrl + "'>" + ci.title.substr(0, 30) + "</a></p>" +
                                "<p style='height: 62px;line-height: 20px' title='" + ci.description + "'> " + ci.description.substr(0, 95) + " </p>" +
                            "</td>" +
                       "</tr>"
                    );
                }
                html.push("</table>");
                $G("searchList").innerHTML = str = html.length == 2 ? lang.noVideo : html.join("");
                var imgs = domUtils.getElementsByTagName($G("searchList"), "img");
                if (!imgs) return;
                for (var i = 0, img; img = imgs[i++];) {
                    domUtils.on(img, "click", function () {
                        changeSelected(this);
                    })
                }
            }
        });
    }

    /**
     * 改变对象o的选中状态
     * @param o
     */
    function changeSelected(o) {
        if (o.getAttribute("selected")) {
            o.removeAttribute("selected");
            o.style.cssText = "filter:alpha(Opacity=100);-moz-opacity:1;opacity: 1;border: 2px solid #fff";
        } else {
            o.setAttribute("selected", "true");
            o.style.cssText = "filter:alpha(Opacity=50);-moz-opacity:0.5;opacity: 0.5;border:2px solid #cd8740;";
        }
    }

    /**
    * 获取网盘中所有视频文件
    */
    function getVideoFiles() {
        var requestUrl = editor.options.videoList;
        var requestData = "disk_id=" + videoParams.diskId + "&dir_id=0";
        function setPageCount(obj) {
            videoParams.totalCount = obj.count;
            initFileHTML();
        }
        AjaxForJson(requestUrl, null, setPageCount, null);
    }
    //将文件信息加载到页面上
    function initFileHTML() {
        $(".fileIconB[name='aFileName']").die().live("click", function () {
            if ($(this).parent().parent().attr("check") == "yes") {
                $(this).parent().parent().css({ "opacity": "", "border": "2px solid #f0f0f0" });
                $(this).parent().parent().attr("check", "no");
            }
            else {
                $(this).parent().parent().css({ "opacity": "0.5", "filter": "Alpha(opacity=70)", "border": "2px solid #cd8740" });
                $(this).parent().parent().attr("check", "yes");
            }
        });

        var requestUrl = editor.options.videoList + "?type=mp3";
        //var requestData = "disk_id=" + videoParams.diskId + "&dir_id=0&style=" + $("div[name='viewType']").attr('list');
        // function setPageList(obj) {
        function responseEvent(obj) {
            var listHtml = '';
            var objR = obj.files;
            for (var i = 0; i < objR.length; i++) {
                if (objR[i].is_dir == true) {
                    listHtml += '';
                }
                else {
                    listHtml += '<ul name="ulFile" style="border:2px solid #f0f0f0;" class="pic_cell" file_id="' + objR[i].file_id + '" dir_id=' + objR[i].dir_id + ' is_dir="' + objR[i].is_dir + '" is_share="' + objR[i].is_share + '"><li class="fileName"><span class="fileIconB" name="aFileName"><em>' + objR[i].file_ext + '</em>';
                    if (objR[i].is_share > 0) {
                        listHtml += '<span class="partakeHand"></span>';
                    }
                    listHtml += '</span><input name="modifyFileName" style="display:none;" type="text" class="editTitle" value="" /><span name="extName"></span>';
                    listHtml += '<a href="/disk/down/' + objR[i].file_id + '" title="' + objR[i].file_name + '">';
                    listHtml += '<span name="dirName">' + objR[i].file_name + '</span></a></li><li class="fileSize grayTxt1">' + objR[i].file_size + '</li></ul>';
                }
            }
            $("div[name='divList']").css('display', 'none');
            $("div[name='divList']").html('');
            $("div[name='divPic']").html(listHtml);
            $("div[name='divPic']").css('display', 'block');

            $('.fileName').css('cursor', 'pointer');
            if (videoParams.totalCount <= 15) {
                $('#commentPageNum').css('display', 'none');
            } else {
                $('#commentPageNum').css('display', 'block');
            }
        }
        var requestMenberpage = new jsPage(videoParams.totalCount, "commentPageNum", "15", requestUrl, null, responseEvent);
        pageMethod.call(requestMenberpage);
    }

    /**
     * 视频搜索相关注册事件
     */
    function addSearchListener() {
        domUtils.on($G("videoSearchBtn"), "click", getMovie);
        domUtils.on($G("videoSearchTxt"), "click", function () {
            if (this.value == lang.static.videoSearchTxt.value) {
                this.value = "";
            }
            this.setAttribute("hasClick", "true");
            selectTxt(this);
        });
        $G("videoSearchTxt").onkeyup = function () {
            this.setAttribute("hasClick", "true");
            this.onkeyup = null;
        };
        domUtils.on($G("videoSearchReset"), "click", function () {
            var txt = $G("videoSearchTxt");
            txt.value = "";
            selectTxt(txt);
            $G("searchList").innerHTML = "";
        });
        domUtils.on($G("videoType"), "change", getMovie);
        domUtils.on($G("videoSearchTxt"), "keyup", function (evt) {
            if (evt.keyCode == 13) {
                getMovie();
            }
        })
    }

    //判断将文件进行分享
    function setFileShare(fileId) {
        function shareFile(data) {
            //DiskParams.disk_id = data;
        }
        AjaxForJson(editor.options.attachFileShare, "fid=" + fileId + "&disk_id=" + videoParams.diskId, shareFile, null);
    }



})();



function Music() {
    this.init();
}
(function () {
    var pages = [],
        panels = [],
        selectedItem = null;
    Music.prototype = {
        total: 70,
        pageSize: 10,
        dataUrl: "http://tingapi.ting.baidu.com/v1/restserver/ting?method=baidu.ting.search.common",
        playerUrl: "http://box.baidu.com/widget/flash/bdspacesong.swf",

        init: function () {
            var me = this;
            domUtils.on($G("J_searchName"), "keyup", function (event) {
                var e = window.event || event;
                if (e.keyCode == 13) {
                    me.dosearch();
                }
            });
            domUtils.on($G("J_searchBtn"), "click", function () {
                me.dosearch();
            });
        },
        callback: function (data) {
            var me = this;
            me.data = data.song_list;
            setTimeout(function () {
                $G('J_resultBar').innerHTML = me._renderTemplate(data.song_list);
            }, 300);
        },
        dosearch: function () {
            var me = this;
            selectedItem = null;
            var key = $G('J_searchName').value;
            if (utils.trim(key) == "") return false;
            key = encodeURIComponent(key);
            me._sent(key);
        },
        doselect: function (i) {
            var me = this;
            if (typeof i == 'object') {
                selectedItem = i;
            } else if (typeof i == 'number') {
                selectedItem = me.data[i];
            }
        },
        onpageclick: function (id) {
            var me = this;
            for (var i = 0; i < pages.length; i++) {
                $G(pages[i]).className = 'pageoff';
                $G(panels[i]).className = 'paneloff';
            }
            $G('page' + id).className = 'pageon';
            $G('panel' + id).className = 'panelon';
        },
        listenTest: function (elem) {
            var me = this,
                view = $G('J_preview'),
                is_play_action = (elem.className == 'm-try'),
                old_trying = me._getTryingElem();

            if (old_trying) {
                old_trying.className = 'm-try';
                view.innerHTML = '';
            }
            if (is_play_action) {
                elem.className = 'm-trying';
                view.innerHTML = me._buildMusicHtml(me._getUrl(true));
            }
        },
        _sent: function (param) {
            var me = this;
            $G('J_resultBar').innerHTML = '<div class="loading"></div>';

            utils.loadFile(document, {
                src: me.dataUrl + '&query=' + param + '&page_size=' + me.total + '&callback=music.callback&.r=' + Math.random(),
                tag: "script",
                type: "text/javascript",
                defer: "defer"
            });
        },
        _removeHtml: function (str) {
            var reg = /<\s*\/?\s*[^>]*\s*>/gi;
            return str.replace(reg, "");
        },
        _getUrl: function (isTryListen) {
            var me = this;
            var param = 'from=tiebasongwidget&url=&name=' + encodeURIComponent(me._removeHtml(selectedItem.title)) + '&artist='
                + encodeURIComponent(me._removeHtml(selectedItem.author)) + '&extra=&autoPlay=' + isTryListen + '' + '&loop=true';
            return me.playerUrl + "?" + param;
        },
        _getTryingElem: function () {
            var s = $G('J_listPanel').getElementsByTagName('span');

            for (var i = 0; i < s.length; i++) {
                if (s[i].className == 'm-trying')
                    return s[i];
            }
            return null;
        },
        _buildMusicHtml: function (playerUrl) {
            var html = '<embed class="BDE_try_Music" allowfullscreen="false" pluginspage="http://www.macromedia.com/go/getflashplayer"';
            html += ' src="' + playerUrl + '"';
            html += ' width="1" height="1" style="position:absolute;left:-2000px;"';
            html += ' type="application/x-shockwave-flash" wmode="transparent" play="true" loop="false"';
            html += ' menu="false" allowscriptaccess="never" scale="noborder">';
            return html;
        },
        _byteLength: function (str) {
            return str.replace(/[^\u0000-\u007f]/g, "\u0061\u0061").length;
        },
        _getMaxText: function (s) {
            var me = this;
            s = me._removeHtml(s);
            if (me._byteLength(s) > 12)
                return s.substring(0, 5) + '...';
            if (!s) s = "&nbsp;";
            return s;
        },
        _rebuildData: function (data) {
            var me = this,
                newData = [],
                d = me.pageSize,
                itembox;
            for (var i = 0; i < data.length; i++) {
                if ((i + d) % d == 0) {
                    itembox = [];
                    newData.push(itembox)
                }
                itembox.push(data[i]);
            }
            return newData;
        },
        _renderTemplate: function (data) {
            var me = this;
            if (data.length == 0) return '<div class="empty">' + lang.emptyTxt + '</div>';
            data = me._rebuildData(data);
            var s = [], p = [], t = [];
            s.push('<div id="J_listPanel" class="listPanel">');
            p.push('<div class="searchPage">');
            for (var i = 0, tmpList; tmpList = data[i++];) {
                panels.push('panel' + i);
                pages.push('page' + i);
                if (i == 1) {
                    s.push('<div id="panel' + i + '" class="panelon">');
                    if (data.length != 1) {
                        t.push('<div id="page' + i + '" onclick="music.onpageclick(' + i + ')" class="pageon">' + (i) + '</div>');
                    }
                } else {
                    s.push('<div id="panel' + i + '" class="paneloff">');
                    t.push('<div id="page' + i + '" onclick="music.onpageclick(' + i + ')" class="pageoff">' + (i) + '</div>');
                }
                s.push('<div class="m-box">');
                s.push('<div class="m-h"><span class="m-t">' + lang.chapter + '</span><span class="m-s">' + lang.singer
                    + '</span><span class="m-z">' + lang.special + '</span><span class="m-try-t">' + lang.listenTest + '</span></div>');
                for (var j = 0, tmpObj; tmpObj = tmpList[j++];) {
                    s.push('<label for="radio-' + i + '-' + j + '" class="m-m">');
                    s.push('<input type="radio" id="radio-' + i + '-' + j + '" name="musicId" class="m-l" onclick="music.doselect(' + (me.pageSize * (i - 1) + (j - 1)) + ')"/>');
                    s.push('<span class="m-t">' + me._getMaxText(tmpObj.title) + '</span>');
                    s.push('<span class="m-s">' + me._getMaxText(tmpObj.author) + '</span>');
                    s.push('<span class="m-z">' + me._getMaxText(tmpObj.album_title) + '</span>');
                    s.push('<span class="m-try" onclick="music.doselect(' + (me.pageSize * (i - 1) + (j - 1)) + ');music.listenTest(this)"></span>');
                    s.push('</label>');
                }
                s.push('</div>');
                s.push('</div>');
            }
            t.reverse();
            p.push(t.join(''));
            s.push('</div>');
            p.push('</div>');
            return s.join('') + p.join('');
        },
        exec: function () {
            var me = this;
            if (selectedItem == null) return;
            $G('J_preview').innerHTML = "";
            editor.execCommand('music', {
                url: me._getUrl(false),
                width: 400,
                height: 95
            });
        }
    };
})();