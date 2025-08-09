$(function() {
    const changeIssuerRequest = function (url, ratingId, issuerId) {
        $.ajax({
            url: url,
            method: "POST",
            data: {ratingId: ratingId, issuerId: issuerId},
        }).done(result => {
            flashMessage('success', 'Эмитент изменен успешно.')
        }).fail(err => {
            flashMessage('error', 'Ошибка при изменении эмитента, попробуйте позже.')
        });
    };

    $('.rating-select-issuer-dropdown').change('change', function () {
        changeIssuerRequest(ajaxRatingChangeIssuerUrl, $(this).data('ratingId'), $(this).val())
    });
});