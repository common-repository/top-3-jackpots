<?php
/**
 * Template of Front-End Editor for Top 3 Lottery Jackpots Widget
 * 
 * @package Top3Jackpots
 * @since Top3Jackpots 1.0.0
 */

defined( 'ABSPATH' ) or die( '' );





/*-------------------------------------------------------------------------------------------------*/
/*---------------------------------------- Editor Template ----------------------------------------*/
/*-------------------------------------------------------------------------------------------------*/
//Render Front-End Editor
function top3jps_render_frontend_editor(){

    $version = '1.0.0';
    wp_enqueue_style( 'top3jps-frontend-editor', TOP_3_JP_ROOT_URL . 'inc/frontend-editor/styles.css', array(), $version );
    wp_enqueue_style( 'jquery' );
    wp_enqueue_style( 'jquery-form' );
    wp_enqueue_script( 'jquery-ui-draggable' );
    wp_enqueue_script( 'top3jps-frontend-editor', TOP_3_JP_ROOT_URL . 'inc/frontend-editor/functions.js', array( 'jquery' , 'jquery-form' ), $version );
    
    $user_id = get_option( 'top3jps_user_id', 1 );
    $custom_css = get_option( 'top3jps_frontend_custom_css', '' );
    $is_public = get_option( 'top3jps_is_public', true );
    $is_public_yes = '';
    $is_public_no = ' selected';
    if( $is_public ){
        $is_public_yes = 'selected';
        $is_public_no = '';
    }

    if( !is_admin() ){
        $preview_button = '<span id="top3jps-preview-editor-css" title="Preview Current Settings">Preview</span>';
    }

    $lottodata_ = get_option( 'top3jps_lottodata', false );
    if( $lottodata_ ){
        $lottodata = unserialize( $lottodata_ );
    } else {
        $lottodata = array();
    }

    $ready_status_class = 'top3jps-true';
    $ready_status_text = 'READY';
    if( !is_array( $lottodata ) || sizeof( $lottodata ) < 1 ){
        $ready_status_class = 'top3jps-false';
        $ready_status_text = 'impossible to collect lotteries data';
    }

    $clicks_history = get_option( 'top3jps_clicks_history', array() );
    $last_day_clicks = 0;
    $last_week_clicks = 0;
    $last_month_clicks = 0;
    $clicks_limit = 0;

    $clear_clicks_history = array_values( $clicks_history );

    if( sizeof( $clear_clicks_history ) > 0 ){
        for( $i = sizeof( $clear_clicks_history ) - 1; $i >= 0; $i-- ){

            //latest value
            if( $i === sizeof( $clear_clicks_history ) - 1 ){
                $last_day_clicks = $clear_clicks_history[$i];
            }

            //latest 7 values
            if( $i >= sizeof( $clear_clicks_history ) - 7 ){
                $last_week_clicks += $clear_clicks_history[$i];
            }

            //latest 28 days
            if( $i >= sizeof( $clear_clicks_history ) - 28 ){
                $last_month_clicks += $clear_clicks_history[$i];
            }
        }
    }

    echo '<div id="top3jps-settings-editor">' .
            '<div>' .
                '<div id="top3jps-settings-editor-header-1">' .
                    '<span id="top3jps-settings-header">Top 3 Lottery Jackpots Settings</span>' .
                    '<span id="top3jps-hide-editor" title="Close Settings">x</span>' .
                '</div>' .
                '<div id="top3jps-settings-editor-header-2">' .
                    '<span id="top3jps-general-tab-toggle" class="top3jps-settings-tab-toggle top3jps-toggle-active" data-target="top3jps-general-tab">General</span>' .
                    '<span id="top3jps-info-tab-toggle" class="top3jps-settings-tab-toggle" data-target="top3jps-info-tab">Info</span>' .
                    '<span id="top3jps-custom-css-tab-toggle" class="top3jps-settings-tab-toggle" data-target="top3jps-custom-css-input">Custom CSS</span>' .
                '</div>' .
                '<div id="top3jps-general-tab" class="top3jps-settings-tab-target top3jps-tab-active">' .
                    '<div class="top3jps-settings-row"><label for="top3jps-user-id-input">User ID: </label><input id="top3jps-user-id-input" type="number" value="' . $user_id . '" min="1" max="1000000" step="1" autocomplete="off"></div>' .
                    '<div class="top3jps-settings-row"><label for="top3jps-is-public">Is Public: </label>' .
                        '<select id="top3jps-is-public">' .
                            '<option value="true"' . $is_public_yes . '>Yes</option>' .
                            '<option value="false"' . $is_public_no . '>No</option>' .
                        '</select>' .
                    '</div>' .
                    '<div class="top3jps-settings-row"><span class="top3jps-ready-status ' . $ready_status_class . '">*Plugin Status: ' . $ready_status_text . '</span></div>' .
                    '<div class="top3jps-settings-row top3jps-plugin-description">' .
                        '<p><span class="top3jps-bold">To use "Top 3 Lottery Jackpots" widget in your site sidebar, use the following steps:</span></p>' .
                        '<p><span class="top3jps-bold">1</span> Go to admin panel and add a WordPress "Text" widget in your sidebar on "Appearance => Widgets" admin screen.</p>' .
                        '<p><span class="top3jps-bold">2</span> Insert shortcode [top3jackpots] into content area of the text widget and click "Save" button.</p>' .
                        '<p><span class="top3jps-bold">3</span> Visit front-end pages and make sure the widget is shown correctly for all pages. Make sure mobile widget is shown correctly for all pages (for Home Page is not used by default). For any CSS adjustments, click "Custom CSS" tab of this settings box and adjust appearance of the widget using CSS. Use "Preview" button for quick live preview the changes and "Save" button to save the changes.</p>' .
                        '<p><span class="top3jps-bold">4</span> Open an affiliate account on <a href="https://www.24monetize.com/" target="_blank">https://www.24monetize.com/</a></p>' .
                        '<p><span class="top3jps-bold">5</span> Get your affiliate id from the affiliate panel, e.g 845.</p>' .
                        '<p><span class="top3jps-bold">6</span> In "General" tab of this settings box specify your User ID and click "Save" button.</p>' .
                        '<p><span class="top3jps-bold">7</span> Switch to "Yes" the "Is Public" option and click "Save" button to make the widget visible for all users.</p>' .
                        '<p><span class="top3jps-bold">8</span> Click on the widget "Play Now" links and make sure they redirect users correctly.</p>' .
                        '<p><span class="top3jps-bold">9</span> Use "Info" tab of this settings box to see clicks history.</p>' .
                    '</div>' .
                '</div>' .
                '<div id="top3jps-info-tab" class="top3jps-settings-tab-target">' .
                    '<div class="top3jps-settings-row top3jps-info-row"><span class="top3jp-info-label">Last Day Clicks: </span><span class="top3jp-info-value">' . $last_day_clicks . '</span></div>' .
                    '<div class="top3jps-settings-row top3jps-info-row"><span class="top3jp-info-label">Last Week Clicks: </span><span class="top3jp-info-value">' . $last_week_clicks . '</span></div>' .
                    '<div class="top3jps-settings-row top3jps-info-row"><span class="top3jp-info-label">Last Month Clicks: </span><span class="top3jp-info-value">' . $last_month_clicks . '</span></div>' .
                '</div>' .
                '<textarea id="top3jps-custom-css-input" class="top3jps-settings-tab-target" value="' . $custom_css . '" autocomplete="off">' . $custom_css . '</textarea>' .
                $preview_button .
                '<span id="top3jps-save-settings" title="Save Current Settings">Save</span>' .
            '</div>' .
            '<form id="top3jps-ajax-form" style="display: none;" action="' . admin_url( "admin-ajax.php?action=top3jps_ajax_frontend_editor_handler" ) . '" method="get" enctype="multipart/form-data">' .
                '<input id="top3jps_ajax_frontend_editor_action" name="top3jps_ajax_frontend_editor_action" type="text" value="" autocomplete="off">' .
                '<input id="top3jps_ajax_frontend_editor_jsondata" name="top3jps_ajax_frontend_editor_jsondata" type="text" value="" autocomplete="off">' .
            '</form>' .
        '</div>';
}



//Bind Front-End Editor
function top3jps_bind_frontend_editor(){
    
    if( function_exists( 'is_user_logged_in' ) && function_exists( 'current_user_can' ) && is_user_logged_in() && current_user_can( 'manage_options' ) && function_exists( 'top3jps_render_frontend_editor' ) ){
        if( is_admin() ){
            add_action( 'admin_footer', 'top3jps_render_frontend_editor' );
        } else {
            add_action( 'wp_footer', 'top3jps_render_frontend_editor' );
        }
    }
}
add_action( 'init', 'top3jps_bind_frontend_editor' );