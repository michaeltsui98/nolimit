/*!
* app sasou
*/
var sy_dialog = new Array();
var sy_dialog_config = new Object();
var sy_config = new Object();
sy_config.dialog = null;
sy_config.url = '';
sy_config.apponclick = null;
sy_config.apppageurl = '';

var tab_parse = 0;

function grid_success(){
		$.parser.parse(".datagrid-btable");
}
function tree_success(){
		$.parser.parse(".datagrid-btable");
}
function reload(id,one,two){
	var obj = $('#'+id+"-dg");
	if(typeof(two)=="undefined"){
		obj.treegrid('reload', parseInt(one));
	} else {
		obj.treegrid('reload', parseInt(two));
	}
}
function gridload(id,params){
	var obj = $('#'+id+"-dg");
	if(typeof(params)!="object"){
		obj.datagrid('load');
	} else {
		obj.datagrid('load', params);
	}
}
function treeload(id,params){
	var obj = $('#'+id+"-dg");
	if(typeof(params)!="object"){
		obj.treegrid('load');
	} else {
		obj.treegrid('load', params);
	}
}
function combotreeSetV(id,text){
	var obj = $("#"+id);
	obj.combotree('setText',text);
}
var extend=function(o,n,override){
   for(var p in n)if(n.hasOwnProperty(p) && (!o.hasOwnProperty(p) || override))o[p]=n[p];
};
function combotreeV(id,node,callback,grid_id,tree_id,id_name,params_in){
	var obj = $("#"+id);
	var combotree = obj.combotree('tree')
	var roots = combotree.tree('getParent',node.target);
	var text_path = '';
	var node_id = (roots)?roots.id:0;
	while(node_id>0){
		text_path =  roots.text + "."+text_path
		if(roots.target)roots = combotree.tree('getParent',roots.target);
		node_id = (roots)?roots.id:0;
	}
	text_path = text_path + node.text+ ".";
	obj.combotree('setText',text_path);
	if(typeof(callback)=="function" )callback(node,text_path);
	var params = {};
	if(typeof(id_name)=="string" ){
		params[id_name]=node.id;
		params['name']=text_path;
	} else {
		params['pid']=node.id;
		params['name']=text_path;
	}
	if(typeof(params_in)=="object")extend(params,params_in);
	if(typeof(grid_id)=="string" )gridload(grid_id,params);
	if(typeof(tree_id)=="string" )treeload(tree_id,params);
}
function send_form(e, t) {
        var n = $(e).attr("action"),
            r = {};
        var tes = '';
        $.each($(e).serializeArray(), function(e, t) {
                if(typeof(r[t.name])=='undefined'){
					r[t.name] = t.value;
				} else {
					r[t.name] = r[t.name] + "," + t.value;
				}
        });
        $.post(n, r, t);
}

function send_form_pop(e) {
        return send_form(e, function(t) {
                show_pop_box(t)
        })
}

function send_form_in(e) {
        return send_form(e, function(jsontext) {
				set_form_notice(jsontext);
        });
}

function set_form_notice(jsontext) {
        var json = null;
        if (jsontext.length > 0) json = eval("(" + jsontext + ")");
        var success_callback = ((typeof(json.success_callback)!='undefined') && (json.success_callback != '')) ? json.success_callback : 'page_flash();';
        var fail_callback = ((typeof(json.fail_callback)!='undefined') && (json.fail_callback != '')) ? json.fail_callback : 'btn_reset();';
        if (json.status) {
        	    //$.messager.alert('提示',json.message,'info');
        	    //setInterval(success_callback,50);
				eval(success_callback);
        	    //ui_tip('', json.message, json.status, success_callback)
        } else {
				eval(fail_callback);
               // ui_tip('', json.message, json.status, fail_callback)
        }
}

function page_flash() {
        window.location.reload();
}
function ajax_flash(c,type) {
		obj = $('#'+c+"-dg");
		if (obj) {
			if(typeof(type)=='undefined' || type=='null'){
				obj.datagrid('reload');
			}else{
				obj.treegrid('reload');
			}
		}
}

function page_back() {
        window.history.back(-1);
}

function page_go(url) {
        window.location.href = url;
}

function addTab(title, url, icon){
	tab_parse = false;
    if ($('#tt').tabs('exists', title)){
        $('#tt').tabs('select', title);
    } else {
		if(icon==null)icon='';
        $('#tt').tabs('add',{
			iconCls:icon,
            title:title,
            href:url,
			//content:'<iframe src="'+url+'" style="padding:0;margin:0;border:0;width:100%;height:100%;"></iframe>',
			closable:true
        });
    }
}

function updateTab(title, url, icon){
	var tab = $('#tt').tabs('getSelected');
	$('#tt').tabs('update', {
		tab: tab,
		options: {
			iconCls:icon,
			title:title,
			href:url,
			closable:true
		}
	});
}

function page_ajax(url, isajax, obj) {
    	url != null && set_apppageurl(url);
    	obj != null && set_click(obj);
        if(typeof(isajax)=='undefined')var isajax = 1;
    	if(url == null && obj == null){
    		//alert('翻页前窗口数量：'+sy_dialog.length);
    		if(sy_dialog_config){
    			if(sy_dialog_config.dialog==null){
    				var n = sy_dialog.length-1;
    				if(n>=0){
    					sy_dialog_config = sy_dialog[n];
    				}
    			}
    			if(sy_dialog_config.apppageurl=='')sy_dialog_config.apppageurl=sy_dialog_config.url;
    		}
    		url = (sy_dialog.length==0)?sy_config.apppageurl:sy_dialog_config.apppageurl;
    		obj = (sy_dialog.length==0)?sy_config.apponclick:sy_dialog_config.apponclick;
    		sy_dialog_config = new Object();
    		//alert("ajax翻页：url："+url+'  obj:'+obj);
    	}
        if (!isajax) {
                window.location.href = url;
        } else {
                var list = null;
                if (obj) {
                        list = $(obj).parent();
                        if (list.attr('id') == null) {
                                list = $(obj).parentsUntil('#ajaxpage').parent();
                                list = list.parent();
                        }
                } else {
                        list = $('#ajaxpage');
                }
                if (list) {
                        list.load(url, null, function() {
							$('#dg').datagrid('refresh');
                        });
                }
        }
}

function page_ui_close() {
        var n = sy_dialog.length - 1;
        if (n >= 0) {
                sy_dialog[n].dialog.close();
        }
}

function set_click(obj){
	if(sy_dialog.length>0){
		var n = sy_dialog.length-1;
		sy_dialog[n].apponclick = obj;
	}else{
		sy_config.apponclick = obj;
	}
}
function set_apppageurl(url){
	if(sy_dialog.length>0){
		var n = sy_dialog.length-1;
		sy_dialog[n].apppageurl = url;
		//alert(sy_dialog[n].apppageurl);
	}else{
		sy_config.apppageurl = url;
        //alert(sy_config.apppageurl);
	}
}

function dialogData_update(){
		sy_dialog_config = sy_dialog.pop();
		var n = sy_dialog.length-1;
		if(n>=0){
			sy_dialog_config.url = sy_dialog[n].url;
			sy_dialog_config.apppageurl = sy_dialog[n].apppageurl;
		}else{
			//alert('没有窗口了');
		}
}

function show_pop_box(t) {
        t == undefined && (t = "lp_pop_box");
        if ($("#" + t).length == 0) {
                var n = $('<div><div id="lp_pop_container"></div></div>');
                n.attr("id", t), n.css("display", "none"), $("body").prepend(n)
        }
        e != "" && $("#lp_pop_container").html(e);
        var r = ($(window).widthui_order() - $("#" + t).width()) / 2;
        $("#" + t).css("left", r), $("#" + t).css("display", "block")
}

function syform_val(formid) {
        var ui_form = $("#" + formid).Validform({
                btnSubmit: "#btn_submit",
                tiptype: function(msg, o, cssctl) {
						if(kbeditor)kbeditor.sync();
                        if (!o.obj.is("form")) {
                                var objtip = o.obj.siblings(".info").find(".ui-form-other");
                                cssctl(objtip, o.type);
                                objtip.text(msg);
								var infoObj=o.obj.siblings(".info");
								if(o.type==2){
									infoObj.fadeOut(200);
								}else{
									if(infoObj.is(":visible")){return;}
									var left=o.obj.offset().left,
										top=o.obj.offset().top;
									var p = $("#dlg");
									var offset = p.offset();
									if(offset){
										left=left-offset.left-5,
										top=top-offset.top;
									}else{
										left=left+50,
										top=top-80;
									}
									infoObj.css({
										left:0,
										top:top
									}).show().animate({
										top:0
									},200);
								}
								
                        } else {
                                var objtip = o.obj.find("#msgdemo");
                                cssctl(objtip, o.type);
                                objtip.text(msg);
                        }
                        if (o.type == 3) {
                                btn_reset();
                        }
                },
                label: ".ui-label",
                showAllError: false,
                datatype: {
                        "zh1-6": /^[\u4E00-\u9FA5\uf900-\ufa2d]{1,6}$/
                },
				beforeSubmit:function(curform){
						var isValid = $(curform).form('validate');
						if(!isValid){
							return false;
						}
				},
                callback: function(form) {
                        send_form(form[0], function(html) {
                                set_form_notice(html);
                        });
                        return false;
                }
        });
}

function btn_reset() {
        var btn_ok = $('.d-state-highlight');
        if (btn_ok.attr('disabled') == 'disabled') {
                btn_ok.removeAttr('disabled');
                btn_ok.attr('value', '保存');
        }
}
var kbeditor = null;
function editorCreate(obj){
	kbeditor = KindEditor.create('#'+obj,{
		allowImageUpload : true,
		allowFileManager : true,
		allowPreviewEmoticons : true,
		syncType : 'auto',
		resizeType:1,
		minWidth:500,
		minHeight:240,
		items:[
        'source', '|', 'undo', 'redo', '|', 'preview', 'print',
        'plainpaste', 'wordpaste', '|', 'justifyleft', 'justifycenter', 'justifyright',
        'justifyfull', 'insertorderedlist', 'insertunorderedlist', 'indent', 'outdent', 'subscript',
        'superscript', 'clearhtml', 'quickformat', '/', 'selectall', '|', 'fullscreen',
        'formatblock', 'fontname', 'fontsize', '|', 'forecolor', 'hilitecolor', 'bold',
        'italic', 'underline', 'strikethrough', 'lineheight', 'removeformat', '|', 'image', 'insertfile', 'table', 'hr', 'emoticons'],
		urlType : 'relative'
	});
}

function ui_dialog(url,obj,x,y,dialog_id) {
		dialog_id = (typeof(dialog_id)=="string")?dialog_id:"dlg";
        if(obj)set_click(obj);
        var title = (obj == null) ? '设置' : obj.innerText;
		$('.window-header .panel-title').html(title);
		x = (x == null) ? 600 : x;
		y = (y == null) ? 400 : y;
		var dlg_obj = $('#'+dialog_id);
		dlg_obj.window('resize',{"width":x,"height":y}).window('center').window('open');
		$('#'+dialog_id).find("#dialog-content").html('加载中...');
		/*$.ajax({
		  url: url,
		  cache: false,
		  success: function(html){
				$("#dialog-content").html('');
				dlg_obj.window('resize');
				var targetObj = $(html).appendTo("#dialog-content");
				$.parser.parse(targetObj);
				var obj= $('#btn_submit');
				if(obj)obj.hide();
		  }
		});
		*/
		$('#'+dialog_id).find("#dialog-content").load(url, null, function() {
				dlg_obj.window('resize');
				var obj= $('#btn_submit');
				if(obj)obj.hide();
				$.parser.parse($('#'+dialog_id).find("#dialog-content").parent());
		});
}

function ui_iframeDialog(url, obj, x, y, dialog_id) {
    dialog_id = (typeof (dialog_id) == "string") ? dialog_id : "dlg";
    if (obj) set_click(obj);
    var title = (obj == null) ? '设置' : obj.innerText;
    $('.window-header .panel-title').html(title);
    x = (x == null) ? 600 : x;
    y = (y == null) ? 400 : y;
    var dlg_obj = $('#' + dialog_id);
    dlg_obj.window('resize', { "width": x, "height": y }).window('center').window('open');
    $('#' + dialog_id).find("#dialog-content").html('加载中...');
    /*$.ajax({
      url: url,
      cache: false,
      success: function(html){
            $("#dialog-content").html('');
            dlg_obj.window('resize');
            var targetObj = $(html).appendTo("#dialog-content");
            $.parser.parse(targetObj);
            var obj= $('#btn_submit');
            if(obj)obj.hide();
      }
    });
    */
    //$('#' + dialog_id).find("#dialog-content").load(url, null, function () {
    //    dlg_obj.window('resize');
    //    var obj = $('#btn_submit');
    //    if (obj) obj.hide();
    //    $.parser.parse($('#' + dialog_id).find("#dialog-content").parent());
    //});
    $('#' + dialog_id).find("#dialog-content").load(url, null, function () {
        dlg_obj.window('resize');
        var obj = $('#btn_submit');
        if (obj) obj.hide();
        $.parser.parse($('#' + dialog_id).find("#dialog-content").parent());
        var iFrame = $('<iframe />').attr({
            id: 'boxen_iframe_content_' + new Date().getTime(),
            width: 640,
            height: 605,
            frameBorder: 0,
            src: "http://dev-wenku.dodoedu.com/Upload/res/t/1"
        });
        $('#' + dialog_id).find("#dialog-content").prepend(iFrame);
    });
}

function saveForm(){
	$('#btn_submit').trigger('click');
}

function cantForm(dialog_id){
	dialog_id = (typeof(dialog_id)=="string")?dialog_id:"dlg";
	$('#'+dialog_id).window('close');
}

function ui_tip(title, body, type, callback, time) {
        title == null && (title = '提示');
        body == null && (body = '无');
        time == null && (time = 1000);
        title = title + '(两秒后自动关闭)';
		$.messager.msg(title,body,type,callback,time);
}

function trim(str) {
        return str.replace(/(^s*)|(s*$)/g, "");
}

function tip_replace(tip_html, tipstyle, titletip, zttext, bodytext) {
        var body = tip_html;
        body = body.replace(/tipstyle/i, tipstyle);
        body = body.replace(/titletip/i, titletip);
        body = body.replace(/zttext/i, zttext);
        body = body.replace(/bodytext/i, bodytext);
        return body;
}

function ui_ajax(url, obj) {
        if(obj)set_click(obj);
        var title = (obj == null) ? '提示' : obj.innerText;
        if(title)title = title.substring(1, title.length);
        $.ajax({
                url: url,
                cache: false,
                success: function(html) {
                        set_form_notice(html);
                }
        });
}

function ui_order(url, obj) {
		if(obj)set_click(obj);
        var title = (obj == null) ? '提示' : obj.innerText;
        //title = title.substring(1, title.length);
        if (url.indexOf('type=up') >= 0) {
                var obj_id = get_obj_id(obj, 'up');
        } else {
                var obj_id = get_obj_id(obj, 'down');
        }
        if (obj_id != '') {
                url = url + '&obj_id=' + obj_id;
                $.ajax({
                        url: url,
                        cache: false,
                        success: function(html) {
                                set_form_notice(html);
                        }
                });
        }
}

function get_obj_id(obj, type) {
        var menuIndex = $(obj).parents('tr').attr('trindex');
        var menuId = $(obj).parents('tr').attr('trid');
        var menuLevel = $(obj).parents('tr').attr('level');
        if (type == 'up') {
                for (var i = parseInt(menuIndex) - 1; i >= 0; i--) {
                        var trI = $(obj).parents('tbody').find('tr').eq(i);
                        var level = trI.attr('level');
                        if (menuLevel == level) {
                                var newId = trI.attr('trid');
                                break;
                        }
                }
        } else {
                for (var i = parseInt(menuIndex) + 1; i < $(obj).parents('tbody').find('tr').length; i++) {
                        var trI = $(obj).parents('tbody').find('tr').eq(i);
                        var level = trI.attr('level');
                        if (menuLevel == level) {
                                var newId = trI.attr('trid');
                                break;
                        }
                }
        }
        return (newId) ? newId : '';
}

function ui_qr(url, obj) {
		if(obj)set_click(obj);
        var title = (obj == null) ? '提示' : obj.innerText;
        var body = '你确定 “' + title + '” 吗？';
        //title = title.substring(1, title.length);
		$.messager.confirm(title, body, function(r){
			if (r){
				$.ajax({
						url: url,
						cache: false,
						success: function(html) {
								set_form_notice(html);
						}
				});
				return false;
			}
		});
}

function outcheck(e) {
        return e != "" ? (alert(e), !1) : !0
}

function checkvalue(e, t, n, r, i) {
        if (!e) return true;
        var s, o, u, a, f, l;
        l = getformvalue(e), l == null ? (lenght = 0, l = "") : u = l.length, s = "", r % 2 >= 1 && l == "" && (s = s + "“" + i + "”" + "不能为空！" + "\n");
        if (r % 4 >= 2) {
                f = "0123456789.";
                for (a = 0; a <= u - 1; a++)
                if (f.indexOf(l.substring(a, a + 1)) == -1) {
                        s = s + "“" + i + "”" + "必需是数字！" + "\n";
                        break
                }
        }
        if (r % 8 >= 4) {
                f = "0123456789";
                for (a = 0; a <= u - 1; a++)
                if (f.indexOf(l.substring(a, a + 1)) == -1) {
                        s = s + "“" + i + "”" + "必需是整数！" + "\n";
                        break
                }
        }
        if (r % 16 >= 8) {
                f = "abcdefghijklmnopqrstuvwxyzabcdefghijklmnopqrstuvwxyz0123456789_-.";
                for (a = 0; a <= u - 1; a++)
                if (f.indexOf(l.substring(a, a + 1)) == -1) {
                        s = s + "“" + i + "”" + "包含非法字符！它只能是字母、数字和“- _ .”。" + "\n";
                        break
                }
        }
        if (r % 32 >= 16) {
                f = t.replace("[a-z]", "abcdefghijklmnopqrstuvwxyz"), f = f.replace("[a-z]", "abcdefghijklmnopqrstuvwxyz"), f = f.replace("[0-9]", "0123456789");
                for (a = 0; a <= u - 1; a++)
                if (f.indexOf(l.substring(a, a + 1)) == -1) {
                        s = s + "“" + i + "”" + "包含非法字符！它只能是" + n + "。" + "\n";
                        break
                }
        }
        return r % 64 >= 32 && (u >= t && u <= n || (s = s + "“" + i + "”" + "的长度必需在" + t + "到" + n + "之间！" + "\n")), r % 128 >= 64 && (parseInt(l) >= parseInt(t) && parseInt(l) <= parseInt(n) || (s = s + "“" + i + "”" + "必需在" + t + "到" + n + "之间！" + "\n")), s != "" ? (alert(s), o = getformtype(e), o != "radio" && o != "checkbox" && e.focus(), !1) : !0
}

function getformtype(e) {
        if (!e) return false;
        var t;
        return t = e.type, typeof t == "undefined" && (t = e[0].type), t
}

function getformvalue(e) {
        if (!e) return false;
        var t, r;
        r = "", t = getformtype(e);
        switch (t) {
        case "radio":
                n = e.length - 1;
                if (isNaN(n) != 1) {
                        for (i = 0; i <= n; i++)
                        if (e[i].checked == 1) return e[i].value;
                        break
                }
                e.checked == 1 ? r = e.value : r = "";
        case "checkbox":
                n = e.length - 1;
                if (isNaN(n) == 1) e.checked == 1 ? r = e.value : r = "";
                else
                for (i = 0; i <= n; i++)
                e[i].checked == 1 && (r != "" && (r += ","), r += e[i].value);
                return r;
        case "select-one":
                n = e.length - 1;
                for (i = 0; i <= n; i++)
                if (e.options[i].selected == 1) {
                        r = e.options[i].value;
                        break
                }
                return r;
        case "select-multiple":
                n = e.length - 1;
                for (i = 0; i <= n; i++)
                e.options[i].selected == 1 && (r != "" && (r += ","), r += e.options[i].value);
                return r;
        default:
                return e.value
        }
        return e.value
}

function ischecked(e, t) {
        if (!e) return false;
        var n, r;
        r = e.length - 1;
        for (n = 0; n <= r; n++)
        if (t == e[n]) return !0;
        return !1
}

function SetValue(e, t) {
        if (!e) return false;
        var n, r, i, s, o = new Array;
        r = "", n = e.type, typeof n == "undefined" && (n = e[0].type);
        switch (n) {
        case "radio":
                s = e.length - 1;
                if (isNaN(s) == 1)(e.value = t) ? e.checked = !0 : e.checked = !1;
                else
                for (i = 0; i <= s; i++)
                e[i].value == t ? e[i].checked = !0 : e[i].checked = !1;
                break;
        case "checkbox":
                s = e.length - 1, o = t.split(",");
                if (isNaN(s) == 1) ischecked(o, e.value) ? e.checked = !0 : e.checked = !1;
                else
                for (i = 0; i <= s; i++)
                ischecked(o, e[i].value) ? e[i].checked = !0 : e[i].checked = !1;
                break;
        case "select-one":
                s = e.options.length - 1;
                for (i = 0; i <= s; i++)
                e.options[i].value == t ? e.options[i].selected = !0 : e.options[i].selected = !1;
                break;
        case "select-multiple":
                s = e.options.length - 1, o = t.split(",");
                for (i = 0; i <= s; i++)
                ischecked(o, e.options[i].value) ? e.options[i].selected = !0 : e.options[i].selected = !1;
                break;
        default:
                return !1
        }
        return !0
}
var nTabs_current = null;

function nTabs(thisObj, Num) {
        if (thisObj.className == "active") return;
        var tabObj = thisObj.parentNode.id;
        var tabList = document.getElementById(tabObj).getElementsByTagName("li");
        for (i = 0; i < tabList.length; i++) {
                if (i == Num) {
                        thisObj.className = "active";
                        document.getElementById(tabObj + "_Content" + i).style.display = "block";
                } else {
                        tabList[i].className = "normal";
                        document.getElementById(tabObj + "_Content" + i).style.display = "none";
                }
        }
}

function symenu(id, id_class) {
        this.id = id;
        this.id_class = id_class;
}
symenu.prototype.show = function() {
        var li_List = document.getElementById(this.id).getElementsByTagName("li");
        var id_class = this.id_class;
        for (var i = 0; i < li_List.length; i++) {
                li_List[i].onmouseover = function() {
                        this.className += " " + id_class;
                }
                li_List[i].onmouseout = function() {
                        this.className = this.className.replace(" " + id_class, "");
                }
        }
}

function setCookie(NameOfCookie, value, expiredays) {
        var ExpireDate = new Date();
        ExpireDate.setTime(ExpireDate.getTime() + (expiredays * 24 * 3600 * 1000));
        document.cookie = NameOfCookie + "=" + escape(value) + ((expiredays == null) ? "" : "; expires=" + ExpireDate.toGMTString());
}

function getCookie(NameOfCookie) {
        if (document.cookie.length > 0) {
                begin = document.cookie.indexOf(NameOfCookie + "=");
                if (begin != -1) {
                        begin += NameOfCookie.length + 1;
                        end = document.cookie.indexOf(";", begin);
                        if (end == -1) end = document.cookie.length;
                        return unescape(document.cookie.substring(begin, end));
                }
        }
        return null;
}

function delCookie(NameOfCookie) {
        if (getCookie(NameOfCookie)) {
                document.cookie = NameOfCookie + "=" + "; expires=Thu, 01-Jan-70 00:00:01 GMT";
        }
}

function showslt(pic, id) {
        document.getElementById("enlarge_images" + id).innerHTML = "<img src='" + pic + "' width='200' height='200'>";
}

function hideslt(id) {
        document.getElementById("enlarge_images" + id).innerHTML = "";
}

Array.prototype.each=function(f){
	for(var i=0;i<this.length;i++) f(this[i],i,this);
}

function f(a,b){
	var sumRow=function(row){return Number(row.cells[1].innerHTML)+Number(row.cells[2].innerHTML)}; 
	return sumRow(a)>sumRow(b)?1:-1; 
} 

function $A(arrayLike){
	for(var i=0,ret=[];i<arrayLike.length;i++) ret.push(arrayLike[i]); 
	return ret;
} 

Function.prototype.bind = function() {
	  var __method = this, args = $A(arguments), object = args.shift(); 
	  return function() { 
		return __method.apply(object, args.concat($A(arguments))); 
	  } 
} 

function CTable(id,rows,target_obj,arr){
	this.tbl=typeof(id)=="string"?document.getElementById(id):id; 
	this.add_obj=typeof(target_obj)=="string"?document.getElementById(target_obj):target_obj;
	this.num = 0;
	this.arr = typeof(arr)=="object"?arr:["input","select","textarea","img","lable"];
	if (rows && /^\d+$/.test(rows)){
		this.addrows(rows);
	}
} 

CTable.prototype={
	addrows:function(n){
		new Array(n).each(this.add.bind(this)) 
	}, 
	add:function(){
		var self=this;
		this.num++;
		var tr = self.tbl.insertRow(-1);
		var content = '';
		var innerhtml = this.add_obj.innerHTML;
		if (innerhtml){
			content = innerhtml.replace(/{n}/g,this.num).replace(/keyNum/g,this.num);
		}
		tr.id=this.num;
		$(tr).html(content);
	},
	addData:function(data){
		var self=this;
		this.num++;
		var tr = self.tbl.insertRow(-1);
		var content = '';
		var innerhtml = this.add_obj.innerHTML;
		if (innerhtml){
			content = innerhtml;
			for(var i=0; i<data.length; i++)
			{
				var reg=new RegExp("{"+data[i].key+"}","g");
				content = content.replace(reg,data[i].value);
			}
			content = content.replace(/{n}/g,this.num).replace(/keyNum/g,this.num);
		}
		tr.id=this.num;
		$(tr).html(content);
	},
	del:function(obj_click){
		var self=this;
		var tr_click = obj_click.parentNode.parentNode;
		$A(self.tbl.rows).each(function(tr){
			if (tr == tr_click) tr.parentNode.removeChild(tr);
		});
	}, 
	up:function(obj_click){
		var self=this;
		var tr_click = obj_click.parentNode.parentNode;
		var upOne=function(tr){
			if (tr.rowIndex>1){
				self.swapValue(tr,self.tbl.rows[tr.rowIndex-2]);
			} 
		}
		$A(self.tbl.rows).each(function(tr){
			if (tr == tr_click) upOne(tr);
		});
	}, 
	down:function(obj_click){
		var self=this;
		var tr_click = obj_click.parentNode.parentNode;
		var downOne=function(tr){       
			if (tr.rowIndex<self.tbl.rows.length)  { 
				self.swapValue(tr,self.tbl.rows[tr.rowIndex]); 
			} 
		}
		$A(self.tbl.rows).each(function(tr){
			if (tr == tr_click) downOne(tr);
		});
	}, 
	highlight:function(){
		var self=this;
		var tr_click = obj_click.parentNode.parentNode;
		self.restoreBgColor();
	}, 
	swapValue:function(tr1,tr2){
		var arr = this.arr;
		var a,b,c;
		for(i = 0; i < arr.length; i++){
			var a = tr1.getElementsByTagName(arr[i]);
			var b = tr2.getElementsByTagName(arr[i]);
			for(j = 0; j < a.length; j++){
				switch(arr[i]){
					case 'input':
				　　　　if(a[j].type == "radio"){
				　　　　　　c = a[j].checked;
							b[j].checked==true?a[j].checked=true:a[j].checked=false;
							c==true?b[j].checked=true:b[j].checked=false;
						}else if(a[j].type == "checkbox"){
				　　　　　　c = a[j].checked;
							b[j].checked==true?a[j].checked=true:a[j].checked=false;
							c==true?b[j].checked=true:b[j].checked=false;
						}else{
				　　　　　　c = a[j].value;
							a[j].value=b[j].value;
							b[j].value=c;
						}
						break;
					case 'select':
			　　　　　　c = a[j].value;
						a[j].value=b[j].value;
						b[j].value=c;
						break;
					case 'textarea':
					　　c = a[j].value;
						a[j].value=b[j].value;
						b[j].value=c;
						break;
					case 'img':
			　　　　　　c = a[j].src;
						a[j].src=b[j].src;
						b[j].src = c;
						break;
					case 'lable':
			　　　　　　c = a[j].innerText;
						a[j].innerText=b[j].innerText;
						b[j].innerText = c;
						break;
				}
			}
		
		}
		var tr1_index=tr1.id;
		var tr2_index=tr2.id;
		var editor_c = document.getElementById('ueditor'+tr1_index);
		if(editor_c){
			var ueditorA = UE.getEditor('ueditor'+tr1_index);
			var ueditorB = UE.getEditor('ueditor'+tr2_index);
			if (ueditorA&&ueditorB){
				var bodyA = ueditorA.getContent();
				var bodyB = ueditorB.getContent();
				ueditorA.setContent(bodyB);
				ueditorB.setContent(bodyA);
			}
		}
	},
	restoreBgColor:function(tr){         
		tr.style.backgroundColor="#ffffff";
	}, 
	setBgColor:function(tr){ 
		tr.style.backgroundColor="#c0c0c0";
	} 
}
function xz(obj_id,id){
	switch(id){
		case 0:
			$.each( $("."+obj_id), function(i, n){
			  $(n).hide();
			});
			break;
		case 1:
			$.each( $("."+obj_id), function(i, n){
			  $(n).show();
			});
			break;
	}
}