$(function() {
    $('body').on("click", "#theme-switcher", function() {
        $.cookie('darkTheme', $.cookie('darkTheme') == 1 ? 0 : 1);
        window.location.reload();
    })
})
