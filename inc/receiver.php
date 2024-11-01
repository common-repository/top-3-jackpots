<?php
/**
 * JavaScript Receiver
 * 
 * @package Top3Jackpots
 * @since Top3Jackpots 1.0.0
 */

defined( 'ABSPATH' ) or die( '' );





/*-------------------------------------------------------------------------------------------------*/
/*-------------------------------- Top 3 Lottery Jackpots Widget Shortcode --------------------------------*/
/*-------------------------------------------------------------------------------------------------*/
function top3jps_js_php_data(){
    
    if( isset( $_GET['top3jps'] ) && $_GET['top3jps'] == 'true' ){
        
        $lottodata_ = get_option( 'top3jps_lottodata', false );
        if( $lottodata_ ){
            $lottodata = unserialize( $lottodata_ );
        }

        if( !is_array( $lottodata ) ){
            echo '';
            exit();
        }

        $timers_js = '';

        for( $t = 1; $t < 4; $t ++ ){

            $rank = substr_count( $lottodata[$t]['amount'], ',' );

            switch ($rank){
                case 1: $rank_symbol = 'T'; break;
                case 2: $rank_symbol = 'M'; break;
                case 3: $rank_symbol = 'B'; break;
                default: $rank_symbol = ''; break;
            }

            $amount_parts = explode( ' ', $lottodata[$t]['amount'] );

            if( $rank_symbol === 'M' ){
                $short_amount = intval( preg_replace( '/\D/', '', $amount_parts[1] ) ) / 1000000;
                if( ( intval( $short_amount ) * 1000000 ) - ( $short_amount * 1000000 ) !== 0 ){
                    $short_amount = number_format( $short_amount, 1, '.', ',' );
                } else {
                    $short_amount = intval( $short_amount );
                }
            }
            
            $short_amount = $amount_parts[0] . ' ' . $short_amount . ' ' .$rank_symbol;

            $timers_js .= "top3jps_CreateCountdown('" . $lottodata[$t]['amount'] . "', '" . $short_amount . "', '" . $lottodata[$t]['jpdate'] . "', 'top3jps-timers" . $t . "');";
        
            if( $t === 1 ){
                $timers_js .= "top3jps_CreateCountdown('" . $lottodata[$t]['amount'] . "', '" . $short_amount . "', '" . $lottodata[$t]['jpdate'] . "', 'top3jps-timers4');";
            }
        }

        header("content-type: application/javascript");
        header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0 , private");
        header("Cache-Control: post-check=0, pre-check=0", false);
        header("Pragma: no-cache, private");
        //header( "Pragma: private" );
        header('Expires: -1');

        echo /** @lang JavaScript */
            "
        function top3jps_timers(){
            " . $timers_js . "
        }
        top3jps_timers();";

        exit();
    }
}
add_action( 'init', 'top3jps_js_php_data' );
/*-------------------------------------------------------------------------------------------------*/
/*-------------------------------------------------------------------------------------------------*/