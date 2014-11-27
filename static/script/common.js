var commonParams = {
    jcDevPath: "http://dev-jc.dodoedu.com",
    jcStaticPath: "http://dev-jc.dodoedu.com",
    wenkuPath: "http://dev-wenku.dodoedu.com",
    jcStsticPath: "http://dev-images.dodoedu.com",
    wenkuStaticPath: "http://dev-images.dodoedu.com/wenku/",
    sqDevPath: "http://dev.dodoedu.com",
    jcType: "/" + subject,//"/" + window.location.pathname.split("/")[1]
    isShowBrowserInfo:true
};

//ajax调用公共方法
function AjaxForJson(requestUrl, requestData, SuccessCallback, errorCallback, successPar) {
    jQuery.ajax({
        type: "POST",
        url: requestUrl,
        data: requestData,
        contentType: "application/x-www-form-urlencoded",
        dataType: "text",
        sync: false,
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
                loginDialog(); //调用登陆弹框
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
                promptMessageDialog({
                    icon: "warning",
                    content: "网络请求错误！",
                    time: 1000
                });
                //alert("ajaxJSONP error");
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
            AjaxForJson(requestUrl, requestData, function (obj) {
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
            var defaultValue = $(this).data('default') ? $(this).data('default') : $(this).attr('datavalue');
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
                    if (this.value == defaultValue) {
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
jQuery(function ($) {
    // 设置文本框最大输入数
    $(':text').checkMaxLen();
});
//tab切换插件
jQuery.fn.extend({
    topDropdownSelect: function (mouseOverStyle) {
        var main = this
        //下拉框隐藏显示
        var timeParam;
        if (mouseOverStyle == undefined || mouseOverStyle == null) {
            mouseOverStyle = '';
        }
        main.find(".dropdown_currentValue").live('mouseover', function () {
            main.find('.dropdownItem').css('visibility', 'visible');
            clearTimeout(timeParam);
        });
        main.find(" .dropdown_currentValue").live('mouseout', function () {
            timeParam = setTimeout(function () {
                main.find('.dropdownItem').css("visibility", "hidden");
            }, 1000);
        });
        main.find(" .dropdownItem").live('mouseover', function () {
            $(this).css('visibility', 'visible');
            clearTimeout(timeParam);
        });
        main.find(" .dropdownItem").live('mouseout', function () {
            timeParam = setTimeout(function () {
                main.find('.dropdownItem').css("visibility", "hidden");
            }, 1000);
        });
        main.find(' .dropdownCell').bind('click', function () {
            main.find('.dropdownCell').find('a').attr('class', 'f-db curDefault');
            $(this).find('a').attr('class', 'f-db curDefault customDropdown_selectedValue');
            main.find('.dropdownItem').css("visibility", "hidden");
            main.find('.dropdownTxt').html($(this).find('a').html());
            //ajax           
            return false;
        });
        // 绑定在其他位置点击时取消显示
        $('body').bind({
            click: function (e) {
                main.find('.dropdownItem').css("visibility", "hidden");
            }
        });
    },

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
                        callback($(this).attr('name'));
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
                if ($(this).attr('class').indexOf('u-puBtn') > 0) {
                    $(this).parent().parent().find('.s-fcGray').css('display', 'none');
                    $(this).attr('class', $(this).attr('class').replace('u-puBtn', 'u-ufBtn'));
                } else {
                    $(this).parent().parent().find('.s-fcGray').css('display', '');
                    $(this).attr('class', $(this).attr('class').replace('u-ufBtn', 'u-puBtn'));
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
    }
});

/*
*js实现分页
*        var requestMenberpage = new jsPage(obj.count, "commentPageNum", "9", appCommentRequestUrl, appCommentRequestData,responseEvent);
*        pageMethod.call(requestMenberpage);
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
                this.outstr = this.outstr + "<b>" + this.count + "</b>";
            }
        }
    }
    if (this.totalpage > this.goNext) {
        if (parseInt((this.cpage - 1) / this.goNext) == 0) {
            for (this.count = 1; this.count <= this.goNext; this.count++) {
                if (this.count != this.cpage) {
                    this.outstr = this.outstr + "<a href='javascript:void(0)' p='" + this.count + "' >" + this.count + "</a>";
                } else {
                    this.outstr = this.outstr + "<b>" + this.count + "</b>";
                }
            }
            this.outstr = this.outstr;// + "<a href='javascript:void(0)' p='" + this.count + "' >&raquo;</a>"
        } else if (parseInt((this.cpage - 1) / this.goNext) == parseInt(this.totalpage / this.goNext)) {
            this.outstr = this.outstr;// + "<a href='javascript:void(0)' p='" + (parseInt((this.cpage - 1) / this.goNext) * this.goNext) + "' >&laquo;<\/a>"
            for (this.count = parseInt(this.totalpage / this.goNext) * this.goNext + 1; this.count <= this.totalpage; this.count++) {
                if (this.count != this.cpage) {
                    this.outstr = this.outstr + "<a href='javascript:void(0)' p='" + this.count + "' >" + this.count + "</a>";
                } else {
                    this.outstr = this.outstr + "<b>" + this.count + "</b>";
                }
            }
        } else {
            var lastP;
            this.outstr = this.outstr;// + "<a href='javascript:void(0)' p='" + (parseInt((this.cpage - 1) / this.goNext) * this.goNext) + "' >&laquo;<\/a>"
            for (this.count = parseInt((this.cpage - 1) / this.goNext) * this.goNext + 1; this.count <= parseInt((this.cpage - 1) / this.goNext) * this.goNext + this.goNext; this.count++) {
                if (this.count != this.cpage) {
                    this.outstr = this.outstr + "<a href='javascript:void(0)' p='" + this.count + "' >" + this.count + "</a>";
                } else {
                    this.outstr = this.outstr + "<b>" + this.count + "</b>";
                }
                if (this.count == this.totalpage) {
                    lastP = "";
                } else {
                    lastP = "";//<a href='javascript:void(0)' p='" + (this.count + 1) + "' >&raquo;</a>
                }
            }
            this.outstr = this.outstr + lastP;
        }
    }
    if (this.totalpage > 1) {
        this.Prestr = "<a href='javascript:void(0)' p='" + parseInt(this.cpage - 1) + "'>&lt;&lt;</a>";
        this.startstr = "<a href='javascript:void(0)' p='" + 1 + "'>首页</a>";
        this.nextstr = "<a href='javascript:void(0)' p='" + parseInt(this.cpage + 1) + "'>&gt;&gt;</a>";
        this.endstr = "<a href='javascript:void(0)' p='" + this.totalpage + "'>尾页</a>";
        if (this.cpage != 1) {
            if (this.cpage >= this.totalpage) {
                document.getElementById(this.page_obj_id).innerHTML = "<div>" + this.Prestr + this.outstr + "<\/div>";// + this.startstr
            }
            else {
                document.getElementById(this.page_obj_id).innerHTML = "<div>" + this.Prestr + this.outstr + this.nextstr + "<\/div>";// + this.startstr + this.endstr
            }
        }
        else {
            document.getElementById(this.page_obj_id).innerHTML = "<div>" + this.outstr + this.nextstr + "<\/div>";// + this.endstr
        }
    }
    else {
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
    $(this.main).replaceWith('<img id="btnLoading" class="loadingStyle"  src="' + commonParams.jcDevPath + '/Modules' + commonParams.jcType + '/static/images/loading.gif" alt="">');
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
    that.html('<div name="contentLoadingDiv" style="margin-top:20px;margin-bottom:20px;text-align: center;"><img style="margin: 0 10px;" src="' + commonParams.jcDevPath + '/Modules' + commonParams.jcType + '/static/images/loading_2.gif" alt=""></div>');
}
//提交ajax是内容最后加载loading图片
function contentAppendLoading(that) {
    that.append('<div name="contentLoadingDiv" style="margin-top:20px;margin-bottom:20px;text-align: center;"><img style="margin: 0 10px;" src="' + commonParams.jcDevPath + '/Modules' + commonParams.jcType + '/static/images/loading_2.gif" alt=""></div>');
}
//移除内容中的loading图片
function removeContentLoading(that) {
    that.find("div[name='contentLoadingDiv']").remove();
}


//提交数据时特殊字符转换
function characterTransform(str) {
    str = str.replace(/\%/g, "%25");
    str = str.replace(/\+/g, "%2B");
    str = str.replace(/\&/g, "%26");
    return str;
}
//Html结构转字符串形式显示 支持<br>换行 
function ToHtmlString(htmlStr) {
    return toTXT(htmlStr);
}
//Html结构转字符串形式显示 
function toTXT(str) {
    if (str) {
        var RexStr = /\<|\>|\"|\'|\&|　| /g
        str = str.replace(RexStr,
        function (MatchStr) {
            switch (MatchStr) {
                case "<":
                    return "&lt;";
                    break;
                case ">":
                    return "&gt;";
                    break;
                case "\"":
                    return "&quot;";
                    break;
                case "'":
                    return "&apos;";
                    break;
                case "&":
                    return "&amp;";
                    break;
                case " ":
                    return " &nbsp;";
                    break;
                default:
                    return MatchStr;
                    break;
            }
        });
    }
    return str;
}
//Html结构转字符串形式显示 
function toHTML(str) {
    if (str) {
        var RexStr = /\&lt;|\&gt;|\&quot;|\&apos;|\&amp;|\&nbsp;| /g
        str = str.replace(RexStr,
        function (MatchStr) {
            switch (MatchStr) {
                case "&lt;":
                    return "<";
                    break;
                case "&gt;":
                    return ">";
                    break;
                case "&quot;":
                    return "\"";
                    break;
                case "&apos;":
                    return "'";
                    break;
                case "&amp;":
                    return "&";
                    break;
                case "&nbsp;":
                    return " ";
                    break;
                default:
                    return MatchStr;
                    break;
            }
        }
        )
    }
    return str;
}

// 时间戳转换日期   
function UnixToDate(unixTime, isFull, timeZone) {
    if (typeof (timeZone) == 'number') {
        unixTime = parseInt(unixTime) + parseInt(timeZone) * 60 * 60;
    }
    var time = new Date(unixTime * 1000);
    var ymdhis = "";
    ymdhis += time.getUTCFullYear() + "-";
    ymdhis += btok((time.getUTCMonth() + 1).toString(), 2, '0') + "-";
    ymdhis += btok(time.getUTCDate().toString(), 2, '0');
    if (isFull === true) {
        ymdhis += " " + btok((parseInt(time.getUTCHours() + 8)).toString(), 2, '0') + ":";
        ymdhis += btok(time.getUTCMinutes().toString(), 2, '0');
    }
    return ymdhis;
}
//填充文字： str 文本框对象, count 总字符长度 , charStr 填充字符
function btok(str, count, charStr) {
    var disstr = "";
    for (var i = 1; i <= (count - str.length) ; i++) {
        disstr += charStr;
    }
    str = disstr + str;
    return str;
}

//文件单位转换
function formatSize(size) {
    if (size) {
        rank = 0;
        rankchar = 'Bytes';
        while (size > 1024) {
            size = size / 1024;
            rank++;
        }
        size = Math.round(size * 100) / 100;
        if (rank == 1) {
            rankchar = "KB";
        }
        else if (rank == 2) {
            rankchar = "MB";
        }
        else if (rank == 3) {
            rankchar = "GB";
        }
        return size + " " + rankchar;
    }
    else {
        return "";
    }
}

//cookie操作
//写cookies 
function setCookie(name, value, expiredays) {
   // var time = seconds ? seconds : 60;
    var exp = new Date();
    exp.setTime(exp.getTime() + (expiredays * 24 * 3600 * 1000));
    document.cookie = "dodoJC_" + name + "=" + escape(value) + ";expires=" + exp.toGMTString() + ";path=/";
}
//读取cookies 
function getCookie(name) {
    name = "dodoJC_" + name;
    var arr, reg = new RegExp("(^| )" + name + "=([^;]*)(;|$)");
    if (arr = document.cookie.match(reg))
        return unescape(arr[2]);
    else
        return null;
}
//删除cookies 
function delCookie(name) {
    name = "dodoJC_" + name;
    var exp = new Date();
    exp.setTime(exp.getTime() - 1);
    var cval = getCookie(name);
    if (cval != null)
        document.cookie = name + "=" + cval + ";expires=" + exp.toGMTString();
}

//去除文件后缀名
function getFileName(fileName) {
    var fileArr = fileName.split(".");
    var fileArrIn = new Array();
    for (var i = 0; i < fileArr.length - 1; i++) {
        fileArrIn.push(fileArr[i]);
    }
    return fileArrIn.join(".");
}

jQuery.fn.extend({
    //动态加载更多
    loadMore: function (params) {
        var mian = this;
        var isloading = false;
        var pageNum = 1;
        var settings = {
            requestUrl: "",
            requestData: null,
            callback: null,
            pageCount: 6
        }
        $.extend(settings, params || {});
        $(mian).find("#loadContent").die().live("click", function () {
            if (!isloading) {
                $(mian).find("#loadContent").css("display", "none");
                $(mian).find("#loadingImg").css("display", "block");
                pageNum++;
                AjaxForJson(settings.requestUrl, settings.requestData + "&p=" + pageNum, function (dataObj) {
                    var count = settings.callback(dataObj);
                    $(mian).find("#loadingImg").css("display", "none");
                    if (count < settings.pageCount) {
                        $(mian).find("#loadContent").css("display", "none");
                    }
                    else {
                        $(mian).find("#loadContent").css("display", "block");
                    }
                });
            }
        });
    },
    //js跳转
    toLocation: function (paranms) {
        var settings = {
            requestUrl: "",
            requestData: null,
            callback: null
        }
        $.extend(settings, paranms || {});
        AjaxForJson(settings.requestUrl, settings.requestData, function (dataObj) {
            settings.callback(dataObj);
        });
    }
});

//点击登陆按钮弹出登陆框
$(document).ready(function () {
    $("a#user_login").unbind().bind("click", function () {
        loginDialog();
        return false;
    });


    //回到顶部、微信 
    $(window).scroll(function () {
        var h = $(window).scrollTop();
        if (h > 300) {
            $("#go_top").fadeIn();
             $("#go_top").css("display","block");
        }
        else {
            $("#go_top").fadeOut();
        }
    });

    $("#go_top").unbind().bind("click", function () {
        $(window).scrollTop(0);

    });

    //iphone打开DEMO
    $('a[name="open_iphone"]').die().live('click', function () {
        var url = $(this).attr('data-url');
     //   $(this).attr('class', 'm-banner-iphone-disable');
        window.open(url, "newwindow", "height=760, width=538,top=30%, left=45%, toolbar=no, menubar=no, scrollbars=no, resizable=no, location=no, status=no")
    });

    //判断浏览器
    var checkBrowser = function () {
        var Sys = {};
        var ua = navigator.userAgent.toLowerCase();
        var s;
        (s = ua.match(/rv:([\d.]+)\) like gecko/)) ? Sys.ie = s[1] :
        (s = ua.match(/msie ([\d.]+)/)) ? Sys.ie = s[1] :
        (s = ua.match(/firefox\/([\d.]+)/)) ? Sys.firefox = s[1] :
        (s = ua.match(/chrome\/([\d.]+)/)) ? Sys.chrome = s[1] :
        (s = ua.match(/opera.([\d.]+)/)) ? Sys.opera = s[1] :
        (s = ua.match(/version\/([\d.]+).*safari/)) ? Sys.safari = s[1] : 0;
        if (Sys.ie == '8.0' || Sys.ie == '7.0' || Sys.ie == '6.0') {
            $('div[name="browser_info"]').css('display', '');
        }
        //if (Sys.ie) alert('IE: ' + Sys.ie);
        //if (Sys.firefox) alert('Firefox: ' + Sys.firefox);
        //if (Sys.chrome) alert('Chrome: ' + Sys.chrome);
        //if (Sys.opera) alert('Opera: ' + Sys.opera);
        //if (Sys.safari) alert('Safari: ' + Sys.safari);
    }
    //delCookie('close_browser_info');
   if (getCookie('close_browser_info')==null) {
        checkBrowser();
    }
    //浏览器提示信息关闭
    $('.text_close').die().live('click', function () {
        $('div[name="browser_info"]').remove();
        setCookie('close_browser_info',false, 1);
        return false;
    });
    //浏览器不再提示信息关闭
    $('a[name="forever_close"]').die().live('click', function () {
        $('div[name="browser_info"]').remove();
        setCookie('close_browser_info', false, 30);
        return false;
    });
});
