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
	var class_id = $("div#course_wrap").data("class");

	//课程评价
	var getRaiseList = function() {
		contentLoading($('[name="raiseLoadMore"]'));
		AjaxForJson(commonParams.jcDevPath + commonParams.jcType + "/course/appraiseList", "course_id=" + course_id + "&p=" + courSetParms.raiseCurPege, function(obj) {
			if (obj.data.data.length == 0) {
				removeContentLoading($('[name="raiseLoadMore"]'));
				return;
			}
			var listHtml = '';
			var info = obj.data.data;
			for (i in info) {
				listHtml += '<div class="classItem clearfix"><div class="logo f-fl"><a href="' + commonParams + '/' + info[i].appraise_user_info.user_id + '/Space/index" title="' + info[i].appraise_user_info.user_realname + '" target="_blank"><img src="' + info[i].appraise_user_info.user_avatar_40 + '" width="40" alt=""></a></div>';
				listHtml += '<dl class="info f-fr"><dt class="className"><a href="javascript:;" >评价 ' + info[i].appraise_score + '</a></dt><dd class="des"><a href="javascript:;"  class="a-Grayl">' + info[i].appraise_remark + '</a></dd></dl></div>';
			}
			removeContentLoading($('[name="raiseLoadMore"]'));
			$('div#appraiseList').append(listHtml);
			if (obj.data.count >= obj.data.limit) {
				var loadMoreHtml = '<div class="courseLoadMore"  name="raiseLoadMore"><a href="javascript:;">加载更多</a></div>';
				$('div#appraiseList').append(loadMoreHtml);
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
		contentLoading($('[name="evaluateLoadMore"]'));
		var student_num = $("div#evaluationList").attr("student_num");
		AjaxForJson(commonParams.jcDevPath + commonParams.jcType + "/course/evaluateList", "course_id=" + course_id + "&p=" + courSetParms.evaluatePage, function(obj) {
			if (obj.data.data.length == 0) {
				$('#evaluationList').append('课程尚未添加测评内容');
				removeContentLoading($('[name="evaluateLoadMore"]'));
				return;
			}
			var listHtml = '';
			var info = obj.data.data;
			for (i in info) {
				var has_join = info[i].evaluate_status ? '已参加' : '未参加';
				var target_param = 'id="' + info[i].id + '"  types="2"  course_id="' + course_id + '" url="' + commonParams.jcType + '/Evaluate/doEvaluate" evaluate_id="' + info[i].evaluate_id + '" evaluate_classify="2" name="do_evaluate"';
				listHtml += '<ul class="item f-fs14 clearfix"><li class="name width330 f-fl" ' + target_param + '><a href="javascript:;" class="f-fs16" >' + info[i].evaluate_title + '</a></li><li class="status f-fl s-fcGBlue">' + has_join + '</li><li class="participantStats f-fr s-fcLGray">参与人数（<span>' + info[i].evaluate_count + '</span>/<span>' + student_num + '</span>）</li></ul>'
			}
			removeContentLoading($('[name="evaluateLoadMore"]'));
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
		contentLoading($('[name="qusetionLoadMore"]'));
		AjaxForJson(commonParams.jcDevPath + commonParams.jcType + "/course/questionList", "course_id=" + course_id + "&p=" + courSetParms.questionPage, function(obj) {
			if (obj.data.data.length == 0) {
				$('#questionList').append('课程尚未添加问答');
				removeContentLoading($('[name="qusetionLoadMore"]'));
				$("span#questionCount").html(0);
				return;
			}
			var listHtml = '';
			var info = obj.data.data;
			for (i in info) {
				listHtml += '<ul class="item clearfix"><li class="name f-fl"><a href="' + commonParams.sqDevPath + '/Question/view/' + info[i].FAQ_id + '"  target="_blank">' + info[i].FAQ_title + '</a></li><li class="date f-fr">' + info[i].FAQ_time + '</li></ul>'
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

	//编辑课程记录
	$("a.addCourseRecord").die().live("click", function() {
		var recordBox = $("div#courseRecord");
		var dialogHtml = '<div style="height:372px/*打开编辑器上面的向下按钮的时候有问题*/;width:700px;margin:0 auto 10px auto"><script type="text/plain" id="editor" style="width:700px;margin: 0 auto;"></script></div>'
		function editEvent() {
			var editor = new UE.ui.Editor({
				maximumWords : 10000,
				imageUrl : commonParams.sqDevPath + "/class/saveAttachment?classId=" + class_id,
				forModule : "class",
				compressSide : 1,
				maxImageSideLength : 550,
			});
			editor.render('editor');
			editor.ready(function() {
				editor.setContent(recordBox.html());
			});
			$("div#PopupsFunc input[name='confirm']").die().live("click", function() {
				function postEvent(obj) {
					if ('success' == obj.type) {
						recordBox.html(editor.getContent());
						styledialog.closeDialog();
					}
				}

				AjaxForJson(commonParams.jcDevPath + commonParams.jcType + "/Course/recordUpdate", "content=" + characterTransform(editor.getContent()) + "&course_id=" + course_id, postEvent, null);
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
	
	//编辑备课夹
	$("a.addLessonFolder").unbind().bind("click", function() {
		var dialogHTML = '<div id="loadingDiv" class="loadingStyle1" style="width:600px;height:300px;"></div>';
		styledialog.initDialogHTML({
			title : "设置备课夹",
			content : dialogHTML,
			//width : 738,
			confirm : {
				show : true,
				name : "提交"
			},
			cancel : {
				show : true,
				name : "取消"
			}
		});
		styledialog.initContent("设置备课夹", dialogHTML, setEvent);
		function setEvent() {
			AjaxForJson(commonParams.jcDevPath + commonParams.jcType + "/Course/lessonFolderSetting", "type=lessonFolder&course_id=" + course_id, function(obj) {
				$("#loadingDiv").replaceWith(obj);
				getLesList();
			});
			$("#PopupsFunc").find("input[name='confirm']").unbind().bind("click", function() {
				var btnloading = new btnLoading(this);
				btnloading.toLoading();
				var folder_id = $("table#lesson_folder").find("tbody").find("input[type='radio']:checked").attr("folder_id");
				//提交按钮变loading图片
				AjaxForJson(commonParams.jcDevPath + commonParams.jcType + "/course/saveLessonFolder", "course_id=" + course_id + "&folder_id=" + folder_id, function(obj) {
					window.location.reload();
				});
			});
			//选择版本后的方法
			$("select#edition").die().live("change", function() {
				getLesList();
			});
			function getLesList() {
				var grade = $("table#lesson_folder").attr("grade");
				var stage = $("table#lesson_folder").attr("stage");
				var requestData = "edition=" + $("select#edition").val() + "&grade=" + grade + "&stage=" + stage;
				contentLoading($("table#lesson_folder").find("tbody"));
				AjaxForJson(commonParams.jcDevPath + commonParams.jcType + "/course/lessonFolderList", requestData, function(dataObj) {
					if (dataObj.type == "success") {
						$("#pageNum").addClass("page");
						var requestMenberpage = new jsPage(dataObj.data.total, "pageNum", "5", commonParams.jcDevPath + commonParams.jcType + "/course/lessonFolderList", requestData, function(obj) {
							var lesListHTML = '';
							for (var i = 0; i < obj.data.rows.length; i++) {
								lesListHTML += '<tr class=""><td><input type="radio" folder_id="' + obj.data.rows[i].id + '"></td><td>' + obj.data.rows[i].title + '</td><td>' + obj.data.rows[i].node_title + '</td><tr>';
							}
							$("table#lesson_folder").find("tbody").html(lesListHTML);

							$("table#lesson_folder").find("tbody").find("input[type='radio']").unbind().bind("click", function() {
								$("table#lesson_folder").find("tbody").find("input[type='radio']").attr("checked", false);
								$(this).attr("checked", "checked");
							});

						});
						pageMethod.call(requestMenberpage);
					}
				});
			}

		}

	});
    //课程印象列表
	var getImpressList = function () {
	    contentLoading($('[name="qusetionLoadMore"]'));
	    AjaxForJson(commonParams.jcDevPath + commonParams.jcType + "/course/impressionList", "course_id=" + course_id, function (obj) {
	        if (obj.data.data.length == 0) {
	            $('#courseImpression').append('课程尚未添加印象');
	            return;
	        }
	        var listHtml = '<ul class="tab5Menu clearfix m-snavCI f-mb10 f-mt10"><li class="menuCell tabMenuCurrent f-fl"><a href="javascript:;" name="praise" class="tabLink">表扬</a></li><li class="menuCell f-fl"><a href="javascript:;" name="criticism" class="tabLink">批评</a></li></ul>';
	        var praiseInfo = obj.data.data.by;
	        if (praiseInfo!=null) {
	        listHtml += '<div id="impression_praise" style="margin:10px auto"><ul class="awardItem f-mb10">';
	            for (i in praiseInfo) { 
	                listHtml += '<li class="clearfix"><div class="f-fl">';
	                listHtml += '<img src="' + praiseInfo[i].icon.img + '">' + praiseInfo[i].icon.title + '</div><div class="f-fr">';
	                var userInfo = praiseInfo[i].rows;
	                for (j in userInfo) {
	                    listHtml += '<a target="_blank" href="' + commonParams.sqDevPath + '/' + userInfo[j].user_id + '/Space/index">' + userInfo[j].user_realname + '</a>'
	                }
	                listHtml += '</div></li>';
	            }
	            listHtml += '</ul></div>';
	        } else {
	            listHtml += '<div id="impression_praise" style="margin:10px auto">暂无表扬信息</div>';
	        }
	        var cirticalInfo = obj.data.data.pp;
	        if (cirticalInfo!=null) {
	        listHtml += '<div id="impression_criticism" style="margin:10px auto;display:none"><ul class="awardItem f-mb10">';
	            for (i in cirticalInfo) {
	                listHtml += '<li class="clearfix"><div class="f-fl">';
	                listHtml += '<img src="' + cirticalInfo[i].icon.img + '">' + cirticalInfo[i].icon.title + '</div><div class="f-fr">';
	                var userInfo = cirticalInfo[i].rows;
	                for (j in userInfo) {
	                    listHtml += '<a target="_blank" href="' + commonParams.sqDevPath + '/' + userInfo[j].user_id + '/Space/index">' + userInfo[j].user_realname + '</a>'
	                }
	                listHtml += '</div></li>';
	            }
                listHtml += '</ul></div>';
	        } else {
	            listHtml += '<div id="impression_criticism" style="margin:10px auto;display:none">暂无批评信息</div>';
	        }
	        $('div#courseImpression').html(listHtml);
	        //印象表扬与批评的切换
	        $('div#courseImpression .tab5Menu').tab2Menu(function () {
	            var tabName = $('div#courseImpression .tab5Menu').find('li.tabMenuCurrent').find("a").attr("name");
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
	    });
	}
	getImpressList();
   
	//添加印象
	$("a.addImpression").die().live("click", function() {
		var dialogHtml = '<div class="ct" id="addImpressionDiv" class="m-addCourseImpress" ><div class="ctAward"><ul class="tab5Menu clearfix m-snavCI f-mb10"><li class="menuCell  f-fl"><a href="javascript:;" name="3"  class="tabLink">表扬</a></li>';
		dialogHtml += '<li class="menuCell tabMenuCurrent f-fl"><a href="javascript:;" name="4" class="tabLink">批评</a></li></ul> <div class="show"><div class="lst f-mb10">  <div class="pre" title="上一页"> <i class="icon-pre"></i></div>';
		dialogHtml += '<div class="itemWrap"><div class="itemCt clearfix" style="width:1000px;"></div></div> <div class="next f-tar" title="下一页"><i class="icon-next"></i></div></div>';
		dialogHtml += '<div class="selectContact clearfix f-mb20"><div id="assignmentRec" class="recText f-fl f-mr5"></div><a data-scope="assignment" data-target="assignmentRec" data-boxtype="0" href="javascript:;" title="选择学生" class="customBtn colourLightGray" name="selectUser">选择学生</a></div>';
		dialogHtml += '</div></div></div>';
		function addEvent() {
			//发表印象表扬与批评的切换
			$('div#addImpressionDiv .tab5Menu').tab2Menu(function() {
				var type = $('div#addImpressionDiv .tab5Menu').find('li.tabMenuCurrent').find("a").attr("name");
				function optionsTab(obj) {
					if ('success' == obj.type) {
						var optionsHtml = '';
						for (index in obj.data) {
							optionsHtml += '<a href="javascript:;"  class="item a-Grayl f-fl" data-type="' + type + '" data-icon_id="' + index + '"><div class="pic"><img id="ico" src="' + obj.data[index].img + '" alt="" /> </div><div class="name">' + obj.data[index].title + '</div></a>';
						}
						$("div#addImpressionDiv div.itemCt").html(optionsHtml);
					}
				}

				AjaxForJson(commonParams.jcDevPath + commonParams.jcType + "/Course/empressionOptions", "type=" + type, optionsTab, null);
			});
			$('div#addImpressionDiv .tab5Menu').find('li').find('a[name="3"]').trigger("click");

			//点击印象
			$("#ico").live("click", function() {
				var temp = $(this);
				$.each($("img[id='ico']"), function() {
					$(this).parents("a.item").removeClass("cur");
				});
				temp.parents("a.item").addClass("cur");
			});

			//点击添加弹窗的确认按钮
			$("#PopupsFunc input[name='confirm']").die().live("click", function(e) {

				var student_ids = '';
				var obj = $("input[name='stuone']");
				$.each(obj, function() {
					var temp = $(this);
					student_ids += temp.val() + ",";
				});
				//颁发对象不能为空
				if (!student_ids) {
					promptMessageDialog({
						icon : "error",
						content : '请选择颁发的学生对象！'
					});
					return false;
				}

				var impression_selected = $("div#addImpressionDiv div.itemCt").find("a.cur");
				var type = impression_selected.data("type");
				var icon_id = impression_selected.data("icon_id");
				//印象不能为空
				if (!icon_id) {
					promptMessageDialog({
						icon : "error",
						content : '请选择要颁发的印象！'
					});
					return false;
				}
				var request_data = "course_id=" + course_id + "&student_ids=" + student_ids + "&type=" + type + "&icon_id=" + icon_id;
				function postEvent(obj) {
					if ('success' == obj.type) {
						promptMessageDialog({
							icon : "finish",
							content : '颁发成功！'
						});
						getImpressList();
						styledialog.closeDialog();
					} else {
						promptMessageDialog({
							icon : "error",
							content : '颁发失败！'
						});
					}
				}
				AjaxForJson(commonParams.jcDevPath + commonParams.jcType + "/Course/addImpressionForCourse", request_data, postEvent, null);
			});
		}
		styledialog.initDialogHTML({
			title : "添加课堂表现",
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
		styledialog.initContent("添加课堂表现", dialogHtml, addEvent);
	});

	//表扬的印象选择列表
	$("a#praise_icons").die().live("click", function() {
		var type = 3;
		function iconsInit(obj) {

			if (obj.type == 'success') {
				var options = '';
				for (index in obj.data) {
					options += '<li><img src="' + obj.data[index].img + '">' + obj.data[index].title + '</li>';
				}
				$("div#impression_icon_list").html(options);
			}
		}

		AjaxForJson(commonParams.jcDevPath + commonParams.jcType + "/Course/impressionOptions", "type=" + type, iconsInit, null);
	});
	//批评的印象选择列表
	$("a#criticize_icons").die().live("click", function() {
		var type = 4;
		function iconsInit(obj) {

			if (obj.type == 'success') {
				var options = '';
				for (index in obj.data) {
					options += '<li><img src="' + obj.data[index].img + '">' + obj.data[index].title + '</li>';
				}
				$("div#impression_icon_list").html(options);
			}
		}

		AjaxForJson(commonParams.jcDevPath + commonParams.jcType + "/Course/impressionOptions", "type=" + type, iconsInit, null);
	});

	//添加课程评测
	$("a.addEvaluation").die().live("click", function() {
		function evaluateDialog(obj) {
			var dialogHtml = '<div class="W-form"><table class="tableSet" >';
			if ('success' == obj.type) {
				if (obj.data.length > 0) {
					for (index in obj.data) {
						dialogHtml += '<tr class="evaluateCell"><td style="width:5%"><input type="checkbox"   value="' + obj.data[index].id + '"/></td><td>' + obj.data[index].evaluate_title + '</td></tr>';
					}
				} else {
					dialogHtml += '<tr class="evaluateCell"><td style="width:80%">未找到与课程相匹配的测评！</td></tr>';
				}
			}
			dialogHtml += '</table></div>';

			function addEvent() {
				$("div#PopupsFunc input[name='confirm']").die().live("click", function() {
					var evaluate_ids = new Array();
					$("tr.evaluateCell input:checked").each(function() {
						var evaluate_id = $(this).val();
						evaluate_ids.push(evaluate_id);
					});
					function postEvent(obj) {
						if ('success' == obj.type) {
							$("div#PopupsFunc input[name='cancel']").trigger("click");
						}
					}

					AjaxForJson(commonParams.jcDevPath + commonParams.jcType + "/Course/addEvaluateForCourse", "course_id=" + course_id + "&evaluate_ids=" + evaluate_ids, postEvent, null);
				});
			}


			styledialog.initDialogHTML({
				title : "新增测评",
				content : dialogHtml,
				width : 450,
				confirm : {
					show : true,
					name : "提交"
				},
				cancel : {
					show : true,
					name : "取消"
				}
			});
			styledialog.initContent("新增测评", dialogHtml, addEvent);
		}

		AjaxForJson(commonParams.jcDevPath + commonParams.jcType + "/Course/evaluateOptionsForCourse", "course_id=" + course_id, evaluateDialog, null);

	});

	//提问
	$("a.addQuestion").die().live("click", function() {
		var course_id = $(this).data("course");
		var dialogHtml = ' <div class="m-addQuetions f-mt10 f-mb10"><div class="ct"><textarea name="question"  cols="116" rows="10" class="customForm_inputBoxDefault"></textarea></div></div>';
		function editEvent() {
			$("div#PopupsFunc input[name='confirm']").die().live("click", function() {
				var content = $("textarea[name='question']").val();
				function postEvent(obj) {
					if ('success' == obj.type) {
						$('div#questionList').html("");
						getQuestionList();
						styledialog.closeDialog();
					} else {
						promptMessageDialog({
							icon : "error",
							content : '提交失败！'
						});
					}
				}

				AjaxForJson(commonParams.jcDevPath + commonParams.jcType + "/Course/addQuestionForCourse", "content=" + content + "&course_id=" + course_id, postEvent, null);
			});
		}


		styledialog.initDialogHTML({
			title : "编辑问题",
			content : dialogHtml,
			width : 610,
			confirm : {
				show : true,
				name : "提交"
			},
			cancel : {
				show : true,
				name : "取消"
			}
		});
		styledialog.initContent("编辑问题", dialogHtml, editEvent);
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

	//低版本浏览器下快捷菜单悬浮
	window.onscroll = function() {
		if (!window.XMLHttpRequest) {
			var bodyScrollTop = "";
			if (document.documentElement && document.documentElement.scrollTop) {
				bodyScrollTop = document.documentElement.scrollTop;
			} else if (document.body) {
				bodyScrollTop = document.body.scrollTop;
			}

			var st = $(document).scrollTop(), winh = $(window).height();
			$(".m-quickNav").css("top", st + winh - 66 - 168);
		}
	};
});
