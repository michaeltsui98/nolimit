var LoadJs_circle = true;

// 解决IE6下不支持indexOf方法的问题
if (!Array.prototype.indexOf) {
    Array.prototype.indexOf = function (obj, start) {
        for (var i = (start || 0), j = this.length; i < j; i++) {
            if (this[i] === obj) { return i; }
        }
        return -1;
    }
}

jQuery(function ($) {

    if (typeof (LoadJs_suggest) == 'undefined') {
        loadjscssfile(commonParams.jcDevPath + '/static/script/suggest.js', 'js');
    }

    $('[name="selectUser"]').die().live({
        click: function (e) {
            var $this = $(this);
            var target = $this.data('target');
            var boxtype = $this.data('boxtype');
            var scope = $this.data('scope');
            $('#' + target).showUserCircle(boxtype, scope);
        }
    });
});

jQuery.extend({
    // 获取群组角色的角色图片
    getGroupAvatar: function (rule) {
        return commonParams.jcDevPath + commonParams.jcType + '/image/group-' + rule + '-40.gif';
    }
})

jQuery.fn.extend({
    /**
     * 将圈子结构数据转换成表单结构数据
     * @param source 待转换结构
     * @param replace 是否替换原有数据
     */
    circleItemToRecItem: function (source, replace) {
        var main = this;
        var inputHtml = '';
        source.each(function (i) {
            var $this = $(this);
            var targetid = $this.attr('data-targetid');
            var targetname = $this.data('targetname');
            var avatar = $this.data('avatar');

            // 如果是替换，或者没找到这个元素时才添加
            if (replace || main.find('#' + id).length <= 0) {
                //<a href="javascript:;" title="" target="" class="item">实验班同学 <em class="del"><em></em></em> </a>
                inputHtml += '<a class="item" data-avatar=' + avatar + ' data-targetid=' + targetid + ' data-targetname=' + targetname + '>' + targetname + '<input type="hidden" name="stuone" value="' + targetid + '" /><em class="del"><em></em></em></a>';
            }
        });
        if (replace) {
            return main.html(inputHtml).initRecItem();
        } else {
            return main.append(inputHtml).initRecItem();
        }
    },


    /**
     * @boxtype 当前弹出框的样式1 标识需要遮照， 0 或 undefined标识不需要遮照
     * @scope  圈子的应用类型，(letter, assignment),这两种获取的对象不一样，并且在选择接收对象的规则上也有差别
     */
    showUserCircle: function (boxtype, scope, callback) {

        var main = this;
        var action = 'getUserCircle';
        if (scope == 'assignment') {
            action = 'getAssignmentCircle';
        }
        //jc.dodoedu.com/xl/course/studentListByClass
        // 获取用户的圈子信息
        $.post(commonParams.jcDevPath + commonParams.jcType + '/course/studentListByCourse', 'course_id=' + $('.addImpression').data('course'), function (obj) {
            var circleHTML = '';
            var data = obj.data;
            // 用户侧边栏教育圈子信息
            for (var i = 0; i < data.length; i++) {
                circleHTML += '<span data-avatar=' + data[i].user_avatar_40 + ' data-targetid=' + data[i].user_id + ' data-targetname=' + data[i].user_realname + '><a>' + data[i].user_realname + '</a></span>';
            }
            // 圈子弹出框
            var CircleDialogHTML = '<div class="PopupsType" id="selectUser"><div class="hwContactL floatL"><div class="SQ_searchBox marginB10 u-r"><div class="customForm_searchTxtInput customForm_searchInputBoxGetFocus width182"><form action=""method="get"><input id="circle-search"name="circle_search"type="text"class="txtInput width144 floatL clearStyle"value="输入姓名搜索联系人"data-default="输入姓名搜索联系人"/><label class="floatR searchBtn"><input name="" type="button" class="icon_magnifier" value=""></label></form></div><ul id="circle-searchTips" class="associateWrap width154" style="display:none; background-color:#fff3e3;"></ul><div class="hwContactCon"><h3 class="active" data-target="circle-favorite"><span class="floatR"></span>学生列表</h3><div id="circle-favorite" class="hwContactListCon">' + circleHTML + '</div></div></div></div><div class="hwContactR floatR"><p><span class="floatR grayTxt">提示：点击下列人员可删除</span>已选择的联系人&nbsp;&nbsp;<a href="javascript:;" id="circle_clear_all" title="清空已选项" class="greenA a-secl">清空</a></p><div class="hwContactRcon"></div></div><div class="clearFloat"></div></div>';
            function userCircleEvent() {

                main.extend({
                    // 输入框
                    searchInput: $('#circle-search').attr('autocomplete', 'off').clearText().pinYinshowSuggest(
                        // 自动建议的包装项
                        jQuery('#circle-searchTips'),
                        // 自动建议的请求地址
                       commonParams.jcDevPath + commonParams.jcType + '/course/studentListByCourse?course_id=' + $('.addImpression').data('course'),
                        // 自动建议每一项的生成
                        function (data) {
                            return '<li class="suggest-item associateCell" data-target="personal" data-rule="student" data-classid="' + data.class_id + '" data-targetid="' + data.user_id + '" data-targetname="' + data.user_realname + '" data-avatar="' + data.user_avatar_64 + '"><a href="javacript:;">' + data.user_realname + '</a></li>';
                        },
                        // 当点击建议项时执行的函数
                        function (element) {
                            var $this = $(element);
                            var studentItem = main.renderItem($this.attr('data-targetid'), $this.data('targetname'), $this.data('avatar'));
                            if (studentItem) {
                                main.targetCon.append(studentItem);
                            }
                        }
                    ),

                    // 侧边栏
                    siderCon: $('div.hwContactCon'),
                    // 侧边栏切换
                    toggleItem: $('div.hwContactCon h3').bind({
                        click: function (e) {
                            main.toggleItem.toggleClass('active');
                            main.siderCon.find('div').slideToggle('slow');
                        }
                    }),

                    // 清空所有选择项
                    clearAll: $('#circle_clear_all').bind({
                        click: function (e) {
                            main.targetCon.find('div').remove();
                        }
                    }),

                    // 作为一个集合选择
                    selectGroup: $('#circle-favorite').find('span').bind({
                        click: function (e) {
                            var $this = $(this);
                            var targetname = $this.text();
                            var targetid = $this.attr('data-targetid');
                            var avatar = $this.data('avatar');// jQuery.getGroupAvatar($this.data('avatar'));
                            var html = main.renderItem(targetid, targetname, avatar);
                            main.targetCon.append(html);
                        }
                    }),

                    // 作为单个的个体选择
                    selectItem: $('#circle-edu').find('span').bind({
                        click: function (e) {
                            var $this = $(this);
                            var groupname = $this.data('targetname');
                            $.get('/Circle/getMembers', { 'target': $this.data('target'), 'rule': $this.data('rule'), 'targetid': $this.attr('data-targetid') }, function (data) {
                                var html = '';
                                for (id in data.members) {
                                    var member = data.members[id];
                                    // 检测用户的有效性
                                    if (member.user_id) {
                                        html += main.renderStudentItem(member.user_id, member.user_realname, member.user_avatar_64, groupname);
                                    }
                                }
                                main.targetCon.append(html);
                            }, 'json');
                        }
                    }),
                    // 组装一个对象
                    renderItem: function (targetid, targetname, avatar) {
                        var html = '';
                        // 给每一个选项做一个唯一编号，避免重复选择
                        if (main.targetCon.find('#' + targetid).length <= 0) {
                            html = '<div id="' + targetid + '" data-targetid="' + targetid + '" data-targetname="' + targetname + '" data-avatar="' + avatar + '"><span class="del"></span><img src="' + avatar + '" width="40" height="40" alt="" class="floatL" /><p class="grayTxt">' + targetname + '</p>';
                            html += '</div>';
                        }
                        return html;
                    },

                    // 选中的元素呈现框
                    targetCon: $('div.hwContactRcon'),

                    // 被选中的元素
                    targetItem: $('div.hwContactRcon div').die().live({
                        click: function (e) {
                            // 事件委托，只有点击的是删除元素时才进行删除
                            if ($(e.target).is('span')) {
                                $(this).remove();
                            }
                        },
                        mouseover: function (e) {
                            $(this).addClass('active');
                        },
                        mouseout: function (e) {
                            $(this).removeClass('active');
                        }
                    }),
                    // 确认
                    submitItem: $("#insidePopupsFunc input[name='confirm']").die().live("click", function (e) {

                        main.circleItemToRecItem(main.targetCon.find('div'), true);
                        if (boxtype == 1) {
                            styledialog.closeDialog();
                        } else {
                            styledialog.closeInsideDialog();
                        }
                    }),

                    // 初始化弹出框
                    initContent: function () {
                        // 获取已经被选中的元素
                        var selectedItem = '';

                        main.find('a.item').each(function (i) {
                            var $this = $(this);
                            var targetid = $this.attr('data-targetid');
                            var targetname = $this.data('targetname');
                            var avatar = $this.data('avatar');
                            selectedItem += main.renderItem(targetid, targetname, avatar);
                        });
                        main.targetCon.append(selectedItem);
                        return main;
                    }
                }).initContent().initRecItem();
            }
            // 如果需要遮照
            if (boxtype == 1) {
                styledialog.initDialogHTML({
                    title: "选择联系人",
                    content: CircleDialogHTML,
                    width: 710,
                    confirm: {
                        show: true,
                        name: "确定"
                    },
                    cancel: {
                        show: true,
                        name: "取消"
                    }
                });
                styledialog.initContent("选择联系人", CircleDialogHTML, userCircleEvent);
            } else {
                styledialog.initInsideDialogHTML({
                    title: "选择联系人",
                    content: CircleDialogHTML,
                    width: 710,
                    confirm: {
                        show: true,
                        name: "确定"
                    },
                    cancel: {
                        show: true,
                        name: "取消"
                    }
                });
                styledialog.initInsideDialogContent("选择联系人", CircleDialogHTML, userCircleEvent);
            }

        }, 'json');
    },

    // 接收者元素绑定事件
    initRecItem: function () {
        return this.bind({
            // 点击删除
            click: function (e) {
                var $target = $(e.target);
                // 事件委托，只有点击的元素使em才移除元素
                if ($target.is("em")) {
                    $target.parents('a').remove();
                }
            }
        }).find('span').bind({
            // 鼠标悬浮效果
            mouseover: function (e) {
                $(this).addClass('active');
            },
            mouseout: function (e) {
                $(this).removeClass('active');
            }
        });
    }
});