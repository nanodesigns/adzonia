<?php
/**
 * AdZonia Shortcode.
 * Usage: [adzonia id="#"] - pass the ID of the ad to show.
 * @see  show_adzonia()
 * @param  array $atts attributes that passed through shortcode.
 * @return string       formatted ad.
 * ----------------------------------------------------
 */
function adzonia_shortcode( $atts ) {    
    $atts = shortcode_atts( array(
                'id' => '',
            ), $atts );

    $adID = $atts['id'];

    ob_start();
    ?>
        <div class="adzonia-ad <?php echo $adID != '' ? 'adzonia-'. $adID : '' ?>">
            <?php show_adzonia( $adID ); ?>
        </div>
    <?php
    return ob_get_clean();
}

add_shortcode( 'adzonia', 'adzonia_shortcode' );
