$(document).ready(function () {
    $('[name="del"]').die().live('click', function () {
        var dataUrl = $(this).attr('data-url');
        function toAgreeDelFile(main) {
            function responesFun(data) {
                if (data.type == 'success') {
                    $(main).parents('li').remove();
                }
                promptMessageDialog({ icon: "finish", content: data.message });//成功finish；警告warning；错误error；提示hint；疑问query
            }
            AjaxForJson(dataUrl, '', responesFun, null, null);
        }
        cueDialog(toAgreeDelFile, this, false, '你确定要删除？');
    });
    $('[name="edit"]').die().live('click', function () {
        var dataUrl = $(this).attr('data-url');
        loadjscssfile(commonParams.jcDevPath + "/Modules" + commonParams.jcType + "/static/css/uploader-thirdCss.css", "css");
        resourceUploadDialog(dataUrl, "资源编辑", function () {
            //loadjscssfile(commonParams.jcDevPath + "/Modules/Xl/static/script/ziyuan/zy_resupload.js", "js");
            $.cachedScript(commonParams.jcDevPath + "/Modules" + commonParams.jcType + "/static/script/ziyuan/zy_resupload.js?" + Math.random()).done(function () {
                $('#up_slt_bb').mulitSelectUpEvent("up_slt_nj");
                $('#up_slt_nj').hybridSelectUpEvent("up_slt_zs");
                $("#up_slt_xd").getBbSelectUpEvent();
                //提交表单
                $("#PopupsFunc").find("input[name='confirm']").unbind().bind("click", function () {
                    var btnloading = new btnLoading(this);
                    btnloading.toLoading();//提交按钮变loading图片
                    var data = $("form[name='frm1']").serialize();
                    AjaxForJson($("form[name='frm1']").attr('action'), data, function (dataobj) {
                        //alert(dataobj);
                        btnloading.toBtn();//loading图片变回提交按钮
                        styledialog.closeDialog();
                        promptMessageDialog({ icon: dataobj.type, content: dataobj.message });//成功finish；警告warning；错误error；提示hint；疑问query
                        setTimeout(function () {
                            window.location.reload();
                        }, 1000);
                    });
                });
            });
        });
    });

});

