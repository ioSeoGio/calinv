$(document).ready(function() {
    $(".alert").each(function (index) {
        $(this).animate({opacity: 1.0}, (index+1)*3000).fadeOut();
    })
});
