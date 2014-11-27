$(document).ready(function () {
    $('[name="rightDropDown"]').topDropdownSelect('customDropdown1MouseOver');

    $("div.unit").find("a[name='packDown']").die().live("click", function () {
        var mian = this;
        if ($.trim($(mian).parents("div.unit").find("div.lst").html()) == "") {
            contentLoading($(mian).parents("div.unit").find("div.lst"));
            AjaxForJson(commonParams.jcDevPath + commonParams.jcType + "/lesson/node/chapter/" + $(this).parent().attr("chapter"), null, function (dataObj) {
                $(mian).parents("div.unit").find("div.lst").html(dataObj);
                $(mian).replaceWith('<a href="javascript:;" title="收起" name="packUp" class="f-fr u-puBtn"></a>');
            });
        }
        else {
            $(mian).parents("div.unit").find("div.lst").css("display", "block");
            $(mian).replaceWith('<a href="javascript:;" title="收起" name="packUp" class="f-fr u-puBtn"></a>');
        }
    });
    $("div.unit").find("a[name='packUp']").die().live("click", function () {
        var mian = this;
        $(mian).parents("div.unit").find("div.lst").css("display", "none");
        $(mian).replaceWith('<a href="javascript:;" title="展开" target="" name="packDown" class="f-fr u-ufBtn"></a>');
    });
    $("div.unit").find("a[name='packDown']").eq(0).trigger("click");
});