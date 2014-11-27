jQuery.fn.extend({
    cldEvent: function () {
        var mian = this;

        var year = $(mian).find("#thismonth").attr("data_year");
        var month = parseInt($(mian).find("#thismonth").attr("data_month")) >= 10 ? $(mian).find("#thismonth").attr("data_month") : "0" + $(mian).find("#thismonth").attr("data_month");

        AjaxForJson($("input[name='ajax_activity_calendar_url']").val(), "data_year=" + year + "&data_month=" + month, function (dataObj) {
            var dataMonth = new Date(year, month, 0);//获取月份下的天数
            var calanderHTML = '<ul class="vsTt clearfix"><li class="everyday f-fs16 f-fl">周日<span class="s-fcLGray f-ml5"></span></li><li class="everyday f-fs16 f-fl">周一<span class="s-fcLGray f-ml5"></span></li><li class="everyday f-fs16 f-fl">周二<span class="s-fcLGray f-ml5"></span></li><li class="everyday f-fs16 f-fl">周三<span class="s-fcLGray f-ml5"></span></li><li class="everyday f-fs16 f-fl">周四<span class="s-fcLGray f-ml5"></span></li><li class="everyday f-fs16 f-fl">周五<span class="s-fcLGray f-ml5"></span></li><li class="everyday f-fs16 f-fl">周六<span class="s-fcLGray f-ml5"></span></li></ul>';
            for (var i = 1; i <= parseInt(dataMonth.getDate()) ; i++) {
                var dayStr = i >= 10 ? i : "0" + i;
                var dataDay = newDate(year + "-" + month + "-" + dayStr);//带参数new Date在IE7下存在问题，会得到NAN的结果
                var dayObj = dataDay.getDay();//获取具体日期下的星期
                if (i == 1) {
                    calanderHTML += '<ul class="vsItem clearfix">';
                    for (var j = 0; j < dayObj; j++) {
                        calanderHTML += '<li class="item1 z-otherMonth f-fl">';
                        calanderHTML += '<dl class="classItem blankDl"><dt class="number"></dt><dt class="name"></dt></dl>';
                        calanderHTML += '</li>';
                    }
                }
                else if (dayObj == 0) {
                    calanderHTML += '</ul><ul class="vsItem clearfix">';
                }
                var todayDate = new Date();
                var todaySty = todayDate.getDate() == i && todayDate.getMonth() + 1 == $(mian).find("#thismonth").attr("data_month") ? "z-today" : "";
                calanderHTML += '<li class="item1 ' + todaySty + ' f-fl">';
                calanderHTML += '<dl class="classItem blankDl"><dt class="number">' + i + '</dt>';
                for (var l = 0; l < dataObj.data.rows.length; l++) {
                    if (i == parseInt(dataObj.data.rows[l].start_date_label)) {
                        calanderHTML += '<dt class="name"><a target="_blank" href="' + commonParams.jcDevPath + commonParams.jcType + '/Activity/activityInfo?activity_id=' + dataObj.data.rows[l].activity_info_id + '" title="' + dataObj.data.rows[l].activity_info_title + '">' + dataObj.data.rows[l].activity_info_title + '</a></dt>';
                    }
                }
                calanderHTML += '</dl></li>';
                if (i == parseInt(dataMonth.getDate())) {
                    for (var k = 1; k < (7 - dayObj) ; k++) {
                        calanderHTML += '<li class="item1 z-otherMonth f-fl">';
                        calanderHTML += '<dl class="classItem blankDl"><dt class="number"></dt><dt class="name"></dt></dl>';
                        calanderHTML += '</li>';
                    }
                    calanderHTML += '</ul>';
                }
            }
            $(mian).find("div.vsWrap").html(calanderHTML);
        });
    },
    changeCalendar: function () {
        var mian = this;
        return $(mian).unbind().bind("click", function () {
            if ($(mian).attr("id") == "prevmonth") {
                if (parseInt($("#thismonth").attr("data_month")) == 1) {
                    $("#thismonth").attr("data_month", "12");
                    $("#thismonth").attr("data_year", $("#thismonth").attr("data_year") - 1);
                    $("#thismonth").html($("#thismonth").attr("data_year") + "-12");
                }
                else {
                    $("#thismonth").attr("data_month", parseInt($("#thismonth").attr("data_month")) - 1);
                    var monthStr = parseInt($("#thismonth").attr("data_month")) >= 10 ? $("#thismonth").attr("data_month") : "0" + $("#thismonth").attr("data_month");
                    $("#thismonth").html($("#thismonth").attr("data_year") + "-" + monthStr);
                }
            }
            else {
                if (parseInt($("#thismonth").attr("data_month")) == 12) {
                    $("#thismonth").attr("data_month", "1");
                    $("#thismonth").attr("data_year", parseInt($("#thismonth").attr("data_year")) + 1);
                    $("#thismonth").html($("#thismonth").attr("data_year") + "-01");
                }
                else {
                    $("#thismonth").attr("data_month", parseInt($("#thismonth").attr("data_month")) + 1);
                    var monthStr = parseInt($("#thismonth").attr("data_month")) >= 10 ? $("#thismonth").attr("data_month") : "0" + $("#thismonth").attr("data_month");
                    $("#thismonth").html($("#thismonth").attr("data_year") + "-" + monthStr);
                }
            }
            $("#courseManage").cldEvent();
        });
    }
});

$(document).ready(function () {
    $("#courseManage").cldEvent();//初始化日历
    $("#prevmonth").changeCalendar();//获取上一月日历
    $("#nextmonth").changeCalendar();//获取下一月日历
});

//兼容IE7的new Date写法
function newDate(str) {
    str = str.split('-');
    var date = new Date();
    date.setUTCFullYear(str[0], str[1] - 1, str[2]);
    date.setUTCHours(0, 0, 0, 0);
    return date;
}