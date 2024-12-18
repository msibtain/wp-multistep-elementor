jQuery(document).ready(function($) {
    createStripeForm();
});

function createStripeFormV2()
{
    var fname = document.getElementById("fname").value;
    var lname = document.getElementById("lname").value;
    var email = document.getElementById("email").value;
    var password = document.getElementById("password").value;
    var customer_id = '';

    const stripe = Stripe( stripe_config.stripe_key );
    const elements = stripe.elements();
    const cardElement = elements.create('card');
    cardElement.mount('#card-element');

    // Handle form submission
    document.getElementById('payment-form').addEventListener('submit', async (event) => {
        event.preventDefault();

        const { paymentMethod, error } = await stripe.createPaymentMethod({
            type: "card",
            card: cardElement,
        });

        if (error) 
        {
            console.error(error);
            // Handle error in your UI
        } 
        else 
        {
            //console.log( paymentMethod );
            createStripe_WPCustomer( fname, lname, email, password, paymentMethod.id );
        }

    });
}

function createStripeForm()
{
    const stripe = Stripe( stripe_config.stripe_key );
    var fname = document.getElementById("fname").value;
    var lname = document.getElementById("lname").value;
    var email = document.getElementById("email").value;
    var password = document.getElementById("password").value;
    var customer_id = '';

    // Fetch the PaymentIntent client secret from the server
    fetch(stripe_config.wp_json + '/stripe/pi', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify({
            "amount": 1,
            "currency": "USD",
            "fname": fname,
            "lname": lname,
            "email": email
            })
        })
        .then(response => response.json())
        .then(data => {
            const clientSecret = data.clientSecret;
            const elements = stripe.elements();
            const cardElement = elements.create('card');
            cardElement.mount('#card-element');

            customer_id = data.customer_id;

            // Handle form submission
            document.getElementById('payment-form').addEventListener('submit', async (event) => {
                event.preventDefault();

                const { paymentIntent, error } = await stripe.confirmCardPayment(clientSecret, {
                    payment_method: {
                        card: cardElement,
                    }
                });

                if (error) {
                    // Display error to the customer
                    console.error(error.message);
                    document.getElementById("card-errors").innerHTML = error.message;
                } else if (paymentIntent.status === 'succeeded') {
                    // Payment succeeded - send data to save_customer.php
                    saveCustomerId( customer_id, fname, lname, email, password, paymentIntent.payment_method );

                }
            });
        });
}

function saveCustomerId( customer_id, fname, lname, email, password, payment_method )
{
    fetch(stripe_config.wp_json + '/customer/create', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify({
            "customer_id": customer_id,
            "fname": fname,
            "lname": lname,
            "email": email,
            "password": password,
            "payment_method": payment_method
            })
        })
        .then(response => response.json())
        .then(data => {

            if (data.success === true)
            {
                window.location = data.success_url;
            }
            else
            {
                document.getElementById("card-errors").innerHTML = data.message;
            }

        });
}

function createStripe_WPCustomer( fname, lname, email, password, payment_method )
{
    fetch(stripe_config.wp_json + '/customer/createv2', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify({
            "fname": fname,
            "lname": lname,
            "email": email,
            "password": password,
            "payment_method": payment_method
            })
        })
        .then(response => response.json())
        .then(data => {

            if (data.success === true)
            {
                window.location = data.success_url;
            }
            else
            {
                document.getElementById("card-errors").innerHTML = data.message;
            }

        });
}