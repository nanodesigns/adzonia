<?php
/**
 * Additional Functions, that can be used if necessary.
 * @package  WP AdZonia
 * @author  Mayeenul Islam <wz.islam@gmail.com>
 */

/**
 * Remove Yoast's WordPress SEO Metabox & Columns.
 * If WordPress SEO plugin is activated, remove the WPSEO Meta Box & Columns
 */
function remove_yoast_metabox_adzonia(){
    remove_meta_box( 'wpseo_meta', 'adzonia', 'normal' );
}
add_action( 'add_meta_boxes', 'remove_yoast_metabox_adzonia', 11 );

function rkv_remove_columns( $columns ) {
	unset( $columns['wpseo-score'] );
	unset( $columns['wpseo-title'] );
	unset( $columns['wpseo-metadesc'] );
	unset( $columns['wpseo-focuskw'] );

	return $columns;
}
add_filter ( 'manage_edit-adzonia_columns', 'rkv_remove_columns' );