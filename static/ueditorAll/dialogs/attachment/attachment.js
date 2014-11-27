//网盘参数
var DiskParams = {
    dir_id: 0,
    disk_id: null,
    file_ids: [],
    orderField: 'file_time',
    order: 'desc',//a 升序，b降序
    fileArray: [],
    currentPage: 1,
    totalCount: null
    // picArray:['JPEG', 'JPG', 'PNG', 'GIF', 'TIFF', 'BMP'],
    // openExt: ['3GPP','MPEG4', 'MOV', 'AVI', 'MPEGPS', 'WMV', 'FLV', 'OGG', 'DOC', 'DOCX', 'XLS', 'XLSX', 'PPT', 'PPTX', 'PDF', 'TIFF', 'SVG', 'EPS', 'PS', 'TTF', 'XPS']
};

Array.prototype.unique4 = function () {
    this.sort();
    var re = [this[0]];
    for (var i = 1; i < this.length; i++) {
        if (this[i] !== re[re.length - 1]) {
            re.push(this[i]);
        }
    }
    return re;
};
window.onload = function () {
    getDiskId();
    initPageEvent();
};
//获取网盘ID
function getDiskId() {
    function setDiskId(data) {
        DiskParams.disk_id = data.diskId;
        getFilesList();
    }
    AjaxForJson(editor.options.getDiskId, null, setDiskId, null);
}
//文件上传弹出框
var dialogUpload = function (html) {
    //var styledialog = new styleDialog
    styledialog.initDialogHTML({
        title: "上传文件",
        content: html,
        width: 470,
        confirm: {
            show: false,
            name: "确认"
        },
        cancel: {
            show: false,
            name: "取消"
        }
    });
    styledialog.initContent("上传文件", html);

    $("input[name='btnClose']").die().live("click", function () {
        styledialog.closeDialog();
    });
    return false;
}

var initPageEvent = function () {
    DiskParams.currentPage = 1;

    //点击文件夹名字,图标进入下一层
    $('.folderIconB[name="aFolderName"]').die().live("click", function () {
        clickFileName(this);
        return false;
    });
    $(".fileIconB[name='aFileName']").live("click", function () {
        if ($(this).parent().parent().attr("check") == "yes") {
            $(this).parent().parent().css({ "opacity": "", "border": "2px solid #f0f0f0" });
            $(this).parent().parent().attr("check", "no");
        }
        else {
            $(this).parent().parent().css({ "opacity": "0.5", "filter": "Alpha(opacity=70)", "border": "2px solid #cd8740" });
            $(this).parent().parent().attr("check", "yes");
        }
    });
    //点击导航栏文件夹名
    $('a[name="aDirName"]').die().live("click", function () {
        var file_id = $(this).attr('file_id');
        DiskParams.currentPage = 1;
        $("li[name='dirAdd']").attr('dir_id', file_id);
        DiskParams.dir_id = file_id;
        getFilesList();
        //initDiskList();
        return false;
    });
    //上传文件
    $("li[name='uploadFile']").die().live("click", function () {
        if (editor.options.forModule == "shequ" || editor.options.forModule == "class") {
            AjaxForJson(editor.options.fileUpload + "?isattach=1", "p=" + DiskParams.currentPage + "&disk_id=" + DiskParams.disk_id + "&dir_id=" + DiskParams.dir_id, dialogUpload, null);
        }
        else if (editor.options.forModule == "school") {
            var html = '<div class="PopupsType"><p class="desP">单文件最大限制100MB，支持多文件上传，您可以一次最多选择5个文件同时上传。</p><div id="videoUpload" class="panel" style="z-index:200"><div class="controller"><span id="startUpload" style="display: none; float: right; background-image: url(../../lang/zh-cn/images/upload.png)"></span><span id="spanButtonPlaceHolder"></span></div><div><div class="fieldset flash" id="fsUploadProgress"></div></div></div></div>';


            styledialog.initDialogHTML({
                title: "上传文件",
                content: html,
                width: 470,
                confirm: {
                    show: false,
                    name: "确认"
                },
                cancel: {
                    show: false,
                    name: "取消"
                }
            });

            styledialog.initContent("上传文件", html, function () {

                $.cachedScript('../../third-party/swfupload/swfupload.js').done(function () {
                    $.cachedScript('../../third-party/swfupload/../../third-party/swfupload/swfupload.queue.js').done(function () {

                        var swfupload,
                        filesList = [];
                        editor.setOpt({
                            fileFieldName: "Filedata"
                        });
                        var videoParams = {
                            totalCount: null,
                            timestamp: null,
                            diskId: null,
                            token: null,
                            sid: null,
                            fid: null
                        }

                        AjaxForJson(editor.options.getDiskId, null, function (data) {
                            if (data.diskId) {
                                videoParams.timestamp = data.timestamp;
                                videoParams.diskId = data.diskId;
                                videoParams.token = data.token;
                                videoParams.sid = data.sid;


                                var settings = {
                                    upload_url: editor.options.fileUploadDone + "dir_id=" + DiskParams.dir_id,           //附件上传服务器地址
                                    file_post_name: editor.options.fileFieldName,      //向后台提交的表单名
                                    flash_url: "../../third-party/swfupload/swfupload.swf",
                                    flash9_url: "../../third-party/swfupload/swfupload_fp9.swf",
                                    post_params: {
                                        "PHPSESSID": videoParams.sid,
                                        "timestamp": videoParams.timestamp,
                                        "diskId": videoParams.diskId,
                                        "token": videoParams.token
                                    }, //解决session丢失问题
                                    file_size_limit: "100 MB",                                 //文件大小限制，此处仅是前端flash选择时候的限制，具体还需要和后端结合判断
                                    file_types: "*.*",                                         //允许的扩展名，多个扩展名之间用分号隔开，支持*通配符
                                    file_types_description: "All Files",                      //扩展名描述
                                    //file_upload_limit: 5,                                   //单次可同时上传的文件数目
                                    file_queue_limit: 5,                                      //队列中可同时上传的文件数目
                                    custom_settings: {                                         //自定义设置，用户可在此向服务器传递自定义变量
                                        progressTarget: "fsUploadProgress",
                                        startUploadId: "startUpload",
                                        timestamp: videoParams.timestamp,
                                        diskId: videoParams.diskId,
                                        token: videoParams.token
                                    },
                                    debug: false,

                                    // 按钮设置
                                    button_image_url: "../../themes/default/images/filescan.png",
                                    button_width: "100",
                                    button_height: "25",
                                    button_placeholder_id: "spanButtonPlaceHolder",
                                    button_text: '<span class="theFont">' + lang.browseFiles + '</span>',
                                    button_text_style: ".theFont { font-size:14px;color:#ffffff}",
                                    button_text_left_padding: 10,
                                    button_text_top_padding: 4,

                                    // 所有回调函数 in handlers.js
                                    swfupload_preload_handler: preLoad,
                                    swfupload_load_failed_handler: loadFailed,
                                    file_queued_handler: fileQueued,
                                    file_queue_error_handler: fileQueueError,
                                    //选择文件完成回调
                                    file_dialog_complete_handler: function (numFilesSelected, numFilesQueued) {
                                        var me = this;        //此处的this是swfupload对象
                                        if (numFilesQueued > 0) {
                                            dialog.buttons[0].setDisabled(true);
                                            var start = $G(this.customSettings.startUploadId);
                                            start.style.display = "";
                                            start.onclick = function () {
                                                me.startUpload();
                                                start.style.display = "none";
                                            }
                                        }
                                    },
                                    upload_start_handler: uploadStart,
                                    upload_progress_handler: uploadProgress,
                                    upload_error_handler: uploadError,
                                    upload_success_handler: function (file, serverData) {
                                        //videoParams.fid = serverData;
                                        var progress = new FileProgress(file, this.customSettings.progressTarget);
                                        progress.setComplete();
                                        progress.setStatus("<span style='color: #0b0;font-weight: bold'>" + lang.uploadSuccess + "</span>");
                                        //filesList.push(editor.options.viewVideo + "/disk_id/" + videoParams.diskId + "/fid/" + serverData);

                                        if (serverData != "") {
                                            DiskParams.fileArray.push(serverData);
                                            DiskParams.fileArray.unique4();

                                        }

                                        //if (serverData.) 

                                        progress.toggleCancel(true, this, lang.delSuccessFile);
                                    },
                                    //上传完成回调
                                    upload_complete_handler: uploadComplete,
                                    //队列完成回调
                                    queue_complete_handler: function (numFilesUploaded) {

                                        //异步刷新父页面列表
                                        var requestUrl = editor.options.getFilesCount;
                                        var requestData = "disk_id=" + DiskParams.disk_id + "&dir_id=" + DiskParams.dir_id;
                                        function setPageCount(obj) {
                                            DiskParams.totalCount = obj;
                                            var requestUrl = editor.options.getDiskList;
                                            var requestData = "disk_id=" + DiskParams.disk_id + "&dir_id=" + DiskParams.dir_id + "&style=" + $("div[name='viewType']").attr('list');
                                            // function setPageList(obj) {
                                            function responseEvent(obj) {
                                                $("span[name='spanDirNav']").html(obj.dirNav);
                                                var objR = arrayClass(objToArr(obj.files), DiskParams.orderField, DiskParams.order);
                                                var listHtml = '';

                                                for (var i = 0; i < objR.length; i++) {
                                                    if (objR[i].is_dir == true) {
                                                        listHtml += '<ul name="ulFile" style="border:2px solid #f0f0f0;" class="pic_cell" file_id="' + objR[i].file_id + '" dir_id=' + objR[i].dir_id + ' is_dir="' + objR[i].is_dir + '"><li class="fileName"><span class="folderIconB" name="aFolderName"><em>&nbsp;</em></span><input name="modifyFileName" style="display:none;"  type="text" class="editTitle" value="" /><span name="extName"></span><a href="javascript:;" title="' + objR[i].file_name + '" name="aFolderName"><span name="dirName">' + objR[i].file_name + '</span><span name="fileCount">(' + objR[i].c + ')</span></a></li><li class="fileSize grayTxt1">' + UnixToDate(objR[i].file_time, true) + '</li></ul>';
                                                    }
                                                    else {
                                                        listHtml += '<ul name="ulFile" style="border:2px solid #f0f0f0;" class="pic_cell" file_id="' + objR[i].file_id + '" dir_id=' + objR[i].dir_id + ' is_dir="' + objR[i].is_dir + '"><li class="fileName"><span class="fileIconB" name="aFileName"><em>' + objR[i].file_ext + '</em>';
                                                        if (objR[i].is_share > 0) {
                                                            listHtml += '<span class="partakeHand"></span>';
                                                        }
                                                        listHtml += '</span><input name="modifyFileName" style="display:none;" type="text" class="editTitle" value="" /><span name="extName"></span>';
                                                        listHtml += '<a href="/disk/down/' + objR[i].file_id + '" title="' + objR[i].file_name + '">';
                                                        listHtml += '<span name="dirName">' + objR[i].file_name + '</span></a></li><li class="fileSize grayTxt1">' + objR[i].file_size + '</li></ul>';
                                                    }
                                                }
                                                $("div[name='divList']").css('display', 'none');
                                                $("div[name='divList']").html('');
                                                $("div[name='divPic']").html(listHtml);
                                                $("div[name='divPic']").css('display', 'block');

                                                $('.fileName').css('cursor', 'pointer');
                                                if (DiskParams.totalCount <= 15) {
                                                    $('#commentPageNum').css('display', 'none');
                                                } else {
                                                    $('#commentPageNum').css('display', 'block');
                                                }


                                                for (var i = 0; i < DiskParams.fileArray.length; i++) {
                                                    $(".SQdisk_picCon").find("ul[file_id='" + DiskParams.fileArray[i] + "']").css({ "opacity": "0.5", "filter": "Alpha(opacity=70)", "border": "2px solid #cd8740" });
                                                    $(".SQdisk_picCon").find("ul[file_id='" + DiskParams.fileArray[i] + "']").attr("check", "yes");
                                                }

                                                DiskParams.fileArray = [];
                                            }
                                            var requestMenberpage = new jsPage(DiskParams.totalCount, "commentPageNum", "15", requestUrl, requestData, responseEvent, DiskParams.currentPage);
                                            pageMethod.call(requestMenberpage);
                                        }
                                        AjaxForJson(requestUrl, requestData, setPageCount, null);

                                        dialog.buttons[0].setDisabled(false);
                                    }
                                };
                                swfupload = new SWFUpload(settings);
                            }
                            else {
                                $("#promptMsg").html("您必须先开通网盘才能上传音频！网盘开通地址：<a id='diskUrl' target='_blank' href='javascript:;'>" + editor.options.diskPath + "</a>");
                                $("#promptMsg").css("color", "red");
                                $("#diskUrl").bind("click", function () {
                                    window.parent.location.href = editor.options.diskPath;
                                    return false;
                                });
                            }

                        }, null);

                    });
                });

            });
        }
        
        return false;
    });
}

//点击文件名事件处理
var clickFileName = function (t) {
    var is_dir = $(t).parent().parent().attr('is_dir');
    var file_id = $(t).parent().parent().attr('file_id');
    if (is_dir == true) {
        $("li[name='dirAdd']").attr('dir_id', file_id);
        DiskParams.dir_id = file_id;
        getFilesList();
    }
}

//获取网盘总数，并初始化网盘列表
function getFilesList() {
    var requestUrl = editor.options.getFilesCount;
    var requestData = "disk_id=" + DiskParams.disk_id + "&dir_id=" + DiskParams.dir_id;
    function setPageCount(obj) {
        DiskParams.totalCount = obj;
        initDiskList();
    }
    AjaxForJson(requestUrl, requestData, setPageCount, null);
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
function btok(str,count,charStr)
{
    var disstr = "";
    for(var i=1;i<=(count-str.length);i++)
    {
        disstr += charStr;
    }
    str = disstr+str;
    return str;
}
//初始化网盘列表
function initDiskList() {
    var requestUrl = editor.options.getDiskList;
    var requestData = "disk_id=" + DiskParams.disk_id + "&dir_id=" + DiskParams.dir_id + "&style=picList"; //+ $("div[name='viewType']").attr('list');
    // function setPageList(obj) {
    function responseEvent(obj) {
        $("span[name='spanDirNav']").html(obj.dirNav);
        var objR = arrayClass(objToArr(obj.files), DiskParams.orderField, DiskParams.order);
        var listHtml = '';

        for (var i = 0; i < objR.length; i++) {
            if (objR[i].is_dir == true) {
                listHtml += '<ul name="ulFile" style="border:2px solid #f0f0f0;" class="pic_cell" file_id="' + objR[i].file_id + '" dir_id=' + objR[i].dir_id + ' is_dir="' + objR[i].is_dir + '" is_share="' + objR[i].is_share + '"><li class="fileName"><span class="folderIconB" name="aFolderName"><em>&nbsp;</em></span><input name="modifyFileName" style="display:none;"  type="text" class="editTitle" value="" /><span name="extName"></span><a href="javascript:;" title="' + objR[i].file_name + '" name="aFolderName"><span name="dirName">' + objR[i].file_name + '</span><span name="fileCount">(' + objR[i].c + ')</span></a></li><li class="fileSize grayTxt1">' + UnixToDate(objR[i].file_time, true) + '</li></ul>';
            }
            else {
                listHtml += '<ul name="ulFile" style="border:2px solid #f0f0f0;" class="pic_cell" file_id="' + objR[i].file_id + '" dir_id=' + objR[i].dir_id + ' is_dir="' + objR[i].is_dir + '" is_share="' + objR[i].is_share + '"><li class="fileName"><span class="fileIconB" name="aFileName"><em>' + objR[i].file_ext + '</em>';
                if (objR[i].is_share > 0) {
                    listHtml += '<span class="partakeHand"></span>';
                }
                listHtml += '</span><input name="modifyFileName" style="display:none;" type="text" class="editTitle" value="" /><span name="extName"></span>';
                listHtml += '<a href="/disk/down/' + objR[i].file_id + '" title="' + objR[i].file_name + '">';
                listHtml += '<span name="dirName">' + objR[i].file_name + '</span></a></li><li class="fileSize grayTxt1">' + objR[i].file_size + '</li></ul>';
            }
        }
        $("div[name='divList']").css('display', 'none');
        $("div[name='divList']").html('');
        $("div[name='divPic']").html(listHtml);
        $("div[name='divPic']").css('display', 'block');

        $('.fileName').css('cursor', 'pointer');
        if (DiskParams.totalCount <= 15) {
            $('#commentPageNum').css('display', 'none');
        } else {
            $('#commentPageNum').css('display', 'block');
        }
    }
    var requestMenberpage = new jsPage(DiskParams.totalCount, "commentPageNum", "15", requestUrl, requestData, responseEvent, DiskParams.currentPage);
    pageMethod.call(requestMenberpage);
};

//将obj对象转为Array
function objToArr(obj) {
    var size = obj.length;
    var keys = [];
    for (var i = 0; i < size; i++) {
        keys[i] = obj[i];
    }
    return keys;
}
function arrayClass(array, key, order) {
    var dirArray = [];
    var fileArray = [];
    for (var k = 0; k < array.length; k++) {
        array[k].is_dir ? dirArray.push(array[k]) : fileArray.push(array[k]);
    }
    dirArray = sortByKey(dirArray, key, order);
    fileArray = sortByKey(fileArray, key, order);
    return dirArray.concat(fileArray);
}
//将array按照某一字段排序
function sortByKey(array, key, order) {
    var data = array.sort(function (a, b) {
        if (key == 'file_ori_size') {
            var x = parseInt(a[key]);
            var y = parseInt(b[key]);
            return ((x < y) ? -1 : ((x > y) ? 1 : 0));
        } else {
            var x = a[key];
            var y = b[key];
            return ((x < y) ? -1 : ((x > y) ? 1 : 0));
        }
    });
    if (order == 'desc') {
        return data.reverse();
    }
    else {
        return data;
    }
}