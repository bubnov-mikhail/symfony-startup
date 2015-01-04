/* 
 * Main JS
 */

var initScrolUp = function() {
    $.scrollUp({
        topDistance: '300',
        topSpeed: 300,
        animation: 'fade',
        animationInSpeed: 200,
        animationOutSpeed: 200,
        activeOverlay: false,
        scrollImg: { active: true, type: 'background', src: '/img/top.png' }
    });
};

var initDatePicker = function(className) {
    if($(className).hasClass('dropdown-menu'))
    {
        return;
    }
    $(className).datepicker({
        format: 'dd.mm.yyyy',
        autoclose: true,
        todayBtn: true,
        todayHighlight: true,
        language: 'ru'
    });
};

var fixModalReloadData = function() {
    $('.modal').each(function(){
        $(this).on('hidden', function() {
            $(this).removeData('modal');
        });
    });
}

// Loading scripts for pages
$(document).ready(function(){
    initDatePicker('.datepicker');
    initScrolUp();
    fixModalReloadData();
    $('a:not(#scrollUp)[title], .tooltip-able[title], [tooltip-able]').tipTip({maxWidth: 800, defaultPosition: "right"});
    $("a[href^='http://']").attr("target","_blank");
});
