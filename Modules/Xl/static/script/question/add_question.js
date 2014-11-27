$(document).ready(function () {
    //addQusetion();
    ////左侧TAB切换
    //$('.tab4Menu').tab2Menu(function (tabName) {
    //    if (tabName == 'qusetionTab') {
    //        $('[name="qusetionList"]').css('display', '');
    //        $('[name="answerList"]').css('display', 'none');
    //    }
    //    else {
    //        $('[name="qusetionList"]').css('display', 'none');
    //        $('[name="answerList"]').css('display', '');
    //    }
    //    return false;
    //});
    function getQuestionList() {		
        //请求问题列表
        var requestData = "type=" + $('#select-all .sel-list').find('a.selected').attr('name') + '&status=' + $('.tab1Menu [name="status"]').find('strong.cur a').attr('name') + '&order=' + $('[name="order"]').find('strong.cur').attr('name');
        AjaxForJson(commonParams.jcDevPath + commonParams.jcType + "/Question/indexAjax", requestData, function (dataObj) {
            if (dataObj.data.data.length > 0) {
                $("#pageNum").addClass("page");
                var requestMenberpage = new jsPage(dataObj.data.num, "pageNum", dataObj.data.page, commonParams.jcDevPath + commonParams.jcType + "/Question/indexAjax", requestData, function (obj) {
                    var lesListHTML = '<ul><li class="head clearfix"><span class="f-fl">标题</span><div class="f-fr">时间</div></li>';
                    for (i in obj.data.data) {
                        lesListHTML += '<li class="clearfix"><a name="question_url" target="_blank" href="#" url_value="' + commonParams.sqDevPath + '/Question/view/' + obj.data.data[i].FAQ_id + '" class="f-fl">' + obj.data.data[i].FAQ_title + '</a><div class="f-fr">' + obj.data.data[i].FAQ_time + '</div></li>';
                    }
                    lesListHTML += '</ul>';
                    $('div[name="qusetionData"]').html(lesListHTML);

                });
                pageMethod.call(requestMenberpage);
            }
            else {
                var info = '暂无数据';
                $('div[name="qusetionData"]').html(info);
				$("#pageNum").html('');
            }
        });
    }
    getQuestionList();
    //选择学段
    $('#select-all .sel-list a').die().live('click', function () {
        $(this).parents('ul').find('a').attr('class', '');
        $(this).attr('class', 'selected');
        getQuestionList();
        return false;
    });
    //问题链接到社区 点击之前判断是否已登陆
    $('a[name="question_url"]').die().live('click', function () {
        if ($('div[name="isLogin"]').attr('user_id') == '') {
            loginDialog(); //调用登陆弹框
            return false;
        } else {
            window.open($(this).attr('url_value'));
            return false;
            //  window.location.href = $(this).attr('url_value');
        }
    });
    //状态TAB切换
    $('.tab1Menu [name="status"]').tab1Menu(function (tabName) {
        getQuestionList();
        return false;
    });
    //排序
    $('[key="order"]').die().live('click', function () {
        if ($(this).attr('class') != 'cur') {
            var infohtml = '';
            if ($(this).attr('name') == 'timeOrder') {
                infohtml = '排序： <strong class="cur" name="timeOrder" key="order">最新<i class="icon-orderdown"></i></strong> | <a href="#" class="whiteA" name="hotOrder" key="order">最热<i class="icon-orderdown-nocur"></i></a> ';
            } else {
                infohtml = '排序： <a class="whiteA" name="timeOrder" key="order">最新<i class="icon-orderdown-nocur"></i></a> | <strong class="cur" name="hotOrder" key="order">最热<i class="icon-orderdown"></i></strong> ';
            }
            $('[name="order"]').html(infohtml);
            getQuestionList();
        }
        return false;
    });
});


