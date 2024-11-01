<?php
/**
 * Plugin Name: Top 3 Lottery Jackpots
 * Plugin URI: https://wordpress.org/plugins/top-3-jackpots/
 * Description: This plugin is made for you to monetize your WordPress website's traffic with a great lottery offer! Shortcode: [top3jackpots]
 * Version: 1.0.7
 * Author: maxtongh
 * Author URI: https://www.offpista.com/
 * License: GPL2
 */

defined( 'ABSPATH' ) or die( '' );





/*-------------------------------------------------------------------------------------------------*/
/*-------------------------------------- PHP GLOBAL VARIABLES -------------------------------------*/
/*-------------------------------------------------------------------------------------------------*/
if( !defined( 'TOP_3_JP_ROOT_URL' ) ){
    define( 'TOP_3_JP_ROOT_URL', plugins_url( '/', __FILE__ ) );
}

if( !defined( 'TOP_3_JP_ROOT_DIR' ) ){
    define( 'TOP_3_JP_ROOT_DIR', plugin_dir_path( __FILE__ ) );
}
/*-------------------------------------------------------------------------------------------------*/
/*-------------------------------------------------------------------------------------------------*/





/*-------------------------------------------------------------------------------------------------*/
/*-------------------------------------------- INCLUDES -------------------------------------------*/
/*-------------------------------------------------------------------------------------------------*/
include_once TOP_3_JP_ROOT_DIR . 'inc/admin-notices.php';
include_once TOP_3_JP_ROOT_DIR . 'inc/frontend-editor/template.php';
include_once TOP_3_JP_ROOT_DIR . 'inc/frontend-editor/receiver.php';
include_once TOP_3_JP_ROOT_DIR . 'inc/countclicks.php';
include_once TOP_3_JP_ROOT_DIR . 'inc/updates.php';
include_once TOP_3_JP_ROOT_DIR . 'inc/shortcodes.php';
include_once TOP_3_JP_ROOT_DIR . 'inc/receiver.php';
include_once TOP_3_JP_ROOT_DIR . 'inc/topmenu.php';
/*-------------------------------------------------------------------------------------------------*/
/*-------------------------------------------------------------------------------------------------*/





/*-------------------------------------------------------------------------------------------------*/
/*------------------------------- PLUGIN ACTIVATION & DEACTIVATION --------------------------------*/
/*-------------------------------------------------------------------------------------------------*/
//Activation
function top3jps_activation_handler(){
    top3jps_updates_set();
}
register_activation_hook( __FILE__, 'top3jps_activation_handler' );



//Deactivation
function top3jps_deactivation_handler(){
    do_action( 'top3jps_deactivation' );
}
register_deactivation_hook( __FILE__, 'top3jps_deactivation_handler' );
/*-------------------------------------------------------------------------------------------------*/
/*-------------------------------------------------------------------------------------------------*/





/*-------------------------------------------------------------------------------------------------*/
/*------------------------------------------ ALWAYS CHECK -----------------------------------------*/
/*-------------------------------------------------------------------------------------------------*/
function top3jps_check_curl_enabled(){
    if( !function_exists( 'curl_init' ) ){
        add_action( 'admin_notices', 'top3jps_admin_notice_curl_issue' );
    }
}
add_action( 'admin_init', 'top3jps_check_curl_enabled' );
/*-------------------------------------------------------------------------------------------------*/
/*-------------------------------------------------------------------------------------------------*/