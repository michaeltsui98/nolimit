$(document).ready(function () {
    $("div.m-studyList").find("ul.tab4Menu").find("li.menuCell").unbind().bind("click", function () {
        if ($(this).attr('class').indexOf('tabMenuCurrent') < 1) {
            $("div.m-studyList").find("ul.tab4Menu").find('li.menuCell').attr('class', 'menuCell f-fl');
            $(this).attr('class', 'menuCell tabMenuCurrent f-fl');
            $("div.m-studyList").find("div.ct").find("ul").css("display", "none");
            $("div.m-studyList").find("div.ct").find("ul[name='" + $(this).attr("name") + "']").css("display", "block");
        }
    });
    //加载更多
    $("div.m-studyActiveList").loadMore({
        requestUrl: $("input[name='ajax_activity_url']").val(),
        requestData: "current_type=" + $("div[name='current_type']").find("li").find("a.selected").attr("option_value") + "&current_class=" + $("div[name='current_class']").find("li").find("a.selected").attr("option_value") + "&current_phase=" + $("div[name='current_phase']").find("li").find("a.selected").attr("option_value") + "&current_order=" + $("div[name='current_order']").find("a.cur").attr("option_value"),
        callback: function (dataObj) {
            if (dataObj.type == "success") {
                var html = '';
                for (var i = 0; i < dataObj.data.rows.length; i++) {
                    if (i % 3 == 0) {
                        html += '<div class="row f-mt20">';
                    }
                    var mrCls = i % 3 == 2 ? "" : "f-mr20";

                    var iconTc = '';
                    var evaluate = parseFloat(dataObj.data.rows[i].activity_avg_evaluate);
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
                    html += '<a href="' + $("input[name='activity_info_url']").val() + '?activity_id=' + dataObj.data.rows[i].activity_info_id + '" target="_blank"><div class="cover"><div class="title">' + dataObj.data.rows[i].activity_info_title + '</div><img src="' + $('input[name="attachment_url"]').val() + dataObj.data.rows[i].activity_info_icon + '" alt="" width="230px" height="155px"></div></a>';
                    html += '<ul class="info"><li class="desc"><h3>活动描述：</h3><p class="">' + dataObj.data.rows[i].activity_info_description + '</p></li>';
                    html += '<li class="data"><div class="docRate clearfix">' + iconTc + ' ' + dataObj.data.rows[i].activity_avg_evaluate + '<em class="f-fs12 s-fcGray">(' + dataObj.data.rows[i].member_total + '参与)</em></div></li>';
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

    $("div[name='current_type']").showList("li");
    $("div[name='current_class']").showList("li");
    $("div[name='current_phase']").showList("li");
    $("div[name='current_order']").showList("a");

    //申请活动弹框
    $("div.m-creatStudy").find("a.imgBtn").unbind().bind("click", function () {
        styledialog.initDialogHTML({
            title: "申请活动",
            url: commonParams.jcDevPath + commonParams.jcType + "/Activity/activityApply",
            width: 642,
            confirm: {
                show: true,
                name: "确认"
            },
            cancel: {
                show: true,
                name: "取消"
            }
        });
        styledialog.initContent("申请活动", "", function () {
            $("#PopupsFunc").find("input[name='confirm']").unbind().bind("click", function () {
                var btnloading = new btnLoading(this);
                btnloading.toLoading(); //提交按钮变loading图片
                var requestData = $("form#apply_form").serialize();
                AjaxForJson($("form#apply_form").attr("action"), requestData, function (dataObj) {
                    if (dataObj.type == "success") {
                        styledialog.closeDialog();
                        styledialog.initDialogHTML({
                            title: "提交成功",
                            content: '<div class="imgDesc"><img src="' + commonParams.jcDevPath + '/Modules' + commonParams.jcType + '/static/images/creatStudyActiveDone.gif" alt="" /></div><div class="txtDesc"><h3>您的活动申请已成功提交专家审核。</h3><p>如审核通过，平台服务人员会与您取得联系，指导您进行相关资料的上传。感谢您的关注和参与！</p></div>',
                            width: 642,
                            confirm: {
                                show: true,
                                name: "我知道了~"
                            },
                            cancel: {
                                show: false,
                                name: "取消"
                            }
                        });
                        styledialog.initContent("提交成功", "", null);
                        $("#PopupsFunc").find("input[name='confirm']").unbind().bind("click", function () {
                            styledialog.closeDialog();
                        });
                    }
                });
            });
        });

        return false;
    });

    getContent.getList();
});

var getContent = {
    isLoaded: true,
    getList: function () {
        getContent.isLoaded = false;
        var current_type = $("div[name='current_type']").find("li").find("a.selected").attr("option_value");
        var current_class = $("div[name='current_class']").find("li").find("a.selected").attr("option_value");
        var current_phase = $("div[name='current_phase']").find("li").find("a.selected").attr("option_value");
        var current_order = $("div[name='current_order']").find("a.cur").attr("option_value");

        var requestUrl = $("input[name='ajax_activity_url']").val();
        var requestData = "current_type=" + current_type + "&current_class=" + current_class + "&current_phase=" + current_phase + "&current_order=" + current_order;
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
                    var evaluate = parseFloat(dataObj.data.rows[i].activity_avg_evaluate);
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
                    html += '<a href="' + $("input[name='activity_info_url']").val() + '?activity_id=' + dataObj.data.rows[i].activity_info_id + '" target="_blank"><div class="cover"><div class="title">' + dataObj.data.rows[i].activity_info_title + '</div><img src="' + $('input[name="attachment_url"]').val() + dataObj.data.rows[i].activity_info_icon + '" alt="" width="230px" height="155px"></div></a>';
                    html += '<ul class="info"><li class="desc"><h3>活动描述：</h3><p class="">' + dataObj.data.rows[i].activity_info_description + '</p></li>';
                    html += '<li class="data"><div class="docRate clearfix">' + iconTc + ' ' + dataObj.data.rows[i].activity_avg_evaluate + '<em class="f-fs12 s-fcGray">(' + dataObj.data.rows[i].member_total + '参与)</em></div></li>';
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

jQuery.fn.extend({
    showList: function (tagType) {
        var mian = this;

        if (tagType == "li") {
            return $(mian).find("ul").find("li").find("a").unbind().bind("click", function () {
                if (getContent.isLoaded) {
                    $(mian).find("ul").find("li").find("a").removeClass("selected");
                    $(this).addClass("selected");
                    getContent.getList();
                }

            });
        }
        else if (tagType == "a") {
            return $(mian).find("a").unbind().bind("click", function () {
                if (getContent.isLoaded) {
                    $(mian).find("a").removeClass("cur");
                    $(this).addClass("cur");
                    getContent.getList();
                }
                return false;
            });
        }
    }
});