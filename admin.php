<?php

class ilabAdmin
{
    function __construct()
    {
        add_action('admin_menu', [$this, 'ilab_stripe_settings_menu']);
        add_action('admin_menu', [$this, 'hide_stripe_payment_page'], 999);
        add_filter( 'user_row_actions', [$this, 'ilab_custom_user_action_link'], 10, 2 );
    }

    function ilab_stripe_settings_menu()
    {
        add_menu_page(
            'Stripe Settings',
            'Stripe Settings',
            'manage_options', 
            'ilab-stripe-settings',
            [$this, 'ilab_stripe_settings_page'],
            'dashicons-admin-generic',
            90
        );

        // Add a new hidden admin page
        add_menu_page(
            'Stripe Payment',          
            'Stripe Payment',                
            'manage_options',             
            'ilab-stripe-payment',          
            [$this, 'ilab_stripe_payment_page'], 
            '',                           
            null                          
        );
    }

    function ilab_stripe_settings_page()
    {
        include('views/admin/settings.php');
    }

    function ilab_custom_user_action_link( $actions, $user )
    {
        
        if ( current_user_can( 'edit_user', $user->ID ) ) 
        {
            $custom_link = add_query_arg( [
                'user_id' => $user->ID,
                'action' => 'stripe_payment',
                'page' => 'ilab-stripe-payment'
            ], admin_url( 'admin.php' ) );
    
            $actions['stripe_payment'] = sprintf(
                '<a href="%s">%s</a>',
                esc_url( $custom_link ),
                __( 'Stripe Payment', 'ilab' )
            );
        }
    
        return $actions;   
    }

    function ilab_stripe_payment_page()
    {
        include('views/admin/stripe_payment.php');
    }

    function hide_stripe_payment_page()
    {
        remove_menu_page('ilab-stripe-payment');
    }
}

new ilabAdmin();