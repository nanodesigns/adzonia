<?php
/**
 * Get AdZonia ad
 * 
 * @param  integer $ad_id Would be the post ID.
 * @return string $the_ad The complete advertisement.
 * ----------------------------------------------------
 */
function get_adzonia( $ad_id ) {
    $the_ad = '';

    $ad = get_post( $ad_id );

    if( $ad !== '' && $ad->post_type === 'adzonia' ) {

        $this_date = date( 'Y-m-d H:i:s', current_time( 'timestamp' ) );
        $datetoday = strtotime( $this_date );

        $postID = $ad->ID;

        $image_ad_url   = get_post_meta( $postID, 'wpadz_ad_image', true );
        $ad_code        = get_post_meta( $postID, 'wpadz_ad_code', true );
        $end_date       = get_post_meta( $postID, 'wpadz_end_date', true );
        $get_target_url = get_post_meta( $postID, 'wpadz_target_url', true );
        $target_url     = $get_target_url != '' ? $get_target_url : '#';

        $endDateString = ( $end_date != '' ? strtotime( $end_date ) : '' );

        if( $datetoday <= $endDateString ) {

            $the_ad .= '<div id="adzonia-ad-'. $postID .'" class="adzonia-holder">';

                if( !empty( $get_target_url ) ) $the_ad .= '<a href="'. esc_url( $target_url ) .'">';

                    // Image ad
                    if ( $image_ad_url !== '' ) {
                        $the_ad .= '<img src="'. esc_url( $image_ad_url ) .'" alt="Advertisement '.the_title_attribute(array('echo'=>0,'post'=>$postID)) .'" />';
                    }
                    // Code ad
                    else if ( $ad_code !== '' ) {
                        $the_ad .= stripslashes( $ad_code );
                    }

                if( !empty( $get_target_url ) ) $the_ad .= '</a>';

            $the_ad .= '</div> <!-- /#adzonia-ad-'. $postID .' .adzonia-holder -->';

        } else {
            $the_ad .= '<div id="adzonia-ad-'. $postID .'" class="adzonia-holder">';
                $the_ad .= '<span style="color:red;">'. __( '<strong>WARNING:</strong> Assigned ad is expired! <em>Extend</em> the term or <em>Delete</em> it.', 'adzonia' ) .'</span>';
            $the_ad .= '</div> <!-- /#adzonia-ad-'. $postID .' .adzonia-holder -->';
        }

    } else {
        $the_ad .= '<div class="adzonia-holder">';
            $the_ad .= '<span style="color:red;">'. __( '<strong>Sorry!</strong> No such Ad exists!', 'adzonia' ) .'</span>';
        $the_ad .= '</div> <!-- /.adzonia-holder -->';
    }
    return $the_ad;    
}


/**
 * Show AdZonia
 * 
 * @see  get_adzonia()
 * 
 * @param  integer $ad_id pass the ID of the AdZonia post.
 * ----------------------------------------------------
 */
function show_adzonia( $ad_id ) {
    echo get_adzonia( $ad_id );
}
