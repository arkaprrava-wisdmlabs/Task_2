<?php
/**
 * Plugin Name: Customer Information Meta
 * Plugin URI: https://github.com/arkaprrava-wisdmlabs/Task_2.git
 * Description: Adds Hear About Us and Mode of Communication field to customers meta data.
 * Version: 1.0.0
 * Author: Arkaprava
 * Text Domain: na
 * License: GPLv2 or later
 * Requires at least: 6.0
 * Requires PHP: 7.3
 * @package newaddons
 */
if( ! defined( 'ABSPATH' )){
    die;
}

/**
 * activates the plugin
 *
 * @return void
 */
function wdm_activate(){
// Require parent plugin
    if ( ! is_plugin_active( 'woocommerce/woocommerce.php' ) and current_user_can( 'activate_plugins' ) ) {
        // Stop activation redirect and show error
        deactivate_plugins( plugin_basename( __FILE__ ) );
        // $err = new WP_Error();
        // $err -> add('required', 'Sorry, but this plugin requires the woocommerce plugin to be installed and active. <br><a href="' . admin_url( 'plugins.php' ) . '">&laquo; Return to Plugins</a>');
        wp_die('Sorry, but this plugin requires the woocommerce plugin to be installed and active. <br><a href="' . admin_url( 'plugins.php' ) . '">&laquo; Return to Plugins</a>');
    }
}
register_activation_hook( __FILE__, 'wdm_activate' );
add_action( 'woocommerce_review_order_before_submit', 'wdm_checkout_field' );
/**
 * It first creates order item meta for Hear About Us and Mode of Communication Fields
 * Then it adds custom input field in checkout page for those order item meta
 *
 * @param [type] $checkout
 * @return void
 */
function wdm_checkout_field( $checkout ) {
    $hear = '';
    $mode = '';
    if(is_user_logged_in(  )){
        $id = get_current_user_id();
        $customer = new WC_Customer( $id );
        $last_order = $customer->get_last_order();
        if(!empty($last_order)){
            $order_id   = $last_order->get_id();
            if(!empty(wc_get_order_item_meta($order_id, 'hear'))){
                $hear = wc_get_order_item_meta($order_id, 'hear');
            }
            if(!empty(wc_get_order_item_meta($order_id, 'mode'))){
                $mode = wc_get_order_item_meta($order_id, 'mode');
            }
        }
    }
    woocommerce_form_field( 
        'hear', 
        array(
            'type'          => 'text',
            'label'         => __('How did you Hear about Us', 'na'),
            ), 
        $hear
    );

    woocommerce_form_field( 
        'mode', 
        array(
            'type'      => 'select',
            'label'     => __('Mode of Communication', 'na'),
            'options'   => array(
                ''          => __('--select--','na'),
                'Email'     => __('Email','na'),
                'Call'      => __('Call', 'na'),
                'Message'   => __('Message', 'na'),
            )
        ), 
        $mode
    );
}
add_action( 'woocommerce_checkout_order_processed', 'wdm_update_checkout_field' , 10, 3);
/**
 * it adds the order item meta that is filled by the user while purchasing the products
 * and fires the action upon clicking place order
 *
 * @param [type] $order_id
 * @param [type] $posted_data
 * @param [type] $order
 * @return void
 */
function wdm_update_checkout_field( $order_id, $posted_data, $order ) {
    if ($_POST['hear']) {
        $hear = $_POST['hear'];
        wc_update_order_item_meta($order_id, 'hear', $hear);
    }
    if ($_POST['mode']) {
        $mode = $_POST['mode'];
        wc_update_order_item_meta($order_id, 'mode', $mode);
    }
}

add_action( 'woocommerce_admin_order_data_after_billing_address', 'wdm_show_field');
add_action( 'woocommerce_after_cart_table', 'wdm_show_field');
/**
 * It shows the latest order item metas of current user if exists
 *
 * @return void
 */
function wdm_show_field(){
    $out = '';
    if(is_user_logged_in(  )){
        $id = get_current_user_id();
        $customer = new WC_Customer( $id );
        $last_order = $customer->get_last_order();
        if(!empty($last_order)){
            $order_id = $last_order->get_id();
            $out .= '<div>';
            if(!empty(wc_get_order_item_meta($order_id, 'hear'))){
                $out .='<p>'.__('How did you Hear about US: ', 'na').'<strong>';
                $out .= wc_get_order_item_meta($order_id, 'hear');
                $out .= '</strong></p>';
            }
            if(!empty(wc_get_order_item_meta($order_id, 'mode'))){
                $out .='<p>'.__('Preffered mode of communication: ', 'na').'<strong>';
                $out .= wc_get_order_item_meta($order_id, 'mode');
                $out .= '</strong></p>';
            }
            $out .= '</div>';
        }
    }
    echo $out;
}


add_action( 'woocommerce_order_details_after_customer_details', 'wdm_order_show_field' );
/**
 * show 'hear' and 'mode' item metas of every order items
 *
 * @param [type] $order_id
 * @return void
 */
function wdm_order_show_field($order){
    $out = '<div>';
    $order_id = $order -> ID;
    if(!empty(wc_get_order_item_meta($order_id, 'hear'))){
        $out .='<p>'.__('How did you Hear about US: ', 'na').'<strong>';
        $out .= wc_get_order_item_meta($order_id, 'hear');
        $out .= '</strong></p>';
    }
    if(!empty(wc_get_order_item_meta($order_id, 'mode'))){
        $out .='<p>'.__('Preffered mode of communication: ', 'na').'<strong>';
        $out .= wc_get_order_item_meta($order_id, 'mode');
        $out .= '</strong></p>';
    }
    $out .= '</div>';
    echo $out;
}