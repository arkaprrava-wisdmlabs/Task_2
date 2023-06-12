<?php
/**
 * Plugin Name: New Addons
 * Description: Add ons created for additional functionality of WooCommerce.
 * Version: 1.0.0
 * Author: Arkaprava
 * Text Domain: na
 * Requires at least: 6.0
 * Requires PHP: 7.3
 *
 * @package WooCommerce
 */

function na_activate(){
// Require parent plugin
    if ( ! is_plugin_active( 'woocommerce/woocommerce.php' ) and current_user_can( 'activate_plugins' ) ) {
        // Stop activation redirect and show error
        wp_die('Sorry, but this plugin requires the woocommerce plugin to be installed and active. <br><a href="' . admin_url( 'plugins.php' ) . '">&laquo; Return to Plugins</a>');
    }
    $users = get_users();
    // Array of WP_User objects.
    foreach ( $users as $user ) {
        if(!metadata_exists( 'user', $user->ID, 'hear')){
            add_user_meta( $user->ID, 'hear', '', true);
        }
        if(!metadata_exists( 'user', $user->ID, 'mode')){
            add_user_meta( $user->ID, 'mode', '', true);
        }
    }
}
register_activation_hook( __FILE__, 'na_activate' );
add_action( 'woocommerce_after_order_notes', 'na_checkout_field' );

function na_checkout_field( $checkout ) {
    if(is_user_logged_in(  )){
        $id = get_current_user_id();
        $hear = get_user_meta($id, 'hear');
        if(empty($hear)){
            $hear = '';
        }
        else{
            $hear = $hear[0];
        }
        $mode = get_user_meta($id, 'mode');
        if(empty($mode)){
            $mode = '';
        }
        else{
            $mode = $mode[0];
        }
        woocommerce_form_field( 
            'hear', 
            array(
                'type'          => 'text',
                'label'         => __('How did you Hear about Us'),
                ), 
            $hear
        );

        woocommerce_form_field( 
            'mode', 
            array(
                'type'      => 'select',
                'label'     => __('Mode of Communication', 'na'),
                'options'   => array(
                    'Email'     => __('Email'),
                    'Call'      => __('Call'),
                    'Message'   => __('Message'),
                )
            ), 
            $mode
        );
    }
}
add_action( 'woocommerce_checkout_process', 'na_update_checkout_field' );
  
function na_update_checkout_field() {   
    if(is_user_logged_in(  )){
        $id = get_current_user_id(); 
        if ($_POST['hear']) {
            $hear = $_POST['hear'];
            update_user_meta($id, 'hear', $hear);
        }
        if ($_POST['mode']) {
            $mode = $_POST['mode'];
            update_user_meta($id, 'mode', $mode);
        }
    }
}
