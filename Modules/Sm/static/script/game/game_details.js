$(document).ready(function () {
    $("a#remark").toRemark();
});

var rateStarParams = {
    zsIndex: null
};

jQuery.fn.extend({
    toRemark: function () {
        var mian = this;
        return (function () {
            $(mian).unbind().bind("click", function () {
                if ($(mian).attr("data-userid") == "") {
                    loginDialog();
                    return false;
                }

                styledialog.initDialogHTML({
                    title: "评分",
                    url: commonParams.jcDevPath + commonParams.jcType + "/game/remark",
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
                    $("li#li_zs").rateControl(new Array('2分', '4分', '6分', '8分', '10分'), "zsIndex");

                    $("#PopupsFunc input[name='confirm']").unbind().bind("click", function () {
                        var zs = rateStarParams.zsIndex ? rateStarParams.zsIndex + 1 : 0;
                        AjaxForJson(commonParams.jcDevPath + commonParams.jcType + "/game/remarkDo/id/" + $(mian).attr("data-id") + "/hdn_ty/" + zs, null, function (data) {
                            if (data.type == "success") {
                                styledialog.closeDialog();
                                promptMessageDialog({
                                    icon: "finish",
                                    content: data.message,
                                    time: 1000
                                });
                            }
                            else if (data.type == "error") {
                                styledialog.closeDialog();
                                promptMessageDialog({
                                    icon: "warning",
                                    content: data.message,
                                    time: 1000
                                });
                            }
                        });
                        return false;
                    });
                });

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
    }
});