<?php
/**
 * Functions
 *
 * Core functions specific to AdZonia.
 *
 * @author      nanodesigns
 * @category    Functions
 * @package     AdZonia
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

/**
 * Parse the AdZonia arguments with defaults.
 *
 * @since  2.0.0 Introduced.
 * 
 * @param  array $args  Array of arguments.
 * @return array        Parsed arguments with defaults.
 * ----------------------------------------------------
 */
function adzonia_parse_defaults( $args ) {

    if( ! is_array($args) ) {
        return 'AdZonia arguments must be in an array';
    }

    $adz_defaults = array(
        'ad_type'     => 'image_ad',
        'image_id'    => '',
        'target_url'  => '',
        'code'        => '',
        'ad_location' => '',
        'end_date'    => '',
    );

    $r =& $args;

    return array_merge( $adz_defaults, $r );

}

/**
 * AdZonia Tooltip
 *
 * Display a responsive, and mobile devices-friendly, conflict-free CSS tooltip dynamically.
 *
 * @since  2.0.0
 * 
 * @param  string $id       HTML id to connect aria-describedby.
 * @param  string $message  The i18 string/plain text.
 * @param  string $position left | right | top
 * @param  string $icon     Dashicon class.
 * @return string           Formatted tooltip that needs proper CSS.
 * ------------------------------------------------------------------------------
 */
function adzonia_tooltip( $id = '', $message = '', $position = 'top', $icon = 'dashicons dashicons-editor-help' ) {

    if( empty($message) )
        return;

    switch ($position) {
        case 'left':
            $class = 'adz-tooltip-left ';
            break;

        case 'right':
            $class = 'adz-tooltip-right ';
            break;

        case 'bottom':
            $class = 'adz-tooltip-bottom ';
            break;
        
        default:
            $class = 'adz-tooltip-top ';
            break;
    }

    ob_start(); ?>

    <span class="adz-tooltip <?php echo esc_attr( $class ) . esc_attr( $icon ); ?>">
        <span id="<?php echo esc_attr( $id ); ?>" class="adz-tooltip-message" role="tooltip">
            <?php echo $message; ?>
        </span>
    </span>

    <?php
    return ob_get_clean();

}

/**
 * AdZonia Ad Places.
 * @return array
 * ------------------------------------------------------------------------------
 */
function __adzonia_ad_places() {
    $ad_places = array(
        'default' => array(
            'before_content' => __( 'Before all the Post/Page Content', 'adzonia' ),
            'after_content'  => __( 'After all the Post/Page Content', 'adzonia' )
        )
    );

    return apply_filters( 'adzonia_ad_places', $ad_places );
}

/**
 * Assistance from Giuseppe (aka Gmazzap, G.M.) (@gmazzap) - Italy.
 * Show the advertisement into the starting of the posts or
 * into the ending of the posts.
 * 
 * @param  \WP_Query $query
 * @since  1.2.1
 * ----------------------------------------------------
 */
function adzonia_ad_position_executioner( \WP_Query $query ) {
    if ( ! $query->is_main_query() ) {
        return;
    }
    
    $key =  '_adzonia_location';
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
    $query->adzonia_after  = $after;

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

/**
 * Get AdZonia advertisement.
 *
 * @since  1.0.0 Introduced.
 * @since  2.0.0 Modified and added more fallbacks.
 * 
 * @param  integer $advertisement_id Advertisement Post ID.
 * @return string                    The formatted advertisement.
 * ----------------------------------------------------
 */
function get_adzonia( $advertisement_id ) {

    if( empty($advertisement_id) ) {
        return;
    }

    // Get the advertisement post.
    $advertisement = get_post( $advertisement_id );

    if( empty($advertisement) ) {
        return;
    }

    if( 'adzonia' !== $advertisement->post_type ) {
        return;
    }

    // Get the meta information of that advertisement.
    $meta_data = get_post_meta( $advertisement->ID, '_adzonia_specs', true );

    if( empty($meta_data) ) {
        return;
    }

    // Settle advertisement specs.
    $ad_specs = adzonia_parse_defaults($meta_data);

    // Set some basics
    $the_advertisement = ''; // to avoid undefined index.
    $date_today        = strtotime( date( 'Y-m-d H:i:s', current_time('timestamp') ) );

    // Settle the values.
    $ad_type     = $ad_specs['ad_type'];
    $end_date    = $ad_specs['end_date'];
    $ad_location = $ad_specs['ad_location'];

    if( !empty($end_date) && $date_today > strtotime($end_date) ) {
        return;
    }

    if( 'image_ad' === $ad_type ) {
        $image_id   = $ad_specs['image_id'];
        $image_url  = wp_get_attachment_url($image_id);
        $target_url = $ad_specs['target_url'];
        $image_alt  = get_post_meta( $image_id, '_wp_attachment_image_alt', true );

        // Start the anchor tag.
        $the_advertisement .= !empty($target_url) ? '<a href="'. esc_url( $target_url ) .'">' : '';

            // Display the image.
            if( !empty($image_url) ) {
                $the_advertisement .= '<img src="'. esc_url($image_url) .'" alt="Advertisement: '. $image_alt .'">';
            }

        // End the anchor tag.
        $the_advertisement .= !empty($target_url) ? '</a>' : '';

    } else if( 'code_ad' === $ad_type ) {
        $ad_code = $ad_specs['code'];

        // Display the code.
        if( !empty($ad_code) ) {
            $the_advertisement .= stripslashes($ad_code);
        }
    }

    return $the_advertisement;
}


/**
 * Show AdZonia
 *
 * A wrapper function to echo the returned AdZonia advertisement
 * content.
 * 
 * @see    get_adzonia()
 * 
 * @param  integer $advertisement_id The ID of the AdZonia post.
 * ----------------------------------------------------
 */
function show_adzonia( $advertisement_id ) {
    echo get_adzonia( intval($advertisement_id) );
}
