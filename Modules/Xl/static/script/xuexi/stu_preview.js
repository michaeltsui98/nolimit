$(document).ready(function () {
    var viewerHeight = $(window).height() - $("#onlTop").height() - $("#onlBottom").height() - 45;
    $("#documentViewer").css("height", viewerHeight + "px");
    $("div.m-courseFAQs").css("height", $(window).height() - $("dl.m-courseInfo").height() - 2 + "px");
    if (perview_type == "isVideo" || perview_type == "isPpt") {
        $("#documentViewer").css("width", viewerHeight * 4 / 3 + "px");
    }
    $('#documentViewer').fileView("documentViewer");
    $(window).resize(function () {
        var viewerHeight = $(window).height() - $("#onlTop").height() - $("#onlBottom").height() - 45;
        $("#documentViewer").css("height", viewerHeight + "px");
        $("div.m-courseFAQs").css("height", $(window).height() - $("dl.m-courseInfo").height() - 2 + "px");
        if (perview_type == "isVideo" || perview_type == "isPpt") {
            $("#documentViewer").css("width", viewerHeight * 4 / 3 + "px");
        }
        $('#documentViewer').fileView("documentViewer");
    });
});

window.onunload = function () {
    var dataObj = $("div[name='data']");
    var studyStatus = stuPrePar.nowTime > 0 ? 1 : 0;
    if (dataObj.attr("study_record_status") > 1) {
        studyStatus = 2;
    }
    if (studyStatus > 0) {
        postParams = "?study_record_status=" + studyStatus + "&study_record_time=" + stuPrePar.nowTime + "&id=" + dataObj.attr("ids");
        $.ajax({
            url: commonParams.jcType + "/Study/getStudyRecord" + postParams,
            type: "POST",
            async: false
        });
    }
};
var stuPrePar = {
    nowTime: $("div[name='data']").attr("study_record_time")
};
//获取播放器播放状态
function ckplayer_status(str) {
    if (str.indexOf("nowtime:") > -1) {
        stuPrePar.nowTime = parseInt(str.substring(8, str.length));
        var a = swfobject.getObjectById('ckplayer_a1').ckplayer_getstatus();
        
    }
}
//播放器播放结束事件
function playerstop() {
    //只有当调用视频播放器时设置e=0或4时会有效果
    var dataObj = $("div[name='data']");
    postParams = "?study_record_status=2&study_record_time=0&id=" + dataObj.attr("ids");
    $.ajax({
        url: commonParams.jcType + "/Study/getStudyRecord" + postParams,
        type: "POST",
        async: false
    });
}