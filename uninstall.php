<?php
/**
 * Uninstall AdZonia
 *
 * Uninstalling/Deleteing the plugin with ads created,
 * with no traces left.
 *
 * @author      nanodesigns
 * @category    Core
 * @package     AdZonia/Uninstaller
 * @version     1.0.0
 */

//if uninstall not called from WordPress exit
if( !defined( 'ABSPATH' ) && !defined( 'WP_UNINSTALL_PLUGIN' ) )
    exit();

// Delete all the advertisements and their addition data
$get_adzonia_posts = get_posts( array(
			'post_type'      => 'adzonia',
			'posts_per_page' => -1,
			'post_status'    => 'any'
		) );

foreach ( $get_adzonia_posts as $post ) {
	setup_postdata( $post );

	wp_delete_post( $post->ID, true ); // bypass trash and delete forcefully
	delete_post_meta( $post->ID, '_adzonia_specs' );
}

wp_reset_postdata();
