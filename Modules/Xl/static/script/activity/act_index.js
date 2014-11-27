jQuery.fn.extend({
    horizontalScroll: function (rightwidth) {
        var mian = this;
        var conDiv = $(mian).find("div[name='conDiv']");
        var showDiv = $(mian).find("div[name='showDiv']");
        var cellDiv = $(mian).find("div[name='cellDiv']");
        var toNextA = $(mian).find("a[name='toNextA']");
        var toPreA = $(mian).find("a[name='toPreA']");
        var rightWidth = rightwidth ? rightwidth : 0;

        var width = cellDiv.width() * cellDiv.length;
        conDiv.css("width", width);

        toNextA.unbind().bind("click", function () {
            if (conDiv.is(":animated")) {
                return;
            }
            var toLeftLimit = (parseInt(conDiv[0].clientWidth / showDiv[0].clientWidth) - 1) * showDiv[0].clientWidth;
            if (conDiv.offset().left < showDiv.offset().left) {
                conDiv.animate({ left: conDiv.offset().left - showDiv.offset().left + showDiv[0].clientWidth + parseInt(rightWidth) }, 800, function () {
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
            var toLeftLimit = (parseInt(conDiv[0].clientWidth / showDiv[0].clientWidth) - 1) * showDiv[0].clientWidth;
            if (conDiv.offset().left > -(toLeftLimit - showDiv.offset().left)) {
                conDiv.animate({ left: conDiv.offset().left - showDiv.offset().left - showDiv[0].clientWidth - parseInt(rightWidth) }, 800, function () {
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
    },
    verticalScroll: function (bottomwidth) {
        var mian = this;
        var conDiv = $(mian).find("div[name='conDiv']");
        var showDiv = $(mian).find("div[name='showDiv']");
        var cellDiv = $(mian).find("div[name='cellDiv']");
        var toNextA = $(mian).find("a[name='toNextA']");
        var toPreA = $(mian).find("a[name='toPreA']");
        var bottomWidth = bottomwidth ? bottomwidth : 0;

        var height = cellDiv.height() * cellDiv.length;
        conDiv.css("height", height);

        toNextA.unbind().bind("click", function () {
            if (conDiv.is(":animated")) {
                return;
            }
            var toTopLimit = (conDiv[0].clientHeight / showDiv[0].clientHeight - 1) * showDiv[0].clientHeight;
            if (conDiv.offset().top < showDiv.offset().top) {
                conDiv.animate({ top: conDiv.offset().top - showDiv.offset().top + showDiv[0].clientHeight + parseInt(bottomWidth) }, 500, function () {
                    //if (conDiv.offset().top <= -(toTopLimit - showDiv.offset().top)) {

                    //}
                    //else if (showDiv.offset().top > conDiv.offset().top && conDiv.offset().top > -(toTopLimit - showDiv.offset().top)) {

                    //}
                    //else if (conDiv.offset().top >= showDiv.offset().top) {

                    //}
                });
            }
            return false;
        });

        toPreA.unbind().bind("click", function () {
            if (conDiv.is(":animated")) {
                return;
            }
            var toTopLimit = (conDiv[0].clientHeight / showDiv[0].clientHeight - 1) * showDiv[0].clientHeight;
            if (conDiv.offset().top > -(toTopLimit - showDiv.offset().top)) {
                conDiv.animate({ top: conDiv.offset().top - showDiv.offset().top - showDiv[0].clientHeight - parseInt(bottomWidth) }, 500, function () {
                    //if (conDiv.offset().top <= -(toTopLimit - showDiv.offset().top)) {

                    //}
                    //else if (showDiv.offset().top > conDiv.offset().top > -(toTopLimit - showDiv.offset().top)) {

                    //}
                    //else if (conDiv.offset().top >= showDiv.offset().top) {

                    //}
                });
            }
            return false;
        });
    },
    constantScroll: function () {
        var isAnimate = false;
        var mian = this;
        var conDiv = $(mian).find("ul[name='conDiv']");
        var showDiv = $(mian).find("div[name='showDiv']");
        var cellDiv = $(mian).find("li[name='cellDiv']");
        var toNextA = $(mian).find("a[name='toNextA']");
        var toPreA = $(mian).find("a[name='toPreA']");

        if (showDiv.height() <= conDiv.height()) {
            //自定义组件的滚动
            toNextA.live("mousedown", function () {
                var cellHeight = cellDiv.height() + 1;
                if (!isAnimate) {
                    isAnimate = true;
                    conDiv.animate({ "top": (showDiv.height() - conDiv.height()) }, 1000, function () {
                        isAnimate = false;
                    });
                    toNextA.bind("mouseup", function () {
                        if (conDiv.is(":animated")) {
                            conDiv.stop();
                            conDiv.animate({ "top": Math.floor((conDiv.offset().top - showDiv.offset().top) / cellHeight) * cellHeight }, 200, function () {
                                isAnimate = false;
                            });
                        }
                    });
                }
            });
            toPreA.live("mousedown", function () {
                var cellHeight = cellDiv.height() + 1;
                if (!isAnimate) {
                    isAnimate = true;
                    conDiv.animate({ "top": 0 }, 1000, function () {
                        isAnimate = false;
                    });
                    toPreA.bind("mouseup", function () {
                        if (conDiv.is(":animated")) {
                            conDiv.stop();
                            conDiv.animate({ "top": parseInt((conDiv.offset().top - showDiv.offset().top) / cellHeight) * cellHeight }, 200, function () {
                                isAnimate = false;
                            });
                        }
                    });
                }
            });
        }
    }
});

$(document).ready(function () {
    $("div.m-studySite").horizontalScroll("8");
    $("div.m-studyTopic").verticalScroll();
    $("div.m-newestActive").constantScroll();

    $("div.m-teachStudy").find("ul.tab4Menu").tab2Menu(function () {
        AjaxForJson($("div.m-teachStudy").find("ul").find("input[name='ajax_activity_list_url']").val(), "current_phase=" + $("div.m-teachStudy").find("ul").find("li.tabMenuCurrent").attr("current_phase"), function (dataObj) {
            $("div.m-teachStudy").find("div.ct").html(dataObj.data);
        });
    });

    $("a#activity_login").unbind().bind("click", function () {
        loginDialog();
        return false;
    });
});