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
                'customer' => $customer->id
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

        if (email_exists($email)) 
        {
            # update password and customer id;
            $objUser = get_user_by("email", $email);

            wp_set_password( $password, $objUser->ID );
            update_user_meta($objUser->ID, "stripe_customer_id", $customer_id);

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

                return new WP_REST_Response([
                    'success' => true, 
                    'message' => 'User added',
                    'success_url' => get_permalink(1292)
                ], 200);   
            }
        }
        
    }
}

global $ilabAPI;
$ilabAPI = new ilabAPI();

if (!function_exists('p_r')){function p_r($s){echo "<pre>";print_r($s);echo "</pre>";}}