<?php
/**
 * Plugin Name: Old Customer Information Meta
 * Plugin URI: https://github.com/arkaprrava-wisdmlabs/Task_2.git
 * Description: Adds Hear About Us and Mode of Communication field to customers meta data.
 * Version: 1.0.0
 * Author: Arkaprava
 * Text Domain: na
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
        public function __construct( $plugin_name ){
            $this->plugin_name = $plugin_name;
            $this->run();
        }
        /**
         * define activation hook
         *
         * @return void
         */
        public function activate(){
            require_once plugin_dir_path( __FILE__ ).'includes/class-wdm-customer-information-meta-activator.php';
            $activate = new WDM_Customer_Information_Meta_Activator();
            register_activation_hook( __FILE__, array( $activate, 'activate' ) );
        }
        /**
         * define dectivation hook
         *
         * @return void
         */
        public function deactivate(){
            require_once plugin_dir_path( __FILE__ ).'includes/class-wdm-customer-information-meta-deativator.php';
            $deactivate = new WDM_Customer_Information_Meta_Deactivator();
            register_deactivation_hook( __FILE__, array( $deactivate, 'deactivate' ) );
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
            add_action('woocommerce_admin_order_data_after_billing_address', array( $admin, 'wdm_order_show_field'));
        }
        /**
         * define all the public hooks
         *
         * @return void
         */
        public function define_public_hooks(){
            require_once plugin_dir_path( __FILE__ ).'public/class-wdm-customer-information-meta-public.php';
            $public = new WDM_Customer_Information_Meta_Public($this->plugin_name);
            add_action('woocommerce_review_order_before_submit', array( $public, 'wdm_checkout_field'));
            add_action('woocommerce_checkout_order_processed', array( $public, 'wdm_update_checkout_field'), 10, 3);
            add_action('woocommerce_after_cart_table', array( $public, 'wdm_show_field'));
            add_action('woocommerce_order_details_after_customer_details', array( $public, 'wdm_order_show_field'));
        }
        /**
         * runs the plugin step by step step
         *
         * @return void
         */
        public function run(){
            $this->activate();
            $this->deactivate();
            $this->define_admin_hooks();
            $this->define_public_hooks();
        }
    }
}
new WDM_Customer_Information_Meta(plugin_basename( __FILE__ ));