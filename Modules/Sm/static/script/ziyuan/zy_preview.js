$(document).ready(function () {
    $('#documentViewer').fileView("documentViewer");
    $("#fav").toCollect();
    $("#remark").toRemark();
    $("li.downLoadWrap").find("a.btn3").toDownload();
});

var rateStarParams = {
    zsIndex: null,
    tyIndex: null
};

//浏览页面的相关操作
jQuery.fn.extend({
    //收藏
    toCollect: function () {
        var mian = this;
        return $(mian).unbind().bind("click", function () {
            if ($(mian).attr("data-uid") == "") {
                loginDialog();
            }
            else {
                $.ajaxJSONP(commonParams.wenkuPath + "/info/fav/id/" + $(mian).attr("data-id"), null, function (data) {
                    if (data.status == -1) {
                        promptMessageDialog({
                            icon: "warning",
                            content: data.msg,
                            time: 1000
                        });
                    }
                    else {
                        promptMessageDialog({
                            icon: "finish",
                            content: data.msg,
                            time: 1000
                        });
                    }
                });
            }
            return false;
        });
    },
    //评分操作
    toRemark: function () {
        var mian = this;
        return (function () {
            $(mian).unbind().bind("click", function () {
                //$("div.ratePopUps").css("display", "block");
                if ($(mian).attr("data-uid") == "") {
                    loginDialog();
                }

                else {
                    styledialog.initDialogHTML({
                        title: "评分",
                        url: commonParams.jcDevPath + commonParams.jcType + "/resource/remark",
                        width: 330,
                        confirm: {
                            show: true,
                            name: "确认"
                        },
                        cancel: {
                            show: true,
                            name: "取消"
                        }
                    });
                    styledialog.initContent("评分", "", function () {
                        $("li#li_zs").rateControl(new Array('完全没用', '有点用，但是没写完', '用处一般', '内容丰富，比较有帮助', '内容非常饱满，表达清晰有亮点', ''), "zsIndex");
                        $("li#li_ty").rateControl(new Array('格式错乱，没法读', '部分内容可读', '能读，但体验不好', '清晰可读', '超赞，适合在线阅读', ''), "tyIndex");

                        $("#PopupsFunc input[name='confirm']").unbind().bind("click", function () {
                            var zs = rateStarParams.zsIndex ? rateStarParams.zsIndex + 1 : 0;
                            var ty = rateStarParams.tyIndex ? rateStarParams.tyIndex + 1 : 0;
                            $.ajaxJSONP(commonParams.wenkuPath + "/info/mark_post/id/" + $(mian).attr("data-id") + "/zs/" + zs + "/ty/" + ty, null, function (data) {
                                if (data.status == 1) {
                                    styledialog.closeDialog();
                                    promptMessageDialog({
                                        icon: "finish",
                                        content: data.msg,
                                        time: 1000
                                    });
                                }
                                else if (data.status == -1) {
                                    styledialog.closeDialog();
                                    promptMessageDialog({
                                        icon: "warning",
                                        content: data.msg,
                                        time: 1000
                                    });
                                }
                            });
                            return false;
                        });
                    });
                }
                return false;
            });
        })();
    },
    //滑动评分插件
    rateControl: function (arrMark, starIndex) {
        var rateStarIndex = null;
        var main = this;
        var markAr = arrMark;
        if (this.length <= 0) {
            return false;
        }
        $(main).each(function () {
            var rateStarIcon = $(main).find("i");
            var rateStarLevel = $(main).find("span.orangeTxt");
            var markAr = arrMark;
            rateStarIcon.css("cursor", "pointer");
            rateStarIcon.bind({
                mouseover: function () {
                    var index = rateStarIcon.index($(this));
                    for (var n = 0; n < index + 1; n++) {
                        rateStarIcon[n].className = "icon_tc3";
                    }
                    rateStarLevel.html(markAr[index]);
                },
                mouseout: function () {
                    rateStarIcon.attr("class", "icon_tc2");
                    rateStarLevel.html(markAr[5]);
                },
                click: function () {
                    switch (starIndex) {
                        case "zsIndex":
                            rateStarParams.zsIndex = rateStarIcon.index($(this));
                            break;
                        case "tyIndex":
                            rateStarParams.tyIndex = rateStarIcon.index($(this));
                            break;
                        default:
                            break;
                    }
                    rateStarIndex = rateStarIcon.index($(this));
                    $("div.ratePopUps").find("a[name='a_confirm']").attr("class", "btnStyle1");
                    return false;
                }
            });
            $(this).find("span[name='i_stars']").bind("mouseleave", function () {
                if (rateStarIndex != null) {
                    for (var n = 0; n < rateStarIndex + 1; n++) {
                        rateStarIcon[n].className = "icon_tc3";
                    }
                    rateStarLevel.html(markAr[rateStarIndex]);
                }
            });
        });
    },
    //下载操作
    toDownload: function () {
        var mian = this;
        return $(mian).unbind().bind("click", function () {
            if ($(mian).attr("data-uid") == "") {
                loginDialog();
            }
            else {
                fileDownDialog(commonParams.wenkuPath + "/info/down?id=" + $(this).attr("data-url"));
            }
            return false;
        });
    }
});