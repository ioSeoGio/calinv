$(function () {
    const trigger = $('[data-bs-toggle="tooltip"]');
    trigger.tooltip({
        placement: 'auto',
    });

    const $tooltipTrigger = $('.click-tooltip');
    $tooltipTrigger.tooltip({
        trigger: 'click',
        placement: 'auto',
    });

    $tooltipTrigger.on('click', function () {
        const $this = $(this);
        setTimeout(() => {
            $this.tooltip('hide');
        }, 1000);
    });
});
