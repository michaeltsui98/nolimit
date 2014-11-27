jQuery(function($){
    var tabItems = $('ul.tab4Menu li.menuCell').bind({
        click: function(e) {
            e.preventDefault();
            var $this = $(this);
            tabItems.removeClass('tabMenuCurrent');
            $this.addClass('tabMenuCurrent');
            $('#info-top-list').load($this.attr('url'));
        }
    });
    tabItems.eq(0).trigger('click');
});