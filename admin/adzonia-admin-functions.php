<?php
/**
 * Admin Functions
 *
 * Functions specific to admin specific pages.
 *
 * @author      nanodesigns
 * @category    Admin/Functions
 * @package     AdZonia
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

/**
 * Enqueue necessary admin scripts.
 * Load scripts only where necessary using get_current_screen().
 * ----------------------------------------------------
 */
function adzonia_admin_scripts() {

    $screen = get_current_screen();
    if( 'adzonia' === $screen->post_type && 'post' === $screen->base ) {

        if( function_exists('wp_enqueue_media') ) {
            wp_enqueue_media();
        }
        else {
            wp_enqueue_script('media-upload');
            wp_enqueue_script('thickbox');
            wp_enqueue_style('thickbox');
        }

        wp_enqueue_style( 'adzonia', ADZ()->plugin_url() .'/assets/css/adzonia-admin.css', array(), ADZ()->version );
        wp_enqueue_script( 'adzonia', ADZ()->plugin_url() .'/assets/js/adzonia-admin.min.js', array('jquery', 'jquery-ui-datepicker'), ADZ()->version, true );

        wp_localize_script(
            'adzonia',
            'adzonia',     //the var key in JS
            array(
                'img_lib_head'    => esc_html__( 'Choose Ad Image', 'adzonia' ),
                'img_btn_text'    => esc_html__( 'Choose Image', 'adzonia' ),
                'msg_both_empty'  => esc_html__( 'Oops! Both image and code fields are empty', 'adzonia' ),
                'msg_both_filled' => esc_html__( 'Oops! You can&rsquo;t fill both Ad Image and Code field!', 'adzonia' )
            )
        );

    } //endif
}

add_action('admin_enqueue_scripts', 'adzonia_admin_scripts');
