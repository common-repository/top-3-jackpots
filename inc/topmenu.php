<?php
/**
 * Admin Top Menu
 * 
 * @package Top3Jackpots
 * @since Top3Jackpots 1.0.0
 */

defined( 'ABSPATH' ) or die( '' );





/*-------------------------------------------------------------------------------------------------*/
/*---------------------------------------- ADMIN TOP MENU -----------------------------------------*/
/*-------------------------------------------------------------------------------------------------*/
function top3jps_admin_top_bar_menu( $wp_admin_bar ){

    $wp_admin_bar->add_node( array(
        'id'    => 'top3jps-top-menu',
        'title' => 'Top 3 Lottery Jackpots',
        'meta'  => array(
                        'class' => 'top3jps-show-settings',
                        'title' => 'Show Top 3 Lottery Jackpots Settings'
                    )
    ) );

    return $wp_admin_bar;
}
add_action( 'admin_bar_menu', 'top3jps_admin_top_bar_menu', 90, 1 );
/*-------------------------------------------------------------------------------------------------*/
/*-------------------------------------------------------------------------------------------------*/