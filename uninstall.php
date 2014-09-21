<?php
//if uninstall not called from WordPress exit
if( !defined( 'ABSPATH' ) && !defined( 'WP_UNINSTALL_PLUGIN' ) )
    exit();

/**
*	Check for hook
*   -----------------------------------------------------*/

/*if ( function_exists('register_uninstall_hook') )
	register_uninstall_hook( __FILE__, 'uninstall_adzonia_features' );*/


// To delete options from options table
delete_option('adzonia_options');

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

}

wp_reset_postdata();


/**
*	Delete options in database
*   -----------------------------------------------------*/

function uninstall_adzonia_features() {

	// To delete options from options table
	//delete_option('post_type_rules_flased_adzonia');

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

	}

	wp_reset_postdata();

}