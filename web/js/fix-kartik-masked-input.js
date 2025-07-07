window.onload = function() {
    $(document).on('afterValidateAttribute', function (event, attribute, messages) {
        var displayInputId = '#' + attribute.id + '-disp'; // ID видимого поля ввода
        var displayInput = $(displayInputId);

        if (messages.length) {
            // Если есть ошибки валидации, добавляем класс is-invalid
            displayInput.addClass('is-invalid');
            // (Опционально) Можно также отобразить сообщение об ошибке
            var errorContainer = displayInput.closest('.form-group').find('.invalid-feedback');
            if (errorContainer.length) {
                errorContainer.text(messages.join(', ')).show();
            }
        } else {
            // Если ошибок нет, убираем класс is-invalid
            displayInput.removeClass('is-invalid');
            // Скрываем сообщение об ошибке
            var errorContainer = displayInput.closest('.form-group').find('.invalid-feedback');
            if (errorContainer.length) {
                errorContainer.hide();
            }
        }
    });
};