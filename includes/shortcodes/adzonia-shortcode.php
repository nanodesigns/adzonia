<?php
/**
 * Shortcode: AdZonia
 *
 * Showing the advertisement anywhere using the shortcode [adzonia id="#"].
 *
 * @author  	nanodesigns
 * @category 	Shortcode
 * @package 	AdZonia
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * AdZonia Shortcode.
 * Usage: [adzonia id="#"] - pass the ID of the ad to show.
 * 
 * @see    show_adzonia()
 * 
 * @param  array $atts  attributes that passed through shortcode.
 * @return string       formatted advertisement.
 * ----------------------------------------------------
 */
function adzonia_shortcode( $atts ) {    
    $atts  = shortcode_atts( array( 'id' => '' ), $atts );
    $ad_id = (int) $atts['id'];

    ob_start();
    	show_adzonia( $ad_id );
    return ob_get_clean();
}

add_shortcode( 'adzonia', 'adzonia_shortcode' );
