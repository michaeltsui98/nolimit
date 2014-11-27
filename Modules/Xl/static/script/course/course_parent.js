var courSetParms = {
	raiseCurPege : 1,
	evaluatePage : 1,
	questionPage : 1,
}

jQuery(function($) {
	if ($.browser.msie) {
		$('input:checkbox').click(function() {
			this.blur();
			this.focus();
		});
	};
	//动态加载js、css
	loadjscssfile(commonParams.jcDevPath + "/static/ueditorAll/themes/default/css/ueditor.css", "css");
	loadjscssfile(commonParams.jcDevPath + "/static/ueditorAll/editor_config.js", "js");
	loadjscssfile(commonParams.jcDevPath + "/static/ueditorAll/editor_all.js", "js");
	var course_id = $("div#course_wrap").data("course");

	//课程评价
	var getRaiseList = function() {
		AjaxForJson(commonParams.jcDevPath + commonParams.jcType + "/parent/courseAppraiseList", "course_id=" + course_id + "&p=" + courSetParms.raiseCurPege, function(obj) {
			if (obj.data.data.length == 0) {
				return;
			}
			var listHtml = '';
			var info = obj.data.data;
			for (i in info) {
				listHtml += '<li><img src="' + info[i].appraise_user_info.user_avatar_16 + '" alt="' + info[i].appraise_user_info.user_realname + '" width="16px"><span><strong class="f-mr10 f-ml5">' + info[i].appraise_score + '分</strong>评语：' + info[i].appraise_remark + '</span></li>'
			}
			$('[name="raiseLoadMore"]').remove();
			$('ul#appraiseList').append(listHtml);
			if (obj.data.count >= obj.data.limit) {
				var loadMoreHtml = '<div class="courseLoadMore"  name="raiseLoadMore"><a href="javascript:;">加载更多</a></div>';
				$('ul#appraiseList').append(loadMoreHtml);
			}
		});
	}
	$('[name="raiseLoadMore"]').die().live("click", function() {
		courSetParms.raiseCurPege += 1;
		getRaiseList();
		return false;
	});
	getRaiseList();

	//随堂测试列表
	var getEvaluateList = function() {
		var student_num = $("div#evaluationList").attr("student_num");
		AjaxForJson(commonParams.jcDevPath + commonParams.jcType + "/parent/courseEvaluateList", "course_id=" + course_id + "&p=" + courSetParms.evaluatePage, function(obj) {
			if (obj.data.data.length == 0) {
				removeContentLoading($('[name="evaluateLoadMore"]'));
				return;
			}
			var listHtml = '';
			var info = obj.data.data;
			for (i in info) {
				var has_join = info[i].evaluate_status ? '已参加' : '未参加';
				listHtml += '<ul class="item f-fs14 clearfix"><li class="name width330 f-fl"><a href="" title=""  class="f-fs16" target="_blank">' + info[i].evaluate_title + '</a></li><li class="status f-fl s-fcGBlue">' + has_join + '</li><li class="participantStats f-fr s-fcLGray">参与人数（<span>' + info[i].evaluate_count + '</span>/<span>' + student_num + '</span>）</li></ul>'
			}
			$('[name="evaluateLoadMore"]').remove();
			$('#evaluationList').append(listHtml);
			if (obj.data.count >= obj.data.limit) {
				var loadMoreHtml = '<div class="courseLoadMore"  name="evaluateLoadMore"><a href="javascript:;">加载更多</a></div>';
				$('#evaluationList').append(loadMoreHtml);
			}
		});
	}
	$('[name="evaluateLoadMore"]').die().live("click", function() {
		courSetParms.evaluatePage += 1;
		getEvaluateList();
		return false;
	});
	getEvaluateList();

	//课程问答列表
	var getQuestionList = function() {
		AjaxForJson(commonParams.jcDevPath + commonParams.jcType + "/parent/courseQuestionList", "course_id=" + course_id + "&p=" + courSetParms.questionPage, function(obj) {
			if (obj.data.data.length == 0) {
				removeContentLoading($('[name="qusetionLoadMore"]'));
				$("span#questionCount").html(0);
				return;
			}
			var listHtml = '';
			var info = obj.data.data;
			for (i in info) {
				listHtml += '<ul class="item clearfix"><li class="name f-fl"><a href="" title="" target="_blank">' + info[i].FAQ_title + '</a></li><li class="date f-fr">' + info[i].FAQ_time + '</li></ul>'
			}
			$("span#questionCount").html(obj.data.total);
			removeContentLoading($('[name="qusetionLoadMore"]'));
			$('div#questionList').append(listHtml);
			if (obj.data.count >= obj.data.limit) {
				var loadMoreHtml = '<div class="courseLoadMore"  name="qusetionLoadMore"><a href="javascript:;">加载更多</a></div>';
				$('div#questionList').append(loadMoreHtml);
			}
		});
	}
	$('[name="qusetionLoadMore"]').die().live("click", function() {
		courSetParms.questionPage += 1;
		getQuestionList();
		return false;
	});
	getQuestionList();

	//印象表扬与批评的切换
	$('div#courseImpress .tab5Menu').tab2Menu(function() {
		var tabName = $('div#courseImpress .tab5Menu').find('li.tabMenuCurrent').find("a").attr("name");
		switch (tabName) {
			case 'praise':
				$("div#impression_praise").css("display", "block");
				$("div#impression_criticism").css("display", "none");
				break;
			case 'criticism':
				$("div#impression_praise").css("display", "none");
				$("div#impression_criticism").css("display", "block");
				break;
			default:
				$("div#impression_praise").css("display", "block");
				$("div#impression_criticism").css("display", "none");
		}
	});

	//保存课程评价数据
	$("a.appraisePost").die().live("click", function() {
		var form = $("form#appraiseForm");
		var course_id = form.find("input[name='course_id']").val();
		var score = form.find("input[name='score']:checked").val();
		var remark = form.find("input[name='remark']").val();
		function appraiseInit(obj) {

		}

		AjaxForJson(commonParams.jcDevPath + commonParams.jcType + "/parent/courseAddAppraise", "course_id=" + course_id + "&score=" + score + "&remark=" + remark, appraiseInit, null);

	});

	//提交问答
	$("a.questionPost").die().live("click", function() {
		var form = $("form#questionForm");
		var course_id = form.find("input[name='course_id']").val();
		var question = form.find("textarea[name='question']").val();
		function questionInit(obj) {

		}

		AjaxForJson(commonParams.jcDevPath + commonParams.jcType + "/parent/courseAddQuestionForCourse", "course_id=" + course_id + "&title=" + question, questionInit, null);
	});

	//提问
	$("a.addQuestion").die().live("click", function() {
		var course_id = $(this).data("course");
		var dialogHtml = ' <form id="questionForm"><textarea name="question" cols=""  style="width:700px;height:300px" rows="" ></textarea>';
		function editEvent() {
			$("div#PopupsFunc input[name='confirm']").die().live("click", function() {
				var content = $("form#questionForm").find("textarea[name='question']").val();
				function postEvent(obj) {
					if ('success' == obj.type) {
						$("div#PopupsFunc input[name='cancel']").trigger("click");
					}
				}

				AjaxForJson(commonParams.jcDevPath + commonParams.jcType + "/Course/addQuestionForCourse", "content=" + content + "&course_id=" + course_id, postEvent, null);
			});
		}


		styledialog.initDialogHTML({
			title : "编辑课程记录",
			content : dialogHtml,
			width : 738,
			confirm : {
				show : true,
				name : "提交"
			},
			cancel : {
				show : true,
				name : "取消"
			}
		});
		styledialog.initContent("编辑课程记录", dialogHtml, editEvent);
	});
	//如果多于5个就隐藏 5个以内就不显示展开
	var courseLength = $('.ct .courseItem ').length;
	if (courseLength > 5) {
		for (var i = 5; i < courseLength; i++) {
			$($('.ct .courseItem ')[i]).css('display', 'none');
		}
	} else {
		$('.courseLoadMore').css('display', 'none');
	}
	$('a[name="foldAndUnfold"]').die().live("click", function() {
		if ($(this).html() == '展开') {
			for (var i = 5; i < courseLength; i++) {
				$($('.ct .courseItem ')[i]).css('display', '');
				$(this).html('收起');
			}
		} else {
			for (var i = 5; i < courseLength; i++) {
				$($('.ct .courseItem ')[i]).css('display', 'none');
				$(this).html('展开');
			}
		}
	});

});
