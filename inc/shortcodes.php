<?php
/**
 * Shortcodes
 * 
 * @package Top3Jackpots
 * @since Top3Jackpots 1.0.0
 */

defined( 'ABSPATH' ) or die( '' );





/*-------------------------------------------------------------------------------------------------*/
/*-------------------------------- Top 3 Lottery Jackpots Widget Shortcode --------------------------------*/
/*-------------------------------------------------------------------------------------------------*/
//Shortcode Handler
function top3jp_shortcode_handler( $atts ){
    
    //Stop further execution if PHP CURL is disabled on server
    if( !function_exists( 'curl_init' ) ){
        return '';
    }

    $lottodata_ = get_option( 'top3jps_lottodata', false );
    if( $lottodata_ ){
        $lottodata = unserialize( $lottodata_ );
    }

    if( !is_array( $lottodata ) || sizeof( $lottodata ) < 1 ){
        return '';
    }

    $version = '1.1.0';
    wp_enqueue_style( 'top3jps-shortcode', TOP_3_JP_ROOT_URL . 'src/css/styles.min.css', array(), $version );
    wp_enqueue_script( 'jquery' );
    wp_enqueue_script( 'top3jps-shortcode-jqcountdown', TOP_3_JP_ROOT_URL . 'src/js/jquery.countdown.js', array( 'jquery' ), $version );
    wp_enqueue_script( 'top3jps-shortcode', TOP_3_JP_ROOT_URL . 'src/js/functions.min.js', array( 'jquery' ), $version );
    
    $user_id = get_option( 'top3jps_user_id', 1 );

    $site_url = site_url();
    
    $is_public = ' style="display: none !important;"';
    if( get_option( 'top3jps_is_public', false ) ){
        $is_public = '';
    } else {
        if( function_exists( 'is_user_logged_in' ) && function_exists( 'current_user_can' ) && is_user_logged_in() && current_user_can( 'manage_options' ) && function_exists( 'top3jps_render_frontend_editor' ) ){
            $is_public = '';
        }
    }

    $top3html = '<div id="top3jps-container"' . $is_public . '>';
    $c = 1;

    foreach( $lottodata as $ld ){
        $top3html .= '<div class="top3jps-sidebar-jackpot">' .
                        '<div class="top3jps-logos">' .
                            '<a class="top3jps-for-gtm-trigger" rel="noindex,nofollow" target="_blank" data-id="' . $user_id . '" data-lottery="' . str_replace( array( "/visit/", "/" ), "", $ld["afflink"] ) . '" href="' . $site_url . $ld['afflink'] . '"><img src="' . TOP_3_JP_ROOT_URL . $ld['logo'] .'" alt="Top 3 Jackpot - ' . $ld['title'] . '" /></a>' .
                        '</div>' .
                        '<div class="top3jps-wrapper">' .
                            '<a class="top3jps-button top3jps-for-gtm-trigger" data-affiliatebutton="' . $ld['title'] . '" target="_blank" data-id="' . $user_id . '" data-lottery="' . str_replace( array( "/visit/", "/" ), "", $ld["afflink"] ) . '" href="' . $site_url . $ld['afflink'] . '" rel="noindex,nofollow">Play Now</a>' .
                        '</div>' .
                        '<div class="top3jps-timers' . $c . '">' .
                            '<div class="top3jps-countdownContainer" style="direction: ltr;">' .
                                '<div class="top3jps-countdownTitle"></div>' .
                                '<div class="top3jps-countdownMobileTitle"></div>' .
                                '<div class="top3jps-countdownTimer"></div>' .
                            '</div>' .
                            '<div style="clear: both;"></div>' .
                        '</div>' .
                        '<div style="clear: both;"></div>' .
                    '</div>';

        $c++;
    }

    if( is_front_page() || is_home() ){
        $is_home_js = 'window.top3jpsIsHP=true;';
    }

    $top3html .= '</div>' .
                    '<div id="top3jps-closer"></div>' .
                    '<script id="top3jps-temp">window.top3jpsRSiteURL="' . site_url() . '";' . $is_home_js . 'window.top3jpsCountClicks="' . admin_url( "admin-ajax.php?action=top3jps_count_clicks" ) . '";window.top3jpsRef="' . base64_decode( "aHR0cHM6Ly93d3cuMjRtb25ldGl6ZS5jb20vbG90dGVyeS8=" ) . '";</script>';

    add_action( 'wp_footer', 'top3jps_render_frontend_css' );
    add_action( 'wp_footer', 'top3jps_render_footer_widget' );

    return $top3html;
}
add_shortcode( 'top3jackpots', 'top3jp_shortcode_handler' );



//Front-End CSS Renderer
function top3jps_render_frontend_css(){

    $custom_css = get_option( 'top3jps_frontend_custom_css', false );
    if( $custom_css ){
            echo '<style id="top3jps-custom-css" type="text/css">' . $custom_css . '</style>';
    }
}



//Footer Widget
function top3jps_render_footer_widget(){

    $lottodata_ = get_option( 'top3jps_lottodata', false );
    if( $lottodata_ ){
        $lottodata = unserialize( $lottodata_ );
    } else {
        $lottodata = array();
    }

    if( !is_array( $lottodata ) || sizeof( $lottodata ) < 1 ){
        return '';
    }

    $user_id = get_option( 'top3jps_user_id', 1 );

    $site_url = site_url();

    $is_public = ' data-ishidden="true" style="display: none !important;"';
    if( get_option( 'top3jps_is_public', false ) ){
        $is_public = '';
    } else {
        if( function_exists( 'is_user_logged_in' ) && function_exists( 'current_user_can' ) && is_user_logged_in() && current_user_can( 'manage_options' ) && function_exists( 'top3jps_render_frontend_editor' ) ){
            $is_public = '';
        }
    }

    echo '<div id="top3jps-footer-container"' . $is_public . '>' .
            '<span id="top3jps-footer-closer">+</span>' .
            '<div class="top3jps-sidebar-jackpot">' .
                '<div class="top3jps-logos">' .
                    '<a class="top3jps-for-gtm-trigger" target="_blank" rel="noindex,nofollow" data-id="' . $user_id . '" data-lottery="' . str_replace( array( "/visit/", "/" ), "", $lottodata[1]["afflink"] ) . '" href="' . $site_url . $lottodata[1]['afflink'] . '"><img src="' . TOP_3_JP_ROOT_URL . $lottodata[1]['logo'] .'" alt="Top 3 Jackpot - ' . $lottodata[1]['title'] . '" /></a>' .
                '</div>' .
                '<div class="top3jps-wrapper">' .
                    '<a class="top3jps-button top3jps-for-gtm-trigger" data-affiliatebutton="' . $lottodata[1]['title'] . '" target="_blank" data-id="' . $user_id . '" data-lottery="' . str_replace( array( "/visit/", "/" ), "", $lottodata[1]["afflink"] ) . '" href="' . $site_url . $lottodata[1]['afflink'] . '" rel="noindex,nofollow">Play Now</a>' .
                '</div>' .
                '<div class="top3jps-timers4">' .
                    '<div class="top3jps-countdownContainer" style="direction: ltr;">' .
                        '<div class="top3jps-countdownTitle"></div>' .
                        '<div class="top3jps-countdownMobileTitle"></div>' .
                        '<div class="top3jps-countdownTimer"></div>' .
                    '</div>' .
                    '<div style="clear: both;"></div>' .
                '</div>' .
                '<div style="clear: both;"></div>' .
            '</div>' .
        '</div>';
}
/*-------------------------------------------------------------------------------------------------*/
/*-------------------------------------------------------------------------------------------------*/
