<?php
/**
 * Count Clicks script
 * 
 * @package Top3Jackpots
 * @since Top3Jackpots 1.0.0
 */

defined( 'ABSPATH' ) or die( '' );





function top3jps_count_clicks(){

    if( isset( $_POST['top3jps_accept_click'] ) ){

        $current_date = gmdate( 'Y-m-d' );
        
        $clicks_history = get_option( 'top3jps_clicks_history', array() );

        $today_timestamp = strtotime( $current_date . ' 00:00:00' );

        if( !isset( $clicks_history[$today_timestamp] ) ){
            $clicks_history[$today_timestamp] = 1;
        } else {
            $clicks_history[$today_timestamp] += 1;
        }

        //Store data only for last 4 weeks
        if( sizeof( $clicks_history ) > 28 ){
            $clicks_history_ = $clicks_history;
            unset( $clicks_history );
            $clicks_history = array();
            $limit = 0;
            foreach( $clicks_history_ as $key => $val ){
                $clicks_history[$key] = $val;
                $limit++;
                if( $limit >= 28 ){
                    break;
                }
            }
        }

        update_option( 'top3jps_clicks_history', $clicks_history );
    }
    wp_die();
}
add_action( 'wp_ajax_top3jps_count_clicks', 'top3jps_count_clicks' );
add_action( 'wp_ajax_nopriv_top3jps_count_clicks', 'top3jps_count_clicks' );