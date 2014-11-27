$(document).ready(function () {
    function getQuestionList() {
        //请求问题列表
        var requestData = "tag=" + $('div[name="searchValue"]').attr('tag_value') + '&key=' + $('div[name="searchValue"]').attr('search_value');
        AjaxForJson(commonParams.jcDevPath + commonParams.jcType + "/Question/searchAjax", requestData, function (dataObj) {
            if (dataObj.data.data.length > 0) {
                $("#pageNum").addClass("page");
                var requestMenberpage = new jsPage(dataObj.data.num, "pageNum", dataObj.data.page, commonParams.jcDevPath + commonParams.jcType + "/Question/searchAjax", requestData, function (obj) {
                    var lesListHTML = '<ul><li class="head clearfix"><span class="f-fl">标题</span><div class="f-fr">时间</div></li>';
                    for (i in obj.data.data) {
                        lesListHTML += '<li class="clearfix"><a name="question_url" target="_blank" href="#" url_value="' + commonParams.sqDevPath + '/Question/view/' + obj.data.data[i].FAQ_id + '" class="f-fl">' + obj.data.data[i].FAQ_title + '</a><div class="f-fr">' + obj.data.data[i].FAQ_time + '</div></li>';
                    }
                    lesListHTML += '</ul>';
                    $('div[name="myData"]').html(lesListHTML);
                });
                pageMethod.call(requestMenberpage);
            }
            else {
                var info = '暂无数据';//<div class="noSp s-fcGray f-tac"><p class="f-fs18 f-mb10">你还没有提出过问题呢！ 有问题吗？</p><p class="f-mb30"></p></div><a href="" title="" target="" class="customBtnLarge colourDarkGreen f-fs18">去提问</a>
                $('div[name="myData"]').html(info);
				$("#pageNum").html('');
            }
        });
    }
    getQuestionList();
    //问题链接到社区 点击之前判断是否已登陆
    $('a[name="question_url"]').die().live('click', function () {
        if ($('div[name="isLogin"]').attr('user_id') == '') {
            loginDialog(); //调用登陆弹框
            return false;
        } else {
            window.open($(this).attr('url_value'));
            return false;
        }
    });
});