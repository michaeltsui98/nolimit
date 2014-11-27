$(document).ready(function () {
    $("li[name='do_evaluate']").die().live("click", function () {
        var mian = this;
        $(mian).toLocation({
            requestUrl: $(mian).attr("url"),
            requestData: "id=" + $(mian).attr("id") + "&types=" + $(mian).attr("types") + "&evaluate_id=" + $(mian).attr("evaluate_id") + "&evaluate_classify=" + $(mian).attr("evaluate_classify") + "&course_id=" + $(mian).attr("course_id"),
            callback: function (dataObj) {
                if (dataObj) {
                    window.open(dataObj);
                }
            }
        });
    });
});