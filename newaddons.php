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
    }
    register_activation_hook( __FILE__, 'na_activate' );