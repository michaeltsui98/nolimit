$(document).ready(function () {
    initList();
});
var stu_detailPorms = {
    page: 1
}
var initList = function () {
    var getListData = function () {
        AjaxForJson(commonParams.jcDevPath + commonParams.jcType + "/Study/studyDetailMoreAjax", 'p=' + stu_detailPorms.page.toString() + '&bb=' + $('[name="bb_list"]').find('option:selected').attr('value') + '&grade=' + $('[name="grade_list"]').find('option:selected').attr('value'), function (data) {
            $('[name="loadMore"]').remove();
            if (data.data != null) {
                $('[name="node_list"]').append(data.data);
                if (data.return_count >= data.limit) {
                    var loadMoreHtml = '<div class="m-learningUnit" name="loadMore"><i class="icon-numberBg"></i><dl class="m-LUcell"><dt class="uTt"><i class="icon-arrLs"></i><div class="refresh_line" style="cursor: pointer;">点击加载更多</div></dt></dl></div>';
                    $('[name="node_list"]').append(loadMoreHtml);
                }
            }
            else {
                //如果是第一页 没有数据 就显示 暂无数据
                if (stu_detailPorms.page == 1) {
                    var loadMoreHtml = '<div class="m-learningUnit"><i class="icon-numberBg"></i><dl class="m-LUcell"><dt class="uTt"><i class="icon-arrLs"></i><div class="refresh_line">暂无数据</div></dt></dl></div>';
                    $('[name="node_list"]').html(loadMoreHtml);
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
            getListData();
        });
    }
    setBBddl();

    //加载更多
    $('[name="loadMore"]').die().live('click', function () {
        stu_detailPorms.page += 1;
        $('.refresh_line').html('<p></p>');
        getListData();
        return false;
    });

    //版本下拉
    $("select[name='bb_list']").die().live("change", function () {
        stu_detailPorms.page = 1;
        $('[name="node_list"]').html('');
        $('.refresh_line').html('<p></p>');
        setBBddl();
      //  getListData();
        return false;
    });
    //年级下拉
    $("select[name='grade_list']").die().live("change", function () {
        stu_detailPorms.page = 1;
        $('[name="node_list"]').html('');
        $('.refresh_line').html('<p></p>');
        getListData();
        return false;
    });
    //请求微视频 测评学习 列表
    var timeParam;
    $('a[name="study_resource_type"]').die().live('click', function () {
        var that = this;
        var liCount = $(that).parents('[name="study_resource_list"]').find('a[name="study_resource_type"]').length;
        var defaultRight =-30;
        var j = 0;
        var rightVal = 0;
        for (var i = liCount - 1; i >= 0; i--) {
            if ($(that).attr('study_value') == $($(that).parents('[name="study_resource_list"]').find('a[name="study_resource_type"]')[i]).attr('study_value')) {
                rightVal = defaultRight + j * 70 + $(that).width()/2;
            }
            j++;
        }
        var topVal = 32;
        AjaxForJson(commonParams.jcDevPath + commonParams.jcType + "/Study/getDifferentResourceType", 'node_id=' + $(that).parents('div[name="study_resource_list"]').attr('node_id') + '&study_resource_type_id=' + $(that).attr('study_value'), function (data) {
            if (data.length == 0) {
                return;
            }
            $('div[name="study_resource_detail"]').remove();
            var listHtml = '<div name="study_resource_detail" class="m-pop width330 ebook-dialog" style="display: block; top: ' + topVal + 'px; right: ' + rightVal + 'px;"><div class="con-pop" ><h5 class="hd"> <a class=" f-fr upload-close" href="javascript:;" title="关闭"><i class="u-closeBtn"></i></a> <strong>';
            listHtml += $(that).html();
            listHtml += '列表</strong></h5><div class="W-form f-oh"><ul class="mul-lst">';
            //如果是测评作业 则在li绑定url
            if ($(that).attr('study_value_code') == 'evaluate_task') {
                for (i in data) {
                    listHtml += '<li name="do_evaluate" evaluate_classify="" course_id="0" evaluate_id="' + data[i].evaluate_id + '" id="' + data[i].id + '" types="' + data[i].type + '" url="' + data[i].url + '" style="cursor: pointer;"><div class="mul-item clearfix"><h4><a href="#">' + data[i].title + '</a></h4></div></li>';
                }
            } else {
                for (i in data) {
                    listHtml += '<li style="cursor: pointer;"><div class="mul-item clearfix"><h4><a href="' + data[i].url + '" target="_blank">' + data[i].title + '</a></h4>';
                    if (data[i].size != 0) {
                        listHtml += '<h5>时长  ' + data[i].size + '</h5>';
                    }
                    listHtml+='</div></li>';
                }
            }

            listHtml += '</ul></div><div class="pop-arrow"><span class="arrow-border"></span><span class="arrow-fill"></span></div></div></div>';
            $(that).parents('div[name="study_resource_list"]').append(listHtml);
        });
        clearTimeout(timeParam);
        return false;
    });
    $('a[name="study_resource_type"]').live('mouseout', function () {
        var that = $('[name="study_resource_detail"]');
        timeParam = setTimeout(function () {
            $(that).css('display', 'none');
        }, 1000);
    });

    //鼠标 在列表上 的动作
    $('div[name="study_resource_detail"]').live('mouseover', function () {
        $(this).css('display', 'block');
        clearTimeout(timeParam);
    });
    $('div[name="study_resource_detail"]').live('mouseout', function () {
        var that = this;
        timeParam = setTimeout(function () {
            $(that).css('display', 'none');
        }, 1000);
    });
    //关闭列表弹出层
    $('.upload-close').die().live('click', function () {
        $('div[name="study_resource_detail"]').remove();
        return false;
    });

    //展开 关闭
    $('[name="close_resource"]').die().live('click', function () {
        $(this).parents('.m-learningUnit').attr('class', 'm-learningUnit');
        $(this).attr('name', 'open_resource').html('展开');
        return false;
    });
    $('[name="open_resource"]').die().live('click', function () {
        $(this).parents('.m-learningUnit').attr('class', 'm-learningUnit z-show');
        $(this).attr('name', 'close_resource').html('关闭');
        return false;
    });
}
