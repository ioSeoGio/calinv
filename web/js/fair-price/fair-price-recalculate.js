$(function() {
    $(".fair-price-slider").on("slide", function(slideEvt) {
        $(this).siblings(".current-value-test").text(slideEvt.value + ' р.');
    });

    $("#recalculate-fair-price-btn").on("click", function() {
        let results = [];
        $(".fair-price-slider").each(function() {
            let value = $(this).val();
            let shareAmount = $(this).data('share-amount');
            results.push(value * shareAmount);
        });
        let newCapitalization = results.reduce((a, b) => a + b, 0)
        console.log(results);
        console.log(newCapitalization);

        const changeIssuerRequest = function (url) {
            $.ajax({
                url: url,
                method: "POST",
            }).done(result => {
                $("#coefficients-container").html(result);
                flashMessage('success', 'Успешно перерасчитаны коэффиценты: P/E, P/B, P/OCF, P/FCF, P/S')
            }).fail(err => {
                flashMessage('error', 'Ошибка при перерасчете коэффициентов эмитента, попробуйте позже.')
            });
        };

        changeIssuerRequest(recalculateUrl + '&capitalization=' + newCapitalization);
    });
});