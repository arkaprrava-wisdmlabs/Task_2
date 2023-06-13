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
register_activation_hook( __FILE__, 'wdm_activate' );
function wdm_activate(){
}
/**
 * requires and always check for the woocommerce plugin
 *
 * @return void
 */
add_action( 'admin_init', 'child_plugin_has_parent_plugin' );
function child_plugin_has_parent_plugin() {
    if ( is_admin() && current_user_can( 'activate_plugins' ) &&  !is_plugin_active( 'woocommerce/woocommerce.php' ) ) {
        add_action( 'admin_notices', 'child_plugin_notice' );

        deactivate_plugins( plugin_basename( __FILE__ ) ); 

        if ( isset( $_GET['activate'] ) ) {
            unset( $_GET['activate'] );
        }
    }
}
/**
 * displays admin notice on dependency plugin not active
 *
 * @return void
 */
function child_plugin_notice(){
    ?><div class="error"><p>Sorry, but This Plugin requires the WooCommerce plugin to be installed and active.</p></div><?php
}
/**
 * It first creates order item meta for Hear About Us and Mode of Communication Fields
 * Then it adds custom input field in checkout page for those order item meta
 *
 * @param [type] $checkout
 * @return void
 */
add_action( 'woocommerce_review_order_before_submit', 'wdm_checkout_field' );
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
/**
 * it adds the order item meta that is filled by the user while purchasing the products
 * and fires the action upon clicking place order
 *
 * @param [type] $order_id
 * @param [type] $posted_data
 * @param [type] $order
 * @return void
 */
add_action( 'woocommerce_checkout_order_processed', 'wdm_update_checkout_field' , 10, 3);
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

/**
 * It shows the latest order item metas of current user if exists
 *
 * @return void
 */
add_action( 'woocommerce_after_cart_table', 'wdm_show_field');
function wdm_show_field(){
    $out = '';
    if(is_user_logged_in(  )){
        $id = get_current_user_id();
        $customer = new WC_Customer( $id );
        $last_order = $customer->get_last_order();
        if(!empty($last_order)){
            $order_id = $last_order->get_id();
            $out .= '<div>';
            $out .= '<h3>Customer Information</h3>';
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


/**
 * show 'hear' and 'mode' item metas of every order items
 *
 * @param [type] $order_id
 * @return void
 */
add_action( 'woocommerce_order_details_after_customer_details', 'wdm_order_show_field' );
add_action( 'woocommerce_admin_order_data_after_billing_address', 'wdm_order_show_field');
function wdm_order_show_field($order){
    $post_type = get_post_type();
    if($post_type !== 'shop_order'){
        if( ! is_user_logged_in(  )){
            return;
        }
    }
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