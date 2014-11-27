var lesDialogs = {
    //新建备课夹弹框
    newLes: function () {
        var newLesHTML = '';
        //加载教材版本
        AjaxForJson('/node/edition', { subject: xk_code }, function (dataObj) {
            newLesHTML += '<div id="select-all"><dl><dt style="height:100%;">版本</dt><dd><div class="sel-list" style="overflow: hidden;width: 537px;"><ul id="edition-list">';
            newLesHTML += '</ul></div></dd></dl>';
            newLesHTML += '<dl><dt style="height:100%;">年级</dt><dd><div class="sel-list" style="overflow: hidden;width: 537px;"><ul id="grade-list">';
            newLesHTML += '</ul></div><a href="javascript:;" name="see-select-left" class="see-select-left see-select-left-un" style="display:none;"> <b class="n-left">&lt;</b></a><a href="javascript:;" name="see-select-right" class="see-select-right" style="display:none;"> <b class="n-right">&gt;</b></a></dd></dl>';
            newLesHTML += '<dl><dt style="height:100%;">章节</dt><dd><div class="sel-list" style="overflow: hidden;width: 537px;"><ul id="chapter-list">';
            newLesHTML += '</ul></div><a href="javascript:;" name="see-select-left" class="see-select-left see-select-left-un" style="display:none;"> <b class="n-left">&lt;</b></a><a href="javascript:;" name="see-select-right" class="see-select-right" style="display:none;"> <b class="n-right">&gt;</b></a></dd></dl>';
            newLesHTML += '<dl><dt style="height:100%;">课名</dt><dd><div class="sel-list" style="overflow: hidden;width: 537px;"><ul id="node-list">';
            newLesHTML += '</ul></div><a href="javascript:;" name="see-select-left" class="see-select-left see-select-left-un" style="display:none;"> <b class="n-left">&lt;</b></a><a href="javascript:;" name="see-select-right" class="see-select-right" style="display:none;"> <b class="n-right">&gt;</b></a></dd></dl></div>';

            function newLesEvent() {
                contentLoading($("ul#edition-list"));

                $("ul#edition-list").html(dataObj);

                AjaxForJson('/node/grade', { id: $("ul#edition-list").find("li").find("a.selected").attr("data-id") }, function (dataObj) {
                    $("ul#grade-list").html(dataObj);
                    $("ul#grade-list").css("width", $("ul#grade-list").find("li").width() * $("ul#grade-list").find("li").length);
                    if ($("ul#grade-list").width() > $("ul#grade-list").parent().width()) {
                        $("ul#grade-list").parents("dd").find("a[name='see-select-right']").css("display", "block");
                    }
                });
                //选择版本加载年级
                $("ul#edition-list li").die().live("click", function () {
                    $("ul#grade-list").html("");
                    $("ul#chapter-list").html("");
                    $("ul#node-list").html("");

                    $(this).parent().find("li").find("a").removeClass("selected");
                    $(this).find("a").addClass("selected");
                    $("ul#grade-list").css("width", "490px");
                    contentLoading($("ul#grade-list"));
                    AjaxForJson('/node/grade', { id: $(this).find("a").attr("data-id") }, function (dataObj) {
                        $("ul#grade-list").html(dataObj);
                        $("ul#grade-list").css("width", $("ul#grade-list").find("li").width() * $("ul#grade-list").find("li").length);
                        if ($("ul#grade-list").width() > $("ul#grade-list").parent().width()) {
                            $("ul#grade-list").parents("dd").find("a[name='see-select-right']").css("display", "block");
                        }
                    });
                });
                //选择年级加载章节
                $("ul#grade-list li").die().live("click", function () {
                    $("ul#chapter-list").html("");
                    $("ul#node-list").html("");

                    $(this).parent().find("li").find("a").removeClass("selected");
                    $(this).find("a").addClass("selected");
                    $("ul#chapter-list").css("width", "490px");
                    contentLoading($("ul#chapter-list"));
                    AjaxForJson('/node/chapter', {
                        subject: xk_code,
                        edition: $("#edition-list").find("li").find("a.selected").attr("data-edition"),
                        grade: $("#grade-list").find("li").find("a.selected").attr("data-grade")
                    }, function (dataObj) {
                        $("ul#chapter-list").html(dataObj);
                        $("ul#chapter-list").css("width", $("ul#chapter-list").find("li").width() * $("ul#chapter-list").find("li").length);
                        if ($("ul#chapter-list").width() > $("ul#chapter-list").parent().width()) {
                            $("ul#chapter-list").parents("dd").find("a[name='see-select-right']").css("display", "block");
                        }
                    });
                });
                //选择章节加载知识节点
                $("ul#chapter-list li").die().live("click", function () {
                    $("ul#node-list").html("");

                    $(this).parent().find("li").find("a").removeClass("selected");
                    $(this).find("a").addClass("selected");
                    $("ul#node-list").css("width", "490px");
                    contentLoading($("ul#node-list"));
                    AjaxForJson('/node/node/id/' + $("#chapter-list").find("li").find("a.selected").attr("data-chapter"), null, function (dataObj) {
                        $("ul#node-list").html(dataObj);
                        $("ul#node-list").css("width", $("ul#node-list").find("li").width() * $("ul#node-list").find("li").length);
                        if ($("ul#node-list").width() > $("ul#node-list").parent().width()) {
                            $("ul#node-list").parents("dd").find("a[name='see-select-right']").css("display", "block");
                        }
                    });
                });
                //选择知识节点
                $("ul#node-list li").die().live("click", function () {
                    $(this).parent().find("li").find("a").removeClass("selected");
                    $(this).find("a").addClass("selected");
                });

                $("#PopupsFunc input[name='confirm']").bind("click", function () {
                    AjaxForJson(commonParams.jcType + "/lesson/add", { edition: $("#edition-list").find("li").find("a.selected").attr("data-edition"), grade: $("#grade-list").find("li").find("a.selected").attr("data-grade"), chapter: $("#chapter-list").find("li").find("a.selected").attr("data-chapter"), node: $("#node-list").find("li").find("a.selected").attr("data-node") }, function (dataObj) {
                        if (dataObj.type == "success") {
                            promptMessageDialog({
                                icon: "finish",
                                content: dataObj.message,
                                time: 1000
                            });
                            setTimeout(function () {
                                window.location.href = commonParams.jcDevPath + commonParams.jcType + "/lesson/addResource/id/" + dataObj.data.id;
                            }, 1000);
                        }
                        else {
                            promptMessageDialog({
                                icon: "warning",
                                content: dataObj.message,
                                time: 1000
                            });
                        }
                    });
                    return false;
                });

                $("a[name='see-select-right']").die().live("mousedown", function () {
                    var mian = this;
                    var conDiv = $(this).parent().find("ul");
                    var showDiv = $(this).parent().find("div.sel-list");

                    $(mian).parent().find("a[name='see-select-left']").css("display", "block");
                    if (conDiv.is(":animated")) {
                        return;
                    }
                    conDiv.animate({ left: showDiv.width() - conDiv.width() }, 800, function () {
                        $(mian).css("display", "none");
                    });
                    $(mian).unbind().bind("mouseup", function () {
                        if (conDiv.is(":animated")) {
                            conDiv.stop();
                        }
                    });

                    return false;
                });

                $("a[name='see-select-left']").die().live("mousedown", function () {
                    var mian = this;
                    var conDiv = $(this).parent().find("ul");
                    var showDiv = $(this).parent().find("div.sel-list");

                    $(mian).parent().find("a[name='see-select-right']").css("display", "block");
                    if (conDiv.is(":animated")) {
                        return;
                    }
                    conDiv.animate({ left: 0 }, 800, function () {
                        $(mian).css("display", "none");
                    });
                    $(mian).unbind().bind("mouseup", function () {
                        if (conDiv.is(":animated")) {
                            conDiv.stop();
                        }
                    });

                    return false;
                });

            }

            styledialog.initDialogHTML({
                title: "新建备课夹",
                content: newLesHTML,
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
            styledialog.initContent("新建备课夹", newLesHTML, newLesEvent);
        });
    }
}

$(document).ready(function () {
    //添加备课夹
    $("a[name='newFolder']").unbind().bind("click", function () {
        lesDialogs.newLes();
        return false;
    });

    // 绑定下拉菜单自动提交
    $('.js-select-auto-submit').bind({
        change: function (e) {
            $(this).closest('form').submit();
        }
    });

    // 绑定ajax类操作
    $('a.js-lesson-copy').bind({
        click: function (e) {
            e.preventDefault();
            var $this = $(this);
            var origin_action = $this.attr('href');
            var origin_text = $this.text();
            $this.attr('href', 'javascript:;');
            $this.text('等待');
            $.get(origin_action, function (json) {
                if (json.type != 'error') {
                    $this.attr('href', origin_action);
                    $this.text(origin_text);
                }

                if (json.type == 'login') {
                    loginDialog();
                } else {
                    promptMessageDialog({ icon: json.type, content: json.message });
                }

                if (json.type == 'success') {
                    setTimeout(function () {
                        window.location.href = commonParams.jcType + "/lesson/record";
                    }, 500);
                }

            }, 'json');
        }
    });

    // 绑定确认操作类
    $('a.js-confirm').bind({
        click: function (e) {
            e.preventDefault();
            var $this = $(this);
            cueDialog(function (main) {
                window.location.href = main.attr('href');
            }, $this, false, $this.data('confirm'));
        }
    })

});