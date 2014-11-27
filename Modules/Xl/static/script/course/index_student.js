jQuery(function ($) {
    //上一月的课程安排
    $("a#prevmonth").die().live("click", function () {
        var starting_point = $("strong#thismonth");
        var starting_month = starting_point.data("month");
        function turntopremonth(obj) {
            $("div#courseList").html(obj);
        }

        AjaxForJson(commonParams.jcDevPath + commonParams.jcType + "/Course/courseForStudentByMonth", "themonth=" + starting_month + "&diff=-1", turntopremonth, null);

    });
    //下一月的课程安排
    $("a#nextmonth").die().live("click", function () {
        var starting_point = $("strong#thismonth");
        var starting_month = starting_point.data("month");
        function turntopremonth(obj) {
            $("div#courseList").html(obj);
        }

        AjaxForJson(commonParams.jcDevPath + commonParams.jcType + "/Course/courseForStudentByMonth", "themonth=" + starting_month + "&diff=1", turntopremonth, null);

    });
    //增加课程评价
    $("a.addAppraise").die().live("click", function () {
        var course_id = $(this).data("course");
        var dialogHtml = '<div class="bd f-mt10 f-mb10"> <form id="appraiseForm" class="m-addAppraisal s-bgc clearfix"><div class="addMarks"><span>课程评分：</span>';
        dialogHtml += '<span name="i_stars"><i class="icon_tc2" style="cursor: pointer;" title="2分"></i><i class="icon_tc2" style="cursor: pointer;" title="4分"></i><i class="icon_tc2" style="cursor: pointer;" title="6分"></i><i class="icon_tc2" style="cursor: pointer;" title="8分"></i><i class="icon_tc2" style="cursor: pointer;" title="10分"></i></span></div>';
        dialogHtml += ' <div class="addTxt"><textarea name="remark"  cols="66" rows="4" maxLength="100" placeholder="最多可输入150个字符" class="customForm_inputBoxDefault"></textarea></div></form></div>';
        styledialog.initDialogHTML({
            title: "新增评价",
            content: dialogHtml,
            width : 425,
            confirm: {
                show: true,
                name: "提交"
            },
            cancel: {
                show: true,
                name: "取消"
            }
        });
        styledialog.initContent("新增评价", dialogHtml, function () {
            $("div.addMarks").rateControl(new Array('2分', '4分', '6分', '8分', '10分'), "zsIndex");
        });

        //保存课程评价数据
        $("input[name='confirm']").die().live("click", function () {
            var form = $("form#appraiseForm");
            var score = (rateStarParams.zsIndex + 1) * 2;
            var remark = form.find("textarea[name='remark']").val();
            function appraiseInit(obj) {
                if (obj.type == 'success') {
                    promptMessageDialog({
                        icon: "finish",
                        content: "提交成功！"
                    });
                    setTimeout(function () {
                        window.location.reload();

                    }, 1000);
                } else {
                    promptMessageDialog({
                        icon: "warning",
                        content: obj.message
                    });
                }
                styledialog.closeDialog();
                rateStarParams.zsIndex = -1;
            }

            AjaxForJson(commonParams.jcDevPath + commonParams.jcType + "/Course/addAppraise", "course_id=" + course_id + "&score=" + score + "&remark=" + remark, appraiseInit, null);

        });
    });
});


var rateStarParams = {
    zsIndex: -1
};

jQuery.fn.extend({
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
                    rateStarParams.zsIndex = rateStarIcon.index($(this));
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
