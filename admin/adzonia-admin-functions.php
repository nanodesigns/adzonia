<?php
/**
 * Enqueue necessary admin scripts.
 * Load scripts only where necessary using get_currecnt_screen().
 * ----------------------------------------------------
 */
function adzonia_admin_scripts() {

    $screen = get_current_screen();
    if( 'adzonia' === $screen->post_type && 'post' === $screen->base ) {
        
        /**
         * jQuery DateTimePicker
         */
        wp_register_style( 'jquery-datetimepicker', ADZ()->plugin_url() .'/libs/jquery-datetimepicker/jquery.datetimepicker.css' );
        wp_register_script( 'jquery-datetimepicker', ADZ()->plugin_url() .'/libs/jquery-datetimepicker/jquery.datetimepicker.min.js', array('jquery'), ADZ()->version, true );

        if( function_exists('wp_enqueue_media') ) {
            wp_enqueue_media();
        }
        else {
            wp_enqueue_script('media-upload');
            wp_enqueue_script('thickbox');
            wp_enqueue_style('thickbox');
        }

        wp_enqueue_style( 'adzonia-admin', ADZ()->plugin_url() .'/assets/css/adzonia-admin.css', array('jquery-datetimepicker'), ADZ()->version );
        wp_enqueue_script( 'adzonia-admin', ADZ()->plugin_url() . '/assets/js/adzonia-admin.min.js', array('jquery', 'jquery-ui-tabs', 'jquery-datetimepicker'), ADZ()->version, true );

        //load only on edit page (not in add-new page)
        //if( $screen->action == '' ) {
            global $post;
            $image_ad   = '';
            $code_ad    = '';
            $image_ad   = get_post_meta( $post->ID, 'wpadz_ad_image', true );
            $code_ad    = get_post_meta( $post->ID, 'wpadz_ad_code', true );

            wp_localize_script(
                'adzonia-admin',
                'adzonia',     //the var key in JS
                array(
                    'is_img_ad'    => $image_ad,
                    'is_code_ad'   => $code_ad,
                    'img_lib_head' => esc_html__( 'Choose Ad Image', 'adzonia' ),
                    'img_btn_text' => esc_html__( 'Choose Image', 'adzonia' )
                )
            );
        //}//endif( $screen->action == '' )

    } //endif
}

add_action('admin_enqueue_scripts', 'adzonia_admin_scripts');
