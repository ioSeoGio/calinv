
// type = error || success
function flashMessage(type, msg)
{
    var printFlash = true;

    if (printFlash) {

        let classes = "alert";

        switch(type) {
            case 'success':
                classes = 'alert alert-success';
                break;
            case 'error':
                classes = 'alert alert-error';
                break;
            default:
                classes = 'alert';
                break;
        }

        if(+$(".alert-wrapper").length)
            $(".alert-wrapper").append(`
                <div class="${classes}">
                    <div class='alert-message'>${msg}</div>
                    <div class='alert-point'><p>+</p></div>
                </div>
            `);
        else
            $(".wrap").append(`
                <div class='alert-wrapper'>
                    <div class="${classes}">
                        <div class='alert-message'>${msg}</div>
                        <div class='alert-point'><p>+</p></div>
                    </div>
                </div>
            `);

        // // hide notification
        $('body').on("click", ".alert-point", function bar() {
            $(this).closest(".alert").fadeOut('slow');
        });

        $(".alert").each((index, item) => {
            setTimeout(function(){
                $(item).fadeOut(1000)
            }, (index + 1) * 1000 )
        });
    }
}

// In the begin of page loading
$(function() {
    $('body').on('click', '.alert-point', function() {
        $(this)
            .closest('.alert')
            .addClass('fade-out')
            .on('animationend', function() {
                $(this).remove();
            });
    });

    (function IIFE(){
        setTimeout(function(){
            $(".alert").each((index, item) => {
                setTimeout(function(){
                    $(item)
                        .css("display", "flex")
                        .addClass('fade-in');
                }, (index) * 300)
            });
        }, 300);

        setTimeout(function(){
            $(".alert:not(.alert-error)")
                .each((index, item) => {
                    setTimeout(function(){
                        $(item)
                            .addClass('fade-out')
                            .on('animationend', function() {
                                $(this).hide(); // remove() или hide() если не нужно удалять
                            });
                    }, (index + 1) * 4000)
                });
        }, 300);

        setTimeout(function(){
            $(".alert.alert-error")
                .each((index, item) => {
                    setTimeout(function(){
                        $(item)
                            .addClass('fade-out')
                            .on('animationend', function() {
                                $(this).hide(); // remove() или hide() если не нужно удалять
                            });
                    }, (index + 1) * 8000)
                });
        }, 300);
    })()
});
