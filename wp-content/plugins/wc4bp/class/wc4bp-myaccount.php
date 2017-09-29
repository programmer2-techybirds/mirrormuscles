<?php

/**
 * @package        WordPress
 * @subpackage     BuddyPress, Woocommerce
 * @author         GFireM
 * @copyright      2017, Themekraft
 * @link           https://github.com/Themekraft/BP-Shop-Integration
 * @license        http://www.opensource.org/licenses/gpl-2.0.php GPL License
 */
// No direct access is allowed
if ( !defined( 'ABSPATH' ) ) {
    die;
}
class WC4BP_MyAccount
{
    protected  $base_html ;
    public static  $prefix ;
    protected  $current_title ;
    public function __construct()
    {
        $this->base_html = '<span class=\'wc4bp-my-account-page\'>' . wc4bp_Manager::get_suffix() . '</span>';
    }
    
    public function get_base_url( $endpoint )
    {
        return bp_core_get_user_domain( bp_loggedin_user_id() ) . 'shop/' . $endpoint;
    }
    
    public static function get_available_endpoints()
    {
        
        if ( wc4bp_Manager::is_woocommerce_active() ) {
            $end_points = wc_get_account_menu_items();
            $end_points = apply_filters( 'wc4bp_add_endpoint', $end_points );
            $exclude = apply_filters( 'wc4bp_woocommerce_exclude_endpoint', array( 'customer-logout', 'dashboard' ) );
            return array_diff_key( $end_points, array_flip( $exclude ) );
        } else {
            return array();
        }
    
    }

}