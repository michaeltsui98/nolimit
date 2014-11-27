$(document).ready(function () {
    initList();
});
var evaluatePorms = {
    page: 1,
    tabName: 'compositeEvaluate',
    evaluate_type: '1'
}

var initList = function () {
    var getListData = function () {
        AjaxForJson(commonParams.jcDevPath + commonParams.jcType + "/Evaluate/" + evaluatePorms.tabName, 'p=' + evaluatePorms.page.toString() + '&bb=' + $('[name="bb"]').find("option:selected").val() + '&nj=' + $('[name="nj"]').find("option:selected").val() + '&evaluate_type=' + evaluatePorms.evaluate_type, function (data) {
            if (data.data.length > 0) {
                $('[name="evaluate_list"]').append(data.data);
                $('[name="loadMore"]').remove();
                if (data.return_count >= data.limit) {
                    var loadMoreHtml = '<div class="m-learningUnit" name="loadMore"><i class="icon-numberBg"></i><dl class="m-LUcell"><dt class="uTt"><i class="icon-arrLs"></i><div class="refresh_line" style="cursor: pointer;">点击加载更多</div></dt></dl></div>';
                    $('[name="evaluate_list"]').append(loadMoreHtml);
                }
            }
            else {
                //如果是第一页 没有数据 就显示 暂无数据
                if (evaluatePorms.page == 1) {
                    var loadMoreHtml = '<div class="m-learningUnit"><dl class="m-LUcell"><dt class="uTt"><div class="refresh_line">暂无数据</div></dt></dl></div>';
                    $('[name="evaluate_list"]').append(loadMoreHtml);
                }
            }
        });
    }
    ////年级下拉列表绑定
    //function setBBddl() {
    //    AjaxForJson(commonParams.jcDevPath + commonParams.jcType + "/Study/getNjByBb", 'bb=' + $("select[name='bb']").find('option:selected').attr('value'), function (data) {
    //        var listHtml = '';
    //        for (i in data) {
    //            listHtml += '<option value="' + data[i].code + '">' + data[i].name + '</option>';
    //        }
    //        $("select[name='nj']").html(listHtml);
    //        getListData();
    //    });
    //}
    //setBBddl();
    getListData();
    //版本下拉
    $("select[name='bb']").die().live("change", function () {
        evaluatePorms.page = 1;
        $('.refresh_line').html('<p></p>');
        $('[name="evaluate_list"]').html('');
        getListData();
        return false;
    });
    //年级下拉
    $("select[name='nj']").die().live("change", function () {
        evaluatePorms.page = 1;
        $('.refresh_line').html('<p></p>');
        $('[name="evaluate_list"]').html('');
        getListData();
        return false;
    });
    //tab切换 数据请求
    $('.tab2Menu').tab2Menu(function (tabName) {
        evaluatePorms.page = 1;
        evaluatePorms.evaluate_type = $('.tab2Menu').find('.tabMenuCurrent ').attr('evaluate_type');
        $('.refresh_line').html('<p></p>');
        $('[name="evaluate_list"]').html('');
        evaluatePorms.tabName = tabName;// 'unitEvaluate';
        getListData();
        return false;
    });
    //加载更多
    $('[name="loadMore"]').die().live('click', function () {
        evaluatePorms.page += 1;
        $('.refresh_line').html('<p></p>');
        getListData();
        return false;
    });
}