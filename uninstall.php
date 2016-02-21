<?php
/**
 * Uninstallation
 * @package  wp-adzonia
 * ----------------------------------------------------
 */
//if uninstall not called from WordPress exit
if( !defined( 'ABSPATH' ) && !defined( 'WP_UNINSTALL_PLUGIN' ) )
    exit();


/**
 * DELETE ALL THE THINGS, THE PLUGIN CREATED
 * ----------------------------------------------------
 */

// To delete options from options table
delete_option('adzonia_options');

// Delete all the advertisements and their addition data
$adz_args = array(
			'post_type' => 'adzonia',
			'posts_per_page' => -1,
			'post_status' => 'any'
		);

$get_adzonia_posts = get_posts( $adz_args );

foreach ( $get_adzonia_posts as $post ) {
	setup_postdata( $post );

	$postid = $post->ID;
	wp_delete_post( $postid, true ); //bypass trash and delete forcefully
	delete_post_meta( $postid, 'wpadz_ad_image' );
	delete_post_meta( $postid, 'wpadz_ad_code' );
	delete_post_meta( $postid, 'wpadz_end_date' );
	delete_post_meta( $postid, 'wpadz_target_url' );
	delete_post_meta( $postid, 'wpadz_location' );
}

wp_reset_postdata();