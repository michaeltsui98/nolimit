/*
**资源上传时类型选择方法
*/
jQuery.fn.extend({
    mulitSelectUpEvent: function (nextSelect) {
        var mian = this;
        return $(mian).die("change").live("change", function () {
            var selectVal = $(mian).find("option:selected").attr('id');  //获取Select选择的项   
            var nextSel = mian;
            while ($(nextSel).next("select").length > 0) {
                $(nextSel).next("select").html('<option value="">请选择</option>');
                nextSel = $(nextSel).next("select");
            }
            //$("#up_slt_zs").html('<option value="">请选择</option>');

            $.ajaxJSONP(commonParams.wenkuPath + '/Upload/node/fid/' + selectVal + '/is_jsonp/1', null, function (data) {
                var obj = null;
                try {
                    obj = eval('(' + data + ')');
                }
                catch (e) {
                    obj = data;
                }
                var selectHtml = '<option value="">请选择</option>';
                for (x in obj) {
                    var val = '';
                    var name = '';
                    for (y in obj[x]) {
                        val = y;
                        name = obj[x][y];
                    }
                    selectHtml += '<option id="' + x + '" value="' + val + '">' + name + '</option>';
                }
                $('#' + nextSelect + '').html(selectHtml);
                if (obj.errorno == 1) {
                    window.location.href = "/User";
                }
            });
        });
    },
    hybridSelectUpEvent: function (nextSelect) {
        var mian = this;
        return $(mian).die("change").live("change", function () {
            $.ajaxJSONP(commonParams.wenkuPath + '/Upload/unit', { 'xd': $("#up_slt_xd").val(), 'xk': $("#up_slt_xk").val(), 'bb': $("#up_slt_bb").find("option:selected").val(), 'nj': $("#up_slt_nj").find("option:selected").val(), "is_jsonp": 1 }, function (data) {
                var obj = null;
                try {
                    obj = eval('(' + data + ')');
                }
                catch (e) {
                    obj = data;
                }
                var selectHtml = '<option value="">请选择</option>';
                for (x in obj) {
                    var val = '';
                    var name = '';
                    selectHtml += obj[x].option;
                }
                $('#' + nextSelect).html(selectHtml);
            });
        });
    },
    getBbSelectUpEvent: function () {
        var mian = this;
        return $(mian).die("change").live("change", function () {
            var selectVal = "";  //获取Select选择的项   
            selectVal = $(mian).find("option:selected").attr('id');

            $.post(commonParams.jcDevPath + commonParams.jcType + '/resource/getNode/fid/' + selectVal, null, function (data) {
                var obj = null;
                try {
                    obj = eval('(' + data + ')');
                }
                catch (e) {
                    obj = data;
                }
                for (var i = 0; i < obj.length; i++) {
                    if (obj[i].code == $("#up_slt_xk").val()) {
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
                            $('#up_slt_bb').html(selectHtml);
                            $('#up_slt_nj').html('<option value="">请选择</option>');
                            $('#up_slt_zs').html('<option value="">请选择</option>');
                            if (objXk.errorno == 1) {
                                window.location.href = "/User";
                            }
                        });
                        return;
                    }
                }
                if (obj.errorno == 1) {
                    window.location.href = "/User";
                }
            });
        });
   
    }
});

if ($("#demo2").length > 0) {
    var resParams = {
        fileType: file_type,
        initUpload: function (options) {
            var settings = {
                path: commonParams.jcDevPath + '/static/',
                divId: 'demo2',
                formId: 'input2',
                tipId: 'tip2',
                upText: '选择文件',
                upMax: 1,//允许上传是文件数量 默认1个
                upMaxsize: 1024 * 1024 * 200,
                upFilter: resParams.fileType[1],
                upImageFile: 1,//是否文件方式上传
                upUrl: commonParams.jcDevPath + '/resource/update?PHPSESSID=' + sid,
                upProgressTip: '已上传：{progress}',
                skin: commonParams.jcDevPath + '/static/syup/skin/green',
                upCallback: 'resParams.uploadCallback'
            }
            $.extend(settings, options || {});
            //加载上传flash
            new syup({
                path: settings.path,
                div_id: settings.divId,
                form_id: settings.formId,
                tip_id: settings.tipId,
                up_text: settings.upText,
                up_max: settings.upMax,//允许上传是文件数量 默认1个
                up_maxsize: settings.upMaxsize,
                up_filter: settings.upFilter,
                up_image_file: settings.upImageFile,//是否文件方式上传
                up_url: settings.upUrl,
                up_progress_tip: settings.upProgressTip,
                skin: settings.skin,
                up_callback: settings.upCallback
            }).show();
        },
        uploadCallback: function (up_obj, type, str) {
            if (type == 'file_select') {
                //document.getElementById(up_obj.form_id).value = str;
            }
            if (type == 'one_complete') {
                var data = eval('(' + str + ')');
                document.getElementById(up_obj.form_id).value = data.file_path + "," + data.file_name;
                document.getElementById("file_size").value = data.size;
                $("input[name='data[doc_title]']").val(getFileName(data.file_name));
                //var obj = document.getElementById(up_obj.tip_id);
                //obj.innerHTML = "上传成功";
            }
            if (type == 'progress') {
                //var obj = document.getElementById(up_obj.tip_id);
                //obj.innerHTML = str;
            }
            if (type == 'error') {
                promptMessageDialog({
                    icon: "warning",
                    content: str,
                    time: 1000
                });
            }
            if (type == 'start') {

            }
        }
    }

    resParams.initUpload();

    //选择资源类型
    $("#select_cate").unbind().bind("change", function () {
        $("input[name='data[file]").val("");
        document.getElementById("file_size").value = "";
        //$("input[name='data[doc_title]").val("");
        $("#tip2").html("");
        var typeId = $(this).find("option:selected").val();
        var upmaxsize;
        if (typeId == "4" || typeId == "5" || typeId == "6") {
            upmaxsize = 1024 * 1024 * 500;
        }
        else {
            upmaxsize = 1024 * 1024 * 20;
        }
        resParams.initUpload({ upFilter: resParams.fileType[typeId], upMaxsize: upmaxsize });
        //$("#tip2").html(resParams.fileType[typeId]);
    });

    //提交表单
    $("input#btn_upload").unbind().bind("click", function () {
        var data = $("form[name='frm1']").serialize();
        $.ajaxJSONP(commonParams.wenkuPath + "/upload/resUploadPost", data, function (dataobj) {
            if (dataobj == 1) {
                promptMessageDialog({
                    icon: "finish",
                    content: "提交成功",
                    time: 1000
                });
            }
            else {
                promptMessageDialog({
                    icon: "warning",
                    content: data.msg,
                    time: 1000
                });
            }
        });
    });
}