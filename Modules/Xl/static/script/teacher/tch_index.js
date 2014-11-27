$(document).ready(function () {
    $("a#publisher_change").unbind().bind("click", function () {
        chooseVersion();

        return false;
    });

    $("a.addSp").unbind().bind("click", function () {
        addAppDialog();//调用添加APP弹框
        return false;
    });
});