jQuery.fn.extend({
    dragAdd: function (container, ifdel) {
        var mian = this;
        var dragobj = {};
        //鼠标mousedown时生成替换元素
        function addevent(e, main) {
            if (dragobj.o != null) {
                return false;
            }
            e = getEvent();
            dragobj.o = main;
            dragobj.xy = getxy(dragobj.o);
            dragobj.xx = new Array((e.clientX - dragobj.xy[1]), (e.clientY - dragobj.xy[0]));
            var st = document.createElement("div");
            dragobj.steal = st;
            st.innerHTML = main.outerHTML;
            st.style.left = (e.clientX + $(document).scrollLeft() - 50) + "px";
            st.style.top = (e.clientY + $(document).scrollTop() - 20) + "px";
            st.style.width = "233px";
            st.style.position = "absolute";
            st.style.zIndex = "3";
            st.style.filter = 'alpha(opacity=50)';
            st.style.opacity = '0.5';
            $("body").append(st);
            return false;
        }

        //拖拽时鼠标mouseover和mouseup事件
        function dragMove() {
            document.onmousemove = function (e) {
                e = getEvent();
                if (dragobj.steal != null) {
                    dragobj.steal.style.left = (e.clientX + $(document).scrollLeft() - 50) + "px";
                    dragobj.steal.style.top = (e.clientY + $(document).scrollTop() - 20) + "px";
                }
            };

            document.onmouseup = function (e) {
                document.onmousemove = null;
                document.onmouseup = null;

                e = getEvent();
                container = $("div.classSection");
                for (var i = 0; i < container.length; i++) {
                    if (inner(container[i], e) == 1) {
                        if (ifdel) {//拖拽后是否删除原有内容
                            if ($(mian)[0] != container.eq(i)[0]) {//循环搜索拖动到的位置
                                if (container.eq(i).find("div.preview").length <= 0) {
                                    var contentObj = $(mian);
                                    var contHTML = contentObj.html();
                                    var containerObj = container.eq(i);
                                    var containerHTML = containerObj.html();
                                    var indexNum = $(mian).index() + 1;

                                    container.eq(i).html(dragobj.o.innerHTML);//到时候换成设计页面的元素
                                    container.eq(i).find("div.p-num").html(i + 1);

                                    //contentObj.html(containerHTML);
                                    //contentObj.find("div.p-num").html(indexNum);

                                    AjaxForJson(commonParams.jcDevPath + commonParams.jcType + "/Lesson/editResource/id/" + containerObj.find("div.preview").attr("resource_id"), "sort=" + i, function (dataObj) {
                                        if (dataObj.type == "success") {
                                            //containerObj.find("div.preview").attr("resource_id", dataObj.data.id);
                                        }
                                        else {
                                            contentObj.html(contHTML);
                                            containerObj.html(containerHTML);
                                            promptMessageDialog({
                                                icon: "warning",
                                                content: dataObj.message,
                                                time: 1000
                                            });
                                        }
                                    });

                                    $(dragobj.steal).remove();
                                    dragobj = {};
                                    $(mian).html('<div class="help help-default" style="display: block;"><div class="h-desc"><h2>' + (container.index(mian) + 1) + '</h2><p>教案设计</p></div></div><div class="help help-hover" style="display: none;"><div class="h-drag"><img src="/Modules' + commonParams.jcType + '/static/images/lesson-ready-help.gif" alt=""></div><div class="h-options"><button name="addText" title="添加文本"><i class="icon-editTxt"></i></button><button name="uploadResource" title="上传资源"><i class="icon-upload-classready"></i></button></div></div>');
                                }
                                else {
                                    var contentObj = $(mian);
                                    var contHTML = contentObj.html();
                                    var contentObjId = contentObj.find("div.preview").attr("resource_id");
                                    var containerObj = container.eq(i);
                                    var containerHTML = containerObj.html();
                                    var containerObjId = containerObj.find("div.preview").attr("resource_id");
                                    var indexNum = $(mian).index() + 1;

                                    container.eq(i).html(dragobj.o.innerHTML);//到时候换成设计页面的元素
                                    container.eq(i).find("div.p-num").html(i + 1);

                                    contentObj.html(containerHTML);
                                    contentObj.find("div.p-num").html(indexNum);

                                    AjaxForJson(commonParams.jcDevPath + commonParams.jcType + "/lesson/changeResource", "id1=" + +contentObjId + "&id2=" + containerObjId, function (dataObj) {
                                        if (dataObj.type == "success") {
                                            //containerObj.find("div.preview").attr("resource_id", dataObj.data.id);
                                        }
                                        else {
                                            contentObj.html(contHTML);
                                            containerObj.html(containerHTML);
                                            promptMessageDialog({
                                                icon: "warning",
                                                content: dataObj.message,
                                                time: 1000
                                            });
                                        }
                                    });

                                    $(dragobj.steal).remove();
                                    dragobj = {};
                                }
                            }
                            else {
                                $(dragobj.steal).remove();
                                dragobj = {};
                            }
                        }
                        else {
                            container.eq(i).html('<div class="preview" resource_id="50"><div class="pre-con"><img src="' + commonParams.wenkuStaticPath + dragobj.o.getAttribute("resource-preview") + '" alt="" style="height:238px;"><div class="p-topLabel"><div class="p-num">' + (i + 1) + '</div><input name="title" class="p-title no-value" value="' + dragobj.o.getAttribute("resource_title") + '"><div class="p-desc">' + dragobj.o.getAttribute("resource_description") + '</div><div class="p-tIcon"><i class="' + dragobj.o.getAttribute("resource_style") + '"></i></div></div><div class="p-options clearfix" style="display:"><div class="f-fl"><a href="javascript:;" class="icon-fileDel"></a><a href="javascript:;" class="icon-fileEdit"></a></div><div class="f-fr"><a href="javascript:;" class="icon-filePreview"></a></div></div></div></div><div class="folder-edit" style="display:none;"><textarea></textarea><a href="javascript:;" title="提交" class="customBtn colourDarkBlue">提交</a></div>');// style="overflow:hidden;text-overflow:ellipsis;white-space:nowrap;width:180px;"

                            var requestData = "id=" + dragobj.o.getAttribute("resource_id") + "&type=" + dragobj.o.getAttribute("resource_type") + "&folder_id=" + $("#folder-resource-list").attr("folder_id") + "&sort=" + i + "&title=" + dragobj.o.getAttribute("resource_title") + "&description=" + dragobj.o.getAttribute("resource_description") + "&preview=" + dragobj.o.getAttribute("resource-preview");
                            var containerObj = container.eq(i);
                            AjaxForJson(commonParams.jcDevPath + commonParams.jcType + "/Lesson/addResourcePost", requestData, function (dataObj) {
                                if (dataObj.type == "success") {
                                    containerObj.find("div.preview").attr("resource_id", dataObj.data.id);
                                    containerObj.find("a.icon-filePreview").attr("href", commonParams.jcDevPath + commonParams.jcType + '/lesson/detail/id/' + $("#folder-resource-list").attr("folder_id") + '/resource/' + dataObj.data.id);
                                }
                                else {
                                    containerObj.html("");
                                    promptMessageDialog({
                                        icon: "warning",
                                        content: dataObj.message,
                                        time: 1000
                                    });
                                }
                            });

                            $(dragobj.steal).remove();
                            dragobj = {};
                        }
                        return;
                    }
                    else if (inner($("div[name='resource-list']")[0], e) == 1 && ifdel) {//判断是否是要删除该资源
                        var containerObj = $(mian);
                        var contHTML = containerObj.html();
                        AjaxForJson(commonParams.jcDevPath + commonParams.jcType + "/Lesson/delResource/id/" + containerObj.find("div.preview").attr("resource_id"), null, function (dataObj) {
                            if (dataObj.type == "success") {
                                //containerObj.find("div.preview").attr("resource_id", dataObj.data.id);
                            }
                            else {
                                $(mian).html(contHTML);
                                promptMessageDialog({
                                    icon: "warning",
                                    content: dataObj.message,
                                    time: 1000
                                });
                            }
                        });
                        $(dragobj.steal).remove();
                        dragobj = {};
                        containerObj.html('<div class="help help-default" style="display: block;"><div class="h-desc"><h2>' + (container.index(mian) + 1) + '</h2><p>教案设计</p></div></div><div class="help help-hover" style="display: none;"><div class="h-drag"><img src="/Modules' + commonParams.jcType + '/static/images/lesson-ready-help.gif" alt=""></div><div class="h-options"><button name="addText" title="添加文本"><i class="icon-editTxt"></i></button><button name="uploadResource" title="上传资源"><i class="icon-upload-classready"></i></button></div></div>');
                        return;
                    }

                }
                $(dragobj.steal).remove();
                dragobj = {};
            };
        }


        function getEvent() //同时兼容ie和ff的写法
        {
            if (document.all) return window.event;
            func = getEvent.caller;
            while (func != null) {
                var arg0 = func.arguments[0];
                if (arg0) {
                    if ((arg0.constructor == Event || arg0.constructor == MouseEvent) || (typeof (arg0) == "object" && arg0.preventDefault && arg0.stopPropagation)) {
                        return arg0;
                    }
                }
                func = func.caller;
            }
            return null;
        }

        //计算鼠标在页面中的位置
        function getxy(e) {
            var a = new Array();
            var t = e.offsetTop;
            var l = e.offsetLeft;
            var w = e.offsetWidth;
            var h = e.offsetHeight;
            while (e = e.offsetParent) {
                t += e.offsetTop;
                l += e.offsetLeft;
            }
            a[0] = t; a[1] = l; a[2] = w; a[3] = h;
            return a;
        }
        //判断module在页面中移动的位置：1为下移，2为内嵌
        function inner(o, e) {
            var a = getxy(o);
            if ((e.clientX + $(document).scrollLeft()) > a[1] && (e.clientX + $(document).scrollLeft()) < (a[1] + a[2]) && e.clientY + $(document).scrollTop() > a[0] && e.clientY + $(document).scrollTop() < (a[0] + a[3])) {
                return 1;
            }
            else {
                return 0;
            }
        }

        return $(mian).die().live("mousedown", function (e) {
            mian = this;
            e = e ? e : event;
            var srcEle = e.target || e.srcElement;
            if (srcEle.className == "icon-fileDel" || srcEle.className == "icon-fileEdit" || srcEle.className == "icon-filePreview" || srcEle.name == "title") {
                return;
            }
            if ($(mian).find("div.preview").length <= 0 && $(mian).attr("class").indexOf("classSection") > -1) {
                return;
            }
            if ($(mian).find("div.preview").css("display") == "none") {
                return;
            }
            //防止拖动选中
            $(this).css("-moz-user-select", "none");
            $(this).css("-webkit-user-select", "none");
            document.onselectstart = function () { return false };

            addevent(e, this);
            dragMove();
        });
    }
});

$(document).ready(function () {
    //动态加载js、css
    loadjscssfile(commonParams.jcDevPath + "/static/ueditorAll/themes/default/css/ueditor.css", "css");
    loadjscssfile(commonParams.jcDevPath + "/static/ueditorAll/editor_config.js", "js");
    loadjscssfile(commonParams.jcDevPath + "/static/ueditorAll/editor_all.js", "js");

    $("div[name='resource-list'] div.Item").dragAdd($("div.classSection"));
    $("div.classSection").dragAdd($("div.classSection"), true);

    //拖拽的显示隐藏效果
    $("div.classSection").live("mouseover", function () {
        $(this).find("div.help-default").css("display", "none");
        $(this).find("div.help-hover").css("display", "block");
        $(this).find("div.p-options").css("display", "block");
    });
    $("div.classSection").live("mouseleave", function () {
        $(this).find("div.help-hover").css("display", "none");
        $(this).find("div.help-default").css("display", "block");
        $(this).find("div.p-options").css("display", "none");
    });

    $("div.m-myFavor").find("ul").unbind().bind("click", function () {
        var mian = this;
        $("div.m-myFavor").find("div.ct").each(function (i) {
            if ($("div.m-myFavor").find("div.ct")[i] != $(mian)[0]) {
                $("div.m-myFavor").find("div.ct").eq(i).hide("normal");
                $("div.m-myFavor").find("ul").find("i").attr("class", "icon-arrowMid");
            }
        });
        $(this).parent().find("div.ct").toggle("normal");
        $(this).find("i").attr("class", "icon-arrowDown");
    });

    //添加文本备课资源
    $("div.h-options").find("button[name='addText']").die().live("click", function () {
        var mian = this;
        var textDialogHTML = '<div style="min-height:376px;margin-bottom:10px; margin-top:10px;"><div style="width:690px;margin: 0 auto;"><label style="display: inline-block;vertical-align: middle;height: 26px;line-height: 26px;overflow: hidden;">标题：</label><input id="resTitle" type="text" class="txtInput clearStyle" data-default="请输入课件名称" value="请输入课件名称" style="color: rgb(181, 181, 181);width:650px;"></div><script type="text/plain" id="editor" style="width:690px;margin: 0 auto;"></script></div>';
        function textDialogEvent() {
            $("input#resTitle").clearText()

            var editor = new UE.ui.Editor();
            editor.render('editor');

            $("#PopupsFunc").find("input[name='confirm']").unbind().bind("click", function () {
                var btnloading = new btnLoading(this);
                btnloading.toLoading(); //提交按钮变loading图片
                var resTitle = $("#resTitle").val() == $("#resTitle").attr("data-default") ? "" : $("#resTitle").val()
                var requestData = "id=&type=0&folder_id=" + $("#folder-resource-list").attr("folder_id") + "&sort=" + ($(mian).parents("div.classRow").index() * 3 + $(mian).parents("div.classSection").index()) + "&title=" + characterTransform(resTitle) + "&description=" + characterTransform(editor.getContent()) + "&preview=";
                AjaxForJson(commonParams.jcDevPath + commonParams.jcType + "/Lesson/addResourcePost", requestData, function (obj) {
                    if (obj.type == "success") {
                        $(mian).parents("div.classSection").html('<div class="preview" resource_id="' + obj.data.id + '"><div class="pre-con"><div style="height:181px;overflow: hidden;margin-top:57px;_zoom:0.80;transform: scale(0.80);-moz-transform: scale(0.80);-webkit-transform: scale(0.80);">' + editor.getContent() + '</div><div class="p-topLabel"><div class="p-num">' + ($(mian).parents("div.classRow").index() * 3 + $(mian).parents("div.classSection").index() + 1) + '</div><input name="title" class="p-title no-value" value="' + resTitle + '"><div class="p-desc"></div><div class="p-tIcon"><i class="' + obj.data.iconStyle + '"></i></div></div><div class="p-options clearfix" style="display:"><div class="f-fl"><a href="javascript:;" class="icon-fileDel"></a><a href="javascript:;" class="icon-fileEdit"></a></div><div class="f-fr"><a href="' + commonParams.jcDevPath + commonParams.jcType + '/lesson/detail/id/' + $("#folder-resource-list").attr("folder_id") + '/resource/' + obj.data.id + '" class="icon-filePreview"></a></div></div></div></div><div class="folder-edit" style="display:none;"><textarea></textarea><a href="javascript:;" title="提交" class="customBtn colourDarkBlue">提交</a></div>');



                        styledialog.closeDialog();
                    }
                    else {
                        promptMessageDialog({
                            icon: "warning",
                            content: dataObj.message,
                            time: 1000
                        });
                        btnloading.toBtn();
                    }
                });
            });
        }

        styledialog.initDialogHTML({
            title: "添加文本",
            content: textDialogHTML,
            width: 738,
            confirm: {
                show: true,
                name: "确认"
            },
            cancel: {
                show: true,
                name: "取消"
            }
        });
        styledialog.initContent("添加文本", textDialogHTML, textDialogEvent);

    });

    //上传备课资源
    $("div.h-options").find("button[name='uploadResource']").die().live("click", function () {
        var mian = this;
        loadjscssfile(commonParams.jcDevPath + "/Modules" + commonParams.jcType + "/static/css/uploader-thirdCss.css", "css");
        resourceUploadDialog(commonParams.jcDevPath + "/resource/index?xd=" + $("#folder-resource-list").attr("stage") + "&xk=" + xk_code, "上传资源", function () {
            //loadjscssfile(commonParams.jcDevPath + "/Modules/Xl/static/script/ziyuan/zy_resupload.js", "js");
            $.cachedScript(commonParams.jcDevPath + "/Modules" + commonParams.jcType + "/static/script/ziyuan/zy_resupload.js?" + Math.random()).done(function () {
                //$('#up_slt_xd').mulitSelectUpEvent("up_slt_xk");
                $('#up_slt_bb').mulitSelectUpEvent("up_slt_nj");
                $('#up_slt_nj').hybridSelectUpEvent("up_slt_zs");
                $("#up_slt_xd").getBbSelectUpEvent();
                //提交表单
                $("#PopupsFunc").find("input[name='confirm']").unbind().bind("click", function () {
                    var btnloading = new btnLoading(this);
                    btnloading.toLoading(); //提交按钮变loading图片
                    var data = $("form[name='frm1']").serialize();
                    $.ajaxJSONP(commonParams.wenkuPath + "/upload/resUploadPost", data, function (dataobj) {
                        if (dataobj.status == 1) {

                            var previewKey = dataobj.msg.doc_page_key ? dataobj.msg.doc_page_key : "";
                            var requestData = "id=" + dataobj.msg.doc_id + "&ext=" + dataobj.msg.doc_ext_name + "&folder_id=" + $("#folder-resource-list").attr("folder_id") + "&sort=" + ($(mian).parents("div.classRow").index() * 3 + $(mian).parents("div.classSection").index()) + "&title=" + dataobj.msg.doc_title + "&description=" + dataobj.msg.doc_summery + "&preview=" + previewKey;
                            AjaxForJson(commonParams.jcDevPath + commonParams.jcType + "/Lesson/addResourcePost", requestData, function (obj) {
                                if (obj.type == "success") {
                                    $(mian).parents("div.classSection").html('<div class="preview" resource_id="' + dataobj.msg.doc_id + '"><div class="pre-con"><img src="' + commonParams.wenkuStaticPath + dataobj.msg.doc_page_key + '" alt="" style="height:238px;"><div class="p-topLabel"><div class="p-num">' + ($(mian).parents("div.classRow").index() * 3 + $(mian).parents("div.classSection").index() + 1) + '</div><input name="title" class="p-title no-value" value="' + dataobj.msg.doc_title + '"><div class="p-desc">' + dataobj.msg.doc_summery + '</div><div class="p-tIcon"><i class="' + obj.data.iconStyle + '"></i></div></div><div class="p-options clearfix" style="display:"><div class="f-fl"><a href="javascript:;" class="icon-fileDel"></a><a href="javascript:;" class="icon-fileEdit"></a></div><div class="f-fr"><a target="_blank" href="' + commonParams.jcDevPath + commonParams.jcType + '/lesson/detail/id/' + $("#folder-resource-list").attr("folder_id") + '/resource/' + obj.data.id + '" class="icon-filePreview"></a></div></div></div></div><div class="folder-edit" style="display:none;"><textarea></textarea><a href="javascript:;" title="提交" class="customBtn colourDarkBlue">提交</a></div>');



                                    styledialog.closeDialog();
                                }
                                else {
                                    promptMessageDialog({
                                        icon: "warning",
                                        content: dataObj.message,
                                        time: 1000
                                    });
                                    btnloading.toBtn();
                                }
                            });
                        }
                        else {
                            promptMessageDialog({
                                icon: "warning",
                                content: dataObj.message,
                                time: 1000
                            });
                        }
                    });
                });
            });
        });
    });

    //添加备课夹的行
    $("div.classAddRow a").unbind().bind("click", function () {
        AjaxForJson(commonParams.jcDevPath + commonParams.jcType + "/Lesson/edit/id/" + $("#folder-resource-list").attr("folder_id"), "resource_layout=" + ($("div.classSection").length + 3), function (dataObj) {
            if (dataObj.type == "success") {
                $("div.class-tb").append('<div class="classRow"><div class="classSection a-l0"><div class="help help-default" style="display: block;"><div class="h-desc"><h2>' + ($("div.classSection").length + 1) + '</h2><p>教案设计</p></div></div><div class="help help-hover" style="display: none;"><div class="h-drag"><img src="/Modules' + commonParams.jcType + '/static/images/lesson-ready-help.gif" alt=""></div><div class="h-options"><button name="addText" title="添加文本"><i class="icon-editTxt"></i></button><button name="uploadResource" title="上传资源"><i class="icon-upload-classready"></i></button></div></div></div><div class="classSection a-l1"><div class="help help-default" style="display: block;"><div class="h-desc"><h2>' + ($("div.classSection").length + 2) + '</h2><p>教案设计</p></div></div><div class="help help-hover" style="display: none;"><div class="h-drag"><img src="/Modules' + commonParams.jcType + '/static/images/lesson-ready-help.gif" alt=""></div><div class="h-options"><button name="addText" title="添加文本"><i class="icon-editTxt"></i></button><button name="uploadResource" title="上传资源"><i class="icon-upload-classready"></i></button></div></div></div><div class="classSection a-l2"><div class="help help-default" style="display: block;"><div class="h-desc"><h2>' + ($("div.classSection").length + 3) + '</h2><p>教案设计</p></div></div><div class="help help-hover" style="display: none;"><div class="h-drag"><img src="/Modules' + commonParams.jcType + '/static/images/lesson-ready-help.gif" alt=""></div><div class="h-options"><button name="addText" title="添加文本"><i class="icon-editTxt"></i></button><button name="uploadResource" title="上传资源"><i class="icon-upload-classready"></i></button></div></div></div></div>');
            }
            else {
                promptMessageDialog({
                    icon: "warning",
                    content: dataObj.message,
                    time: 1000
                });
            }
        });

        return false;
    });

    //删除备课夹的某个资源
    $("a.icon-fileDel").die().live("click", function () {
        var main = this;
        var resousId = $(main).parents("div.preview").attr("resource_id");
        cueDialog(function (main) {
            AjaxForJson(commonParams.jcDevPath + commonParams.jcType + "/Lesson/delResource/id/" + resousId, null, function (dataObj) {
                if (dataObj.type == "success") {
                    $(main).parents("div.classSection").html('<div class="help help-default" style="display: block;"><div class="h-desc"><h2>' + ($(main).parents("div.classRow").index() * 3 + $(main).parents("div.classSection").index() + 1) + '</h2><p>教案设计</p></div></div><div class="help help-hover" style="display: none;"><div class="h-drag"><img src="/Modules' + commonParams.jcType + '/static/images/lesson-ready-help.gif" alt=""></div><div class="h-options"><button name="addText" title="添加文本"><i class="icon-editTxt"></i></button><button name="uploadResource" title="上传资源"><i class="icon-upload-classready"></i></button>');
                }
                else {
                    promptMessageDialog({
                        icon: "warning",
                        content: dataObj.message,
                        time: 1000
                    });
                }
            });
        }, this, false, "确定要移除此资源吗？");

        return false;
    });

    //修改备课夹资源描述
    $("a.icon-fileEdit").die().live("click", function () {

        var mian = this;
        var textDialogHTML = '<div style="min-height:376px;margin-bottom:10px; margin-top:10px;"><div style="width:690px;margin: 0 auto;"><label style="display: inline-block;vertical-align: middle;height: 26px;line-height: 26px;overflow: hidden;">标题：</label><input id="resTitle" type="text" class="txtInput clearStyle" data-default="请输入课件名称" value="请输入课件名称" style="color: rgb(181, 181, 181);width:650px;"></div><script type="text/plain" id="editor" style="width:690px;margin: 0 auto;"></script></div>';
        function textDialogEvent() {
            $("input#resTitle").clearText();

            var editor = new UE.ui.Editor();
            editor.render('editor');
            editor.ready(function () {
                editor.setContent($(mian).parents("div.classSection").find("div.preview").find("div.pre-con").find("div").eq(0).html());
            });
            $("input#resTitle").val($(mian).parents("div.classSection").find("div.preview").find("input[name='title']").val());

            $("#PopupsFunc").find("input[name='confirm']").unbind().bind("click", function () {
                var btnloading = new btnLoading(this);
                btnloading.toLoading(); //提交按钮变loading图片
                var resTitle = $("#resTitle").val() == $("#resTitle").attr("data-default") ? "" : $("#resTitle").val();
                var requestData = "id=&type=0&folder_id=" + $("#folder-resource-list").attr("folder_id") + "&sort=" + ($(mian).parents("div.classRow").index() * 3 + $(mian).parents("div.classSection").index()) + "&title=" + characterTransform(resTitle) + "&description=" + characterTransform(editor.getContent()) + "&preview=";
                AjaxForJson(commonParams.jcDevPath + commonParams.jcType + "/Lesson/addResourcePost", requestData, function (obj) {
                    if (obj.type == "success") {
                        $(mian).parents("div.classSection").html('<div class="preview" resource_id="' + obj.data.id + '"><div class="pre-con"><div style="height:181px;overflow: hidden;margin-top:57px;_zoom:0.80;transform: scale(0.80);-moz-transform: scale(0.80);-webkit-transform: scale(0.80);">' + editor.getContent() + '</div><div class="p-topLabel"><div class="p-num">' + ($(mian).parents("div.classRow").index() * 3 + $(mian).parents("div.classSection").index() + 1) + '</div><input name="title" class="p-title no-value" value="' + resTitle + '"><div class="p-desc"></div><div class="p-tIcon"><i class="' + obj.data.iconStyle + '"></i></div></div><div class="p-options clearfix" style="display:"><div class="f-fl"><a href="javascript:;" class="icon-fileDel"></a><a href="javascript:;" class="icon-fileEdit"></a></div><div class="f-fr"><a href="' + commonParams.jcDevPath + commonParams.jcType + '/lesson/detail/id/' + $("#folder-resource-list").attr("folder_id") + '/resource/' + obj.data.id + '" class="icon-filePreview"></a></div></div></div></div><div class="folder-edit" style="display:none;"><textarea></textarea><a href="javascript:;" title="提交" class="customBtn colourDarkBlue">提交</a></div>');

                        styledialog.closeDialog();
                    }
                    else {
                        promptMessageDialog({
                            icon: "warning",
                            content: dataObj.message,
                            time: 1000
                        });
                        btnloading.toBtn();
                    }
                });
            });
        }

        styledialog.initDialogHTML({
            title: "添加文本",
            content: textDialogHTML,
            width: 738,
            confirm: {
                show: true,
                name: "确认"
            },
            cancel: {
                show: true,
                name: "取消"
            }
        });
        styledialog.initContent("添加文本", textDialogHTML, textDialogEvent);

        return false;
    });

    //编辑标题事件
    $("input[name='title']").die().live("blur", function () {
        var titleCon = $(this).val();
        AjaxForJson(commonParams.jcDevPath + commonParams.jcType + "/Lesson/editResource/id/" + $(this).parents("div.preview").attr("resource_id"), "title=" + titleCon, function (dataObj) {
            if (dataObj.type == "success") {

            }
            else {
                promptMessageDialog({
                    icon: "warning",
                    content: dataObj.message,
                    time: 1000
                });
            }
        });
    });

    //编辑描述事件
    $("div.folder-edit").find("a.customBtn").die().live("click", function () {
        var main = this;
        var desCon = $(main).parent().find("textarea").val();
        AjaxForJson(commonParams.jcDevPath + commonParams.jcType + "/Lesson/editResource/id/" + $(main).parents("div.classSection").find("div.preview").attr("resource_id"), "description=" + desCon, function (dataObj) {
            if (dataObj.type == "success") {
                $(main).parent().css("display", "none");
                $(main).parents("div.classSection").find("div.preview").css("display", "block");
                $(main).parents("div.classSection").find("div.preview").find("div.p-desc").html(desCon);
            }
            else {
                promptMessageDialog({
                    icon: "warning",
                    content: dataObj.message,
                    time: 1000
                });
            }
        });
        return false;
    });

    //搜索平台资源
    $("#searchResource").bind("click", function () {
        var title = "搜索平台资源";
        var url = commonParams.jcDevPath + commonParams.jcType + "/lesson/resourceSearch";
        function searchResource() {
            var dialogContainer = $('#editFraCon');

            // 侧栏选择版本后ajax获取年级
            var editionSelect = $('#slt_bb').bind({
                change: function (e) {
                    var editionId = editionSelect.find('option:selected').attr('id');
                    if (editionId) {
                        $.get('/node/gradeByEdition', { edition: editionId }, function (json) {
                            if (json.type == 'success') {
                                var options = '<option>请选择</option>';
                                for (var i in json.data) {
                                    var item = json.data[i];
                                    options += '<option id="' + item.id + '" value="' + item.code + '">' + item.name + '</option>';
                                }
                                gradeSelect.html(options);
                            }
                        }, 'json');
                    }
                }
            });

            var gradeSelect = $('#slt_nj').bind({
                change: function (e) {
                    unitList.load('/node/nodeTree', {
                        subject: $('#hdn_xk').val(),
                        edition: editionSelect.val(),
                        grade: gradeSelect.val()
                    });
                }
            });

            var unitList = $('ul.unitList');

            var contentContainer = dialogContainer.find('div.W-form');

            // 捕获页面的a标签改为ajax请求
            dialogContainer.find('a.js-ajax, div.page a').die().live({
                click: function (e) {
                    e.preventDefault();
                    contentContainer.load($(this).attr('href'), function () {
                        contentContainer.append('<div class="m-ftc"><div id="PopupsFunc" class="w-btn"><input name="confirm" type="button" class="customBtn colourDarkGreen customBtnNormal f-mr10 " value="确定"><input name="cancel" type="button" class="customBtn colourLightGray customBtnNormal" value="取消"></div></div>');
                    });
                }
            });

            // 绑定添加到备课夹的按钮事件（加入input）
            dialogContainer.find('a.js-addToFolder').die().live({
                click: function (e) {
                    var $this = $(e.target);
                    if ($this.text() == '已选择') {
                        return false;
                    }

                    $this.text('已选中');
                    var resource_id = $this.attr('resource_id');
                    $this.append('<input type="hidden" name="resources[]" value="' + resource_id + '">');
                }
            });

            // 确定按钮事件的绑定
            $("#PopupsFunc").find("input[name='confirm']").die().live("click", function () {
                var btnloading = new btnLoading(this);
                btnloading.toLoading(); //提交按钮变loading图片
                var batchForm = $('#batch-add-resource-form');
                $.post(batchForm.attr('action'), batchForm.serialize() + '&folder_id=' + $("#folder-resource-list").attr("folder_id"), function (json) {
                    promptMessageDialog({
                        icon: json.type,
                        content: json.message,
                        time: 1000
                    });
                    if (json.type == 'success') {
                        window.location.reload();
                    }
                }, 'json');
            });
        }
        styledialog.initDialogHTML({
            title: title,
            url: url,
            width: 1002,
            confirm: {
                show: true,
                name: "确认"
            },
            cancel: {
                show: true,
                name: "取消"
            }
        });
        styledialog.initContent(title, "", searchResource);
        return false;
    });

    // 绑定ajax提交事件
    $('form.ajax-form').each(function (i) {
        var main = $(this);
        main.bind({
            submit: function (e) {
                e.preventDefault();
                $.post(main.attr('action'), main.serialize(), function (json) {
                    promptMessageDialog({
                        icon: json.type,
                        content: json.message
                    });
                }, 'json');
            }
        });

        main.find('a.js-submit').bind({
            click: function (e) {
                e.preventDefault();
                main.submit();
            }
        });
    });

    // 侧边栏的点击加载更多
    $('.ajax-page').each(function (i) {
        $(this).initAjaxPage();
    });

});

jQuery.fn.extend({
    initAjaxPage: function () {
        if (this.length <= 0) {
            return this;
        }
        var main = this;
        main.extend({
            url: this.data('url'),
            page: 1,
            loading: false,
            content: this.find('.ajax-content'),
            ctrl: this.find('.refresh_line').bind({
                click: function (e) {
                    if (main.loading) {
                        return false;
                    }
                    main.page += 1;
                    main.loading = true;
                    main.ctrl.html('<p></p>');
                    $.get(main.url, { page: main.page }, function (content) {
                        main.content.append(content);
                        main.loading = false;
                        main.ctrl.html('<a href="javascript:;">点击加载更多</a>');
                        if (content.length <= 0) {
                            main.ctrl.hide();
                        }
                    });
                }
            })
        });
    }
});