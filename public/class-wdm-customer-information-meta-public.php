<?php
if( ! class_exists( 'WDM_Customer_Information_Meta_Public' ) ){
    /**
     * working with customer side data and frontend pressentation
     */
    class WDM_Customer_Information_Meta_Public{
        /**
         * defines the plugin basename
         *
         * @var [type]
         */
        protected $plugin_name;
        /**
         * constructor for the class
         *
         * @param [type] $plugin_name
         */
        public function __construct($plugin_name){
            $this->plugin_name = $plugin_name;
        }
        /**
         * It first creates order item meta for Hear About Us and Mode of Communication Fields
         * Then it adds custom input field in checkout page for those order item meta
         *
         * @param [type] $checkout
         * @return void
         */
        public function wdm_checkout_field( $checkout ) {
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
         * It shows the latest order item metas of current user if exists
         *
         * @return void
         */
        public function wdm_show_field(){
            $out = '';
            if(is_user_logged_in(  )){
                $id = get_current_user_id();
                $customer = new WC_Customer( $id );
                $last_order = $customer->get_last_order();
                if(!empty($last_order)){
                    $order_id = $last_order->get_id();
                    $head =  '<div>';
                    $head .= '<h3>Customer Information</h3>';
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
                    if( $out !== '</div>' ){
                        $out = $head . $out;
                    }
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
        public function wdm_order_show_field($order){
            $post_type = get_post_type();
            if( ! is_user_logged_in()){
                return;
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