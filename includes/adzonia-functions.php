<?php
/**
 * Parse the AdZonia arguments with defaults.
 *
 * @since  2.0.0 Introduced.
 * 
 * @param  array $args  Array of arguments.
 * @return array        Parsed arguments with defaults.
 * ----------------------------------------------------
 */
function adz_parse_defaults( $args ) {

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
function adz_tooltip( $id = '', $message = '', $position = 'top', $icon = 'dashicons dashicons-editor-help' ) {

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
    $ad_specs = adz_parse_defaults($meta_data);

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
