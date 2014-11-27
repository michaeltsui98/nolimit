var courSetParms = {
	raiseCurPege : 1,
	evaluatePage : 1,
	questionPage : 1
}

jQuery(function($) {
	if ($.browser.msie) {
		$('input:checkbox').click(function() {
			this.blur();
			this.focus();
		});
	};
	//动态加载js、css
	loadjscssfile(commonParams.jcStaticPath + "/static/ueditorAll/themes/default/css/ueditor.css", "css");
	loadjscssfile(commonParams.jcStaticPath + "/static/ueditorAll/editor_config.js", "js");
	loadjscssfile(commonParams.jcStaticPath + "/static/ueditorAll/editor_all.js", "js");
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
				listHtml += '<div class="classItem clearfix"><div class="logo f-fl"><a href="' + commonParams.sqDevPath + '/' + info[i].appraise_user_info.user_id + '/Space/index" title="' + info[i].appraise_user_info.user_realname + '" target="_blank"><img src="' + info[i].appraise_user_info.user_avatar_40 + '" width="40" alt=""></a></div>';
				listHtml += '<dl class="info f-fr"><dt class="className"><p  class="f-fs16" > ' + info[i].appraise_score + '分</p></dt><dd class="des"><a href="javascript:;"  class="a-Grayl">' + info[i].appraise_remark + '</a></dd></dl></div>';
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

				if ($('#evaluationList').find("li").length == 0) {
				//	$('#evaluationList').append('<div class="m-emptytips"><p>没有更多</p></div>');
				//} else {
					$('#evaluationList').append('<div class="m-emptytips"><p>暂无内容</p></div>');
				}
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

				if ($('#questionList').find("li").length > 0) {
					$('#questionList').append('<div class="m-emptytips"><p>没有更多</p></div>');
				} else {
					$('#questionList').append('<div class="m-emptytips"><p>暂无内容</p></div>');
				}

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
				var recordText = '';
				if (recordBox.find("div.recordText").length == 1) {
					recordText = recordBox.find("div.recordText").text();
				}
				editor.setContent(recordText);
			});
			$("#PopupsFunc").find("input[name='confirm']").unbind().bind("click", function() {
				function postEvent(obj) {
					if ('success' == obj.type) {
						if (editor.getContent().length > 0) {
							recordBox.html('<div class="recordText">'+editor.getContent()+'</div>');
						}else{
							recordBox.html('<div class="m-emptytips"><p>暂无内容</p></div>');
						}
						styledialog.closeDialog();
					} else {
						promptMessageDialog({
							icon : "error",
							content : '编辑失败！'
						});
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
							    lesListHTML += '<tr class="" name="lessonTr"><td><input type="radio" folder_id="' + obj.data.rows[i].id + '"></td><td>' + obj.data.rows[i].title + '</td><td>' + obj.data.rows[i].chapter_info.data + '</td><tr>';
							}
							$("table#lesson_folder").find("tbody").html(lesListHTML);

							$("tr[name='lessonTr']").unbind().bind("click", function () {
							    $("tr[name='lessonTr']").find("input[type='radio']").prop("checked", false);
							    $(this).find("input[type='radio']").prop("checked", true);
							});

						});
						pageMethod.call(requestMenberpage);
					}
				});
			}

		}

	});
	//课程印象列表
	var getImpressList = function() {
		contentLoading($('[name="qusetionLoadMore"]'));
		AjaxForJson(commonParams.jcDevPath + commonParams.jcType + "/course/impressionList", "course_id=" + course_id, function(obj) {
			if (obj.data.data.length == 0) {
				$('#courseImpression').append('<div class="m-emptytips"><p>暂无内容</p></div>');
				return;
			}
			var listHtml = '<ul class="tab5Menu clearfix m-snavCI f-mb10 f-mt10"><li class="menuCell tabMenuCurrent f-fl"><a href="javascript:;" name="praise" class="tabLink">表扬</a></li><li class="menuCell f-fl"><a href="javascript:;" name="criticism" class="tabLink">批评</a></li></ul>';
			var praiseInfo = obj.data.data.by;
			if (praiseInfo != null) {
			    listHtml += '<div id="impression_praise" style="margin:10px auto" class="impression-list"><ul class="awardItem f-mb10  impression-list">';
				for (i in praiseInfo) {
				    listHtml += '<li class="clearfix"><div class="f-fl clearfix impression">';
				    var medal_info=praiseInfo[i].medal_info;
				    for(m in medal_info){
				        listHtml+='<span class="impression-item"><img src="' +medal_info[m].medal_icon + '" height="40" /><span>' + medal_info[m].medal_name + '</span></span>';
				    }
				    var userInfo = praiseInfo[i].user_info;
				    listHtml += '</div><div class="f-fr impression-student">';
					for (j in userInfo) {
						listHtml += '<a target="_blank" href="' + commonParams.sqDevPath + '/' + j + '/Space/index">' + userInfo[j].user_realname + '</a>'
					}
					listHtml += '</div></li>';
				}
				listHtml += '</ul></div>';
			} else {
				listHtml += '<div id="impression_praise" style="margin:10px auto;"><div class="m-emptytips"><p>暂无内容</p></div></div>';
			}
			var cirticalInfo = obj.data.data.pp;
			if (cirticalInfo != null) {
			    listHtml += '<div id="impression_criticism" style="margin:10px auto;display:none;" class="impression-list"><ul class="awardItem f-mb10  impression-list">';
			    for (i in cirticalInfo) {
			        listHtml += '<li class="clearfix"><div class="f-fl clearfix impression">';
			        var medal_info = cirticalInfo[i].medal_info;
			        for (m in medal_info) {
			            listHtml += '<span class="impression-item"><img src="' + medal_info[m].medal_icon + '" height="40" /><span>' + medal_info[m].medal_name + '</span></span>';
			        }
			        var userInfo = cirticalInfo[i].user_info;
			        listHtml += '</div><div class="f-fr impression-student">';
			        for (j in userInfo) {
			            listHtml += '<a target="_blank" href="' + commonParams.sqDevPath + '/' + j + '/Space/index">' + userInfo[j].user_realname + '</a>'
			        }
			        listHtml += '</div></li>';
			    }
			    listHtml += '</ul></div>';
			} else {
				listHtml += '<div id="impression_criticism" style="margin:10px auto;display:none"><div class="m-emptytips"><p>暂无内容</p></div></div>';
			}
			$('div#courseImpression').html(listHtml);
			//印象表扬与批评的切换
			$('div#courseImpression .tab5Menu').tab2Menu(function() {
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
	$("a.addImpression").die().live("click", function () {
	    var dialogHtml = '<div class="ct" id="addImpressionDiv"><div class="impressIssue">';
	    dialogHtml+='<div class="impreessListTitle"><div class="nav emotionalBox"><span emotional="1" class="emotional">表扬</span><span emotional="2" class="emotional active">批评</span></div><div class="clearFloat"></div></div>';
	    dialogHtml+='<div class="impreessListTitle impreessListTitleFixed"><div class="nav nav_fixed fieldBox"><span class="active field_cell" field="1">品德发展</span>|<span class=" field_cell" field="2">学业发展</span>|<span class=" field_cell" field="3">身心发展</span>|<span class=" field_cell" field="4">兴趣特长</span>|<span class=" field_cell" field="5">实践能力</span></div><div class="clearFloat"></div></div>';
	    dialogHtml+='<div class="Class_roll"><span class="pre cursor_hand"></span><div class="honorShow grayTxt1"></div><span class="next cursor_hand"></span></div>';
	    dialogHtml += ' <div class="tagBox"><div class="recText floatL" id="assignmentRec"></div><span class="allContent userCircleBox" data-scope="assignment" data-target="assignmentRec" data-boxtype="0" href="javascript:;" title="选择学生" name="selectUser">选择学生</span></div></div></div>';
	   function addEvent() {
	       function filedSwitch(obj) {
	           if (obj.type == 'success') {
	               var optionsHtml = '';
	               for (index in obj.data) {
	                   optionsHtml += '<div class="honorShowCell floatL curPointer"><span class="medalIcon"><div><a href="javascript:;" class="medal_cell" medal_id="' + obj.data[index].id + '" medal_name="' + obj.data[index].medal_name + '"><img src="' + obj.data[index].medal_icon + '" width="64px" height="64px"></a></div></span><em>' + obj.data[index].medal_name + '</em></div>';
	               }
	               $("div.honorShow").html(optionsHtml);
	           }
	       }
	       //选择表扬或批评
	       $("span.emotional").die().live("click", function () {
	           $("span.emotional").removeClass("active");
	           $(this).addClass("active");
	           var field = $("div.fieldBox").find("span.active").attr("field");
	           var emotional = $(this).attr("emotional");
	           AjaxForJson(commonParams.jcDevPath + commonParams.jcType + "/course/impressionMedal", "emotional=" + emotional + "&field=" + field, filedSwitch, null);
	           return false;
	       });
	       $("div.emotionalBox").find('span[emotional="1"]').trigger("click");
	       //选择维度
	       $("span.field_cell").die().live("click", function () {
	           $("span.field_cell").removeClass("active");
	           $(this).addClass("active");
	           var field = $(this).attr("field");
	           var emotional = $("div.emotionalBox").find("span.active").attr("emotional");
	           AjaxForJson(commonParams.jcDevPath + commonParams.jcType + "/course/impressionMedal", "emotional=" + emotional + "&field=" + field, filedSwitch, null);
	           return false;
	       });

	       //勋章选择效果
	       $("a.medal_cell").die().live("click", function () {
	           var actLenght = $(this).parents("div.honorShow").find("div.honorShowActive").length;
	           if ($(this).parents("div.honorShowCell").attr("class").indexOf("honorShowActive") > -1) {
	               $(this).parents("div.honorShowCell").removeClass("honorShowActive");
	           } else {
	               if (actLenght >= 2) {
	                   $(this).parents("div.honorShow").find("div.honorShowActive").eq(actLenght - 1).removeClass("honorShowActive");
	               }
	               $(this).parents("div.honorShowCell").addClass("honorShowActive");
	               $(this).parents("div.honorShow").prepend($(this).parents("div.honorShowCell ")[0].outerHTML);
	               $(this).parents("div.honorShowCell").remove();
	           }
	           return false;
	       });

			//点击添加弹窗的确认按钮
			$("#PopupsFunc").find("input[name='confirm']").unbind().bind("click", function() {
			    var field = $("div.fieldBox").find("span.active").attr("field");
			    var emotional = $("div.emotionalBox").find("span.active").attr("emotional");
			    var medal_id = new Array();
			    var medal_name = new Array();
			    $("div.honorShowActive a.medal_cell").each(function () {
			        medal_id.push($(this).attr("medal_id"));
			        medal_name.push($(this).attr("medal_name"));
			    });
			    var user_id = '';
			    var obj = $("input[name='stuone']");
			    $.each(obj, function () {
			        var temp = $(this);
			        user_id += temp.val() + ",";
			    });
			    //颁发对象不能为空
			    if (!user_id) {
			        promptMessageDialog({
			            icon: "error",
			            content: '请选择颁发的学生对象！'
			        });
			        return false;
			    }
			    //选择勋章
			    if (medal_id.length == 0) {
			        promptMessageDialog({
			            icon: "warning",
			            content: "请选择勋章!"
			        });
			        return false;
			    }
			    function postEvent(obj) {
			        if ("success" == obj.type) {
			            promptMessageDialog({
			                icon: "finish",
			                content: "添加成功!"
			            });
			            getImpressList();
			            styledialog.closeDialog();
			        }
			    }
			    var param = "course_id=" + course_id + "&emotional=" + emotional + "&field=" + field + "&medal_id=" + medal_id + "&medal_name=" + medal_name + "&user_id=" + user_id;
			    AjaxForJson(commonParams.jcDevPath + commonParams.jcType + "/Course/addImpressionForCourse", param, postEvent, null);
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
				if (obj.data.total > 0) {
					for (index in obj.data.rows) {
						dialogHtml += '<tr class="evaluateCell"><td style="width:5%"><input type="checkbox"   value="' + obj.data.rows[index].id + '"/></td><td>' + obj.data.rows[index].evaluate_title + '</td></tr>';
					}
				} else {
					dialogHtml += '<tr class="evaluateCell"><td style="width:80%">未找到与课程相匹配的测评！</td></tr>';
				}
			}
			dialogHtml += '</table></div>';

			function addEvent() {
				$("#PopupsFunc").find("input[name='confirm']").unbind().bind("click", function() {
					var evaluate_ids = new Array();
					$("tr.evaluateCell input:checked").each(function() {
						var evaluate_id = $(this).val();
						evaluate_ids.push(evaluate_id);
					});
					function postEvent(obj) {
					    if ('success' == obj.type) {
					        $('#evaluationList').html('');
					        courSetParms.evaluatePage = 1;
							getEvaluateList();
							styledialog.closeDialog();
						} else {
							promptMessageDialog({
								icon : "error",
								content : '添加失败！'
							});
							styledialog.closeDialog();
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
			$("#PopupsFunc").find("input[name='confirm']").unbind().bind("click", function() {
				var content = $("textarea[name='question']").val();
				function postEvent(obj) {
					if ('success' == obj.type) {
					    $('div#questionList').html("");
					    courSetParms.questionPage = 1;
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

	//编辑课程记录
	if ($('#courseRecord').height() > 300) {
		$('#courseRecord').css('height', '400px').css('overflow', 'hidden');
		$('#course_record').append('<div class="ft"><div class="courseLoadMore"  name="recordLoadMore" is_show="true"><a href="javascript:;">展 开</a></div></div>');
	}
	$('div[name="recordLoadMore"]').die().live('click', function() {
		if ($(this).attr('is_show') == 'true') {
			$('#courseRecord').css('height', '100%').css('overflow', 'inherit');
			$(this).attr('is_show', 'false').find('a').html('收 起');
		} else {
			$('#courseRecord').css('height', '400px').css('overflow', 'hidden');
			$(this).attr('is_show', 'true').find('a').html('展 开');
		}
		return false;
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
	//锚点后切换样式
	$("div.m-quickNav").find("a.item").unbind().bind("click", function() {
		var mian = this;
		$("div.m-quickNav").find("a").find("i").each(function() {
			$(this).attr("class", $(this).attr("class").replace("1", ""));
		})
		$(mian).find("i").attr("class", $(mian).find("i").attr("class") + "1");
	});
});
