//公共弹框
var styledialog = {
    initDialogHTML: function (settings) {
        this.settings = {
            title: "",
            content: "",
            width: 710,
            url: "",
            confirm: {
                show: true,
                name: "确认",
                disablie: false
            },
            cancel: {
                show: false,
                name: "取消"
            },
            button: {
                show: false,
                html: ""
            }
        }
        $.extend(this.settings, settings || {});
        if (this.settings.url) {
            var responseObj = $.ajax({
                url: this.settings.url,
                async: false
            }).responseText;
            try {
                this.settings.content = eval('(' + responseObj + ')');
                if (this.settings.content.type == "login") {
                    loginDialog();
                    return;
                }
            }
            catch (e) {
                this.settings.content = responseObj;
            }
        }
        var dialogConHtml = '<div class="m-upload" style="margin:0;width:' + this.settings.width + 'px;" id="PopupsFra"><h5 class="hd"><a class="icon-close f-fr upload-close" href="javascript:;" title="关闭"></a><strong>' + this.settings.title + '</strong></h5><div id="editFraCon" name="editFraCon"><div class="W-form" style="width:' + this.settings.width + 'px;">' + this.settings.content;
        if (this.settings.button.show || this.settings.confirm.show || this.settings.cancel.show) {
            dialogConHtml += '<div class="m-ftc">';

            dialogConHtml += '<div id="PopupsFunc" class="w-btn">';
            if (this.settings.button.show) {
                dialogConHtml += this.settings.button.html;
            }
            if (this.settings.confirm.show) {
                dialogConHtml += '<input name="confirm" type="button" class="customBtn colourDarkGreen customBtnNormal f-mr10 " value="' + this.settings.confirm.name + '">';
            }
            if (this.settings.cancel.show) {
                dialogConHtml += '<input name="cancel" type="button" class="customBtn colourLightGray customBtnNormal" value="' + this.settings.cancel.name + '">';
            }
            dialogConHtml += '</div>';
            dialogConHtml += '</div>';
        }
        dialogConHtml += '</div></div></div><div id="divBg" class="pageBg"><iframe style="position: absolute;width: 100%;height: 100%;z-index:-1;"></iframe></div>';
        $("body").eq(0).append(dialogConHtml);

        $("#divBg").css("display", "block");
        $("#PopupsFra").css("display", "block");
        var main = this;
        $("#PopupsFra h5 .icon-close").bind("click", function () {
            main.closeDialog();
            return false;
        });
        $("#PopupsFunc input[name='cancel']").bind("click", function () {
            main.closeDialog();
            return false;
        });
        this.initContent();
    },

    initContent: function (tittleCon, contentHTML, eventFun) {

        var windowWidth, windowHeight;
        if (document.documentElement.clientWidth == 0) {
            windowWidth = document.documentElement.offsetWidth;
        } else {
            windowWidth = document.documentElement.clientWidth;
        }
        if (document.documentElement.clientHeight == 0) {
            windowHeight = document.documentElement.offsetHeight;
        } else {
            windowHeight = document.documentElement.clientHeight;
        }
        var bodyScrollTop = 0;
        var bodyScrollLeft = 0;
        if (document.documentElement && document.documentElement.scrollTop) {
            bodyScrollTop = document.documentElement.scrollTop;
            bodyScrollLeft = document.documentElement.scrollLeft;
        } else if (document.body) {
            bodyScrollTop = document.body.scrollTop;
            bodyScrollLeft = document.body.scrollLeft;
        }
        var documentHeight = document.documentElement.clientHeight + document.documentElement.scrollHeight;
        var documentWidth = document.documentElement.clientWidth + document.documentElement.scrollWidth;
        var dialogHeight = $("#PopupsFra")[0].clientHeight;
        var dialogWidth = $("#PopupsFra")[0].clientWidth;
        $("#divBg").css({
            "width": document.body.offsetWidth,
            "height": document.body.offsetHeight < $(window).height() ? $(window).height() : document.body.offsetHeight
        });
        var PopupsFraTop = windowHeight / 2 - dialogHeight / 2 + bodyScrollTop >= 0 ? windowHeight / 2 - dialogHeight / 2 + bodyScrollTop : 0;
        var PopupsFraLfet = windowWidth / 2 - dialogWidth / 2 + bodyScrollLeft >= 0 ? windowWidth / 2 - dialogWidth / 2 + bodyScrollLeft : 0;
        $("#PopupsFra").css({
            "top": PopupsFraTop,
            "left": PopupsFraLfet
        });

        if (eventFun != null) {
            eventFun();
        }
    },

    initInsideDialogHTML: function (settings) {
        this.settings = {
            title: "",
            content: "",
            width: 710,
            url: "",
            confirm: {
                show: true,
                name: "确认",
                disablie: false
            },
            cancel: {
                show: true,
                name: "取消"
            },
            button: {
                show: false,
                html: ""
            }
        }
        $.extend(this.settings, settings || {});
        if (this.settings.url) {
            var responseObj = $.ajax({
                url: this.settings.url,
                async: false
            }).responseText;
            try {
                this.settings.content = eval('(' + responseObj + ')');
                if (this.settings.content.type == "login") {
                    this.settings.content = '<form action="' + commonParams.sqDevPath + '/DDApi/auth/clientauthorizeinfo" method="post" id="loginFrom"><div class="userId">账 号：<input name="userName" id="userName" type="text"></div><div class="PW">密 码：<input name="userPass" id="userPass" type="password"></div><input type="hidden" name="access_token" id="access_tokens" value="fe5d5df6acdf4854c36751439b95edc5"><input type="hidden" name="appKey" id="appKey" value="b8c8320d9c6d35c0b7dc412a44549bbb"><input type="hidden" name="callBackUrl" id="callBackUrl" value="' + commonParams.jcDevPath + '/Sign/callBack"><input type="hidden" name="responseType" id="responseType" value="code"><input type="hidden" name="state" id="state" value="' + commonParams.jcDevPath + '"><div class="btn"><input name="" type="submit" value="登 录" class="loginBtn"><a href="javascript:;" title="" target="" class="grayA" onclick="history.go(-1)">取消</a></div></form>';
                }
            }
            catch (e) {
                this.settings.content = responseObj;
            }
        }
        var dialogConHtml = '<div class="m-upload" style="width:' + this.settings.width + 'px;" id="insidePopupsFra"><h5 class="hd"><a class="icon-close f-fr upload-close" href="javascript:;" title="关闭"></a><strong>' + this.settings.title + '</strong></h5><div id="insideEditFraCon" name="insideEditFraCon"><div class="W-form" style="width:' + this.settings.width + 'px;">' + this.settings.content;
        if (this.settings.button.show || this.settings.confirm.show || this.settings.cancel.show) {
            dialogConHtml += '<div class="m-ftc">';
            dialogConHtml += '<div id="insidePopupsFunc" class="w-btn">';
            if (this.settings.button.show) {
                dialogConHtml += this.settings.button.html;
            }
            if (this.settings.confirm.show) {
                dialogConHtml += '<input name="confirm" type="button" class="customBtn colourDarkGreen customBtnNormal f-mr10 " value="' + this.settings.confirm.name + '">';
            }
            if (this.settings.cancel.show) {
                dialogConHtml += '<input name="cancel" type="button" class="customBtn colourLightGray customBtnNormal" value="' + this.settings.cancel.name + '">';
            }
            dialogConHtml += '</div>';
            dialogConHtml += '</div>';
        }

        dialogConHtml += '</div></div></div><div id="insideDivBg" class="pageBg"></div>';
        $("body").eq(0).append(dialogConHtml);

        $("#insideDivBg").css("display", "block");
        $("#insideDivBg").css("z-index", "911");
        $("#insidePopupsFra").css("z-index", "912");

        var main = this;
        $("#insidePopupsFra h5 .icon-close").bind("click", function () {
            main.closeInsideDialog();
            return false;
        });
        $("#insidePopupsFunc input[name='cancel']").bind("click", function () {
            main.closeInsideDialog();
            return false;
        });
    },

    initInsideDialogContent: function (tittleCon, contentHTML, eventFun) {

        var windowWidth = document.documentElement.clientWidth;
        var windowHeight = document.documentElement.clientHeight;
        var bodyScrollTop = 0;
        if (document.documentElement && document.documentElement.scrollTop) {
            bodyScrollTop = document.documentElement.scrollTop;
        } else if (document.body) {
            bodyScrollTop = document.body.scrollTop;
        }
        var documentHeight = document.documentElement.clientHeight + document.documentElement.scrollHeight;
        var documentWidth = document.documentElement.clientWidth + document.documentElement.scrollWidth;
        var dialogHeight = $("#insidePopupsFra")[0].clientHeight;
        var dialogWidth = $("#insidePopupsFra")[0].clientWidth;
        $("#insideDivBg").css({
            "width": document.body.offsetWidth,
            "height": document.body.offsetHeight < $(window).height() ? $(window).height() : document.body.offsetHeight
        });
        var PopupsFraTop = windowHeight / 2 - dialogHeight / 2 + bodyScrollTop >= 0 ? windowHeight / 2 - dialogHeight / 2 + bodyScrollTop : 0;
        var PopupsFraLfet = windowWidth / 2 - dialogWidth / 2 >= 0 ? windowHeight / 2 - dialogHeight / 2 + bodyScrollTop : 0;
        $("#insidePopupsFra").css({
            "top": PopupsFraTop,
            "left": windowWidth / 2 - dialogWidth / 2
        });
        if (eventFun != null) {
            eventFun();
        }
    },

    closeDialog: function () {
        document.body.parentNode.style.overflow = "";
        $("#PopupsFra").remove();
        $("#divBg").remove();
    },
    closeInsideDialog: function () {
        document.body.parentNode.style.overflow = "";
        $("#insidePopupsFra").remove();
        $("#insideDivBg").remove();
    }

}

//确认弹框
function cueDialog(confirmFun, that, inside, content) {
    var cueContent = "确定要删除吗？删除后不可恢复。";
    if (content) {
        cueContent = content;
    }
    var cueHTML = '<div class="customTipsWrap"><i class="icon_query f-fl"></i><div class="f-fl width252 f-fs14 f-pt5 f-mb10">' + cueContent + '</div><div class="clear"></div></div>';
    if (inside) {
        styledialog.initInsideDialogHTML({
            title: "提示",
            content: cueHTML,
            width: 330,
            confirm: {
                show: true,
                name: "确定"
            },
            cancel: {
                show: true,
                name: "取消"
            }
        });
        styledialog.initInsideDialogContent("提示", cueHTML, null);
    } else {
        styledialog.initDialogHTML({
            title: "提示",
            content: cueHTML,
            width: 330,
            confirm: {
                show: true,
                name: "确定"
            },
            cancel: {
                show: true,
                name: "取消"
            }
        });
        styledialog.initContent("提示", cueHTML, null);
    }
    $(".w-btn input[name='confirm']").unbind().bind("click", function () {
        var btnloading = new btnLoading(this);
        btnloading.toLoading(); //提交按钮变loading图片
        confirmFun(that);
        if (inside) {
            styledialog.closeInsideDialog();
        } else {
            styledialog.closeDialog();
        }
        return false;
    });
    $(".w-btn input[name='cancel']").unbind().bind("click", function () {
        if (inside) {
            styledialog.closeInsideDialog();
        } else {
            styledialog.closeDialog();
        }
        return false;
    });
}

//提示弹框
function promptMessageDialog(settings) {
    this.settings = {
        icon: "warning",
        content: "数据返回出现异常！",
        time: 1000
    }
    $.extend(this.settings, settings || {});
    var iconName = this.settings.icon == "finish" || this.settings.icon == "success" ? "finish" : this.settings.icon;
    var dialogContent = '<div class="customTipsSuccessfulOperation"><i class="icon_' + iconName + '"></i>' + this.settings.content + '</div>';

    $("body").eq(0).append(dialogContent);

    var windowWidth = document.documentElement.clientWidth;
    var windowHeight = document.documentElement.clientHeight;
    var bodyScrollTop = 0;
    if (document.documentElement && document.documentElement.scrollTop) {
        bodyScrollTop = document.documentElement.scrollTop;
    } else if (document.body) {
        bodyScrollTop = document.body.scrollTop;
    }
    var documentHeight = document.documentElement.clientHeight + document.documentElement.scrollHeight;
    var documentWidth = document.documentElement.clientWidth + document.documentElement.scrollWidth;
    var dialogHeight = $(".customTipsSuccessfulOperation")[0].clientHeight;
    var dialogWidth = $(".customTipsSuccessfulOperation")[0].clientWidth;
    var editFraTop = windowHeight / 2 - dialogHeight / 2 + bodyScrollTop >= 0 ? windowHeight / 2 - dialogHeight / 2 + bodyScrollTop : 0;
    var editFraLfet = windowWidth / 2 - dialogWidth / 2 >= 0 ? windowHeight / 2 - dialogHeight / 2 + bodyScrollTop : 0;
    $(".customTipsSuccessfulOperation").css({
        "top": editFraTop,
        "left": windowWidth / 2 - dialogWidth / 2,
        "position": "absolute",
        "z-index": "950"
    });
    setTimeout(function () {
        $(".customTipsSuccessfulOperation").remove();
    }, this.settings.time);
}

//教材文档上传弹框
function resourceUploadDialog(url, title, callback) {
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
    styledialog.initContent("文件资源", "", callback);
}

//文件下载弹框
function fileDownDialog(downLoadUrl) {
    var dialogHTML = '<a href="' + downLoadUrl + '" target="_self">点击下载</a>'
    styledialog.initDialogHTML({
        title: "文件下载",
        content: dialogHTML,
        width: 380,
        confirm: {
            show: false,
            name: "确认"
        },
        cancel: {
            show: true,
            name: "取消"
        }
    });
    styledialog.initContent("文件下载", "", null);
}

//添加应用弹框
function addAppDialog() {
    function addAppEvent() {
        AjaxForJson($("input[name='sys_app_list_url']").val(), null, function (dataObj) {
            var addAppHTML = '<div class="m-spLst m-addAppLst s-bgc" >';
            if (dataObj.rows.length <= 0) {
                addAppHTML += '<div class="tips s-fcLGray f-fl">暂时没有更多应用！</div>'
            }
            else {
                for (var i = 0; i < dataObj.rows.length; i++) {
                    var addDel = dataObj.rows[i].my_app_id ? 'item-added' : 'item-neverAdd';
                    var my_app_id = dataObj.rows[i].my_app_id ? dataObj.rows[i].my_app_id : "";
                    addAppHTML += '<a href="javascript:;" app_url="' + dataObj.rows[i].url + '" my_app_id="' + my_app_id + '" app_id="' + dataObj.rows[i].app_id + '" title="" target="_self" class="item ' + addDel + ' f-tac f-fl u-r"> <i class="icon-' + dataObj.rows[i].icon + '"></i><div class="txt f-tac">' + dataObj.rows[i].name + '</div><i class="label"></i></a>';
                }
            }

            addAppHTML += '<div class="f-cb"></div></div>';

            $("#loadingDiv").replaceWith(addAppHTML);

            //添加应用
            $("div.m-addAppLst").find("a.item-neverAdd").die().live("click", function () {
                var mian = this;

                AjaxForJson($("input[name='add_app_url']").val(), { app_id: $(mian).attr("app_id") }, function (dataObj) {
                    if (dataObj.type == "success") {
                        $("div#app_list").find("a.addSp").before('<a href="' + $(mian).attr("app_url") + '" title="" target="_self" class="item f-tac f-fl" app_id="' + dataObj.data + '"><i class="icon-resourceSp"></i><div class="txt f-tac">' + $(mian).find("div.txt").html() + '</div></a>');

                        $(mian).attr("my_app_id", dataObj.data);
                        $(mian).removeClass("item-neverAdd").addClass("item-added");
                    }
                    else {
                        this.settings = {
                            icon: "warning",
                            content: dataObj.message,
                            time: 1000
                        }
                    }
                });

                return false;
            });
            //添加应用
            $("div.m-addAppLst").find("a.item-added").die().live("click", function () {
                var mian = this;

                AjaxForJson($("input[name='del_app_url']").val(), { my_app_id: $(mian).attr("my_app_id") }, function (dataObj) {
                    if (dataObj.type == "success") {
                        $("div#app_list").find("a[app_id='" + $(mian).attr("my_app_id") + "']").remove();

                        $(mian).attr("my_app_id", "");
                        $(mian).removeClass("item-added").addClass("item-neverAdd");
                    }
                    else {
                        this.settings = {
                            icon: "warning",
                            content: dataObj.message,
                            time: 1000
                        }
                    }
                });
            });
        });

    }

    var dialogHTML = '<div id="loadingDiv" class="loadingStyle1" style="width:600px;height:200px;"></div>';
    styledialog.initDialogHTML({
        title: "添加应用",
        content: dialogHTML,
        width: 600,
        confirm: {
            show: false,
            name: "确认"
        },
        cancel: {
            show: true,
            name: "取消"
        }
    });
    styledialog.initContent("添加应用", dialogHTML, addAppEvent);
}

//选择版本
function chooseVersion() {
    var bbstr = $("input[name='publisher_json']").val();

    bbObj = eval('(' + bbstr + ')');

    versionHTML = '';
    newLesHTML = '<div id="select-all"><dl><dt>版本</dt><dd><div class="sel-list"><ul id="edition-list">';
    for (var key in bbObj) {
        if ($("input[name='publisher_selected_code']").val() == key) {
            newLesHTML += '<li><a href="javascript:;" edition_id="' + key + '" class="selected">' + bbObj[key] + '</a></li>';
        }
        else {
            newLesHTML += '<li><a href="javascript:;" edition_id="' + key + '">' + bbObj[key] + '</a></li>';
        }
    }
    newLesHTML += '</ul></div></dd></dl></div>';

    function versionEvent() {
        //选择版本事件
        $("ul#edition-list li").unbind().bind("click", function () {
            $(this).parent().find("li").find("a").removeClass("selected");
            $(this).find("a").addClass("selected");
        });
        //选择确认事件
        $("#PopupsFunc input[name='confirm']").bind("click", function () {
            AjaxForJson($("input[name='publisher_change_ajax_url']").val(), { publisher_code: $("ul#edition-list").find("li").find("a.selected").attr("edition_id") }, function (dataObj) {
                if (dataObj.type == "success") {
                    $("div.BVer").find("strong").html($("ul#edition-list").find("li").find("a.selected").html());
                    $("input[name='publisher_selected_code']").val($("ul#edition-list").find("li").find("a.selected").attr("edition_id"));
                    styledialog.closeDialog();
                    promptMessageDialog({
                        icon: "finish",
                        content: dataObj.message,
                        time: 1000
                    });
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
    }

    styledialog.initDialogHTML({
        title: "版本选择",
        content: newLesHTML,
        width: 600,
        confirm: {
            show: true,
            name: "确认"
        },
        cancel: {
            show: true,
            name: "取消"
        }
    });
    styledialog.initContent("版本选择", versionHTML, versionEvent);
}

//登陆弹框
function loginDialog(callBack) {
    styledialog.initDialogHTML({
        title: "登陆",
        url: commonParams.jcDevPath + commonParams.jcType + "/index/login",
        width: 560,
        confirm: {
            show: false,
            name: "登陆"
        },
        cancel: {
            show: false,
            name: "取消"
        }
    });
    styledialog.initContent("登陆", "", function () {
        $("#btn_login").die().live("click", function () {
            loginEvent();
        });

        $("input[name='userPass']").unbind().bind('keyup', function (event) {
            if (event.keyCode == 13) {
                loginEvent();
            }
        });

        function loginEvent() {
            if ($.trim($("input[name='userName']").val()) == "") {
                promptMessageDialog({
                    icon: "warning",
                    content: '用户名不能为空',
                    time: 1000
                });
                return;
            }
            if ($.trim($("input[name='userPass']").val()) == "") {
                promptMessageDialog({
                    icon: "warning",
                    content: '密码不能为空',
                    time: 1000
                });
                return;
            }
            var btnloading = new btnLoading($("input#btn_login")[0]);
            btnloading.toLoading(); //提交按钮变loading图片
            var requestData = $("form#loginFrom").serialize();
            var requestUrl = $("form#loginFrom").attr("action");
            $.ajaxJSONP(requestUrl, requestData, function (dataObj) {
                if (dataObj.errcode == "0") {
                    window.location.reload();
                }
                else if (dataObj.errcode == "1") {
                    promptMessageDialog({
                        icon: "warning",
                        content: '用户名密码错误',
                        time: 1000
                    });
                    $("input[name='userPass']").focus();
                    btnloading.toBtn();
                }
                else if (dataObj.errcode == "2") {
                    promptMessageDialog({
                        icon: "warning",
                        content: '尚未注册',
                        time: 1000
                    });
                    $("input[name='userPass']").focus();
                    btnloading.toBtn();
                }
                else {
                    promptMessageDialog({
                        icon: "warning",
                        content: '程序内部错误',
                        time: 1000
                    });
                    $("input[name='userPass']").focus();
                    btnloading.toBtn();
                }
            });
        }
    });
}