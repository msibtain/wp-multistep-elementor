<?php
/*
Plugin Name: Multistep form integration
Plugin URI: https://ilab.com
Description: WordPress Plugin for Multistep form integration and Stripe payments
Author: ilab
Version: 1.0.0
Author URI: ilab.com
*/

require_once __DIR__ . '/api.php';
require_once __DIR__ . '/admin.php';

class WP_Multistep_Elementor
{
    function __construct()
    {
        add_action('wp_enqueue_scripts', [$this, 'ilab_wp_scripts']);
        add_shortcode('wp_multistep_elementor', [$this, 'ilab_wp_multistep_elementor']);
        add_shortcode('stripe_payment_form', [$this, 'ilab_stripe_payment_form']);
    }

    function ilab_wp_scripts()
    {
        wp_register_style( 'ilab_css', plugins_url( 'css/ilab.css', __FILE__ ), array(), time() );
        wp_register_style( 'stripe_css', plugins_url( 'css/stripe.css', __FILE__ ), array(), time() );

        wp_register_script( 'stripe_library', 'https://js.stripe.com/v3/' );
        wp_register_script( 'ilab_js', plugins_url( 'js/ilab.js', __FILE__ ), array('jquery'), time() );
        wp_register_script( 'stripe_js', plugins_url( 'js/stripe.js', __FILE__ ), array('jquery'), time() );
    }

    function ilab_wp_multistep_elementor()
    {
        ob_start();
        wp_enqueue_style( 'ilab_css' );
        wp_enqueue_script( 'ilab_js' );
        return ob_get_clean();
    }

    function ilab_stripe_payment_form()
    {
        ob_start();
        global $ilabAPI;   
        wp_enqueue_style( 'stripe_css' );
        wp_enqueue_script( 'stripe_library' );
        wp_enqueue_script( 'stripe_js' );        
        wp_add_inline_script( 'stripe_js', 'const stripe_config = ' . json_encode( array(
            'wp_json' => home_url() . "/wp-json",
            'stripe_key' => $ilabAPI->getStripeKey(),
            'stripe_secret' => $ilabAPI->getStripeSecret(),
        ) ), 'before' );

        include("views/stripe_form.php");
        return ob_get_clean();
    }
}

new WP_Multistep_Elementor();