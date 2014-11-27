$(document).ready(function () {
    loadjscssfile(commonParams.jcDevPath + "/static/ueditorAll/themes/default/css/ueditor.css", "css");
    loadjscssfile(commonParams.jcDevPath + "/static/ueditorAll/editor_config.js", "js");
    loadjscssfile(commonParams.jcDevPath + "/static/ueditorAll/editor_all.js", "js");

    //图表初始化
    var typeData = $("#canvas_type").find("input[name='canvas_type_value']").val();
    var typeObj = eval('(' + typeData + ')');
    var color = ["#F7464A", "#46BFBD", "#FDB45C", "#949FB1", "#4D5360"];
    var doughnutData = [];
    for (var i = 0; i < typeObj.length; i++) {
        doughnutData.push({
            value: parseInt(typeObj[i].activity_times),
            color: color[i],
            explain: typeObj[i].activity_info_type_label
        });
    }
    var myDoughnut = new Chart(document.getElementById("canvas_type").getContext("2d")).Doughnut(doughnutData);

    var classData = $("#canvas_class").find("input[name='canvas_class_value']").val();
    var classObj = eval('(' + classData + ')');
    var doughnutData = [];
    for (var j = 0; j < classObj.length; j++) {
        doughnutData.push({
            value: parseInt(classObj[j].activity_times),
            color: color[j],
            explain: classObj[j].activity_info_class_label
        });
    }
    var myDoughnut = new Chart(document.getElementById("canvas_class").getContext("2d")).Doughnut(doughnutData);

    //时间选择
    $("#start_time").calendar({
        onSetDate: function () {
            $("#end_time").calendar({ minDate: this.getDate('y') + "-" + (this.getDate('M') > 9 ? this.getDate('M') : "0" + this.getDate('M')) + "-" + (this.getDate('d') > 9 ? this.getDate('d') : "0" + this.getDate('d')) });
        }
    });
    //获取研训主题列表
    initActList();
    $("a#search_activity").unbind().bind("click", function () {
        initActList();
    });
});

//获取研训主题列表
function initActList() {
    var requestUrl = commonParams.jcDevPath + $("input[name='ajax_teacher_activity_list_url']").val();
    var requestData = "start_time=" + $("#start_time").val() + "&end_time=" + $("#end_time").val();
    AjaxForJson(requestUrl, requestData, function (dataObj) {
        if (dataObj.type == "success") {
            $("#pageNum").addClass("page");
            var requestMenberpage = new jsPage(dataObj.data.total, "pageNum", "5", requestUrl, requestData, function (obj) {
                var actListHTML = '';
                for (var i = 0; i < obj.data.rows.length; i++) {
                    var blogAct = obj.data.rows[i].activity_member_experience_blogid ? '<a href="' + obj.data.rows[i].blog_url + '" target="_blank"><span class="s-fcGreen">查看心得</span></a>' : '<a href="javascript:;" name="addActBlog" activity_id="' + obj.data.rows[i].activity_info_id + '" target="_blank"><span class="s-fcOrange">添加心得</span></a>';
                    actListHTML += '<tr class=""><td>' + UnixToDate(obj.data.rows[i].activity_info_start_date) + '</td><td>' + obj.data.rows[i].activity_info_title + '</td><td>' + blogAct + '</td></tr>';
                }
                $("table#tableTest").find("tbody").html(actListHTML);

                //添加研训心得弹框
                $("a[name='addActBlog']").unbind().bind("click", function () {
                    var mian = this;
                    var textDialogHTML = '<div style="min-height:376px;margin-bottom:10px; margin-top:10px;"><div style="width:690px;margin: 0 auto;"><label style="display: inline-block;vertical-align: middle;height: 26px;line-height: 26px;overflow: hidden;">标题：</label><input id="actTitle" type="text" class="txtInput clearStyle" data-default="请输入心得名称" value="请输入心得名称" style="color: rgb(181, 181, 181);width:650px;"></div><script type="text/plain" id="editor" style="width:690px;margin: 0 auto;"></script></div>';
                    function textDialogEvent() {
                        $("input#actTitle").clearText();

                        var editor = new UE.ui.Editor();
                        editor.render('editor');

                        //提交研训心得
                        $("#PopupsFunc").find("input[name='confirm']").unbind().bind("click", function () {
                            var actTitle = $("#actTitle").val() == $("#actTitle").attr("data-default") ? "" : $("#actTitle").val()
                            var requestData = "activity_id=" + $(mian).attr("activity_id") + "&blog_title=" + characterTransform(actTitle) + "&blog_content=" + characterTransform(editor.getContent());
                            if (actTitle == "") {
                                promptMessageDialog({
                                    icon: "warning",
                                    content: "心得标题不能为空！",
                                    time: 1000
                                });
                                return;
                            }
                            if (editor.getContent() == "") {
                                promptMessageDialog({
                                    icon: "warning",
                                    content: "心得内容不能为空！",
                                    time: 1000
                                });
                                return;
                            }
                            var btnloading = new btnLoading(this);
                            btnloading.toLoading(); //提交按钮变loading图片
                            AjaxForJson(commonParams.jcDevPath + $("input[name='ajax_post_experience_blog']").val(), requestData, function (obj) {
                                if (obj.type == "success") {
                                    $(mian).html('<span class="s-fcGreen">查看心得</span>');
                                    $(mian).attr("href", obj.data);
                                    $(mian).attr("name", "");
                                    $(mian).unbind("click");

                                    styledialog.closeDialog();
                                }
                                else {
                                    promptMessageDialog({
                                        icon: "warning",
                                        content: obj.message,
                                        time: 1000
                                    });
                                    btnloading.toBtn();
                                }
                            });
                        });
                    }

                    styledialog.initDialogHTML({
                        title: "添加心得",
                        content: textDialogHTML,
                        width: 738,
                        confirm: {
                            show: true,
                            name: "确认"
                        },
                        cancel: {
                            show: true,
                            name: "取消"
                        }
                    });
                    styledialog.initContent("添加心得", textDialogHTML, textDialogEvent);

                    return false;
                });
            });
            pageMethod.call(requestMenberpage);
        }
    });
}