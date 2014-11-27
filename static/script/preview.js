//初始化播放器
jQuery.fn.extend({
    fileView: function (containerId, videoStar) {
        var mian = this;
        if (perview_type == "isTxt") {
            $('#' + containerId).FlexPaperViewer({
                config: {
                    jsDirectory: commonParams.jcDevPath + '/static/flexpaper',
                    cssDirectory: commonParams.jcDevPath + '/static/flexpaper',
                    SWFFile: commonParams.jcStsticPath + '/wenku/' + swf_key,
                    //SWFFile: "http://dev-jc.dodoedu.com/static/flexpaper/test.swf",

                    Scale: 1,
                    ZoomTransition: 'easeOut',
                    ZoomTime: 0.5,
                    ZoomInterval: 0.2,
                    FitPageOnLoad: true,
                    FitWidthOnLoad: false,
                    FullScreenAsMaxWindow: false,
                    ProgressiveLoading: true,
                    MinZoomSize: 0.2,
                    MaxZoomSize: 5,
                    SearchMatchAll: false,
                    InitViewMode: 'Portrait',
                    RenderingOrder: 'flash,html',
                    //StartAtPage: mark_page,

                    FitWidthOnLoad: true,
                    ProgressiveLoading: true,
                    ViewModeToolsVisible: true,
                    ZoomToolsVisible: true,
                    NavToolsVisible: true,
                    CursorToolsVisible: true,
                    SearchToolsVisible: true,
                    WMode: 'transparent',
                    localeChain: 'zh_CN'
                }
            });
            $.ajaxJSONP(commonParams.wenkuPath + "/info/views/id/" + id, null, function () { });
        }
        else if (perview_type == "isPpt") {
            var objHeight = document.getElementById(containerId).offsetHeight;
            var objWidth = document.getElementById(containerId).offsetWidth;
            if (document.getElementById(containerId).offsetWidth / document.getElementById(containerId).offsetHeight > 4 / 3) {
                objWidth = document.getElementById(containerId).offsetHeight * 4 / 3;
            }
            else {
                objHeight = document.getElementById(containerId).offsetWidth * 3 / 4;
            }
            new syPPT({
                width: objWidth,
                height: objHeight,
                swf_id: containerId,//上传flash元素id
                file_url: commonParams.jcStsticPath + '/wenku/' + swf_key,
                botton_hidden: 0,
                skin: commonParams.jcStaticPath + '/static/fpaper/skin/comp'
            }, objWidth, objHeight).show();

            $.ajaxJSONP(commonParams.wenkuPath + "/info/views/id/" + id, null, function () {
                $('#' + containerId).css("border", "1px solid #96b9cf");
            });
        }
        else if (perview_type == "isVideo") {
            var ifStar = videoStar ? "1" : "0";
            var studyTime = 0;
            if ($("div[name='data']").length > 0) {
                studyTime = parseInt($("div[name='data']").attr("study_record_time"));
            }
            //var logoImg = subject.toLowerCase() == "xl" ? commonParams.jcDevPath + "/static/ckplayer/xlLogo.png" : commonParams.jcDevPath + "/static/ckplayer/smLogo.png";
            var flashvars = {
                f: commonParams.jcStsticPath + '/resource/' + file_key,
                a: '',//调用时的参数，只有当s>0的时候有效
                s: '0',//调用方式，0=普通方法（f=视频地址），1=网址形式,2=xml形式，3=swf形式(s>0时f=网址，配合a来完成对地址的组装)
                p: ifStar,//视频默认0是暂停，1是播放
                i: commonParams.jcStaticPath + '/Modules' + commonParams.jcType + '/static/images/videoLogo.jpg',
                h: '4',//播放http视频流时采用何种拖动方法，=0不使用任意拖动，=1是使用按关键帧，=2是按时间点，=3是自动判断按什么(如果视频格式是.mp4就按关键帧，.flv就按关键时间)，=4也是自动判断(只要包含字符mp4就按mp4来，只要包含字符flv就按flv来)
                e: '0',//视频结束后的动作，0是调用js函数，1是循环播放，2是暂停播放并且不调用广告，3是调用视频推荐列表的插件，4是清除视频流并调用js功能和1差不多，5是暂停播放并且调用暂停广告
                v: 50,
                g: studyTime,//视频直接g秒开始播放
                h: 0,
                c: 0
                //my_title: $("dl.learnDes dt strong").html(),//视频标题
                //my_summary: $("#desc_all").text(),//视频介绍，请保持在一行文字，不要换行
                //my_url: encodeURIComponent(window.location.href)
            };
            var params = { bgcolor: '#FFF', allowFullScreen: true, allowScriptAccess: 'always', wmode: "transparent" };//这里定义播放器的其它参数如背景色（跟flashvars中的b不同），是否支持全屏，是否支持交互
            var attributes = { id: 'ckplayer_a1', name: 'ckplayer_a1', menu: 'false' };
            //下面一行是调用播放器了，括号里的参数含义：（播放器文件，要显示在的div容器，宽，高，需要flash的版本，当用户没有该版本的提示，加载初始化参数，加载设置参数如背景，加载attributes参数，主要用来设置播放器的id）
            var objHeight = document.getElementById(containerId).offsetHeight;
            var objWidth = document.getElementById(containerId).offsetWidth;
            if (document.getElementById(containerId).offsetWidth / document.getElementById(containerId).offsetHeight > 16 / 9) {
                objWidth = document.getElementById(containerId).offsetHeight * 16 / 9;
            }
            else {
                objHeight = document.getElementById(containerId).offsetWidth * 9 / 16;
            }

            if ($("#documentDiv").length > 0) {
                $("#documentDiv").css("top", ($("#documentDiv").parent().height() - objHeight) / 2 + "px");
            }

            swfobject.embedSWF(commonParams.jcStaticPath + '/static/ckplayer/ckplayer.swf', containerId, objWidth, objHeight, '10.0.0', commonParams.jcStaticPath + '/static/ckplayer/expressInstall.swf', flashvars, params, attributes);

        }
    }
});

////获取播放器播放状态
//function ckplayer_status(str) {

//    if (str == "102") {
//        //alert("ok");
//    }
//    else if (str == "nowtime:0") {
//        var a = swfobject.getObjectById('ckplayer_a1').ckplayer_getstatus();
//        if (a.play) {
//            $.ajaxJSONP(commonParams.wenkuPath + "/info/views/id/" + id, null, function () { });
//        }
//    }

//}
////播放器播放结束事件
//function playerstop() {
//    //只有当调用视频播放器时设置e=0或4时会有效果
//    //alert('播放完成');
//}