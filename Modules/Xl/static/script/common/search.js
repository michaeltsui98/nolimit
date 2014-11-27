jQuery(function ($) {

    // 公共搜索下拉菜单独有事件绑定
    var publicSearch = $('#public_search')
    publicSearch.find('li.dropdownCell').bind({
        click: function (e) {
            publicSearch.find('form').attr('action', '/Search/' + $(this).attr('search_type'));
        }
    }).eq(0).trigger('click');
    //初始化下拉列表
    $.topDropdownSelect('customDropdownStyle2', 'customDropdown1MouseOver');
    $('div.seaNav div').not('div.active').hover(
        function () {
            $(this).addClass('active').find('a').addClass('whiteA');
        },
        function () {
            $(this).removeClass('active').find('a').removeClass('whiteA');
        }
    );
    $('#search').clearText();
    // 搜索
    $('#search').initSearch();
});

jQuery.fn.extend({
    initSearch: function () {
        if (this.length <= 0) {
            return false;
        }
        var id = this.attr('id');
        var main = this;
        main.extend({
            // 搜索建议容器
            suggest: jQuery('#suggest-' + id),
            // 搜索建议选项
            suggestItem: null,
            // 当前选中的搜索建议索引值
            currentIndex: null,
            currentItem: null,
            activeColor: '#F6F6F6',
            itemColor: '#FFFFFF',
            // 搜索建议
            showsuggest: function (keyword) {
                jQuery.getJSON('/Search/suggest', { 'keyword': keyword }, function (data) {
                    if (data.length > 0) {
                        var html = '';
                        jQuery.each(data, function (item) {
                            html += '<li class="associateCell"><a href="javascript:;" title="">' + data[item] + '</a></li>';
                        });
                        main.suggestItem = main.suggest.html(html).show().find('li.associateCell');
                        main.suggestItem.bind({
                            click: function (e) {
                                // 设置输入框的值为选中的值
                                var value = jQuery(this).text();
                                main.val(value);
                                main.search(value);

                                // 隐藏预搜索
                                //main.preSearch(value);

                                // 隐藏搜索建议
                                main.suggest.hide();
                            }
                        }).hover(
                            function (e) {
                                this.style.backgroundColor = main.activeColor;
                            },
                            function (e) {
                                this.style.backgroundColor = main.itemColor;
                            }
                        );
                    };
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
                }
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
            // 预搜索
            preSearch: function (keyword) {
                jQuery.get(window.location.href, { 'keyword': keyword }, function (html) {
                    jQuery("div.SQserpCon").html(html);
                }, 'html');
            },
            search: function (keyword) {
                if (keyword.length > 1) {
                    var url = main.parent().attr('action');
                    window.location.href = url + '?keyword=' + keyword;
                }
            }
        }).bind({
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
                        // 回车键
                    case 13:
                        // 阻止回车提交事件
                        e.preventDefault();
                        if (main.currentIndex != null) {
                            main.search(main.currentItem.text());
                        }
                        break;
                    default:
                        var e_value = jQuery.trim(e.target.value);
                        if (e_value.length > 0) {
                            main.showsuggest(e_value);
                        }
                }

            }
        }).parent().bind({
            submit: function (e) {
                // 如果搜索的关键字长度过少则不提交
                if (main.val().length < 2) {
                    return false;
                }
            }
        });

        $('body').bind({
            click: function (e) {
                main.suggest.hide();
            }
        })
    }
})