jQuery(function($) {
    //评论列表
    $("div[name='get_comment_list']").get_comment_list();
})

jQuery.fn.extend({
    //评论列表
    get_comment_list: function() {
        var that = this;
        var comment_type = $(that).attr('comment_type');
        var comment_type_id = $(that).attr('comment_type_id');
        //ajax请求
        $.get(commonParams.jcDevPath + "/Comment/commentList", {
            'comment_type': comment_type,
            'comment_type_id': comment_type_id
        }, function(data) {
            $(that).html(data);
        })
    },
    //评论列表的分页处理
    get_comment_page_list: function() {
        $(this).die().live("click", function(e) {
            e.preventDefault();
            var that = this;
            var url = $(that).attr('href');
            var comment_type = $("div[name='get_comment_list']").attr('comment_type');
            var comment_type_id = $("div[name='get_comment_list']").attr('comment_type_id');
            var comment_number = $("div[name='get_comment_list']").attr('comment_number') ? $("div[name='get_comment_list']").attr('comment_number') : "0";
            //ajax请求
            $.get(commonParams.jcDevPath + url, {
                'comment_type': comment_type,
                'comment_type_id': comment_type_id,
                'comment_number': comment_number
            }, function(data) {
                $("div[name='get_comment_list']").html(data);
            })
        })
    },
    //添加评论
    add_comment: function() {
        $(this).die().live("click", function() {
            var comment_type = $("div[name='get_comment_list']").attr('comment_type');
            var comment_type_id = $("div[name='get_comment_list']").attr('comment_type_id');
            var comment_content = $("textarea[name='comment_content']").val();
            var datas = '';
            if ($.trim(comment_content).length == 0) {
                promptMessageDialog({
                    icon: "warning",
                    content: '内容不能为空!',
                    time: 1000
                });
                return false;
            }

            var btnloading = new btnLoading($(this).find("a")[0]);
            btnloading.toLoading(); //提交按钮变loading图片
            //ajax请求
            $.get(commonParams.jcDevPath + "/Comment/addComment", {
                'comment_type': comment_type,
                'comment_type_id': comment_type_id,
                'comment_content': comment_content
            }, function(data) {
                try {
                    datas = eval('(' + data + ')');
                } catch (e) {
                    datas = data;
                }

                if (datas.type == 'login') {
                    loginDialog();
                    return false;
                } else if (datas.type == 'error') {
                    promptMessageDialog({
                        icon: "warning",
                        content: '添加失败!',
                        time: 1000
                    });
                    return false;
                } else {
                    promptMessageDialog({
                        icon: "finish",
                        content: '添加成功!',
                        time: 1000
                    });
                    $("div[name='all_comment']").find("ul").prepend(datas.data);
                    $("textarea[name='comment_content']").val("");
                }
                btnloading.toBtn();
            });

        });
    },
    //删除评论
    delete_comment: function() {
        $(this).die().live("click", function() {
            var that = this;
            var comment_id = $(that).attr('val');
            var datas = '';
            //ajax请求
            $.get(commonParams.jcDevPath + "/Comment/deleteComment", {
                'comment_id': comment_id,
            }, function(data) {
                try {
                    datas = eval('(' + data + ')');
                } catch (e) {
                    datas = data;
                }
                if (datas.type == 'error') {
                    promptMessageDialog({
                        icon: "warning",
                        content: '删除失败!',
                        time: 1000
                    });
                    return false;
                } else {
                    $(that).parent().parent().remove();
                }
            })
        })
    }
})

//评论分页处理
$("div[name='page']").find("a").get_comment_page_list();
//添加评论
$("div[name='add_comment']").add_comment();
//删除评论
$("a[name='del_comment']").delete_comment();