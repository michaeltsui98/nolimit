$(document).ready(function () {
    $("select[name='xd']").mulitSelectUpEvent("select[name='xk']");
    $("select[name='xk']").mulitSelectUpEvent("select[name='bb']");
    $("select[name='bb']").mulitSelectUpEvent("select[name='nj']");

    //提交表单返回数据填入文本框
    $("input#tj").unbind().bind("click", function () {
        var data = $("form").serialize();
        $.post("/Gen/genUnitWap", data, function (dataobj) {
            $("textarea[name='wap_url']").val(dataobj);
        });
    });
});

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
                $(nextSelect).html(selectHtml);
            });
        });
    }
});