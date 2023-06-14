<?php
if( ! class_exists( 'WDM_Customer_Information_Meta_Public' ) ){
    /**
     * working with customer side data and frontend pressentation
     */
    class WDM_Customer_Information_Meta_Public{
        /**
         * It first creates order item meta for Hear About Us and Mode of Communication Fields
         * Then it adds custom input field in checkout page for those order item meta
         *
         * @param [type] $checkout
         * @return void
         */
        protected $plugin_dir_path;
        public function __construct($plugin_dir_path){
            $this->plugin_dir_path = $plugin_dir_path;
        }
        function wdm_checkout_field( $checkout ) {
            $hear = '';
            $mode = '';
            if(is_user_logged_in(  )){
                $id = get_current_user_id();
                $customer = new WC_Customer( $id );
                $last_order = $customer->get_last_order();
                if(!empty($last_order)){
                    $order_id   = $last_order->get_id();
                    $container = wc_get_order_item_meta($order_id, 'hear');
                    if(!empty($container)){
                        $hear = $container;
                    }
                    $container = wc_get_order_item_meta($order_id, 'mode');
                    if(!empty($container)){
                        $mode = $container;
                    }
                }
            }
            woocommerce_form_field( 
                'hear', 
                array(
                    'type'          => 'text',
                    'label'         => __('How did you Hear about Us', 'wdm_cim'),
                    ), 
                $hear
            );

            woocommerce_form_field( 
                'mode', 
                array(
                    'type'      => 'select',
                    'label'     => __('Mode of Communication', 'wdm_cim'),
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
        public function wdm_update_checkout_field( $order_id, $posted_data, $order ) {
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
         * It shows the latest order item metas of current user if exists in the cart page
         *
         * @return void
         */
        public function wdm_cart_show_field(){
            $out = '';
            if(is_user_logged_in(  )){
                $id = get_current_user_id();
                $customer = new WC_Customer( $id );
                $last_order = $customer->get_last_order();
                if(!empty($last_order)){
                    $order_id = $last_order->get_id();
                    $head =  '<div>';
                    $head .= '<h3>'.__('Customer Information', 'wdm_cim').'</h3>';
                    if(!empty(wc_get_order_item_meta($order_id, 'hear'))){
                        $out .='<p>'.__('How did you Hear about US: ', 'wdm_cim').'<strong>';
                        $out .= wc_get_order_item_meta($order_id, 'hear');
                        $out .= '</strong></p>';
                    }
                    if(!empty(wc_get_order_item_meta($order_id, 'mode'))){
                        $out .='<p>'.__('Preffered mode of communication: ', 'wdm_cim').'<strong>';
                        $out .= wc_get_order_item_meta($order_id, 'mode');
                        $out .= '</strong></p>';
                    }
                    $out .= '</div>';
                    if($out !== '</div>'){
                        $out = $head . $out;
                    }
                }
            }
            echo $out;
        }
        /**
         * overrides the template order-details-customer
         *
         * @param [type] $template
         * @param [type] $template_name
         * @param [type] $template_path
         * @return void
         */
        function wdm_override_woocommerce_order_billing_template( $template, $template_name, $template_path ) {
            if ( $template_name === 'order/order-details-customer.php' ) {
                $template = $this->plugin_dir_path . 'includes/order-details-customer.php';
            }
            return $template;
        }
    }
}