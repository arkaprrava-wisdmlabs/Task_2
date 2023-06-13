<?php
/**
 * Plugin Name: New Customer Information Meta
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
            $this->filters = array();
            $this->actions = array();
            $this->run();
        }
        public function activate(){
            require_once plugin_dir_path( __FILE__ ).'includes/class-wdm-customer-information-meta-activator.php';
            $activate = new WDM_Customer_Information_Meta_Activator();
            register_activation_hook( __FILE__, array( $activate, 'activate' ) );
        }
        public function deactivate(){
            require_once plugin_dir_path( __FILE__ ).'includes/class-wdm-customer-information-meta-deativator.php';
            $deactivate = new WDM_Customer_Information_Meta_Deactivator();
            register_deactivation_hook( __FILE__, array( $deactivate, 'deactivate' ) );
        }
        public function setup(){
            $this->define_admin_hooks();
            $this->define_public_hooks();
            foreach($this->filters as $filter_hook){
                add_filter( $filter_hook['hook'], array( $filter_hook['class'], $filter_hook['function'] ) );
            }
            foreach($this->actions as $action_hook){
                add_filter( $action_hook['hook'], array( $action_hook['class'], $action_hook['function'] ) );
            }
        }
        public function define_admin_hooks(){
            require_once plugin_dir_path( __FILE__ ).'admin/class-wdm-customer-information-meta-admin.php';
            $admin = new WDM_Customer_Information_Meta_Admin($this->plugin_name);
            $this->actions = $this->add($this->actions, 'admin_init', $admin, 'child_plugin_has_parent_plugin');
            $this->actions = $this->add($this->actions, 'woocommerce_admin_order_data_after_billing_address', $admin, 'wdm_order_show_field');
        }
        public function define_public_hooks(){
            require_once plugin_dir_path( __FILE__ ).'public/class-wdm-customer-information-meta-public.php';
            $public = new WDM_Customer_Information_Meta_Public($this->plugin_name);
            $this->actions = $this->add($this->actions, 'woocommerce_review_order_before_submit', $public, 'wdm_checkout_field');
            $this->actions = $this->add($this->actions, 'woocommerce_checkout_order_processed', $public, 'wdm_update_checkout_field');
            $this->actions = $this->add($this->actions, 'woocommerce_after_cart_table', $public, 'wdm_show_field');
            $this->actions = $this->add($this->actions, 'woocommerce_order_details_after_customer_details', $public, 'wdm_order_show_field');
        }
        public function add($hooks, $hook, $class, $function){
            $hooks[] = array(
                'hook'   => $hook,
                'class'  => $class,
                'function' => $function
            );
    
            return $hooks;
        }
        public function run(){
            $this->activate();
            $this->deactivate();
            $this->setup();
        }
    }
}
new WDM_Customer_Information_Meta(plugin_basename( __FILE__ ));