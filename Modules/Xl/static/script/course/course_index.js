jQuery(function($) {
	//添加课程节次
	$("div.vsWrap").find("a.a-secl").die().live("click", function() {
		var sordNum = $("div.vsWrap").find("ul").length;
		var wrapUlHTML = '<ul class="vsItem clearfix"><li class="Lessons f-fl f-tac"><strong class="f-fs20">第' + sordNum + '节</strong></li>';
		for (var i = 0; i < $("div.vsWrap").find("ul").eq(0).find("li").length - 1; i++) {
			wrapUlHTML += '<li class="' + $("div.vsWrap").find("ul").eq(1).find("li").eq(i + 1).attr("class") + '" data-date="' + $("div.vsWrap").find("ul").eq(1).find("li").eq(i + 1).attr("data-date") + '" data-sort="' + sordNum + '"><dl class="classItem blankDl"><dt class="name f-fs14 "></dt><dt class="name f-fs14"></dt></dl></li>'
		}
		wrapUlHTML += '</ul>';

		$(this).before(wrapUlHTML);

		return false;
	});
	//日历单元格选中效果
	$("div.vsWrap li.item1").die("mouseover").live("mouseover", function(e) {
		   var class_name = $(this).attr("class");
		if (-1 != class_name.indexOf("z-overdue")) {
			$(this).attr("title","不能設置")
			return false;
		}
		$(this).addClass("lesson-bg-hover");
		
	});
	$("div.vsWrap li.item1").die("mouseout").live("mouseout", function(e) {
		var class_name = $(this).attr("class");
		if (-1 != class_name.indexOf("z-overdue")) {
			$(this).attr("title","")
			return false;
		}
		$(this).removeClass("lesson-bg-hover");
	});
	//调用课程设置弹框和弹框中相关操作
	$("div.vsWrap li.lesson-bg-hover").die("click").live("click", function(e) {		
		e = e ? e : getEvent();
		var srcEle = e.target || e.srcElement;
		if (srcEle.tagName.toLowerCase() == "a") {
			return;
		}
		var mian = this;

		function setEvent() {
			AjaxForJson(commonParams.jcDevPath + commonParams.jcType + "/Course/courseSetting", null, function(obj) {
				$("#loadingDiv").replaceWith(obj);

				function getLesList() {
				    var requestData = "grade=" + $("select#grade").val() + "&edition=" + $("select#edition").val();
				    $("table#lesson_folder").find("tbody").html('<tr><td style="text-align:center;" colspan="3" name="loadingDiv"></td></tr>')
				    contentLoading($("table#lesson_folder").find("td[name='loadingDiv']"));
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

				//选择年级后的方法
				$("select#grade").unbind().bind("change", function() {
					var grade = $(this).val();
					//请求年级下的班级
					$("table#lesson_folder").find("tbody").html('<tr><td style="text-align:center;" colspan="3" name="loadingDiv"></td></tr>')
					contentLoading($("table#lesson_folder").find("td[name='loadingDiv']"));
					AjaxForJson(commonParams.jcDevPath + commonParams.jcType + "/Course/classList", "grade=" + grade, function(obj) {
						var options = '';
						for (index in obj.data) {
							options += '<option value="' + obj.data[index].class_id + '">' + obj.data[index].class_name + '</option>';
						}
						$("select#class").html(options);
						getLesList();

					}, null);
				});
				//选择版本后的方法
				$("select#edition").unbind().bind("change", function() {
					getLesList();
				});

				//绑定选择课程确定事件
				$("#PopupsFunc").find("input[name='confirm']").die().live("click", function() {
					if (!$("select#class").val()) {
						promptMessageDialog({
							icon : "error",
							content : dataObj.message
						});
					}
					var btnloading = new btnLoading(this);
					btnloading.toLoading();
					var folder_id = $("table#lesson_folder").find("tbody").find("input[type='radio']:checked").attr("folder_id");
					//提交按钮变loading图片
					AjaxForJson(commonParams.jcDevPath + commonParams.jcType + "/course/saveCourse", {
						class_id : $("select#class").val(),
						grade : $("select#grade").val(),
						date : $(mian).attr("data-date"),
						sort : $(mian).attr("data-sort"),
						lesson_folder_id : folder_id,
						remark : $("textarea[name='remark']").val()
					}, function(dataObj) {
						if (dataObj.type == "success") {
							var course_id = dataObj.data.course_id;
							var status_div = $(mian).find("div.status-ico");

							if (status_div.length > 0) {
								status_div.find("span.prepareLessons i.icon-tickOff").remove();
								if (folder_id) {
									status_div.find("i.icon-prepareLessons").before('<i class="icon-tickOff"></i>');
								}
								var status_html = status_div.html();
								status_html = '<div class="status-ico">' + status_html + '</div>';
							} else {
								var status_html = '<div class="status-ico"> <span class="iconWrap f-ib f-mr10 z-finish lessonfolder" title="课程评价"><i class="icon-lessonfolder"></i></span>';
								status_html += '<span class="iconWrap f-ib f-mr10 z-finish courseDes" title="课程问答"><i class="icon-courseDes"></i></span>';
								status_html += '<span class="iconWrap f-ib f-mr10 z-finish prepareLessons" title="备课夹">';
								if (folder_id) {
									status_html += '<i class="icon-tickOff"></i>';
								}
								status_html += ' <i class="icon-prepareLessons"></i></span></div> ';
							}
							var item_con = '<dl class="classItem  s-bgcPurple"><dt class="name f-fs14">' + $("select#class").find("option:selected").text() + '</dt>';
							item_con += '<dt class="name f-fs14"><a href="' + commonParams.jcDevPath + commonParams.jcType + '/course/courseDetail?course_id=' + course_id + '" target="blank" class="s-fcWhite">详情</a></dt></dl>';
							$(mian).html(status_html + item_con);
							styledialog.closeDialog();
						} else {
							promptMessageDialog({
								icon : "error",
								content : dataObj.message
							});
							btnloading.toBtn();
						}
					});
				});

				//初始化备课列表
				getLesList();

			}, null);
		}

		var dialogHTML = '<div id="loadingDiv" class="loadingStyle1" style="width:600px;height:300px;"></div>';
		styledialog.initDialogHTML({
			title : "课程设置",
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
		styledialog.initContent("课程设置", dialogHTML, setEvent);
	});
	//课程表显示切换
	$("a.courseListSwitch").die().live("click", function() {
		var start_date = $(this).parent().data("start");
		var format = $(this).data("format");

		function switchPost(obj) {
			$("div#courseManage").html(obj);
		}

		AjaxForJson(commonParams.jcDevPath + commonParams.jcType + "/Course/courseList", "start=" + start_date + "&format=" + format, switchPost, null);
	});
	//上一周的课程安排
	$("a#prevweek").die().live("click", function() {
		var starting_point = $("strong#thisweek");
		var starting_date = starting_point.data("weekstart");
		var format = $("div#courseListSwitch").find("a.z-sel").data("format");
		function turntopreweek(obj) {
			$("div#courseManage").html(obj);
		}

		AjaxForJson(commonParams.jcDevPath + commonParams.jcType + "/Course/courseByWeek", "weekstart=" + starting_date + "&diff=-1&format=" + format, turntopreweek, null);

	});
	//下一周的课程安排
	$("a#nextweek").die().live("click", function() {
		var starting_point = $("strong#thisweek");
		var starting_date = starting_point.data("weekstart");
		var format = $("div#courseListSwitch").find("a.z-sel").data("format");
		function turntopreweek(obj) {
			$("div#courseManage").html(obj);
		}

		AjaxForJson(commonParams.jcDevPath + commonParams.jcType + "/Course/courseByWeek", "weekstart=" + starting_date + "&diff=1&format=" + format, turntopreweek, null);

	});
	//上一月的课程安排
	$("a#prevmonth").die().live("click", function() {
		var starting_point = $("strong#thismonth");
		var starting_month = starting_point.data("month");
		var format = $("div#courseListSwitch").find("a.z-sel").data("format");
		function turntopremonth(obj) {
			$("div#courseManage").html(obj);
		}

		AjaxForJson(commonParams.jcDevPath + commonParams.jcType + "/Course/courseByMonth", "themonth=" + starting_month + "&diff=-1&format=" + format, turntopremonth, null);

	});
	//下一月的课程安排
	$("a#nextmonth").die().live("click", function() {
		var starting_point = $("strong#thismonth");
		var starting_month = starting_point.data("month");
		var format = $("div#courseListSwitch").find("a.z-sel").data("format");
		function turntopremonth(obj) {
			$("div#courseManage").html(obj);
		}

		AjaxForJson(commonParams.jcDevPath + commonParams.jcType + "/Course/courseByMonth", "themonth=" + starting_month + "&diff=1&format=" + format, turntopremonth, null);

	});
});
