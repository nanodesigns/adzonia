<?php
// Get the back end options
$get_options = get_option('adzonia_options');


/**
 * AdZonia FrontEnd stylesheet.
 * @see  option is checked or not.
 * ----------------------------------------------------
 */
if( $get_options['adzonia_css_check'] === true ) {
    function adzonia_output_css() {
        wp_enqueue_style( 'adzonia', plugins_url('assets/css/adzonia.css', __FILE__) );
    }
    add_action( 'wp_enqueue_scripts', 'adzonia_output_css' );
}


/**
 * Assistance from Giuseppe (aka Gmazzap, G.M.) (@gmazzap) - Italy.
 * @param  \WP_Query $query
 * @since  1.2.1
 * ----------------------------------------------------
 *
 * Show the advertisement into the starting of the posts or
 * into the ending of the posts.
 */
function adzonia_ad_position_executioner( \WP_Query $query ) {
    if ( ! $query->is_main_query() ) {
        return;
    }
    $key =  'wpadz_location';
    $ads_args = array(
            'post_type' => 'adzonia',
            'meta_query' => array(
              array(
                'key' => $key,
                'value' => array( 'after_content', 'before_content' ),
                'compare' => 'IN'
              )
            ),
                'nopaging' => true,
                'post_status' => 'publish'
            );
    $ads = get_posts( $ads_args );
    if ( empty( $ads ) ) {
        return;
    }
    $before = '';
    $after = '';
    foreach( $ads as $ad ) {
        $ad_content = get_adzonia( $ad->ID );
        if ( get_post_meta( $ad->ID, $key, true ) === 'before_content' ) {
            $before .= $ad_content;
        } else {
            $after .= $ad_content;
        }
    }

    $query->adzonia_before = $before;
    $query->adzonia_after = $after;

    add_filter( 'the_content', 'adzonia_ad_position_func', PHP_INT_MAX  );
    add_filter( 'the_excerpt', 'adzonia_ad_position_func', PHP_INT_MAX  );

    // at the very end remove the filter
    add_action( 'loop_end', 'adzonia_remove_hooks' );
}
add_action( 'loop_start', 'adzonia_ad_position_executioner' );

function adzonia_ad_position_func( $content ) {
    global $wp_query;
    if ( isset($wp_query->adzonia_before) && ! empty($wp_query->adzonia_before) ) {
        $content = $wp_query->adzonia_before . $content;
    }
    if ( isset($wp_query->adzonia_after) && ! empty($wp_query->adzonia_after) ) {
        $content = $content . $wp_query->adzonia_after;
    }
    // reset $wp_query vars
    //$wp_query->adzonia_before = $wp_query->adzonia_after = '';

    return $content;
}

function adzonia_remove_hooks() {
    remove_action( 'loop_start', 'adzonia_ad_position_executioner' );
    remove_filter( 'the_content', 'adzonia_ad_position_func', PHP_INT_MAX  );
    remove_filter( 'the_excerpt', 'adzonia_ad_position_func', PHP_INT_MAX  );
    remove_action( current_filter(), __FUNCTION__  );
}
