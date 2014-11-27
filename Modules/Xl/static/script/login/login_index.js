$(document).ready(function () {


    $("input.login-btn").die().live("click", function () {
        loginEvent();
    });

    $("input[name='userPass']").unbind().bind('keyup', function (event) {
        if (event.keyCode == 13) {
            loginEvent();
        }
    });

    $("div.m-enterSection").enterTypeChange();

    function loginEvent() {
        var btnloading = new btnLoading($("input.login-btn")[0]);
         $("input.login-btn").replaceWith('<input class="login-loading-btn" type="button" value="" >');//提交按钮变loading图片
        var requestData = $("form#loginFrom").serialize();
        var requestUrl = $("form#loginFrom").attr("action");
        $.ajaxJSONP(requestUrl, requestData, function (dataObj) {
            if (dataObj.errcode == "0" && dataObj.state == "index") {
                if (dataObj.role_code == "1") {
                    window.location.href = commonParams.jcDevPath + commonParams.jcType + "/student";
                }
                else if (dataObj.role_code == "2") {
                    window.location.href = commonParams.jcDevPath + commonParams.jcType + "/teacher";
                }
                else if (dataObj.role_code == "3") {
                    window.location.href = commonParams.jcDevPath + commonParams.jcType + "/parent";
                }
            }
            else if (dataObj.errcode == "1") {
                promptMessageDialog({
                    icon: "warning",
                    content: '用户名密码错误',
                    time: 1000
                });
                $("input[name='userPass']").focus();
                btnloading.toBtn();
                $("input.login-loading-btn").replaceWith('<input class="login-btn" type="button" value="登录">');
            }
            else if (dataObj.errcode == "2") {
                promptMessageDialog({
                    icon: "warning",
                    content: '尚未注册',
                    time: 1000
                });
                $("input[name='userPass']").focus();
                btnloading.toBtn();
                $("input.login-loading-btn").replaceWith('<input class="login-btn" type="button" value="登录">');
            }
            else {
                promptMessageDialog({
                    icon: "warning",
                    content: '程序内部错误',
                    time: 1000
                });
                $("input[name='userPass']").focus();
                btnloading.toBtn();
                $("input.login-loading-btn").replaceWith('<input class="login-btn" type="button" value="登录">');
            }
        });
    }
});

jQuery.fn.extend({
    enterTypeChange: function () {
        var mian = this;
        return $(mian).find("a").unbind().bind("mouseover", function () {
            var that=this;
            $("div.m-popLayer").each(function () {
                if ($(this).attr("name") == $(that).attr("name")) {
                    $(this).css({ "height": $(window).height() - 380 + "px", "display": "block" });
                }
                else {
                    $(this).css("display", "none");
                }
            });
            return false;
        });
    }
});