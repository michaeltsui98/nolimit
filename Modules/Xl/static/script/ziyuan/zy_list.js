$(document).ready(function () {
    //$('#slt_xd').mulitSelectEvent("slt_xk");
    //$('#slt_bb').mulitSelectEvent("slt_nj");
    //$('#slt_nj').hybridSelectEvent("ul.unitList");

    $('#slt_xd').locationSlectEvent();
    $('#slt_bb').locationSlectEvent();
    $('#slt_nj').locationSlectEvent();

    $("#resUpload").die().live("click", function () {

        loadjscssfile(commonParams.jcDevPath + "/Modules" + commonParams.jcType + "/static/css/uploader-thirdCss.css", "css");
        var xdVal = $("#slt_xd").val() ? $("#slt_xd").val() : $("#hdn_xd").val();
        var zsVal = $("ul.unitList").find("a.cur").attr("data-id") ? $("ul.unitList").find("a.cur").attr("data-id") : "";
        resourceUploadDialog(commonParams.jcDevPath + "/resource/index?xd=" + xdVal + "&xk=" + $("#hdn_xk").val() + "&bb=" + $("#slt_bb").val() + "&nj=" + $("#slt_nj").val() + "&zs=" + zsVal, "上传资源", function () {
            $.cachedScript(commonParams.jcDevPath + "/Modules" + commonParams.jcType + "/static/script/ziyuan/zy_resupload.js?" + Math.random()).done(function () {
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
                            promptMessageDialog({
                                icon: "finish",
                                content: "添加成功",
                                time: 1000
                            });
                            setTimeout(function () {
                                styledialog.closeDialog();
                            }, 1000);
                        }
                        else {
                            promptMessageDialog({
                                icon: "warning",
                                content: dataobj.msg,
                                time: 1000
                            });
                            btnloading.toBtn();
                        }
                    });
                });
            });
        });

    });

});

/*
**资源上传时类型选择方法
*/
jQuery.fn.extend({
    mulitSelectEvent: function (nextSelect) {
        var mian = this;
        return $(mian).die("change").live("change", function () {
            var selectVal = "";  //获取Select选择的项   
            selectVal = $(mian).find("option:selected").attr('id');
            var nextSel = mian;
            while ($(nextSel).next("select").length > 0) {
                $(nextSel).next("select").html('<option value="">请选择</option>');
                nextSel = $(nextSel).next("select");
            }
            $("#slt_zs").html('<option value="">请选择</option>');

            $("div.leftInfo").find("strong").find("a").each(function () {
                var mian = this;
                $(mian).attr("href", changeHref($(mian).attr("href")));
            });

            $.post(commonParams.jcDevPath + commonParams.jcType + '/resource/getNode/fid/' + selectVal, null, function (data) {
                var obj = null;
                try {
                    obj = eval('(' + data + ')');
                }
                catch (e) {
                    obj = data;
                }
                if (nextSelect == "slt_xk") {
                    for (var i = 0; i < obj.length; i++) {
                        if (obj[i].code == $("#hdn_xk").val()) {
                            $.post(commonParams.jcDevPath + commonParams.jcType + '/resource/getNode/fid/' + obj[i].id, null, function (dataXk) {
                                var objXk = null;
                                try {
                                    objXk = eval('(' + dataXk + ')');
                                }
                                catch (e) {
                                    objXk = data;
                                }
                                var selectHtml = '<option value="">请选择</option>';
                                for (var i = 0; i < objXk.length; i++) {
                                    selectHtml += '<option id="' + objXk[i].id + '" value="' + objXk[i].code + '">' + objXk[i].name + '</option>';
                                }
                                $('#slt_bb').html(selectHtml);
                                if (objXk.errorno == 1) {
                                    window.location.href = "/User";
                                }
                            });
                            return;
                        }
                    }
                }
                else {
                    var selectHtml = '<option value="">请选择</option>';
                    for (var i = 0; i < obj.length; i++) {
                        selectHtml += '<option id="' + obj[i].id + '" value="' + obj[i].code + '">' + obj[i].name + '</option>';
                    }
                    $('#' + nextSelect + '').html(selectHtml);
                }
                if (obj.errorno == 1) {
                    window.location.href = "/User";
                }
            });
        });
    },
    hybridSelectEvent: function (nextSelect) {
        var mian = this;
        return $(mian).die("change").live("change", function () {
            $("div.leftInfo").find("strong").find("a").each(function () {
                var mian = this;
                $(mian).attr("href", changeHref($(mian).attr("href")));
            });

            $.post(commonParams.jcDevPath + commonParams.jcType + '/resource/getUnit/xd/' + $("#slt_xd").val() + '/xk/' + $("#hdn_xk").val() + '/bb/' + $("#slt_bb").find("option:selected").val() + '/nj/' + $("#slt_nj").find("option:selected").val(), null, function (data) {
                var obj = null;
                try {
                    obj = eval('(' + data + ')');
                }
                catch (e) {
                    obj = data;
                }
                if (obj) {
                    conListHTML = '<li class="unitCell"><div class="lesson_name lesson_all"><a href="' + commonParams.jcType + '/resource/' + $("#slt_xd").val() + '-' + $("#slt_bb").find("option:selected").val() + '-' + $("#slt_nj").find("option:selected").val() + '-0" data-id="0"><strong>全部课程</strong></a></div><div class="f-cb"></div></li>';
                    for (var i = 0; i < obj.length; i++) {
                        if (obj[i].node_fid == "0") {
                            if (i != 0) {
                                conListHTML += '</ul></li>';
                            }
                            conListHTML += '<li class="unitCell"><a href="' + commonParams.jcType + '/resource/' + obj[i].xd + '-' + obj[i].bb + '-' + obj[i].nj + '-' + obj[i].id + '" data-id="' + obj[i].id + '" class="lesson_name lesson_point" title=""><strong>' + obj[i].node_title + '</strong></a><ul class="lessonList lesson_line">';
                        }
                        else {
                            conListHTML += '<li><a href="' + commonParams.jcType + '/resource/' + obj[i].xd + '-' + obj[i].bb + '-' + obj[i].nj + '-' + obj[i].id + '" data-id="' + obj[i].id + '">' + obj[i].node_title + '</a></li>';
                        }
                    }
                    conListHTML += '</ul></li><li class="unitCellEnd"><i class="lesson_end"></i></li>';
                    $("ul.unitList").html(conListHTML);
                    //$("ul.unitList").jscroll({
                    //    W: "8px"
                    //});
                }
                else {
                    $("ul.unitList").html('<ul class="unitList" style=" position: relative;"><li class="list-empty">没有课程目录！请选择教材对应的年级</li></ul>');
                }
            });
        });
    },
    locationSlectEvent: function () {
        var mian = this;
        return $(mian).die("change").live("change", function () {
            var xdVal = $('#slt_xd').val() ? $('#slt_xd').val() : "0"
            var bbVal = $('#slt_bb').val() ? $('#slt_bb').val() : "0";
            var njVal = "0";
            if ($(mian).attr("id") == "slt_nj") {
                njVal = $('#slt_nj').val();
            }
            window.location.href = commonParams.jcDevPath + commonParams.jcType + "/resource/" + xdVal + "-" + bbVal + "-" + njVal + "-0";
        });
    }
});

function changeHref(hrefStr) {
    var locArr = hrefStr.split("?");
    var paramsArr = locArr[0].split("/");
    var xdVal = $('#slt_xd').val() ? $('#slt_xd').val() : "0";
    var bbVal = $('#slt_bb').val() ? $('#slt_bb').val() : "0";
    var njVal = $('#slt_nj').val() ? $('#slt_nj').val() : "0";
    paramsArr[paramsArr.length - 1] = xdVal + "-" + bbVal + "-" + njVal + "-0";
    locArr[0] = paramsArr.join("/");
    return locArr.join("?");
}