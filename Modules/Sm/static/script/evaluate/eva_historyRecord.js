$(document).ready(function () {
    initList();
});
var evaluatePorms = {
    page: 1,
    tabName: 'all_evaluate'
}

var initList = function () {
    //初始化请求数据
    var getListData = function () {
        AjaxForJson(commonParams.jcDevPath + commonParams.jcType + "/Evaluate/evaluateHistoryAjax", 'p=' + evaluatePorms.page.toString() + '&evaluate_type=' + evaluatePorms.tabName + '&bb=' + $('[name="bb"]').find("option:selected").val() + '&nj=' + $('[name="nj"]').find("option:selected").val() + '&evaluate_record_status=' + $('[name="evaluate_record_status"]').find("option:selected").val(), function (data) {
            if (data.data.length>0) {
                $('[name="evaluate_history"]').append(data.data);
                $('[name="loadMore"]').remove();
                if (data.return_count >= data.limit) {
                    var loadMoreHtml = '<div class="m-learningUnit" name="loadMore"><i class="icon-numberBg"></i><dl class="m-LUcell"><dt class="uTt"><i class="icon-arrLs"></i><div class="refresh_line" style="cursor: pointer;">点击加载更多</div></dt></dl></div>';
                    $('[name="evaluate_history"]').append(loadMoreHtml);
                }
            }
            else {
                //如果是第一页 没有数据 就显示 暂无数据
                if (evaluatePorms.page == 1) {
                    var loadMoreHtml = '<div class="m-learningUnit"><dl class="m-LUcell"><dt class="uTt"><div class="refresh_line">暂无数据</div></dt></dl></div>';
                    $('[name="evaluate_history"]').append(loadMoreHtml);
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
         
    //    });
    //}
    //setBBddl();
   getListData();
    //版本下拉
    $("select[name='bb']").die().live("change", function () {
        evaluatePorms.page == 1;
        $('.refresh_line').html('<p></p>');
        $('[name="evaluate_history"]').html('');
        // setBBddl();
        getListData();
        return false;
    });
    //年级下拉
    $("select[name='nj']").die().live("change", function () {
        evaluatePorms.page == 1;
        $('.refresh_line').html('<p></p>');
        $('[name="evaluate_history"]').html('');
        getListData();
        return false;
    });
    //tab切换请求数据
    $('.tab1Menu').tab1Menu(function (tabName) {
        evaluatePorms.page == 1;
        $('.refresh_line').html('<p></p>');
        $('[name="evaluate_history"]').html('');
        evaluatePorms.tabName = tabName;
        getListData();
        return false;
    });
    //下拉列表切换 请求数据
    $('select[name="evaluate_record_status"]').die().live('change', function () {
        evaluatePorms.page == 1;
        $('.refresh_line').html('<p></p>');
        $('[name="evaluate_history"]').html('');
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
    $('.u-closeBtn').die().live('click', function () {
        $(this).parents('.m-tips').css('display', 'none');
    });
}