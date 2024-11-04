jQuery(document).ready(function($) {
    $("input[type=radio]").click(function() {
        $(this).closest('.e-form__step').find('.e-form__buttons__wrapper__button-next').click();
    });
});