<?php
/*
Plugin Name: Multistep form integration
Plugin URI: https://ilab.com
Description: WordPress Plugin for Multistep form integration
Author: ilab
Version: 1.0.0
Author URI: ilab.com
*/

class WP_Multistep_Elementor
{
    function __construct()
    {
        add_action('wp_enqueue_scripts', [$this, 'ilab_wp_scripts']);
        add_shortcode('wp_multistep_elementor', [$this, 'ilab_wp_multistep_elementor']);
    }

    function ilab_wp_scripts()
    {
        wp_register_style( 'ilab_css', plugins_url( 'css/ilab.css', __FILE__ ), array(), time() );
        wp_register_script( 'ilab_js', plugins_url( 'js/ilab.js', __FILE__ ), array('jquery'), time() );
    }

    function ilab_wp_multistep_elementor()
    {
        ob_start();
        wp_enqueue_style( 'ilab_css' );
        wp_enqueue_script( 'ilab_js' );
        return ob_get_clean();
    }
}

new WP_Multistep_Elementor();