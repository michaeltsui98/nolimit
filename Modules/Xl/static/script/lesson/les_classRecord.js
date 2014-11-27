$(document).ready(function () {
    //添加到课程事件
    $("a[name='addCourse']").unbind().bind("click", function () {
        var folder_id = $(this).attr('folder_id');
        var title = "添加到课程";
        var url = commonParams.jcDevPath + commonParams.jcType + "/Course/courseHtmlApi";
        function addCourse() {
            var content = '<div class="bd f-mt10"><div class="courseType s-bgc f-mb10"><dl class="clearfix f-mb10"><dt class="f-fl">课程对象：</dt><dd class="f-fl"><span><em>年级:</em><select name="grade" id="grade"></select></span><span class="f-ml20"><em>班级:</em><select id="class"></select></span></dd></dl><dl class="clearfix"><dt class="f-fl">课程备注：</dt><dd class="f-fl"><textarea name=""id=""cols="80"rows="1"placeholder="请输入备注"></textarea></dd></dl></div></div>';
            $('#editFraCon li.item1 a').bind({
                click: function(e) {
                    e.stopPropagation();
                }
            });
            $('#editFraCon li.item1').not('li.z-overdue').hover(function(){
                $(this).addClass('lesson-bg-hover');
            }, function(){
                $(this).removeClass('lesson-bg-hover');
            });
            $('#editFraCon li.item1').bind({
                click: function (e) {
                    var contentContainer = $(this);
                    if (contentContainer.hasClass('z-overdue')) {
                        promptMessageDialog({
                            icon: "warning",
                            content: "过期节次！",
                            time: 1000
                        });
                        return false;
                    }

                    if (contentContainer.find('dl').length > 0) {
                        promptMessageDialog({
                            icon: "warning",
                            content: "该节次已有课程！",
                            time: 1000
                        });
                        return false;
                    }

                    var date = contentContainer.data('date');
                    var sort = contentContainer.data('sort');
                    styledialog.initInsideDialogHTML({
                        title: '课程设置',
                        content: content,
                        width: 710,
                        confirm: {
                            show: true,
                            name: "确认"
                        },
                        cancel: {
                            show: true,
                            name: "取消"
                        }
                    });
                    styledialog.initInsideDialogContent('', '', function () {
                        var classContainer = $('#class');
                        var gradeContainer = $('#grade').bind({
                            change: function (e) {
                                $.get(commonParams.jcType + '/course/classList', { grade: e.target.value }, function (json) {
                                    if (json.type == 'success') {
                                        var options = '';
                                        for (var i in json.data) {
                                            var item = json.data[i];
                                            options += '<option value="' + item.class_id + '">' + item.class_name + '</option>';
                                        }
                                        classContainer.html(options);
                                    }
                                }, 'json');
                            }
                        });
                        // 动态加载年级信息
                        $.get('/node/gradeOptions', function (json) {
                            if (json.type == 'success') {
                                var options = '';
                                for (var i in json.data) {
                                    var name = json.data[i];
                                    options += '<option value="' + i + '">' + name + '</option>';
                                }
                                gradeContainer.html(options).trigger('change');
                            };
                        }, 'json');

                        // 点击确定时的事件绑定
                        $('#insidePopupsFunc input[name="confirm"]').bind({
                            click: function (e) {
                                $.post(commonParams.jcType + '/course/saveCourse', {
                                    class_id: classContainer.val(),
                                    grade: gradeContainer.val(),
                                    date: date,
                                    sort: sort,
                                    lesson_folder_id: folder_id
                                }, function (json) {
                                    if (json.type == 'success') {
                                        promptMessageDialog({
                                            icon: json.type,
                                            content: '操作成功',
                                            time: 1000
                                        });
                                        styledialog.closeInsideDialog();
                                        styledialog.closeDialog();
                                    }
                                }, 'json');
                            }
                        })
                    });
                }
            })
        }
        styledialog.initDialogHTML({
            title: title,
            url: url,
            width: 762,
            confirm: {
                show: true,
                name: "确认"
            },
            cancel: {
                show: true,
                name: "取消"
            }
        });
        styledialog.initContent(title, "", addCourse);
        return false;
    });
});