<?php
/**
 * AJAX Receiver of Front-End Editor for Top 3 Lottery Jackpots Widget
 * 
 * @package Top3Jackpots
 * @since Top3Jackpots 1.0.0
 */

defined( 'ABSPATH' ) or die( '' );





/*-------------------------------------------------------------------------------------------------*/
/*---------------------------------------------- AJAX Receiver Handler ---------------------------------------------*/
/*-------------------------------------------------------------------------------------------------*/
function top3jps_ajax_frontend_editor_handler(){
    
    $response = new stdClass();
    $response->status = false;

    //Check required
    if( isset( $_GET['top3jps_ajax_frontend_editor_action'] ) && isset( $_GET['top3jps_ajax_frontend_editor_jsondata'] ) ){

        $action = sanitize_text_field( $_GET['top3jps_ajax_frontend_editor_action'] );
 
        switch( $action ){

            case 'save_settings':

                $json_data = stripslashes( $_GET['top3jps_ajax_frontend_editor_jsondata'] );
                $json_obj = json_decode( $json_data );
                if( !isset( $json_obj->customcss ) || !isset( $json_obj->userid ) || !isset( $json_obj->ispublic ) ){
                    $response->status = false;
                }
                
                update_option( 'top3jps_frontend_custom_css', urldecode( $json_obj->customcss ) );
                
                if( isset( $json_obj->userid ) && intval( $json_obj->userid ) > 0 ){
                    update_option( 'top3jps_user_id', intval( $json_obj->userid ) );
                }

                if( isset( $json_obj->ispublic ) && $json_obj->ispublic == 'true' ){
                    update_option( 'top3jps_is_public', true );
                } else {
                    update_option( 'top3jps_is_public', false );
                }

                $response->status = true;
                break;

            default: break;
        }


    }

    echo json_encode( $response ) . '###';
    wp_die();
}
add_action( 'wp_ajax_top3jps_ajax_frontend_editor_handler', 'top3jps_ajax_frontend_editor_handler' );
/*-------------------------------------------------------------------------------------------------*/
/*-------------------------------------------------------------------------------------------------*/