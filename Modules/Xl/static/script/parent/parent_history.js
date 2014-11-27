var initList = function () {
    var getHistroyList = function () {
        var url = commonParams.jcDevPath + commonParams.jcType + "/Parent/studyHistoryMoreAjaxByNode";
        var params='bb=' + $('[name="bb_list"]').find('option:selected').attr('value') + '&grade=' + $('[name="grade_list"]').find('option:selected').attr('value');
        if (stu_detailPorms.currentTab == 'history') {
            params='p=' + stu_detailPorms.page.toString() + '&bb=' + $('[name="bb_list"]').find('option:selected').attr('value') + '&grade=' + $('[name="grade_list"]').find('option:selected').attr('value')
            url = commonParams.jcDevPath + commonParams.jcType + "/Parent/studyHistoryMoreAjax";
        }
        AjaxForJson(url, params, function (data) {
            if (data.data != null) {
                $('[name="history_list"]').append(data.data);
                $('[name="loadMore"]').remove();
              
                if (stu_detailPorms.currentTab == 'structure') {
                    $('.u-puBtn').unbind().packUpAndDown();
                    $('.u-ufBtn').unbind().packUpAndDown();
                }
                else {
                    if (data.every_page >= data.limit) {
                        var loadMoreHtml = '<ul class="item clearfix f-fs16" style="text-align: center;cursor: pointer;" name="loadMore"><div class="refresh_line">点击加载更多</div></ul>';
                        $('[name="history_list"]').append(loadMoreHtml);
                    }
                }
            }
            else {
                //如果是第一页 没有数据 就显示 暂无数据
                if (stu_detailPorms.page == 1) {
                    var loadMoreHtml = '<ul class="item clearfix f-fs16" style="text-align: center;"><div class="refresh_line">暂无数据</div></ul>';
                    $('[name="history_list"]').append(loadMoreHtml);
                }
            }
        });
    }

    //年级下拉列表绑定
    function setBBddl() {
        AjaxForJson(commonParams.jcDevPath + commonParams.jcType + "/Study/getNjByBb", 'bb=' + $("select[name='bb_list']").find('option:selected').attr('value'), function (data) {
            var listHtml = '';
            for (i in data) {
                listHtml += '<option value="' + data[i].code + '">' + data[i].name + '</option>';
            }
            $("select[name='grade_list']").html(listHtml);
            getHistroyList();
        });
    }
    setBBddl();
    //版本下拉
    $("select[name='bb_list']").die().live("change", function () {
        stu_detailPorms.page = 1;
        $('[name="history_list"]').html('');
        $('.refresh_line').html('<p></p>');
        setBBddl();
        return false;
    });
    //年级下拉
    $("select[name='grade_list']").die().live("change", function () {
        stu_detailPorms.page = 1;
        $('[name="history_list"]').html('');
        $('.refresh_line').html('<p></p>');
        getHistroyList();
        return false;
    });
    //加载更多
    $('[name="loadMore"]').die().live('click', function () {
        stu_detailPorms.page += 1;
        $('.refresh_line').html('<p></p>');
        getHistroyList();
        return false;
    });
    $('.tab2Menu').tab2Menu(function (tabName) {
        stu_detailPorms.page = 1;
        stu_detailPorms.currentTab = tabName;
        $('[name="history_list"]').html('');
        getHistroyList();
        return false;
    });
    $('.u-closeBtn').die().live('click', function () {
        $(this).parents('.m-tips').css('display', 'none');
        return false;
    });
}

var stu_detailPorms = {
    page: 1,
    currentTab: 'history'
}

$(document).ready(function () {
    initList();
});
