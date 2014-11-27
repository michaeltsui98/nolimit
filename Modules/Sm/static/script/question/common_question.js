$(document).ready(function () {
    addQusetion();
    //左侧TAB切换
    $('.tab4Menu').tab2Menu(function (tabName) {
        if (tabName == 'qusetionTab') {
            $('[name="qusetionList"]').css('display', '');
            $('[name="answerList"]').css('display', 'none');
        }
        else {
            $('[name="qusetionList"]').css('display', 'none');
            $('[name="answerList"]').css('display', '');
        }
        return false;
    });
});

var addQusetion = function () {
    $('.askAndAnswer').die().live('click', function () {
       
        //我要提问 弹窗数据初始化
        function initEvent() {
            //选择年级
            var selectGrade = function (xd_code, year_code) {
                AjaxForJson(commonParams.jcDevPath +commonParams.jcType + "/Question/gradeAjax", 'xd_code=' + xd_code, function (data) {
                    var htmlSelect = '<option value="">请选择</option>';
                     for (i in data) {
                         htmlSelect += '<option value="' + i + '">' + data[i] + '</option>';
                    }
                    $('select[name="data[grade]"]').html(htmlSelect);
                    if (year_code != '') {
                        $('select[name="data[grade]"]').find('option[value="' + year_code + '"]').attr("selected", true);
                    }
                });
            }
            AjaxForJson(commonParams.jcDevPath +commonParams.jcType + "/Question/addAjax", '', function (data) {
                if (data.xd_code != '') {
                    $("select[name='data[stage]'] option[value='" + data.xd_code + "']").attr("selected", true);
                    selectGrade(data.xd_code, data.year_code);
                }
            });
            //选择学段
            $('select[name="data[stage]"]').die().live('change', function () {
                var checkOption = $(this).find("option:selected");  //获取Select选择的项   
                //如果点击“不选”则不做任何操作
                if (checkOption.attr('value') != '') {
                    selectGrade(checkOption.attr('value'), '');
                }
            });

            $("div#PopupsFunc input[name='confirm']").die().live("click", function () {
                var serializeStr = $("form[name='addPublicQuestion']").serialize();
                function responesFun(data) {
                    if (data.type == 'error') {
                        promptMessageDialog({ icon: "error", content: data.message });
                    } else {
                        styledialog.closeDialog();
                    }
                }
                AjaxForJson(commonParams.jcDevPath +commonParams.jcType + "/Question/addPublicQuestion", serializeStr, responesFun, null, null);
            });
        }

        styledialog.initDialogHTML({
            title: "我要提问",
            url: commonParams.jcDevPath +commonParams.jcType + "/Question/addPublicQuestion",
            width: 600,
            confirm: {
                show: true,
                name: "提交"
            },
            cancel: {
                show: true,
                name: "取消"
            }
        });
        styledialog.initContent("我要提问", commonParams.jcDevPath +commonParams.jcType + "/Question/addPublicQuestion", initEvent);
        return false;
    });
}