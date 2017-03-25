<?php
/**
 * AdZonia Shortcode.
 * Usage: [adzonia id="#"] - pass the ID of the ad to show.
 * 
 * @see    show_adzonia()
 * 
 * @param  array $atts attributes that passed through shortcode.
 * @return string       formatted ad.
 * ----------------------------------------------------
 */
function adzonia_shortcode( $atts ) {    
    $atts  = shortcode_atts( array( 'id' => '' ), $atts );
    $ad_id = (int) $atts['id'];

    show_adzonia( $ad_id );
}

add_shortcode( 'adzonia', 'adzonia_shortcode' );
