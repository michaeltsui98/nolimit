$(document).ready(function () {
    //初始化搜索列表
    $("div[name='current_order']").showList();
    getContent.getList();

    //加载更多
    $("div.m-studyActiveList").loadMore({
        requestUrl: $("input[name='ajax_activity_url']").val(),
        requestData: "key=" + $("input[name='key']").val() + "&current_order=" + $("div[name='current_order']").find("a.cur").attr("option_value"),
        callback: function (dataObj) {
            if (dataObj.type == "success") {
                var html = '';
                for (var i = 0; i < dataObj.data.rows.length; i++) {
                    if (i % 3 == 0) {
                        var mtCls = i == 0 ? "" : "f-mt20";
                        html += '<div class="row ' + mtCls + '">';
                    }
                    var mrCls = i % 3 == 2 ? "" : "f-mr20";

                    var iconTc = '';
                    var evaluate = parseFloat(dataObj.data.rows[i].activity_info_evaluate);
                    for (var j = 0; j < 5; j++) {
                        if (evaluate <= j) {
                            iconTc += '<i class="icon_tc2"></i>';
                        }
                        else if (evaluate > j && evaluate < j + 1) {
                            iconTc += '<i class="icon_tc4"></i>';
                        }
                        else if (evaluate >= j + 1) {
                            iconTc += '<i class="icon_tc3"></i>';
                        }
                    }

                    html += '<div class="rowItem s-bsdStyle1 f-fl ' + mrCls + '">';
                    html += '<a href="' + $("input[name='activity_info_url']").val() + '?activity_id=' + dataObj.data.rows[i].id + '" target="_blank"><div class="cover"><div class="title">' + dataObj.data.rows[i].activity_info_title + '</div><img src="' + $('input[name="attachment_url"]').val() + dataObj.data.rows[i].activity_info_icon + '" alt="" width="232px" height="155px"></div></a>';
                    html += '<ul class="info"><li class="desc"><h3>活动描述：</h3><p class="">' + dataObj.data.rows[i].activity_info_description + '</p></li>';
                    html += '<li class="data"><div class="docRate clearfix">' + iconTc + ' ' + dataObj.data.rows[i].activity_info_evaluate + '<em class="f-fs12 s-fcGray">(' + dataObj.data.rows[i].activity_info_member + '参与)</em></div></li>';
                    html += '</ul></div>';
                    if (i % 3 == 2 || i == dataObj.data.rows.length - 1) {
                        html += '<div class="f-cb"></div></div>';
                    }
                }
                $("div.m-studyActiveList").find("div.ct").append(html);
            }
            return dataObj.data.rows.length;
        },
        pageCount: 6
    });
});

jQuery.fn.extend({
    showList: function () {
        var mian = this;

        return $(mian).find("a").unbind().bind("click", function () {
            if (getContent.isLoaded) {
                $(mian).find("a").removeClass("cur");
                $(this).addClass("cur");
                getContent.getList();
            }
            return false;
        });
    }
});

var getContent = {
    isLoaded: true,
    getList: function () {
        getContent.isLoaded = false;
        var current_order = $("div[name='current_order']").find("a.cur").attr("option_value");

        var requestUrl = $("input[name='ajax_activity_url']").val();
        var requestData = "key=" + $("input[name='key']").val() + "&current_order=" + current_order;
        AjaxForJson(requestUrl, requestData, function (dataObj) {
            getContent.isLoaded = true;
            if (dataObj.type == "success") {
                var html = '';
                for (var i = 0; i < dataObj.data.rows.length; i++) {
                    if (i % 3 == 0) {
                        var mtCls = i == 0 ? "" : "f-mt20";
                        html += '<div class="row ' + mtCls + '">';
                    }
                    var mrCls = i % 3 == 2 ? "" : "f-mr20";

                    var iconTc = '';
                    var evaluate = parseFloat(dataObj.data.rows[i].activity_info_evaluate);
                    for (var j = 0; j < 5; j++) {
                        if (evaluate <= j) {
                            iconTc += '<i class="icon_tc2"></i>';
                        }
                        else if (evaluate > j && evaluate < j + 1) {
                            iconTc += '<i class="icon_tc4"></i>';
                        }
                        else if (evaluate >= j + 1) {
                            iconTc += '<i class="icon_tc3"></i>';
                        }
                    }

                    html += '<div class="rowItem s-bsdStyle1 f-fl ' + mrCls + '">';
                    html += '<a href="' + $("input[name='activity_info_url']").val() + '?activity_id=' + dataObj.data.rows[i].id + '" target="_blank"><div class="cover"><div class="title">' + dataObj.data.rows[i].activity_info_title + '</div><img src="' + $('input[name="attachment_url"]').val() + dataObj.data.rows[i].activity_info_icon + '" alt="" width="232px" height="155px"></div></a>';
                    html += '<ul class="info"><li class="desc"><h3>活动描述：</h3><p class="">' + dataObj.data.rows[i].activity_info_description + '</p></li>';
                    html += '<li class="data"><div class="docRate clearfix">' + iconTc + ' ' + dataObj.data.rows[i].activity_info_evaluate + '<em class="f-fs12 s-fcGray">(' + dataObj.data.rows[i].activity_info_member + '参与)</em></div></li>';
                    html += '</ul></div>';
                    if (i % 3 == 2 || i == dataObj.data.rows.length - 1) {
                        html += '<div class="f-cb"></div></div>';
                    }
                }
                $("div.m-studyActiveList").find("div.ct").html(html);

                $("div.m-studyActiveList").find("#loadingImg").css("display", "none");
                if (dataObj.data.rows.length < 6) {
                    $("div.m-studyActiveList").find("#loadContent").css("display", "none");
                }
                else {
                    $("div.m-studyActiveList").find("#loadContent").css("display", "block");
                }
            }
        });
    }
}