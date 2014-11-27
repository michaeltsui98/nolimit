var perview_type = "";
var swf_key = "";
var file_key = "";
var id = "";

loadjscssfile(commonParams.jcDevPath + "/static/flexpaper/flexpaper.css", "css");
loadjscssfile(commonParams.jcDevPath + "/static/fpaper/swfobject.js", "js");
loadjscssfile(commonParams.jcDevPath + "/static/flexpaper/flexpaper.js", "js");
loadjscssfile(commonParams.jcDevPath + "/static/flexpaper/flexpaper_handlers.js", "js");
loadjscssfile(commonParams.jcDevPath + "/static/ckplayer/ckplayer.js", "js");

jQuery.fn.extend({
    changeView: function (number) {
        var main = this;
        //生成资源预览
        function initView(index) {
            $("div.bd-con").html('<div class="iframe-w loadingStyle1" id="documentViewer" style="width:80%;height:95%;margin:0 auto;">');
            if (main.find("li").eq(index).attr("resource_id") == "0") {
                AjaxForJson(commonParams.jcDevPath + commonParams.jcType + "/lesson/ajaxTextPreview/id/" + main.find("li").eq(index).attr("detail_id"), null, function (dataObj) {
                    if (dataObj.type == "success") {
                        $('#documentViewer').html('<div style="height:100%;background-color:white;color:black;overflow-y: scroll;padding:10px;word-break: break-all;">' + dataObj.data.content + '</div>');
                    }
                });
            }
            else {
                AjaxForJson(commonParams.jcDevPath + commonParams.jcType + "/lesson/ajaxResourcePreview/id/" + main.find("li").eq(index).attr("resource_id"), null, function (dataObj) {
                    if (dataObj.type == "success") {
                        if (dataObj.data.info.status == "1" || dataObj.data.info.status == "3") {//状态1、3是可预览状态
                            perview_type = dataObj.data.type;
                            if (dataObj.data.info.swf_key) {
                                swf_key = dataObj.data.info.swf_key;
                            }
                            if (dataObj.data.info.file_key) {
                                file_key = dataObj.data.info.file_key;
                            }
                            id = dataObj.data.info.id;

                            $('#documentViewer').fileView("documentViewer");
                        }
                        else if (dataObj.data.info.status == "0") {//状态0为文档正在转换
                            $('#documentViewer').html("文档正在转换中...");
                        }
                        else {//状态2为文档转换失败
                            $('#documentViewer').html("文档转换失败");
                        }
                    }
                    else {
                        promptMessageDialog({
                            icon: "warning",
                            content: dataObj.message,
                            time: 1000
                        });
                    }
                });
            }
            if (index == 0) {
                $("div.bd-l").css("display", "none");
                $("div.bd-r").css("display", "block");
            }
            else if (index == main.find("li.l-item").length - 1) {
                $("div.bd-l").css("display", "block");
                $("div.bd-r").css("display", "none");
            }
            else {
                $("div.bd-l").css("display", "block");
                $("div.bd-r").css("display", "block");
            }
            main.find("li.l-item").removeClass("cur");
            main.find("li.l-item").eq(index).addClass("cur");
            if (main.find("li.l-item").eq(index).offset().top > $("div.g-sd1").height() - main.find("li.l-item").eq(index).height()) {
                $("div.g-sd1").scrollTop(main.find("li.l-item").eq(index).offset().top + main.find("li.l-item").eq(index).height() - $("div.g-sd1").height() + $("div.g-sd1").scrollTop());
            }
            else if (main.find("li.l-item").eq(index).offset().top < 0) {
                $("div.g-sd1").scrollTop($("div.g-sd1").scrollTop() - main.find("li.l-item").eq(index).height() - 14);
            }
        }
        //选择资源加载
        $("ul.m-lessonList").find("li.l-item").unbind().bind("click", function () {
            initView($("ul.m-lessonList").find("li.l-item").index($(this)));
        });
        //绑定前后加载资源
        function moveView(starmove) {
            var curIndex = main.find("li.cur").index() - 1;
            if (starmove == "pre") {
                if (curIndex > 0) {
                    initView(curIndex - 1);
                }
            }
            else if (starmove == "aft") {
                if (curIndex < main.find("li.l-item").length - 1) {
                    initView(curIndex + 1);
                }
            }
        }
        $("div.bd-l").unbind().bind("click", function () {
            moveView("pre");
        });
        $("div.bd-r").unbind().bind("click", function () {
            moveView("aft");
        });

        return initView(number);
    }
});

$(document).ready(function () {
    if ($("ul.m-lessonList").find("li").length == 0) {
        promptMessageDialog({
            icon: "warning",
            content: "没有可预览的资源！",
            time: 1000
        });
    }
    else {
        var index = $("ul.m-lessonList").find("li.cur").index() > -1 ? $("ul.m-lessonList").find("li.cur").index() : 0;
        $("ul.m-lessonList").css("height", "100%");
        $.cachedScript(commonParams.jcDevPath + "/static/script/jscroll.js").done(function () {
            $("ul.m-lessonList").jscroll({
                W: "8px"
            });
        });
        $("ul.m-lessonList").changeView(index);
    }
});