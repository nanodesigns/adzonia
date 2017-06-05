<?php
/**
 * CPT 'adzonia'
 *
 * Functions to initiate the Custom Post Type 'adzonia'.
 *
 * @author      nanodesigns
 * @category    Post Type
 * @package     AdZonia
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

/**
 * Register AdZonia Custom Post Type
 *
 * Custom post type to get the advertisement information
 * in WordPress' way.
 *
 * @since  1.0.0
 * ----------------------------------------------------
 */
function adzonia_register_cpt_adzonia() {
    $labels = array(
        'name'                  => _x( 'AdZonia', 'AdZonia', 'adzonia' ),
        'singular_name'         => _x( 'AdZonia', 'AdZonia', 'adzonia' ),
        'add_new'               => _x( 'New AdZonia', 'AdZonia', 'adzonia' ),
        'add_new_item'          => __( 'Add New AdZonia', 'adzonia' ),
        'edit_item'             => __( 'Edit AdZonia', 'adzonia' ),
        'new_item'              => __( 'New AdZonia', 'adzonia' ),
        'view_item'             => __( 'View AdZonia', 'adzonia' ),
        'search_items'          => __( 'Search AdZonia', 'adzonia' ),
        'not_found'             => __( 'No AdZonia is created yet. Try making one first', 'adzonia' ),
        'not_found_in_trash'    => __( 'No AdZonia found in Trash', 'adzonia' ),
        'parent_item_colon'     => __( 'Parent AdZonia:', 'adzonia' ),
        'menu_name'             => _x( 'AdZonia', 'AdZonia', 'adzonia' ),
    );

    $args = array(
        'labels'                => $labels,
        'hierarchical'          => false,
        'description'           => 'Get the advertisement information into post format',
        'supports'              => array( 'title', 'excerpt' ),
        'public'                => false,
        'show_ui'               => true,
        'show_in_menu'          => true,
        'menu_position'         => 25,
        'menu_icon'             => ADZ()->plugin_url() .'/assets/images/adzonia-icon.png',
        'show_in_nav_menus'     => false,
        'publicly_queryable'    => false,
        'exclude_from_search'   => true,
        'has_archive'           => false,
        'query_var'             => true,
        'can_export'            => true,
        'rewrite'               => true,
        'capability_type'       => 'post'
    );

    if( ! post_type_exists( 'adzonia' ) ) {
    	register_post_type( 'adzonia', $args );
    }
}

add_action( 'init', 'adzonia_register_cpt_adzonia' );


/**
 * Add Columns to AdZonia List Table
 * 
 * @param  array $columns Default WordPress columns for post types.
 * @return array          Modified columns.
 *
 * @since  1.2.0
 * ----------------------------------------------------
 */
function adzonia_set_custom_columns( $columns ) {

    //Insert columns after 'title'
    $index = array_search( 'title', array_keys( $columns ) );
    if( $index !== false ){
        $before = array_slice( $columns, 0, $index + 1 );
        $after = array_splice( $columns, $index + 1, count( $columns ) );
        $columns = $before + array(
			'ad_id'    => __( 'ID', 'adzonia' ),
			'ad_image' => __( 'Preview', 'adzonia' )
            ) + $after + array(
				'until'         => __( 'Until', 'adzonia' ),
				'adz_shortcode' => __( 'Shortcode', 'adzonia' )
            );
    }
    return $columns;

}

add_filter( 'manage_edit-adzonia_columns', 'adzonia_set_custom_columns', 50 );


/**
 * Fill the columns with their data.
 *
 * @since  1.2.0
 * 
 * @param  array $column  specific post type column.
 * @param  integer $post_id adzonia post id.
 * ----------------------------------------------------
 */
function adzonia_custom_column( $column, $post_id ) {
    $meta_data = get_post_meta( $post_id , '_adzonia_specs' , true );
    switch ( $column ) {
        case 'ad_id' :
            echo $post_id;
            break;

        case 'ad_image' :
            if( 'image_ad' === $meta_data['ad_type'] ) {
                $image_url = wp_get_attachment_url($meta_data['image_id']);
                echo '<img src="'. esc_url($image_url) .'" width="80" height="auto" />';
            } else {
                echo '<code>CodeAd</code>';
            }
            break;

        case 'until' :
            if( !empty($meta_data['end_date']) ) {
                echo mysql2date( 'd F Y', $meta_data['end_date']);
            } else {
                echo '&mdash;';
            }
            break;

        case 'adz_shortcode' :
            echo '<code>[adzonia id="'. $post_id .'"]</code>';
            break;
    }
}

add_action( 'manage_adzonia_posts_custom_column' , 'adzonia_custom_column', 10, 2 );
