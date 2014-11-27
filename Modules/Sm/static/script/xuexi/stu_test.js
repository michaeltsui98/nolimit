$(document).ready(function () {
    $('.u-puBtn').packUpAndDown();
    $('.u-ufBtn').packUpAndDown();
    $('.tab2Menu').tab2Menu(function () {
        alert('tab2');
    });
    $('.u-closeBtn').die().live('click', function () {
        $(this).parents('.m-tips').css('display', 'none');
    });
});