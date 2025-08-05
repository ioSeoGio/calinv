$(function() {
    $('body').on("click", "#theme-switcher", function() {
        $.cookie('darkTheme', $.cookie('darkTheme') == 1 ? 0 : 1);
        window.location.reload();
    });

    const observer = new MutationObserver(function(mutations) {
        mutations.forEach(function(mutation) {
            if (mutation.addedNodes.length) {
                $(mutation.addedNodes).each(function() {
                    if ($(this).is('.redactor-toolbar, .redactor-editor, .redactor-box')) {
                        if ($.cookie('darkTheme') == '1') {
                            $(this).addClass('dark');
                        }
                    }
                });
            }
        });
    });

    // Наблюдаем за изменениями в DOM
    observer.observe(document.body, {
        childList: true,
        subtree: true
    });
})