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
        public function wdm_order_show_field($order){
            $post_type = get_post_type();
            if($post_type !== 'shop_order'){
                if( ! is_user_logged_in(  )){
                    return;
                }
            }
            $out = '<div>';
            $order_id = $order -> ID;
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
            $out .= '</div>';
            echo $out;
        }
    }
}