<?php
if( ! class_exists( 'WDM_Customer_Information_Meta_Admin' ) ){
    /**
     * Working with server side data and admin panel
     */
    class WDM_Customer_Information_Meta_Admin {
        /**
         * defines plugin base name
         *
         * @var [type]
         */
        protected $plugin_name;
        /**
         * constructor of the class defines the $plugin_name variable
         *
         * @param [type] $plugin_name
         */
        public function __construct($plugin_name){
            $this->plugin_name = $plugin_name;
        }
        /**
         * requires and always check for the woocommerce plugin
         *
         * @return void
         */
        public function wdm_has_woocommerce() {
            if ( is_admin() && current_user_can( 'activate_plugins' ) &&  !is_plugin_active( 'woocommerce/woocommerce.php' ) ) {
                add_action( 'admin_notices',array( $this, 'wdm_admin_notice' ), 10, 0);

                deactivate_plugins( $this->plugin_name ); 

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
        public function wdm_admin_notice(){
            ?><div class="error"><p>Sorry, but Customer Information Meta Plugin requires the WooCommerce plugin to be installed and active.</p></div><?php
        }
        /**
         * displays order item meta in edit order page
         *
         * @param [type] $order
         * @return void
         */
        function wdm_show_admin_field($order){
            $order_id = $order->ID;
            $out = '';
            $container = wc_get_order_item_meta($order_id, 'hear');
            if(!empty($container)){
                $out .='<p>'.__('How did you Hear about US: ', 'wdm_cim').'<strong>';
                $out .= $container;
                $out .= '</strong></p>';
            }
            $container = wc_get_order_item_meta($order_id, 'mode');
            if(!empty($container)){
                $out .='<p>'.__('Preffered mode of communication: ', 'wdm_cim').'<strong>';
                $out .= $container;
                $out .= '</strong></p>';
            }
            echo $out;
        }
    }
}