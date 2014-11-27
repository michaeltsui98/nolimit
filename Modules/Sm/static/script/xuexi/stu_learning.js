$(document).ready(function () {
    var createEvent = function () {
        //提交我要提问事件
        $("#PopupsFra [name='confirm']").unbind().bind("click", function () {
            var selectValue = $('[name="jcbb"]').find('option:selected').attr('value');
            if (selectValue != '') {
                AjaxForJson(commonParams.jcDevPath + commonParams.jcType + "/Study/addDefaultVersionAjax ", "default_version=" + selectValue, function (data) {
                    if (data.type == 'success') {
                        window.location.href = commonParams.jcDevPath + commonParams.jcType + '/Study/studyDetail/';
                    } else {
                        promptMessageDialog({ icon: "warning", content: data.message });//成功finish；警告warning；错误error；提示hint；疑问query
                    }
                }, null, null);
            }
            return false;
        });
    }

    $('[name="banbenSelect"]').die().live('click', function () {
        var createHTML = '<div class="formStyle-item "><label for="" class="formStyle-label"><span class="formStyle-required">*</span>教材版本：</label><select id="jcbb" name="jcbb"><option value="">请选择</option>';
        var setBBSelect = function (data) {
            if (data == null || data.length == 0) {
                return;
            } else {
                for (i in data) {
                    createHTML += '<option value="' + i + '">' + data[i] + '</option>';
                }
            }
            createHTML += '</select></div>';
            styledialog.initDialogHTML({
                title: "选择版本",
                content: createHTML,
                width: 380,
                confirm: {
                    show: true,
                    name: "确认",
                    disablie: true
                },
                cancel: {
                    show: true,
                    name: "取消"
                }
            });

            styledialog.initContent("选择版本", createHTML, createEvent);
        }
        AjaxForJson(commonParams.jcDevPath + commonParams.jcType + "/Study/getJcVersionAjax", "", setBBSelect, null, null);
    });

    $('[name="submitVersion"]').die().live('click', function () {
        AjaxForJson(commonParams.jcDevPath + commonParams.jcType + "/Study/addDefaultVersionAjax ", "default_version=" + $(this).attr('default_version_id'), function (data) {
            if (data.type == 'success') {
                window.location.href = commonParams.jcDevPath + commonParams.jcType + '/Study/studyDetail/';
            } else {
                promptMessageDialog({ icon: "warning", content: data.message });//成功finish；警告warning；错误error；提示hint；疑问query
            }
        }, null, null);
    });
});