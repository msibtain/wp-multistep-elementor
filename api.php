<?php
require 'stripe-php/vendor/autoload.php';

class ilabAPI
{
    private string $stripe_key;
    private string $stripe_secret;

    function __construct()
    {
        $this->stripe_key       = get_option("ilab_stripe_key");
        $this->stripe_secret    = get_option("ilab_stripe_secret");

        add_action('rest_api_init', function () {
            register_rest_route('stripe', '/pi/', array(
                'methods' => 'POST',
                'callback' => [$this, 'stripe_payment_intent'],
                'permission_callback' => '__return_true'
            ));
        });

        add_action('rest_api_init', function () {
            register_rest_route('customer', '/create/', array(
                'methods' => 'POST',
                'callback' => [$this, 'wp_create_user'],
                'permission_callback' => '__return_true'
            ));
        });

        add_action('rest_api_init', function () {
            register_rest_route('customer', '/createv2/', array(
                'methods' => 'POST',
                'callback' => [$this, 'wp_create_user_v2'],
                'permission_callback' => '__return_true'
            ));
        });
    }

    function getStripeKey(): string
    {
        return $this->stripe_key;
    }

    function getStripeSecret(): string
    {
        return $this->stripe_secret;
    }

    function stripe_payment_intent( $data )
    {
        $input = file_get_contents("php://input");
        //parse_str($input, $data);
        $data = json_decode($input, true);

        \Stripe\Stripe::setApiKey( $this->stripe_secret  );

        $customerData = [
            'name' => @$data['fname'] . ' ' . @$data['lname'],
            'email' => $data['email']
        ];
        
        // Create a new customer in Stripe (optional)
        $customer = \Stripe\Customer::create([
            'name' => $customerData['name'],
            'email' => $customerData['email']
        ]);
        

        try {
            $paymentIntent = \Stripe\PaymentIntent::create([
                'amount' => $data['amount'] * 100,
                'currency' => $data['currency'],
                'customer' => $customer->id,
                'automatic_payment_methods' => [
                    'enabled' => 'true',
                ],
                'payment_method_options' => [
                    'card' => [
                        'setup_future_usage' => 'off_session',
                    ],
                ],
            ]);
        
            $output = [
                'clientSecret' => $paymentIntent->client_secret,
                'customer_id' => $paymentIntent->customer
            ];
        
            // Save the customer ID in your database if needed
            // $customer_id = $paymentIntent->customer;
            // Save $customer_id in your database.
            return new WP_REST_Response($output, 200);

        } catch (Error $e) {
            return new WP_REST_Response(['error' => $e->getMessage()], 404);
        }
    }

    function wp_create_user()
    {
        $input = file_get_contents("php://input");
        $data = json_decode($input, true);

        $fname          = $data['fname'];
        $lname          = $data['lname'];
        $email          = $data['email'];
        $password       = $data['password'];
        $customer_id    = $data['customer_id'];
        $payment_method = $data['payment_method'];

        if (email_exists($email)) 
        {
            # update password and customer id;
            $objUser = get_user_by("email", $email);

            wp_set_password( $password, $objUser->ID );
            update_user_meta($objUser->ID, "stripe_customer_id", $customer_id);
            update_user_meta($objUser->ID, "stripe_payment_method", $payment_method);

            //$this->updateCustomerPaymentMethod( $customer_id, $payment_method );

            return new WP_REST_Response([
                'success' => true, 
                'message' => 'User added',
                'success_url' => get_permalink(1292)
            ], 200);   

        }
        else
        {
            $userdata = array(
                
                'user_pass'				=> $password,
                'user_login' 			=> $email,
                'user_nicename' 		=> $fname,
                'user_email' 			=> $email,
                'display_name' 			=> $fname,
                'nickname' 				=> $fname, 
                'first_name' 			=> $fname,
                'last_name' 			=> $lname,
                'description' 			=> ''
            );
            $user_id = wp_insert_user( $userdata );

            //$user_id = wp_create_user($email, $password, $email);

            
            if (is_wp_error($user_id)) 
            {
                return new WP_REST_Response(['success' => false, 'message' => $user_id->get_error_message()], 200);   
            } 
            else 
            {
                update_user_meta($user_id, "stripe_customer_id", $customer_id);
                update_user_meta($user_id, "stripe_payment_method", $payment_method);

                //$this->updateCustomerPaymentMethod( $customer_id, $payment_method );

                return new WP_REST_Response([
                    'success' => true, 
                    'message' => 'User added',
                    'success_url' => get_permalink(1292)
                ], 200);   
            }
        }
        
    }

    function wp_create_user_v2()
    {
        $input = file_get_contents("php://input");
        $data = json_decode($input, true);

        $fname          = $data['fname'];
        $lname          = $data['lname'];
        $email          = $data['email'];
        $password       = $data['password'];
        $payment_method = $data['payment_method'];

        $customerData = [
            'name' => $fname . ' ' . $lname,
            'email' => $email
        ];

        $customer_id = $this->createStripeCustomer( $customerData );
        $this->createStripePaymentIntent( 1, "USD", $customer_id );

        if (email_exists($email)) 
        {
            # update password and customer id;
            $objUser = get_user_by("email", $email);

            wp_set_password( $password, $objUser->ID );
            update_user_meta($objUser->ID, "stripe_customer_id", $customer_id);

            $this->updateCustomerPaymentMethod( $customer_id, $payment_method );

            return new WP_REST_Response([
                'success' => true, 
                'message' => 'User added',
                'success_url' => get_permalink(1292)
            ], 200);   

        }
        else
        {
            $userdata = array(
                
                'user_pass'				=> $password,
                'user_login' 			=> $email,
                'user_nicename' 		=> $fname,
                'user_email' 			=> $email,
                'display_name' 			=> $fname,
                'nickname' 				=> $fname, 
                'first_name' 			=> $fname,
                'last_name' 			=> $lname,
                'description' 			=> ''
            );
            $user_id = wp_insert_user( $userdata );

            //$user_id = wp_create_user($email, $password, $email);

            
            if (is_wp_error($user_id)) 
            {
                return new WP_REST_Response(['success' => false, 'message' => $user_id->get_error_message()], 200);   
            } 
            else 
            {
                update_user_meta($user_id, "stripe_customer_id", $customer_id);

                $this->updateCustomerPaymentMethod( $customer_id, $payment_method );

                return new WP_REST_Response([
                    'success' => true, 
                    'message' => 'User added',
                    'success_url' => get_permalink(1292)
                ], 200);   
            }
        }
        
    }

    function updateCustomerPaymentMethod( $customer_id, $payment_method_id )
    {
        \Stripe\Stripe::setApiKey( $this->stripe_secret  );

        try {
            
            $paymentMethod = \Stripe\PaymentMethod::retrieve($payment_method_id);
            $paymentMethod->attach(['customer' => $customer_id]);

            
            \Stripe\Customer::update($customer_id, [
                'invoice_settings' => ['default_payment_method' => $payment_method_id]
            ]);

            return true;
            
        } catch (Exception $e) {
            return false;
        }
    }

    function stripe_customer_charge( $amount, $customer_id, $payment_method_id )
    {
        \Stripe\Stripe::setApiKey( $this->stripe_secret  );

        

        try {

            $paymentIntent = \Stripe\PaymentIntent::create([
                'amount' => $amount * 100,
                'currency' => "usd",
                'customer' => $customer_id,
                'payment_method' => $payment_method_id,
                'off_session' => true,
                'confirm' => true,
                'automatic_payment_methods' => [
                    'enabled' => 'true',
                ],
                
            ]);
        
            return "Charge successful!";
        } catch (\Stripe\Exception\ApiErrorException $e) {
            return 'Error: ' . $e->getMessage();
        }
    }

    function stripe_customer_charge_old( $amount, $customer_id )
    {
        \Stripe\Stripe::setApiKey( $this->stripe_secret  );

        try {
            $charge = \Stripe\Charge::create([
                'amount' => $amount * 100,
                'currency' => 'usd',
                'customer' => $customer_id,
                'description' => 'Charge for customer from WP Admin',
            ]);
        
            return "Charge successful! Charge ID: " . $charge->id;
        } catch (\Stripe\Exception\ApiErrorException $e) {
            return 'Error: ' . $e->getMessage();
        }
    }

    function createStripeCustomer( $customerData )
    {
        # create customer in Stripe;
        \Stripe\Stripe::setApiKey( $this->stripe_secret  );
                
        // Create a new customer in Stripe (optional)
        $customer = \Stripe\Customer::create([
            'name' => $customerData['name'],
            'email' => $customerData['email']
        ]);

        return $customer->id;
    }

    function createStripePaymentIntent( $amount, $currency, $customer_id )
    {
        $paymentIntent = \Stripe\PaymentIntent::create([
            'amount' => $amount * 100,
            'currency' => $currency,
            'customer' => $customer_id
        ]);
    }
}

global $ilabAPI;
$ilabAPI = new ilabAPI();

if (!function_exists('p_r')){function p_r($s){echo "<pre>";print_r($s);echo "</pre>";}}