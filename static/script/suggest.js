// 用于js自动加载
var LoadJs_suggest = true;

jQuery.fn.extend({
    showSuggest: function (wrapper, url, htmlCall, clickCall) {
        if (this.length <= 0) {
            return false;
        }

        var main = this;
        return main.attr('autocomplete', 'off').extend({
            // 搜索建议选项
            suggestItem: null,
            // 当前选中的搜索建议索引值
            currentIndex: null,
            currentItem: null,
            activeColor: '#F6F6F6',
            itemColor: '#FFFFFF',
            // 搜索建议
            showsuggest: function (keyword) {
                jQuery.getJSON(url + keyword, function (data) {
                    if (data.length > 0) {
                        var html = '';
                        jQuery.each(data, function (item) {
                            html += htmlCall(data[item]);
                        });
                        main.suggestItem = wrapper.css({
                            left: main.position().left + 'px'
                        }).html(html).show().find('li.associateCell');
                        main.suggestItem.bind({
                            click: function (e) {
                                clickCall(this);
                                wrapper.hide();
                            }
                        }).hover(
                            function (e) {
                                this.style.backgroundColor = main.activeColor;
                            },
                            function (e) {
                                this.style.backgroundColor = main.itemColor;
                            }
                        );
                    } else {
                        wrapper.hide();
                    }
                }, 'json')
            },
            // 选中上一个搜索建议
            selectPrev: function () {
                if (main.currentIndex == null || main.currentIndex <= 0) {
                    main.currentIndex = main.suggestItem.length;
                };
                main.currentIndex -= 1;
                main.focusCurrent();
            },
            // 选中上一个搜索建议
            selectNext: function () {
                if (main.currentIndex == null) {
                    main.currentIndex = -1;
                };
                main.currentIndex += 1;
                main.focusCurrent();
            },
            // 当前元素获取焦点
            focusCurrent: function () {
                main.currentItem = jQuery(main.suggestItem.get(main.currentIndex));
                main.val(main.currentItem.text());
                main.currentIndex %= main.suggestItem.length;
                main.suggestItem.css('backgroundColor', main.itemColor);
                main.currentItem.css('backgroundColor', main.activeColor);
            },
            init: function () {
                // 当当前元素失去焦点时，隐藏建议项
                $('body').bind({
                    click: function (e) {
                        wrapper.hide();
                    }
                });
                return main;
            }
        }).bind({
            keydown: function (e) {
                if (e.keyCode == 13) {
                    if (main.currentIndex != null) {
                        main.currentItem.trigger('click');
                    }
                    // 阻止回车提交事件
                    e.preventDefault();
                    return false;
                }
            },
            keyup: function (e) {
                switch (e.keyCode) {
                    // 向下键
                    case 40:
                        main.selectNext();
                        break;
                        // 向上键
                    case 38:
                        main.selectPrev();
                        break;
                        // 回车事件
                    case 13:
                        break;
                    default:
                        var e_value = jQuery.trim(e.target.value);
                        if (e_value.length > 0) {
                            main.showsuggest(e_value);
                        }
                }

            }
        }).init();
    },

    pinYinshowSuggest: function (wrapper, url, htmlCall, clickCall) {
        if (typeof (LoadJs_pinyinEngine) == 'undefined') {
            loadjscssfile(commonParams.jcDevPath + '/static/script/pinyinEngine.js', 'js');
            //  loadjscssfile(commonParams.jcDevPath + '/shequPage/common/pinyinEngine.js', 'js');
        }
        var main = this;
        if (this.length <= 0) {
            return false;
        }

        jQuery.getJSON(url, function (data) {
            var userData = null;
            if (data.data.length > 0) {
                var engine = pinyinEngine(); // 初始化搜索引擎
                var userData = data.data;
                // 填充数据
                var tmplCache
                var loadSchool = function (callback) {
                    //engine.resetCache();// 重置查询索引缓存
                    var txt = [];
                    for (var i in userData) {
                        // 建立拼音查询索引缓存
                        engine.setCache([userData[i].user_realname], userData[i]);
                    };
                };

                loadSchool();

                var timer;
                var $input = main.attr('autocomplete', 'off')[0];
                var $unisContent = wrapper[0];
                var oldVal = $input.value;

                return main.attr('autocomplete', 'off').extend({
                    // 搜索建议选项
                    suggestItem: null,
                    // 当前选中的搜索建议索引值
                    currentIndex: null,
                    currentItem: null,
                    activeColor: '#F6F6F6',
                    itemColor: '#FFFFFF',
                    // 搜索建议
                    showsuggest: function (keyword) {
                        var that = this;
                        var val = $input.value;
                        //if (val === oldVal) return;
                        oldVal = $input.value;

                        clearTimeout(timer);
                        timer = setTimeout(function () {
                            engine.search(keyword, function (data) {
                                if (data.length > 0) {
                                    var html = '';
                                    var dataNum = data.length > 10 ? 10 : data.length;
                                    for (var j = 0; j < dataNum; j++) {
                                        html += htmlCall(data[j].content);
                                    }
                                    main.suggestItem = wrapper.html(html).show().find('li.associateCell');
                                    main.suggestItem.bind({
                                        click: function (e) {
                                            e.preventDefault();
                                            clickCall(this);
                                            wrapper.hide();
                                        }
                                    }).hover(
                                        function (e) {
                                            this.style.backgroundColor = main.activeColor;
                                        },
                                        function (e) {
                                            this.style.backgroundColor = main.itemColor;
                                        }
                                    );
                                } else {
                                    wrapper.hide();
                                }
                            });

                        }, 40); // 延时可以减小查询频率
                    },
                    // 选中上一个搜索建议
                    selectPrev: function () {
                        if (main.currentIndex == null || main.currentIndex <= 0) {
                            main.currentIndex = main.suggestItem.length;
                        };
                        main.currentIndex -= 1;
                        main.focusCurrent();
                    },
                    // 选中上一个搜索建议
                    selectNext: function () {
                        if (main.currentIndex == null) {
                            main.currentIndex = -1;
                        };
                        main.currentIndex += 1;
                        main.focusCurrent();
                    },
                    // 当前元素获取焦点
                    focusCurrent: function () {
                        main.currentItem = jQuery(main.suggestItem.get(main.currentIndex));
                        main.val(main.currentItem.text());
                        main.currentIndex %= main.suggestItem.length;
                        main.suggestItem.css('backgroundColor', main.itemColor);
                        main.currentItem.css('backgroundColor', main.activeColor);
                    },
                    init: function () {
                        // 当当前元素失去焦点时，隐藏建议项
                        $('body').bind({
                            click: function (e) {
                                wrapper.hide();
                            }
                        });
                        return main;
                    }
                }).bind({
                    keydown: function (e) {
                        if (e.keyCode == 13) {
                            if (main.currentIndex != null) {
                                main.currentItem.trigger('click');
                            }
                            // 阻止回车提交事件
                            e.preventDefault();
                            return false;
                        }
                    },
                    keyup: function (e) {
                        switch (e.keyCode) {
                            // 向下键
                            case 40:
                                main.selectNext();
                                break;
                                // 向上键
                            case 38:
                                main.selectPrev();
                                break;
                                // 回车事件
                            case 13:
                                break;
                            default:
                                var e_value = jQuery.trim(e.target.value);
                                if (e_value.length > 0) {
                                    main.showsuggest(e_value);
                                }
                        }

                    }
                }).init();

            } else {
                wrapper.hide();
            }
        }, 'json');

    }
})