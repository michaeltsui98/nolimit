$(document).ready(function () {
    $('#slt_xd').mulitSelectEvent("slt_xk");
    $('#slt_bb').mulitSelectEvent("slt_nj");
    $('#slt_nj').hybridSelectEvent();

    getResourceList();
    //最新最热排名
    $("div.rightInfo").find("a").die().live("click", function () {
        $(this).parent().find("strong").replaceWith('<a href="javascript:;" data-order="' + $(this).parent().find("strong").attr('data-order') + '" class="whiteA">' + $(this).parent().find("strong").text() + '<i class="icon-orderdown-nocur"></i></a>')

        $(this).replaceWith('<strong class="cur" data-order="' + $(this).attr('data-order') + '">' + $(this).text() + '<i class="icon-orderdown"></i></strong>');

        getResourceList();
        return false;
    });
    //类型切换
    $("div.leftInfo").find("strong").find("a").unbind().bind("click", function () {
        $("div.leftInfo").find("strong").removeClass("cur");
        $(this).parent().addClass("cur");
        getResourceList();
    });
    //选择关键字
    //$("ul#ul_key").find("span.name").find("a").unbind().bind("click", function () {
    //    $("#hdn_key").val($(this).attr("data-key"));
    //    getResourceList();
    //    return false;
    //});
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

                                getResourceList();

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

                    getResourceList();
                }
                if (obj.errorno == 1) {
                    window.location.href = "/User";
                }
            });
        });
    },
    hybridSelectEvent: function () {
        var mian = this;
        return $(mian).die("change").live("change", function () {

            getResourceList();
        });
    }
});

function getResourceList() {
    var xd = $("#slt_xd").val() ? $("#slt_xd").val() : "0";
    var bb = $("#slt_bb").val() ? $("#slt_bb").val() : "0";
    var nj = $("#slt_nj").val() ? $("#slt_nj").val() : "0";
    var countUrl = commonParams.jcType + "/resource/getSearchCount"
    var requestUrl = commonParams.jcType + "/resource/getsearch";
    var requestData = "key=" + $("input#hdn_key").val() + "&xd=" + xd + "&bb=" + bb + "&nj=" + nj + "&type=" + $("div.leftInfo").find("strong.cur").find("a").attr("data-type") + "&order=" + $("div.rightInfo").find("strong.cur").attr("data-order");
    contentLoading($("div#list_content"));
    AjaxForJson(countUrl, requestData, function (dataObj) {
        $("#em_count").html(dataObj.data.count);//绑定在页面搜索结果个数
        var requestMenberpage = new jsPage(dataObj.data.count, "pagehtml", "20", requestUrl, requestData, function (obj) {
            if (obj.type == "success") {
                if (obj.data.count <= 0) {
                    $("div#list_content").html('<div class="list-empty">暂时没有数据！</div>');
                }
                else {
                    $("div#list_content").html(obj.data.data);

                }
            }
            else {
                $("div#list_content").html('<li class="list-empty">数据有问题！</li>');
            }
        });
        pageMethod.call(requestMenberpage);
    });
}