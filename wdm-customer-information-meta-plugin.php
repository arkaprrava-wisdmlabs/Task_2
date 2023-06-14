<?php
/**
 * Plugin Name: WDM Customer Information Meta
 * Plugin URI: https://github.com/arkaprrava-wisdmlabs/Task_2.git
 * Description: Adds Hear About Us and Mode of Communication field to customers meta data.
 * Version: 1.0.0
 * Author: Arkaprava
 * Text Domain: wdm_cim
 * License: GPLv2 or later
 * Requires at least: 6.0
 * Requires PHP: 7.3
 * @package Practice_plugin
 */

 if( ! defined( 'ABSPATH' )){
    die;
}

if( ! class_exists( 'WDM_Customer_Information_Meta' ) ){
    /**
     * main plugin defines the plugin
     */
    class WDM_Customer_Information_Meta{
        /**
         * defines the plugin name
         *
         * @var [type]
         */
        protected $plugin_name;
        /**
         * defines all the filter hooks in an array
         *
         * @var [type]
         */
        protected $fiters;
        /**
         * defines all the ation hooks in an array
         *
         * @var [type]
         */
        protected $actions;
        /**
         * constructor of the class
         *
         * @param [type] $plugin_name
         */
        protected $misc_function;
        public function __construct( $plugin_name ){
            $this->plugin_name = $plugin_name;
            require_once plugin_dir_path( __FILE__ ).'includes/class-wdm-customer-information-meta-misc.php';
            $this->misc_function = new WDM_Customer_Information_Meta_Misc();
            $this->define_admin_hooks();
            $this->define_public_hooks();
        }
        /**
         * define all admin hooks
         *
         * @return void
         */
        public function define_admin_hooks(){
            require_once plugin_dir_path( __FILE__ ).'admin/class-wdm-customer-information-meta-admin.php';
            $admin = new WDM_Customer_Information_Meta_Admin($this->plugin_name);
            add_action('admin_init', array( $admin, 'child_plugin_has_parent_plugin'));
        }
        /**
         * define all the public hooks
         *
         * @return void
         */
        public function define_public_hooks(){
            require_once plugin_dir_path( __FILE__ ).'public/class-wdm-customer-information-meta-public.php';
            $public = new WDM_Customer_Information_Meta_Public();
            add_action('woocommerce_after_checkout_billing_form', array( $public, 'wdm_checkout_field'));
            add_action('woocommerce_checkout_order_processed', array( $public, 'wdm_update_checkout_field'), 10, 3);
            add_action('woocommerce_after_cart_table', array( $public, 'wdm_show_field'));
            add_filter( 'woocommerce_order_get_formatted_billing_address',array($this->misc_function, 'wdm_billing_address_filter') , 10, 3 );
        }
    }
}
new WDM_Customer_Information_Meta(plugin_basename( __FILE__ ));