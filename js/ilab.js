jQuery(document).ready(function($) {

    /* when radio option is chosen, user will be redirected to Next step automatically */
    $("input[type=radio]").click(function() {
        $(this).closest('.e-form__step').find('.e-form__buttons__wrapper__button-next').click();
    });

    /* when user will click the div, radio button in it will be selected automatically */
    $(".elementor-field-option").click(function() {
        $(this).find("input[type=radio]").prop("checked", true);
        $(this).closest('.e-form__step').find('.e-form__buttons__wrapper__button-next').click();

        $(this).closest('.e-form__step').find('.elementor-field-option').removeClass('active');
        $(this).addClass('active');
    });

    createStripeForm();

});

function createStripeForm()
{
    const stripe = Stripe( stripe_config.stripe_key );
    const parentDiv = document.getElementById('payment-form');
    const form = parentDiv.querySelector('form');

    form.addEventListener('submit', async (event) => {
        event.preventDefault();
        console.log('form submitted...');
    });

}