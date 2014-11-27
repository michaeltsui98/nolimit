$(document).ready(function () {
    function getQuestionList() {
        //请求问题列表
        var requestData = "type=" + $('.tab1Menu [name="type"]').find('strong.cur a').attr('name') + '&order=' + $('[name="order"]').find('strong.cur').attr('name');
        AjaxForJson(commonParams.jcDevPath + commonParams.jcType + "/Question/myAjax", requestData, function (dataObj) {
            if (dataObj.data.data.length > 0) {
                $("#pageNum").addClass("page");
                var requestMenberpage = new jsPage(dataObj.data.num, "pageNum", dataObj.data.page, commonParams.jcDevPath + commonParams.jcType + "/Question/myAjax", requestData, function (obj) {
                    var lesListHTML = '<ul><li class="head clearfix"><span class="f-fl">标题</span><div class="f-fr">时间</div></li>';
                    for (i in obj.data.data) {
                        lesListHTML += '<li class="clearfix"><a target="_blank" href="' + commonParams.sqDevPath + '/Question/view/' + obj.data.data[i].FAQ_id + '" class="f-fl">' + obj.data.data[i].FAQ_title + '</a><div class="f-fr">' + obj.data.data[i].in_time + '</div></li>';
                    }
                    lesListHTML += '</ul>';
                    $('div[name="myData"] ul').html(lesListHTML);
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

    //状态TAB切换
    $('.tab1Menu [name="type"]').tab1Menu(function (tabName) {
        $('div[name="myData"]').html('<ul><li class="head clearfix"><span class="f-fl">标题</span><div class="f-fr">时间</div></li></ul>');
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