<?php
if( ! class_exists( 'WDM_Customer_Information_Meta_Misc' ) ){
    /**
     * working with customer side data and frontend pressentation
     */
    class WDM_Customer_Information_Meta_Misc{
        /**
         * show 'hear' and 'mode' item metas of every order items
         *
         * @param [type] $order_id
         * @return void
         */
        public function wdm_billing_address_filter( $address, $raw_address, $order ){
            if( ! is_user_logged_in(  )){
                return $address;
            }
            $out = '<div><br>';
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
            $address .= $out;
            // filter...
            return $address;
        }
    }
}