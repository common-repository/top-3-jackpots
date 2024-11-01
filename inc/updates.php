<?php
/**
 * Manage updates for jackpots
 * 
 * @package Top3Jackpots
 * @since Top3Jackpots 1.0.0
 */

defined( 'ABSPATH' ) or die( '' );





/*-------------------------------------------------------------------------------------------------*/
/*------------------------------- PLUGIN ACTIVATION & DEACTIVATION --------------------------------*/
/*-------------------------------------------------------------------------------------------------*/
function top3jps_updates_set(){
    
    //register wp cron
    wp_schedule_event( time(), 'hourly', 'top3jps_cron_update_jackpots_action' );
    top3jps_cron_update_jackpots();
}


//Deactivation
function top3jps_updates_unset(){
    
    //unregister wp cron
    wp_clear_scheduled_hook( 'jpcron_top3jps' );

    //clear lotteries data
    delete_option( 'top3jps_lottodata' );
    delete_option( 'top3j_lottodata_preset' );
}
add_action( 'top3jps_deactivation', 'top3jps_updates_unset' );
/*-------------------------------------------------------------------------------------------------*/
/*-------------------------------------------------------------------------------------------------*/





/*-------------------------------------------------------------------------------------------------*/
/*----------------------------------------- WP CRON TASKS -----------------------------------------*/
/*-------------------------------------------------------------------------------------------------*/
if( wp_next_scheduled( 'top3jps_cron_update_jackpots_action' ) === false ){
    wp_schedule_event( time(), 'hourly', 'top3jps_cron_update_jackpots_action' );
}


function top3jps_cron_update_jackpots(){

    //Stop further execution if PHP CURL is disabled on server
    if( !function_exists( 'curl_init' ) ){
        return;
    }

    $request_headers = array(
        'User-Agent:Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/63.0.3239.108 Safari/537.36',
        'Accept: text/html,application/xhtml+xml,application/xml;',
        'Accept-Language:en-GB,en-US;q=0.9,en;q=0.8'
    );

    $ch = curl_init();
    curl_setopt( $ch, CURLOPT_URL, base64_decode( 'aHR0cDovL2ZlZWRzLmxvdHRvZWxpdGUuY29tL3Jzcy5waHA/bGFuZz1lbiZhY2NvdW50PWY4YmEwMzk2JnNpdGU9MiZ0eXBlPTM=' ) );
    curl_setopt( $ch, CURLOPT_HTTPHEADER, $request_headers );
    curl_setopt( $ch, CURLOPT_RETURNTRANSFER, 1 );
    curl_setopt( $ch, CURLOPT_CONNECTTIMEOUT, 10 );
    curl_setopt( $ch, CURLOPT_TIMEOUT, 10 );
    $response = curl_exec( $ch );

    $x = new SimpleXmlElement( $response );

    $site_url = site_url();
    $default_lotteries_data = array(
        2 => array( "name" => "MegaMillions", "currency" => "USD", "nextday" => true, "GMT" => "04:00:00", "afflink" => "/visit/mega-millions/", "logourl" => "logos/2.png", "status" => true ),
        3 => array( "name" => "Powerball", "currency" => "USD", "nextday" => true, "GMT" => "04:00:00", "afflink" => "/visit/powerball/", "logourl" => "logos/3.png", "status" => true ),
        8 => array( "name" => "EuroMillions", "currency" => "EUR", "nextday" => false, "GMT" => "21:00:00", "afflink" => "/visit/euromillions/", "logourl" => "logos/8.png", "status" => true ),
        12 => array( "name" => "ElGordo", "currency" => "EUR", "nextday" => false, "GMT" => "12:00:00", "afflink" => "/visit/el-gordo/", "logourl" => "logos/12.png", "status" => true ),
        18 => array( "name" => "SuperEnalotto", "currency" => "EUR", "nextday" => false, "GMT" => "19:00:00", "afflink" => "/visit/superenalotto/", "logourl" => "logos/18.png", "status" => true ),
        25 => array( "name" => "UKEuroMillions", "currency" => "GBR", "nextday" => false, "GMT" => "21:00:00", "afflink" => "/visit/euromillions-uk/", "logourl" => "logos/25.png", "status" => true )
    );
    $default_lotteries_id = array_keys( $default_lotteries_data );

    $lotteries_data_ = get_option( 'top3j_lottodata_preset', false );
    if( !$lotteries_data_ ){
        $lotteries_data_ = serialize( array() );
    }
    $lotteries_data = unserialize( $lotteries_data_ );
    if( !is_array( $lotteries_data ) ){
        $lotteries_data = array();
    }
    $lotteries_id = array();
    if( sizeof( $lotteries_data ) > 0 ){
        $lotteries_id = array_keys( $lotteries_data );
    }

    $i = 1;
    $lottodata = array();

    foreach( $x->channel->item as $entry ){

        $url = strval( $entry->link );
        $query = parse_url($url, PHP_URL_QUERY);
        $vars = array();
        parse_str( $query, $vars );
        $lot_id = intval( $vars['lot_id'] );

        //Skip entry if no lottery ID
        if( $lot_id <= 0 ){
            continue;
        }

        //Default values
        $logourl = '';
        $afflink = '';
        $status = false;
        if( in_array( $lot_id, $default_lotteries_id ) ){
            $logourl = $default_lotteries_data[$lot_id]['logourl'];
            $afflink = $default_lotteries_data[$lot_id]['afflink'];
            $status = $default_lotteries_data[$lot_id]['status'];
        } else {
            continue;
        }

        //User's values
        if( in_array( $lot_id, $lotteries_id ) ){
            $logourl = $lotteries_data[$lot_id]['logourl'];
            $afflink = $lotteries_data[$lot_id]['afflink'];
            $status = $lotteries_data[$lot_id]['status'];
        }

        //Skip disabled lottery
        if( !$status ){
            continue;
        }

        $desc = $entry->description ;
        str_replace( "&nbsp;", "", $desc );

        $ex1 = explode( "Draw date:", $desc );
        $ex2 = explode( "Jackpot:", $ex1[1] );
        $ex3 = explode( "<br /><a", $ex2[1] );


        $title = strval( $entry->title );
        $enddate = $ex2[0] ;
        $amount = trim( $ex3[0] );

        $exdate = explode( "<br />", $enddate );
        $enddate = $exdate[0] ;

        $explodedate = explode( "-", $enddate );
        $year = trim( $explodedate[0] );
        $month = trim( $explodedate[1] );
        $day = trim( $explodedate[2] );

        if( $default_lotteries_data[$lot_id]['nextday'] ){
            $drawdate = gmdate( 'm/d/Y H:i:s', strtotime( $year . '-' . $month . '-' . $day . ' ' . $default_lotteries_data[$lot_id]['GMT'] . ' + 1 day' ) );
        } else {
            $drawdate = gmdate( 'm/d/Y H:i:s', strtotime( $year . '-' . $month . '-' . $day . ' ' . $default_lotteries_data[$lot_id]['GMT'] ) );
        }

        $jpdate = $drawdate . " UTC";

        $lottodata[$i]['afflink'] = $afflink;
        $lottodata[$i]['logo'] = $logourl;
        $lottodata[$i]['title'] = $title;
        $lottodata[$i]['amount'] = $amount;
        $lottodata[$i]['jpdate'] = $jpdate;

        $i++;
        if( $i == 4 ){
            break;
        }
    }

    update_option( 'top3jps_lottodata', serialize( $lottodata ) );
}
add_action( 'top3jps_cron_update_jackpots_action', 'top3jps_cron_update_jackpots' );
/*-------------------------------------------------------------------------------------------------*/
/*-------------------------------------------------------------------------------------------------*/ 