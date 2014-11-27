var commonParams = {
    jcDevPath: "http://jc.dodoedu.com",
    wenkuPath: "http://dev-wenku.dodoedu.com",
    jcStsticPath: "http://dev-images.dodoedu.com"
};

//ajax调用公共方法
function AjaxForJson(requestUrl, requestData, SuccessCallback, errorCallback, successPar) {
    jQuery.ajax({
        type: "POST",
        url: requestUrl,
        data: requestData,
        contentType: "application/x-www-form-urlencoded",
        dataType: "text",
        success: function (data) {
            var obj = null;
            try {
                obj = eval('(' + data + ')');
            } catch (ex) {
                obj = data;
            }
            if (obj == null) {
                return;
            }
            if (obj.status == 0) {
                //window.location.href = commonParams.dodoDevPath + "/userSignUp/login";
            }
            else if (obj.type == "login") {
                loginDialog();
            }
            else {
                SuccessCallback(obj, successPar);
            }
            //$('img').error(function () {
            //    $(this).attr('src', commonParams.dodoStaticPath + '/shequPage/common/image/noFindPic.gif');
            //    if ($(this).width() > 160) {
            //        $(this).css('width', '160px');
            //    }
            //});
        },
        error: function (err) {
            err;
        },
        complete: function (XHR, TS) {
            XHR = null
        }
    });
}

// ajax跨域请求方法
jQuery.extend({
    ajaxJSONP: function (url, data, callback) {
        $.ajax({
            type: "get",
            async: false,
            url: url,
            data: data,
            dataType: "jsonp",
            jsonp: "callback",//传递给请求处理程序或页面的，用以获得jsonp回调函数名的参数名(一般默认为:callback)
            success: function (json) {
                callback(json);
            },
            error: function (e) {
                alert("ajaxJSONP error");
            }
        });
    }
});

//动态加载js/css文件
function loadjscssfile(filename, filetype) {
    if (filetype == "js") { //判断文件类型 
        var fileref = document.createElement('script');//创建标签
        fileref.setAttribute("type", "text/javascript");//定义属性type的值为text/javascript
        fileref.setAttribute("src", filename);//文件的地址
        var fileArr = filename.split("/");
        fileref.setAttribute("id", fileArr[fileArr.length - 1]);
    }
    else if (filetype == "css") { //判断文件类型 
        var fileref = document.createElement("link");
        fileref.setAttribute("rel", "stylesheet");
        fileref.setAttribute("type", "text/css");
        fileref.setAttribute("href", filename);
    }
    if (typeof fileref != "undefined")
        document.getElementsByTagName("head")[0].appendChild(fileref);
}
//改进jquery动态加载js方法
//定义一个全局script的标记数组，用来标记是否某个script已经下载到本地
var scriptsArray = new Array();
$.cachedScript = function (url, options) {
    //循环script标记数组
    for (var s in scriptsArray) {
        //如果某个数组已经下载到了本地
        if (scriptsArray[s] == url) {
            return {  //则返回一个对象字面量，其中的done之所以叫做done是为了与下面$.ajax中的done相对应
                done: function (method) {
                    if (typeof method == 'function') {  //如果传入参数为一个方法
                        method();
                    }
                }
            };
        }
    }
    //这里是jquery官方提供类似getScript实现的方法，也就是说getScript其实也就是对ajax方法的一个拓展
    options = $.extend(options || {}, {
        dataType: "script",
        url: url,
        cache: true //其实现在这缓存加与不加没多大区别
    });
    scriptsArray.push(url); //将url地址放入script标记数组中
    return $.ajax(options);
};

//图片轮播器
var picPlayer = (function (params) {
    var playerPar = {
        containerObj: null,
        speed: null,
        timer: null,

        picsObj: null,
        btnObj: null,
        picWidth: null,
        btnClsssName: "",

        picTitleObj: null,
        picTitle: new Array(),
        picUrl: new Array(),

        requestUrl: "",
        requestData: null
    };
    $.extend(playerPar, params || {});
    function initPicPlayer() {
        var pics = playerPar.picsObj;
        var btns = playerPar.btnObj;
        //选中的图片
        var selectedItem;
        //选中的按钮
        var selectedBtn;
        //自动播放的id
        var playID;
        //选中图片的索引
        var selectedIndex;
        for (var i = 0; i < btns.length; i++) {
            (function () {
                var index = i;
                btns[i].onclick = function () {
                    if (selectedBtn == this) {
                        return;
                    }
                    setSelectedItem(index);
                };
            })();
        }
        setSelectedItem(0);

        function setSelectedItem(index) {
            var picWidth = playerPar.picWidth;
            selectedIndex = index;
            clearInterval(playID);
            selectedItem = pics[parseInt(index)];
            playerPar.containerObj.animate({
                left: (0 - picWidth) * index
            }, playerPar.speed, function () {
                //自动播放方法
                playID = setTimeout(function () {
                    var index = selectedIndex + 1;
                    if (index > pics.length - 1)
                        index = 0;
                    setSelectedItem(index);
                }, playerPar.timer);
            });
            playerPar.picTitleObj.html(playerPar.picTitle[index]);
            playerPar.picTitleObj.attr("href", playerPar.picUrl[index]);
            if (selectedBtn) {
                selectedBtn.className = "";
            }
            selectedBtn = btns[parseInt(index)];
            selectedBtn.className = playerPar.btnClsssName;
        }
    }


    return {
        showPicPlayer: function () {
            var requestUrl = playerPar.requestUrl;
            $.post(requestUrl, requestData, function (obj) {
                if (obj.type == "success" && obj.data.length > 0) {
                    var picHTMLs = '<div id="SQalbumConDiv" style="position:relative;width:2670px;height:217px;">';
                    for (var i = 0; i < 5; i++) {
                        var pic = "";
                        var title = "";
                        var url = "";
                        if (i < obj.data.length) {
                            pic = obj.data[i].pic;
                            title = obj.data[i].title;
                            url = obj.data[i].url;
                        } else {
                            if (obj.data.length == 1) {
                                pic = obj.data[0].pic;
                                title = obj.data[0].title;
                                url = obj.data[0].url;
                            } else if (obj.data.length == 2) {
                                pic = obj.data[(i - obj.data.length) % obj.data.length].pic;
                                title = obj.data[(i - obj.data.length) % obj.data.length].title;
                                url = obj.data[(i - obj.data.length) % obj.data.length].url;
                            } else {
                                pic = obj.data[i - obj.data.length].pic;
                                title = obj.data[i - obj.data.length].title;
                                url = obj.data[i - obj.data.length].url;
                            }
                        }
                        picHTMLs += '<a title="" target="_blank" href="' + url + '" style="width:534px; height:217px;float:left;"><img id="picitem' + i + '" src="' + pic + '" alt="" width="534" height="217" /></a>';
                        playerPar.picTitle[i] = title;
                        playerPar.picUrl[i] = url;
                    }
                    picHTMLs += '</div>';
                    playerPar.containerObj.append(SQblogParams.blogPicHTMLs);
                }
                initPicPlayer();
            });
        }
    }
})();

jQuery.fn.extend({
    /**
     * 获取焦点时清除默认文本
     * @return {jQuery}
     */
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
    },


    /**
     * 获取焦点样式改变
     */
    changeStyle: function () {
        return this.each(function (i) {
            $(this).bind({
                blur: function (e) {
                    $(this).removeClass("inputBoxFocusStyle");
                },
                focus: function (e) {
                    $(this).addClass("inputBoxFocusStyle");
                }
            });
        });
    },


    /**
    *检验最大长度
    */
    checkMaxLen: function () {
        return this.each(function (i) {
            var max_length = $(this).attr("maxLength");
            if ($(this).val().length > max_length) {
                $(this).val($(this).val().substring(0, max_length));
            }
        });
    }
});

//tab切换插件
jQuery.fn.extend({
    tabMenu4: function () {
        var main = this;
        return $(main).find("li").unbind().bind({
            mouseover: function () {

            },
            mouseout: function () {

            },
            click: function () {

            }
        });
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
                        callback($(this).attr('name'));
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
    tabTopChange: function (callback) {
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
                        callback();
                    }
                    return false;
                }
            })
        })
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
    }
});

jQuery.fn.extend({
    //弹性运动插件
    elasticities: function () {
        var mian = this[0];
        var speed = 0;
        var cur = 0;
        var timer = null;
        cur = -mian.offsetLeft;

        function doMove(iTarget) {

            speed += iTarget + mian.offsetLeft;
            speed *= 0.8;

            cur += speed;

            if (cur > 0) {
                cur = Math.ceil(cur);
            }
            else {
                cur = Math.floor(cur);
            }

            if (Math.abs(iTarget - cur) < 1 && Math.abs(speed) < 1) {
                clearInterval(timer);
                timer = null;
            }
            else {
                mian.style.left = -cur + 'px';
            }
        }

        mian.style.left = -cur + 'px';
        if (timer) {
            clearInterval(timer);
        }
        timer = setInterval(function () {
            doMove(-500);
        }, 35);
    },
    //动态加载更多插件
    loadMore: function () {
        var isEnd = false;
        var pageNum = 1;
    }
});

/*
*js实现分页
**/
//方法驱动
function pageMethod() {
    var obj = this;
    obj.resetTotal();
    obj.reloadpageAjax(obj.currentPageNum);
    obj.page(); //生成页码
    ready2go.call(obj);
}
//添加页码点击事件
function ready2go() {
    var obj = this;
    $("#" + obj.page_obj_id + " a").die().live("click", function () {
        obj.target_p = parseInt($(this).attr("p"));
        gotopage.call(obj, obj.target_p);
    });
}
//跳转至哪一页
function gotopage(target) {
    this.cpage = target; //把页面计数定位到第几页
    this.page();
    this.reloadpageAjax(target);
}
//初始化各个属性
function jsPage(listLength, page_obj_id, pagesize, requesturl, requestdata, responsevent, currentpagenum, successpar) {
    // list_id 结果集UL的id
    // list_class 要显示的类别
    // page_id 存放页码的id
    // pagesize 每页显示多少条
    this.page_obj_id = page_obj_id;
    this.page_obj = $("#" + page_obj_id); //存放页码的div
    this.results = parseInt(listLength); // 总记录数等于所有记录

    this.totalpage; // 总页数
    this.pagesize = parseInt(pagesize); //每页记录数
    this.cpage = currentpagenum; //当前页,默认显示第一页
    this.count;
    this.target_p;
    this.curcount;
    this.outstr = ""; // 输出页码html 
    this.goNext = 5;//每次生成多少页码
    this.requestUrl = requesturl;//ajax请求地址
    this.requestData = requestdata;//ajax请求参数
    this.responseEvent = responsevent;//请求成功调用的方法
    this.successPar = successpar ? successpar : null;//请求成功调用方法的参数
    this.currentPageNum;
    if (currentpagenum) {
        this.currentPageNum = currentpagenum;
        this.cpage = parseInt(currentpagenum);
    }
    else {
        this.currentPageNum = 1;
        this.cpage = 1;
    }
}
//加载当前目标也内容
jsPage.prototype.reloadpage = function (p) {
    this.li.hide();
    for (var i = this.pagesize * p - this.pagesize; i < this.pagesize * p; i++) {
        this.li.eq(i).show();//eq指定第几个li显示
    }
};
//ajax加载当前目标页内容
jsPage.prototype.reloadpageAjax = function (p) {
    var requestData = this.requestData + "&p=" + p;
    AjaxForJson(this.requestUrl, requestData, this.responseEvent, null, this.successPar);
};
//计算总页数
jsPage.prototype.resetTotal = function () {
    if (this.results == 0) {
        this.totalpage = 0;
        this.cpage = 0;
    } else if (this.results <= this.pagesize) {
        this.totalpage = 1;
    } else if (parseInt(this.results / this.pagesize) == 1) {
        this.totalpage = 2;
    } else if (parseInt(this.results / this.pagesize) > 1 && this.results % this.pagesize == 0) {
        this.totalpage = this.results / this.pagesize;
    } else {
        this.totalpage = parseInt(this.results / this.pagesize) + 1;
    }
};
//加载页面跳转控件
jsPage.prototype.page = function () {
    if (this.totalpage <= this.goNext) {
        for (this.count = 1; this.count <= this.totalpage; this.count++) {
            if (this.count != this.cpage) {
                this.outstr = this.outstr + "<a href='javascript:void(0)' p='" + this.count + "' >" + this.count + "</a>";
            } else {
                this.outstr = this.outstr + "<span class='current' >" + this.count + "</span>";
            }
        }
    }
    if (this.totalpage > this.goNext) {
        if (parseInt((this.cpage - 1) / this.goNext) == 0) {
            for (this.count = 1; this.count <= this.goNext; this.count++) {
                if (this.count != this.cpage) {
                    this.outstr = this.outstr + "<a href='javascript:void(0)' p='" + this.count + "' >" + this.count + "</a>";
                } else {
                    this.outstr = this.outstr + "<span class='current'>" + this.count + "</span>";
                }
            }
            this.outstr = this.outstr + "<a href='javascript:void(0)' p='" + this.count + "' >&raquo;</a>";
        } else if (parseInt((this.cpage - 1) / this.goNext) == parseInt(this.totalpage / this.goNext)) {
            this.outstr = this.outstr + "<a href='javascript:void(0)' p='" + (parseInt((this.cpage - 1) / this.goNext) * this.goNext) + "' >&laquo;<\/a>";
            for (this.count = parseInt(this.totalpage / this.goNext) * this.goNext + 1; this.count <= this.totalpage; this.count++) {
                if (this.count != this.cpage) {
                    this.outstr = this.outstr + "<a href='javascript:void(0)' p='" + this.count + "' >" + this.count + "</a>";
                } else {
                    this.outstr = this.outstr + "<span class='current'>" + this.count + "</span>";
                }
            }
        } else {
            var lastP;
            this.outstr = this.outstr + "<a href='javascript:void(0)' p='" + (parseInt((this.cpage - 1) / this.goNext) * this.goNext) + "' >&laquo;<\/a>";
            for (this.count = parseInt((this.cpage - 1) / this.goNext) * this.goNext + 1; this.count <= parseInt((this.cpage - 1) / this.goNext) * this.goNext + this.goNext; this.count++) {
                if (this.count != this.cpage) {
                    this.outstr = this.outstr + "<a href='javascript:void(0)' p='" + this.count + "' >" + this.count + "</a>";
                } else {
                    this.outstr = this.outstr + "<span class='current'>" + this.count + "</span>";
                }
                if (this.count == this.totalpage) {
                    lastP = "";
                } else {
                    lastP = "<a href='javascript:void(0)' p='" + (this.count + 1) + "' >&raquo;</a>";
                }
            }
            this.outstr = this.outstr + lastP;
        }
    }
    if (this.totalpage > 1) {
        this.Prestr = "<a href='javascript:void(0)' p='" + parseInt(this.cpage - 1) + "'>上一页</a>";
        this.startstr = "<a href='javascript:void(0)' p='" + 1 + "'>首页</a>";
        this.nextstr = "<a href='javascript:void(0)' p='" + parseInt(this.cpage + 1) + "'>下一页</a>";
        this.endstr = "<a href='javascript:void(0)' p='" + this.totalpage + "'>尾页</a>";
        if (this.cpage != 1) {
            if (this.cpage >= this.totalpage) {
                document.getElementById(this.page_obj_id).innerHTML = "<div>" + this.startstr + this.Prestr + this.outstr + "<\/div>";
            }
            else {
                document.getElementById(this.page_obj_id).innerHTML = "<div>" + this.startstr + this.Prestr + this.outstr + this.nextstr + this.endstr + "<\/div>";
            }
        }
        else {
            document.getElementById(this.page_obj_id).innerHTML = "<div>" + this.outstr + this.nextstr + this.endstr + "<\/div>";
        }
    }
    else {
        //document.getElementById(this.page_obj_id).innerHTML = "<div>" + this.outstr + "<\/div>";
        document.getElementById(this.page_obj_id).innerHTML = "";
    }
    this.outstr = "";
};

//提交ajax时将按钮变成loading图片防止重复提交
function btnLoading(that) {
    this.main = that;
    this.btnHTML = that.outerHTML;
}
//将提交按钮转换为loading图片
btnLoading.prototype.toLoading = function (ifCover) {
    $(this.main).replaceWith('<img id="btnLoading" class="loadingStyle" src="' + commonParams.dodoStaticPath + '/shequPage/common/image/loading.gif" alt="">');
    if (ifCover) {
        $("body").eq(0).append('<div id="coverImg" style="position:absolute;background:#f6f6f6;border:2px solid #C1C1C1;z-index:100;"><img style="margin: 0 10px;" src="' + commonParams.dodoStaticPath + '/shequPage/common/image/loading_1.gif" alt=""></div><div id="divCover" class="pageBg"></div>');
        $("#divCover").css("display", "block");
        var windowWidth, windowHeight;
        if (document.documentElement.clientWidth == 0) {
            windowWidth = document.documentElement.offsetWidth;
        }
        else {
            windowWidth = document.documentElement.clientWidth;
        }
        if (document.documentElement.clientHeight == 0) {
            windowHeight = document.documentElement.offsetHeight;
        }
        else {
            windowHeight = document.documentElement.clientHeight;
        }
        var bodyScrollTop = 0;
        var bodyScrollLeft = 0;
        if (document.documentElement && document.documentElement.scrollTop) {
            bodyScrollTop = document.documentElement.scrollTop;
            bodyScrollLeft = document.documentElement.scrollLeft;
        }
        else if (document.body) {
            bodyScrollTop = document.body.scrollTop;
            bodyScrollLeft = document.body.scrollLeft;
        }
        var documentHeight = document.documentElement.clientHeight + document.documentElement.scrollHeight;
        var documentWidth = document.documentElement.clientWidth + document.documentElement.scrollWidth;
        var dialogHeight = $("#coverImg")[0].clientHeight;
        var dialogWidth = $("#coverImg")[0].clientWidth;
        $("#divCover").css({ "width": document.documentElement.scrollWidth, "height": document.documentElement.scrollHeight });
        var editFraTop = windowHeight / 2 - dialogHeight / 2 + bodyScrollTop >= 0 ? windowHeight / 2 - dialogHeight / 2 + bodyScrollTop : 0;
        var editFraLfet = windowWidth / 2 - dialogWidth / 2 + bodyScrollLeft >= 0 ? windowWidth / 2 - dialogWidth / 2 + bodyScrollLeft : 0;
        $("#coverImg").css({
            "top": editFraTop,
            "left": editFraLfet
        });
    }
};
//将loading图片转换为提交按钮
btnLoading.prototype.toBtn = function (ifCover) {
    $("img#btnLoading").replaceWith(this.btnHTML);
    if (ifCover) {
        $("#coverImg").remove();
        $("#divCover").remove();
    }
};
//提交ajax是内容中出现加载loading图片
function contentLoading(that) {
    that.html('<div name="contentLoadingDiv" class="alignCenter" style="margin-top:20px;margin-bottom:20px;"><img class="loadingStyle" src="' + commonParams.dodoStaticPath + '/shequPage/common/image/loading_2.gif" alt=""></div>');
}
//提交ajax是内容最后加载loading图片
function contentAppendLoading(that) {
    that.append('<div name="contentLoadingDiv" class="alignCenter" style="margin-top:20px;margin-bottom:20px;"><img class="loadingStyle" src="' + commonParams.dodoStaticPath + '/shequPage/common/image/loading_2.gif" alt=""></div>');
}
//移除内容中的loading图片
function removeContentLoading(that) {
    that.find("div[name='contentLoadingDiv']").remove();
}