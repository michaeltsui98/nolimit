$(document).ready(function () {
    initEvent();
    $("#sub_question").addQuestion();//添加问题
    $("dl.itemAQ").addAnswer();//添加问题的回答
});
var onlineParms = {
    currentList: ''
}
var initEvent = function () {
    //微视频 测评学习列表请求
    var timeParam;
    $('a[name="study_resource_type"]').die().live('click', function () {
        var that = this;
        //如果点击的是当前list 则判断是隐藏还是显示
        if (onlineParms.currentList == $(that).attr('study_value_code')) {
            if ($('[tp="' + onlineParms.currentList + '"]').css('display') == 'none') {
                $('[tp="' + onlineParms.currentList + '"]').css('display', 'block');
            } else {
                $('[tp="' + onlineParms.currentList + '"]').css('display', 'none');
            }
        } else {
            //study_value_code = "evaluate_task"
            //如果不是 就请求其他的列表
            onlineParms.currentList = $(that).attr('study_value_code');
            var rightVal = 600 - $(that).offset().left + $(that).width() / 2;
            AjaxForJson(commonParams.jcDevPath + commonParams.jcType + "/Study/getDifferentResourceType", 'node_id=' + $('a[name="node_title"]').attr('node_value') + '&study_resource_type_id=' + $(that).attr('study_value'), function (data) {
                if (data.length == 0) {
                    return;
                }
                var listHtml = '<div tp="' + onlineParms.currentList + '" name="study_resource_list" class="m-pop width330 ebook-dialog" style="display: block; top: 43px; right: ' + rightVal + 'px;"><div class="con-pop" ><h5 class="hd"> <a class=" f-fr upload-close" href="javascript:;" title="关闭"><i class="u-closeBtn"></i></a> <strong>';
                listHtml += $(that).html();
                listHtml += '列表</strong></h5><div class="W-form f-oh"><ul class="mul-lst">';
                //如果是测评作业 则在li绑定url
                if ($(that).attr('study_value_code') == 'evaluate_task') {
                    for (i in data) {
                        listHtml += '<li name="do_evaluate" evaluate_classify="" course_id="0" evaluate_id="' + data[i].evaluate_id + '" id="' + data[i].id + '" types="' + data[i].type + '" url="' + data[i].url + '" style="cursor: pointer;"><div class="mul-item clearfix"><h4><a href="#">' + data[i].title + '</a></h4></div></li>';
                    }
                } else {
                    for (i in data) {
                        listHtml += '<li style="cursor: pointer;"><div class="mul-item clearfix"><h4><a href="' + data[i].url + '">' + data[i].title + '</a></h4>';
                        if (data[i].size != 0) {
                            listHtml += '<h5>时长  ' + data[i].size + '</h5>';
                        }
                        listHtml += '</div></li>';
                    }
                }

                listHtml += '</ul></div><div class="pop-arrow"><span class="arrow-border"></span><span class="arrow-fill"></span></div></div></div>';
                $('div[name="popUp"]').html(listHtml);
            });
        }
        clearTimeout(timeParam);
        return false;
    });
    $('a[name="study_resource_type"]').live('mouseout', function () {
        var that = $('[name="study_resource_list"]');
        timeParam = setTimeout(function () {
            $(that).css('display', 'none');
        }, 1000);
    });

    $('div[name="study_resource_list"]').live('mouseover', function () {
        $(this).css('display', 'block');
        clearTimeout(timeParam);
    });
    $('div[name="study_resource_list"]').live('mouseout', function () {
        var that = this;
        timeParam = setTimeout(function () {
            $(that).css('display', 'none');
        }, 1000);
    });

    $('.upload-close').die().live('click', function () {
        onlineParms.currentList = '';
        $('div[name="popUp"]').html('');
        return false;
    });

    $('a[name="recommend"]').die().live('click', function () {
        var url = $(this).attr('request_url');
        window.open(url);
    });
};

//问题与回答插件
jQuery.fn.extend({
    addQuestion: function () {
        var mian = this;

        //获取问题的加载更多
        $("div#content").loadMore({
            requestUrl: commonParams.jcDevPath + commonParams.jcType + "/Study/getQuestionList",
            requestData: "node_id=" + $("a[name='node_title']").attr("node_value"),
            callback: function (dataObj) {
                if (dataObj.type == "success") {
                    var questionList = '';
                    for (var j = 0; j < dataObj.data.answer_list.length; j++) {
                        var hasAnswer = parseInt(dataObj.data.answer_list[j].FAQ_answersCount) > 0 ? true : false;
                        questionList += '<dl class="itemAQ">';
                        questionList += '<dt class="ttAQ clearfix"><strong class="f-fs14" name="qustion_title" question_id="' + dataObj.data.answer_list[j].id + '">' + dataObj.data.answer_list[j].FAQ_title + '</strong></dt>';
                        questionList += '<dd class="des">';
                        if (hasAnswer) {
                            questionList += '<ul page="1">';
                            for (var i = 0; i < dataObj.data.answer_list[j].FAQ_answersList.length; i++) {
                                questionList += '<li class="answer">' + dataObj.data.answer_list[j].FAQ_answersList[i].answer_content + '</li>';
                            }
                            questionList += '</ul>';
                        }

                        questionList += '</dd>';
                        questionList += '<dd class="info"><a name="answer_count" href="javascript:;" title="" add_answer_url="' + commonParams.jcType + '/Study/addAnswer"><span>' + dataObj.data.answer_list[j].FAQ_answersCount + '</span> 个回答</a></dd>';
                        var display = hasAnswer ? "block" : "none";
                        questionList += '<dd class="commet" style="display:' + display + ';"><textarea class="customForm_textarea banResize customForm_inputBoxDefault clearStyle" style="height: 30px; width: 250px; color: rgb(181, 181, 181);" name="commentText" datavalue="回答问题..." maxht="50px" minht="16px" maxlen="1000" rows="" cols="">回答问题...</textarea><input class="customBtn colourLightGray f-mt10" type="button" value="回答"></dd>';
                        questionList += '</dl>';

                    }
                    $("div#questionMore").before(questionList);
                }
                else {
                    promptMessageDialog({
                        icon: "finish",
                        content: dataObj.message,
                        time: 1000
                    });
                }

                return dataObj.data.answer_list.length;
            },
            pageCount: 10
        });

        return $(mian).die().live("click", function () {
            var btnloading = new btnLoading(this);
            btnloading.toLoading(); //提交按钮变loading图片
            var requestUrl = $(mian).attr("add_question_url");
            var requestData = { node_id: $("a[name='node_title']").attr("node_value"), question_title: $("textarea[name='question_content']").val(), grade: $(mian).attr("grade") };
            if ($.trim($("textarea[name='question_content']").val()) == "") {
                promptMessageDialog({
                    icon: "warning",
                    content: '请输入提问内容！',
                    time: 1000
                });
                return;
            }
            AjaxForJson(requestUrl, requestData, function (dataObj) {
                if (dataObj.type == "success") {
                    promptMessageDialog({
                        icon: "finish",
                        content: dataObj.message,
                        time: 1000
                    });
                    var ansListHTML = '';
                    ansListHTML += '<dl class="itemAQ">';
                    ansListHTML += '<dt class="ttAQ clearfix"><strong class="f-fs14" name="qustion_title" question_id="' + dataObj.data.question_id + '">' + dataObj.data.question_title + '</strong></dt>';
                    ansListHTML += '<dd class="des"></dd>';

                    ansListHTML += '<dd class="info"><a name="answer_count" href="javascript:;" title="" add_answer_url="' + commonParams.jcType + '/Study/addAnswer"><span>0</span> 个回答</a></dd>';
                    ansListHTML += '<dd class="commet" style="display:none;"><textarea class="customForm_textarea banResize customForm_inputBoxDefault" style="height: 30px;width: 250px; color: rgb(181, 181, 181);" name="commentText" datavalue="回答问题..." maxht="50px" minht="16px" maxlen="1000" rows="" cols="">回答问题...</textarea><input class="customBtn colourLightGray f-mt10" type="submit" value="回答"></dd>';
                    ansListHTML += '</dl>';
                    $("textarea[name='question_content']").val("");
                    $("div#content").prepend(ansListHTML);
                }
                else {
                    promptMessageDialog({
                        icon: "warning",
                        content: dataObj.message,
                        time: 1000
                    });
                }
                btnloading.toBtn();
            });
            return false;
        });
    },
    addAnswer: function () {
        var mian = this;
        //加载更多回答
        $(mian).find("dd.des").find("a[name='loadContent']").die().live("click", function () {
            var that = this;
            var page = parseInt($(this).parents("dl.itemAQ").find("dd.des").find("ul").attr("page")) + 1
            $(this).css("display", "none");
            $(this).parent().find("a[name='loadingImg']").css("display", "block");
            AjaxForJson(commonParams.jcType + "/Study/getAnswerList", "question_id=" + $(this).parents("dl.itemAQ").find("strong[name='qustion_title']").attr("question_id") + "&p=" + page, function (dataObj) {
                var ansList = '';
                for (var i = 0; i < dataObj.data.answer_list.length; i++) {
                    ansList += '<li class="answer">' + dataObj.data.answer_list[i].answer_content + '</li>';
                }
                $(that).parents("dl.itemAQ").find("dd.des").find("ul").append(ansList);
                $(that).parents("dl.itemAQ").find("dd.des").find("ul").attr("page", page);

                $(that).parent().find("a[name='loadingImg']").css("display", "none");
                if (dataObj.data.answer_list.length > 0) {
                    $(that).css("display", "block");

                }
            });

            return false;
        });

        document.onclick = function (e) {
            e = e ? e : getEvent();
            var srcEle = e.target || e.srcElement;
            if ($(srcEle).is("dd.commet *")) {
                return;
            }
            if (srcEle.name == "answer_count") {
                return;
            }
            else {
                $("dl.itemAQ").find("dd.des").each(function () {
                    if ($(this).find("ul").length <= 0) {
                        $(this).parent().find("dd.commet").css("display", "none");
                    }
                });
            }
        };

        $(mian).find("dd.commet").find("textarea").clearText();
        $(mian).find("dd.info").find("a[name='answer_count']").die().live("click", function () {
            $("dl.itemAQ").find("dd.des").each(function () {
                if ($(this).find("ul").length <= 0) {
                    $(this).parent().find("dd.commet").css("display", "none");
                }
            });
            $(this).parents("dl.itemAQ").find("dd.commet").css("display", "block");
            $(this).parents("dl.itemAQ").find("dd.commet").find("textarea").clearText();
        });
        $(mian).find("dd.commet").find("input").die().live("click", function () {
            var that = this;
            var dlObj = $(that).parents("dl.itemAQ");

            var textObj = dlObj.find("dd.commet").find("textarea[name='commentText']");
            if (textObj.val() == "" || textObj.val() == textObj.attr("datavalue")) {
                promptMessageDialog({
                    icon: "warning",
                    content: '请输入回答内容！',
                    time: 1000
                });
                return;
            }
            var btnloading = new btnLoading(this);
            btnloading.toLoading(); //提交按钮变loading图片
            var requestUrl = dlObj.find("dd.info").find("a[name='answer_count']").attr("add_answer_url");
            var requestData = "question_id=" + dlObj.find("strong[name='qustion_title']").attr("question_id") + "&answer_content=" + dlObj.find("dd.commet").find("textarea[name='commentText']").val();
            AjaxForJson(requestUrl, requestData, function (dataObj) {
                if (dataObj.type == "success") {
                    promptMessageDialog({
                        icon: "finish",
                        content: dataObj.message,
                        time: 1000
                    });
                    textObj.val(textObj.attr("datavalue"));
                    if (dlObj.find("dd.des").find("ul").length <= 0) {
                        dlObj.find("dd.des").html('<ul><li class="answer">' + dataObj.data.answer_content + '</li></ul>');
                    }
                    else {
                        dlObj.find("dd.des").find("ul").prepend('<li class="answer">' + dataObj.data.answer_content + '</li>');
                    }
                    var count = parseInt(dlObj.find("dd.info").find("a[name='answer_count']").find("span").html());
                    dlObj.find("dd.info").find("a[name='answer_count']").find("span").html(count + 1);
                    dlObj.find("dd.commet").css("display", "block");
                }
                else {
                    promptMessageDialog({
                        icon: "warning",
                        content: dataObj.message,
                        time: 1000
                    });
                }
                btnloading.toBtn();
            });
        });
    }
});