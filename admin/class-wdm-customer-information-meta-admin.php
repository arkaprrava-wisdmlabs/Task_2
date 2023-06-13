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
        public function child_plugin_has_parent_plugin() {
            if ( is_admin() && current_user_can( 'activate_plugins' ) &&  !is_plugin_active( 'woocommerce/woocommerce.php' ) ) {
                add_action( 'admin_notices', array( $this, 'child_plugin_notice' ) );

                deactivate_plugins( $this->plugin_name); 

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
        public function child_plugin_notice(){
            ?><div class="error"><p>Sorry, but Customer Information Meta Plugin requires the WooCommerce plugin to be installed and active.</p></div><?php
        }
        /**
         * show 'hear' and 'mode' item metas of every order items
         *
         * @param [type] $order_id
         * @return void
         */
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
    }
}