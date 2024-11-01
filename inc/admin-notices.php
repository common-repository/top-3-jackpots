<?php
/**
 * Admin Notices
 * 
 * @package Top3Jackpots
 * @since Top3Jackpots 1.0.0
 */

defined( 'ABSPATH' ) or die( '' );





/*-------------------------------------------------------------------------------------------------*/
/*--------------------------------------- DEPENDENCY ISSUES ---------------------------------------*/
/*-------------------------------------------------------------------------------------------------*/

//PHP CURL is disabled
function top3jps_admin_notice_curl_issue(){
    ?><div class="notice notice-warning">
        <p>Looks like PHP CURL is disabled on this server. It is required dependency for "Top 3 Lottery Jackpots" plugin (PHP CURL is used for jackpots update). Please ask your server administrator to enable PHP CURL. For any questions, please, use <a href="https://wordpress.org/support/plugin/top-3-jackpots">WordPress support forum for this plugin</a>.</p>
    </div><?php
}
/*-------------------------------------------------------------------------------------------------*/
/*-------------------------------------------------------------------------------------------------*/
