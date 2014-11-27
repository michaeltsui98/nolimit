var commonParams = {

};

jQuery.fn.extend({
    //tab切换插件
    tabTopChange: function () {
        var main = this;
        main.extend({
            tabTopLi: main.find('a').bind({
                //mouseover: function (e) {
                //    //鼠标经过，如果不是活动TAB，则会高亮图标
                //    if ($(this).attr('class').indexOf('tabMenuCurrent') < 1) {
                //        var currentClass = $(this).find('i').attr('class').replace('Gray', '');
                //        $(this).find('i').attr('class', currentClass);
                //    }
                //},
                //mouseout: function (e) {
                //    if ($(this).attr('class').indexOf('tabMenuCurrent') < 1) {
                //        var currentClass = $(this).find('i').attr('class') + 'Gray';
                //        $(this).find('i').attr('class', currentClass);
                //    }
                //},
                click: function (e) {
                    //鼠标经过，如果不是当前TAB，则会改变样式并作数据请求
                    if ($(this).attr('class').indexOf('z-crt') < 1) {
                        main.find('a').attr('class', 'item');
                        $(this).attr('class', 'item z-crt');
                        //ajax
                    }
                    return false;
                }
            })
        })
    },
    tab2Menu: function (callback) {
        var main = this;
        main.extend({
            tabTopLi: main.find('li').bind({
                click: function (e) {
                    //鼠标经过，如果不是当前TAB，则会改变样式并作数据请求
                    if ($(this).attr('class').indexOf('tabMenuCurrent') < 1) {
                        main.find('li').attr('class', 'menuCell f-fl');
                        $(this).attr('class', 'menuCell tabMenuCurrent f-fl');
                        callback();
                    }
                    return false;
                }
            })
        })
    },
    tab1Menu: function (callback) {
        var main = this;
        main.extend({
            tabTopLi: main.find('strong').bind({
                click: function (e) {
                    //鼠标经过，如果不是当前TAB，则会改变样式并作数据请求
                    if ($(this).attr('class') != 'cur') {
                        main.find('strong').attr('class', '');
                        $(this).attr('class', 'cur');
                        callback();
                    }
                    return false;
                }
            })
        })
    },
    //展开 收缩
    packUpAndDown: function () {
        var main = this;
        $(main).bind({
            click: function (e) {
                if ($(main).attr('class').indexOf('u-puBtn') > 0) {
                    main.parent().parent().find('.s-fcGray').css('display', 'none');
                    main.attr('class', main.attr('class').replace('u-puBtn', 'u-ufBtn'));
                } else {
                    main.parent().parent().find('.s-fcGray').css('display', '');
                    main.attr('class', main.attr('class').replace('u-ufBtn', 'u-puBtn'));
                }
                return false;
            }
        })
    },
    clearText: function () {
        return this.each(function (i) {
            var defaultValue = $(this).data('default');
            if (this.defaultValue == defaultValue) {
                this.style.color = "#b5b5b5";
                $(this).addClass('clearStyle');
            }
            $(this).bind({
                blur: function (e) {
                    this.value = jQuery.trim(this.value);
                    if (this.value.length <= 0 && this.defaultValue == defaultValue) {
                        this.value = this.defaultValue
                        this.style.color = "#b5b5b5";
                        $(this).addClass('clearStyle');
                    } else {
                        $(this).removeClass('clearStyle');
                    }
                },
                focus: function (e) {
                    if (this.value == $(this).data('default')) {
                        this.value = "";
                        this.style.color = "#383838";
                    }
                    $(this).removeClass('clearStyle');
                }
            });
        });
    }
});
jQuery.extend({
    topDropdownSelect: function (obj, mouseOverStyle) {
        //下拉框隐藏显示
        var timeParam;
        if (mouseOverStyle == undefined || mouseOverStyle == null) {
            mouseOverStyle = '';
        }
        $('.' + obj + " .dropdown_currentValue").live('mouseover', function () {
            $('.' + obj).find('.dropdownItem').css('visibility', 'visible');
            clearTimeout(timeParam);
        });
        $('.' + obj + " .dropdown_currentValue").live('mouseout', function () {
            timeParam = setTimeout(function () {
                $('.' + obj).find('.dropdownItem').css("visibility", "hidden");
            }, 1000);
        });
        $('.' + obj + " .dropdownItem").live('mouseover', function () {
            $(this).css('visibility', 'visible');
            clearTimeout(timeParam);
        });
        $('.' + obj + " .dropdownItem").live('mouseout', function () {
            timeParam = setTimeout(function () {
                $('.' + obj).find('.dropdownItem').css("visibility", "hidden");
            }, 1000);
        });
        $('.' + obj + ' .dropdownCell').bind('click', function () {
            $('.' + obj + ' .dropdownCell').find('a').attr('class', 'f-db curDefault');
            $(this).find('a').attr('class', 'f-db curDefault customDropdown_selectedValue');
            $('.' + obj).find('.dropdownItem').css("visibility", "hidden");
            $('.' + obj).find('.dropdownTxt').html($(this).find('a').html());
            //ajax           
            return false;
        });
        // 绑定在其他位置点击时取消显示
        $('body').bind({
            click: function (e) {
                $('.' + obj).find('.dropdownItem').css("visibility", "hidden");
            }
        });
    },
    dropdownInput: function (obj) {
        $('.' + obj + ' .customForm_inputBoxGetFocus1').bind('focus', function () {
            $('.' + obj).find('.associateWrap').css('visibility', 'visible');
            return false;
        });
        $(document).on('click', '.' + obj + ' .associateCell', function () {
            $('.' + obj + ' .customForm_inputBoxGetFocus1').val($(this).find('a').html());
            $('.' + obj).find('.associateWrap').css('visibility', 'hidden');
            return false;
        });
        document.onclick = function (e) {
            e = e ? e : event;
            var srcEle = e.target || e.srcElement;
            if (srcEle.className.indexOf("customForm_inputBoxGetFocus1") < 0) {
                $('.' + obj).find('.associateWrap').css('visibility', 'hidden');
            }
        };
    },
    AjaxForJson: function (requestUrl, requestData, SuccessCallback, errorCallback, successPar) {
        alert(requestUrl);
    }
});