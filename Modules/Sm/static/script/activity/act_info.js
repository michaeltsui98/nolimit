$(document).ready(function () {
    //学员动态轮播
    if ($("#content_ul").find("li").length > 5) {//当动态多于5条是才实现滚动
        function autoScroll(conObj, ulObj) {
            $(conObj).find(ulObj).animate({
                top: "-30px"
            }, 500, function () {
                $(this).css({ top: "0px" }).find("li:first").appendTo(this);
            });
        }
        setInterval(function () {
            $("#content_div").find("#content_ul").animate({
                top: "-30px"
            }, 500, function () {
                $(this).css({ top: "0px" }).find("li:first").appendTo(this);
            })
        }, 3000);
    }
    //视频列表的横向滚动
    $("div.m-relatedVideo").horizontalScroll("18");
    //视频浏览
    if (perview_type) {
        $('#documentViewer').fileView("documentViewer");
    }
    //点击列表播放视频
    $("li[name='cellDiv']").unbind().bind("click", function () {
        id = $(this).attr("video_id");
        file_key = $(this).attr("video_file_key");
        ext = $(this).attr("video_ext");
        $("div#contentViewer").html('<div id="documentViewer" style="width:682px;height:382px;"></div>');
        if (perview_type) {
            $('#documentViewer').fileView("documentViewer");
        }
    });

    //参加研训事件
    $("#join_activity").die().live("click", function () {
        AjaxForJson($(this).attr("apply_url"), "activity_id=" + $(this).attr("activity_id"), function (dataObj) {
            if (dataObj.type == "success") {
                promptMessageDialog({
                    icon: "finish",
                    content: dataObj.message,
                    time: 1000
                });
                setTimeout(function () {
                    window.location.reload();
                }, 1000);
            }
            else {
                promptMessageDialog({
                    icon: "warning",
                    content: dataObj.message,
                    time: 1000
                });
            }
        });
    });

    //研训评分
    $("#remark_activity").toRemark();
});

jQuery.fn.extend({
    horizontalScroll: function (rightwidth) {
        var mian = this;
        var conDiv = $(mian).find("[name='conDiv']");
        var showDiv = $(mian).find("[name='showDiv']");
        var cellDiv = $(mian).find("[name='cellDiv']");
        var toNextA = $(mian).find("a[name='toNextA']");
        var toPreA = $(mian).find("a[name='toPreA']");
        var rightWidth = rightwidth ? rightwidth : 0;

        var width = (cellDiv.width() + parseInt(rightWidth)) * cellDiv.length;
        conDiv.css("width", width);

        toNextA.unbind().bind("click", function () {
            if (conDiv.is(":animated")) {
                return;
            }
            var toLeftLimit = (parseInt(conDiv[0].clientWidth / showDiv[0].clientWidth) - 1) * showDiv[0].clientWidth;
            if (conDiv.offset().left < showDiv.offset().left) {
                conDiv.animate({ left: conDiv.offset().left - showDiv.offset().left + showDiv[0].clientWidth }, 800, function () {
                    //if (conDiv.offset().left <= -(toLeftLimit - showDiv.offset().left)) {

                    //}
                    //else if (showDiv.offset().left > conDiv.offset().left && conDiv.offset().left > -(toLeftLimit - showDiv.offset().left)) {

                    //}
                    //else if (conDiv.offset().left >= showDiv.offset().left) {

                    //}
                });
            }
            return false;
        });

        toPreA.unbind().bind("click", function () {
            if (conDiv.is(":animated")) {
                return;
            }
            var toLeftLimit = (Math.ceil(conDiv[0].clientWidth / showDiv[0].clientWidth) - 1) * showDiv[0].clientWidth;
            if (conDiv.offset().left > -(toLeftLimit - showDiv.offset().left)) {
                conDiv.animate({ left: conDiv.offset().left - showDiv.offset().left - showDiv[0].clientWidth }, 800, function () {
                    //if (conDiv.offset().left <= -(toLeftLimit - showDiv.offset().left)) {

                    //}
                    //else if (showDiv.offset().left > conDiv.offset().left > -(toLeftLimit - showDiv.offset().left)) {

                    //}
                    //else if (conDiv.offset().left >= showDiv.offset().left) {

                    //}
                });
            }
            return false;
        });
    }
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

                var remarkHTML = '<div class="inner">';
                remarkHTML += '<ul class="f-mb10"><input name="hdn_ty" type="hidden" value="0"><li><strong class="font14">你来评分：</strong><span class="orangeTxt">点击星星就能打分！</span></li><li class="rateControl" id="li_zs"><span>游戏评分</span><span name="i_stars"><i class="icon_tc2" style="cursor: pointer;"></i><i class="icon_tc2" style="cursor: pointer;"></i><i class="icon_tc2" style="cursor: pointer;"></i><i class="icon_tc2" style="cursor: pointer;"></i><i class="icon_tc2" style="cursor: pointer;"></i></span><span class="orangeTxt"></span></li></ul>';
                remarkHTML += '<div class="f-cb "></div></div>';
                styledialog.initDialogHTML({
                    title: "评分",
                    content: remarkHTML,
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
                    $("li#li_zs").rateControl(new Array('1分', '2分', '3分', '4分', '5分'), "zsIndex");

                    $("#PopupsFunc input[name='confirm']").unbind().bind("click", function () {
                        var zs = rateStarParams.zsIndex ? rateStarParams.zsIndex + 1 : 0;
                        AjaxForJson($(mian).attr("evaluate_url"), "activity_member_pid=" + $(mian).attr("activity_member_pid") + "&activity_member_uid=" + $(mian).attr("activity_member_uid") + "&activity_member_evaluate=" + zs, function (data) {
                            if (data.type == "success") {
                                styledialog.closeDialog();
                                promptMessageDialog({
                                    icon: "finish",
                                    content: data.message,
                                    time: 1000
                                });
                                setTimeout(function () {
                                    window.location.reload();
                                }, 1000);
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