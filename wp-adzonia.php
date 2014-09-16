<?php
/**
 * Plugin Name: WP AdZonia
 * Plugin URI: http://nanodesignsbd.com
 * Description: A simpler and easier Ad management Plugin for WordPress sites, and most astonishing - it's in WordPress way
 * Version: 1.2
 * Author: Mayeenul Islam (@mayeenulislam)
 * Author URI: http://nishachor.com
 * License: GNU General Public License v2.0
 * License URI: http://www.gnu.org/licenses/gpl-2.0.html
 */


/*  Copyright 2014 nanodesigns (email: info@nanodesignsbd.com)

    This plugin is a free software; you can redistribute it and/or modify
    it under the terms of the GNU General Public License, version 2, as
    published by the Free Software Foundation.

    This plugin is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.

    You should have received a copy of the GNU General Public License
    along with this program; if not, write to the Free Software
    Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
*/

// let not call the files directly
if( !defined( 'ABSPATH' ) ) exit;


/**
*   MAKE PLUGIN TRANSLATION-READY
*   -----------------------------------------------------*/
load_plugin_textdomain( 'wp-adzonia', FALSE, 'wp-adzonia/assets/languages' );



/**
*   AdZonia CSS
*    - For Admin styling.
*    - For Front-end styling.
*   -----------------------------------------------------*/

function adzonia_css() {
    wp_enqueue_style( 'adzonia-admin-style', plugins_url('css/admin-style.css', __FILE__) );
    wp_enqueue_style( 'datepicker-style', plugins_url('css/jquery.datetimepicker.css', __FILE__) );
}

add_action( 'admin_enqueue_scripts', 'adzonia_css' );

function adzonia_output_css() {
    wp_enqueue_style( 'adzonia-output-style', plugins_url('css/output.css', __FILE__) );
}

add_action( 'wp_enqueue_scripts', 'adzonia_output_css' );




/**
*   ENQUEUE NECESSARY SCRIPTS
*   Custom post type to get the advertisement information in WordPress way.
*   -----------------------------------------------------*/

function adzonia_admin_scripts() {

    $screen = get_current_screen();

    if( $screen->post_type === 'adzonia' && $screen->base == 'post' ) {

        //wp_enqueue_script( 'jquery-lib-scripts', plugins_url('/js/jquery-1.11.1.min.js', __FILE__) );
        wp_enqueue_script( 'jquery' );
        wp_enqueue_script( 'datepicker-js', plugins_url('/js/jquery.datetimepicker.js', __FILE__), '', '', true );
        wp_enqueue_script( 'adzonia', plugins_url( '/js/adzonia-scripts.min.js', __FILE__ ), '', '', true );

        if(function_exists('wp_enqueue_media')) {
            wp_enqueue_media();
        }
        else {
            wp_enqueue_script('media-upload');
            wp_enqueue_script('thickbox');
            wp_enqueue_style('thickbox');
        }

    } //endif

}

add_action('admin_enqueue_scripts', 'adzonia_admin_scripts');


function media_uploader() {
    global $post_type;
    if( 'adzonia' == $post_type) {
        
    }
}

add_action('admin_enqueue_scripts', 'media_uploader');




/**
*   REGISTER AdZonia POST TYPE
*
*   Custom post type to get the advertisement information
*    in WordPress way
*   -----------------------------------------------------*/

function register_cpt_adzonia() {

    $labels = array(
        'name' => _x( 'AdZonia', 'wp-adzonia' ),
        'singular_name' => _x( 'AdZonia', 'wp-adzonia' ),
        'add_new' => _x( 'Add New', 'wp-adzonia' ),
        'add_new_item' => _x( 'Add New AdZonia', 'wp-adzonia' ),
        'edit_item' => _x( 'Edit AdZonia', 'wp-adzonia' ),
        'new_item' => _x( 'New AdZonia', 'wp-adzonia' ),
        'view_item' => _x( 'View AdZonia', 'wp-adzonia' ),
        'search_items' => _x( 'Search AdZonia', 'wp-adzonia' ),
        'not_found' => _x( 'No AdZonia is created yet. Try making one first', 'wp-adzonia' ),
        'not_found_in_trash' => _x( 'No AdZonia found in Trash', 'wp-adzonia' ),
        'parent_item_colon' => _x( 'Parent AdZonia:', 'wp-adzonia' ),
        'menu_name' => _x( 'AdZonia', 'wp-adzonia' ),
    );

    $args = array(
        'labels' => $labels,
        'hierarchical' => false,
        'description' => 'Get the advertisement information into post format',
        'supports' => array( 'title', 'excerpt' ),
        'public' => true,
        'show_ui' => true,
        'show_in_menu' => true,
        'menu_position' => 25,
        'menu_icon' => plugins_url('/images/adzonia-icon.png', __FILE__),
        'show_in_nav_menus' => false,
        'publicly_queryable' => true,
        'exclude_from_search' => true,
        'has_archive' => false,
        'query_var' => true,
        'can_export' => true,
        'rewrite' => true,
        'capability_type' => 'post'
    );

    register_post_type( 'adzonia', $args );
    
}

add_action( 'init', 'register_cpt_adzonia' );





/**
*   AdZonia Metabox
*   Product specification.
*   -----------------------------------------------------*/

function adzonia_specifications_meta_box() {
    add_meta_box(
        'adzonia-info',                                        // metabox ID
        __('AdZonia Specification', 'wp-adzonia'),         // metabox title
        'adzonia_specifications_specifics',                      // callback function
        'adzonia',                                         // post type (+ CPT)
        'normal',                                               // 'normal', 'advanced', or 'side'
        'high'                                                  // 'high', 'core', 'default' or 'low'
    );
}

add_action( 'add_meta_boxes', 'adzonia_specifications_meta_box' );


// Field Array
$prefix = 'wpadz_';
$adzonia_meta_fields = array(
    array(
        'label'=> 'Ad Image',
        'desc'  => 'Add an image if you wish to show an image ad',
        'id'    => $prefix.'ad_image',
        'type'  => 'ad_image'
    ),
    array(
        'label'=> 'Ad Code',
        'desc'  => 'If your ad is a Code-ad, then write down the code here',
        'id'    => $prefix.'ad_code',
        'type'  => 'ad_code'
    ),
    array(
        'label'=> 'End Date',
        'desc'  => 'Choose a date until when the ad will be visible',
        'id'    => $prefix.'end_date',
        'type'  => 'end_date'
    ),
    array(
        'label'=> 'Target URL',
        'desc'  => 'Enter the URL, to where the ad will direct the viewer after clicking',
        'id'    => $prefix.'target_url',
        'type'  => 'target_url'
    )
);


// The Callback
function adzonia_specifications_specifics() {
global $adzonia_meta_fields, $post;
// Use nonce for verification
echo '<input type="hidden" name="adzonia_nonce" value="'.wp_create_nonce(basename(__FILE__)).'" />';
?>
    <div class="row adz-meta-div">

        <p><?php _e( 'Either create an <strong>Image ad</strong> (<span class="dashicons dashicons-format-image p-icon"></span>), or a <strong>Code ad</strong> (<span class="dashicons dashicons-editor-code p-icon"></span>). Mixure won\'t be counted, sorry.', 'wp-adzonia' ) ?></p>

        <table id="adz-meta-table">
            <?php
            foreach ($adzonia_meta_fields as $field) {
                // get value of this field if it exists for this post
                $meta = get_post_meta($post->ID, $field['id'], true);
                // begin a table row with
                    switch($field['type']) {
                        // case items will go here
                        
                        case 'ad_image':
                            echo '<tr>';
                                echo '<td><div class="dashicons dashicons-format-image"></div></td>';
                                echo '<td class="adz-label-td"><label for="'.$field['id'].'">'.$field['label'].'</label></td>';
                                echo '<td class="adz-info-td">';
                                    echo '<input type="text" name="'.$field['id'].'" id="'.$field['id'].'" value="'.$meta.'" />';
                                    echo '<input type="button" name="nano_ad_image" class="button" id="nano-ad-image" value="Upload"/>';
                                echo '</td>';
                                echo '<td><span class="dashicons dashicons-editor-help adz-tooltip-icon" data-tooltip="'. $field['desc'] .'"></span></td>';
                            echo '</tr>';
                        break;

                        case 'ad_code':
                            echo '<tr>';
                                echo '<td><div class="dashicons dashicons-editor-code"></div></td>';
                                echo '<td class="adz-label-td"><label for="'.$field['id'].'">'.$field['label'].'</label></td>';
                                echo '<td class="adz-info-td">';
                                    echo '<textarea name="'.$field['id'].'" id="'.$field['id'].'" cols="50" rows="5">'.$meta.'</textarea>';
                                echo '</td>';
                                echo '<td><span class="dashicons dashicons-editor-help adz-tooltip-icon" data-tooltip="'. $field['desc'] .'"></span></td>';
                            echo '</tr>';
                        break;

                        case 'end_date':
                            echo '<tr>';
                                echo '<td><div class="dashicons dashicons-calendar"></div></td>';
                                echo '<td class="adz-label-td"><label for="'.$field['id'].'">'.$field['label'].'</label></td>';
                                echo '<td class="adz-info-td">';
                                    echo '<input type="text" name="'.$field['id'].'" id="'.$field['id'].'" value="'.$meta.'" autocomplete="off" />';
                                echo '</td>';
                                echo '<td><span class="dashicons dashicons-editor-help adz-tooltip-icon" data-tooltip="'. $field['desc'] .'"></span></td>';
                            echo '</tr>';
                        break;

                        case 'target_url':
                            echo '<tr>';
                                echo '<td><div class="dashicons dashicons-admin-links"></div></td>';
                                echo '<td class="adz-label-td"><label for="'.$field['id'].'">'.$field['label'].'</label></td>';
                                echo '<td class="adz-info-td">';
                                    echo '<input type="text" name="'.$field['id'].'" id="'.$field['id'].'" value="'.$meta.'" placeholder="http://example.com" />';
                                echo '</td>';
                                echo '<td><span class="dashicons dashicons-editor-help adz-tooltip-icon" data-tooltip="'. $field['desc'] .'"></span></td>';
                            echo '</tr>';
                        break;

                    } //end switch
            } // end foreach
            ?>
        </table>

    </div> <!-- .row -->

    <?php
}




// Save the Data
function save_adzonia_meta( $post_id ) {
    global $adzonia_meta_fields;
     
    // verify nonce
    if ( !wp_verify_nonce( $_POST['adzonia_nonce'], basename(__FILE__) ) ) 
        return $post_id;
    // check autosave
    if ( defined('DOING_AUTOSAVE') && DOING_AUTOSAVE )
        return $post_id;
    // check permissions
    if ( 'adzonia' == $_POST['post_type'] ) {
        if ( !current_user_can('edit_page', $post_id) )
            return $post_id;
        } elseif ( !current_user_can('edit_post', $post_id) ) {
            return $post_id;
    }
     
    // loop through fields and save the data
    foreach ( $adzonia_meta_fields as $field ) {
        $old = get_post_meta($post_id, $field['id'], true);
        $new = $_POST[$field['id']];
        if ( $new && $new != $old ) {
            update_post_meta($post_id, $field['id'], $new);
        } elseif ( '' == $new && $old ) {
            delete_post_meta($post_id, $field['id'], $old);
        }
    } // end foreach
}

add_action('save_post', 'save_adzonia_meta');
add_action('new_to_publish', 'save_adzonia_meta');


// If Yoast SEO plugin is activated, remove the WPSEO Meta Box

function remove_yoast_metabox_adzonia(){
    remove_meta_box( 'wpseo_meta', 'adzonia', 'normal' );
}
add_action( 'add_meta_boxes', 'remove_yoast_metabox_adzonia', 11 );




/**
*   ADD COLUMNS TO AdZonia LIST TABLE
*   -----------------------------------------------------*/

function set_custom_adzonia_columns( $columns ) {
    //Insert columns after 'title'
    $index = array_search( "title", array_keys( $columns ) );
    if( $index !== false ){
        $before = array_slice( $columns, 0, $index + 1 );
        $after = array_splice( $columns, $index + 1, count( $columns ) );
        $columns = $before + array(
            'ad_id' => __( 'ID', 'wp-adzonia' ),
            'ad_image' => __( 'Preview', 'wp-adzonia' )
            ) + $after + array(
                'until' => __( 'Until', 'wp-adzonia' ),
                'adz_shortcode' => __( 'Shortcode', 'wp-adzonia' )            
            );
    }
    return $columns;
}
 
add_filter( 'manage_edit-adzonia_columns', 'set_custom_adzonia_columns', 50 );



function custom_adzonia_column( $column, $post_id ) {
    switch ( $column ) {

        case 'ad_id' :
            echo $post_id;
            break;

        case 'ad_image' :
            $image_url = get_post_meta( $post_id , 'wpadz_ad_image' , true );
            $ad_codes = get_post_meta( $post_id , 'wpadz_ad_code' , true );
            if ( $image_url != '' )
                echo '<img src="'. $image_url .'" width="80" height="auto" />';
            else if ( $ad_codes != '' )
                echo '<code>CodeAd</code>';
            break;

        case 'until' :
            $to_date = get_post_meta( $post_id , 'wpadz_end_date' , true );
            echo mysql2date( 'Y/m/j', $to_date) . '<br/>' . mysql2date( 'g:i A', $to_date);
            break;

        case 'adz_shortcode' :
            echo '<code>[wp-adzonia id="'. $post_id .'"]</code>';
            break;

    }
}

add_action( 'manage_adzonia_posts_custom_column' , 'custom_adzonia_column', 10, 2 );




/**
*   SHOW AdZonia
*   @param (int) $ad_id. Would be the post ID.
*   -----------------------------------------------------*/

function show_adzonia( $ad_id ) {
    $ad = get_post( $ad_id );

    $thisDate = date( 'Y-m-d H:i:s', current_time( 'timestamp' ) );
    $datetoday = strtotime( $thisDate );

    $postID = $ad->ID;

    $image_ad_url = get_post_meta( $postID, 'wpadz_ad_image', true );
    $ad_code = get_post_meta( $postID, 'wpadz_ad_code', true );
    $end_date = get_post_meta( $postID, 'wpadz_end_date', true );
    $get_target_url = get_post_meta( $postID, 'wpadz_target_url', true );
    $target_url = $get_target_url != '' ? $get_target_url : '#';

    $endDateString = ( $end_date != '' ? strtotime( $end_date ) : '' );

    if( $datetoday <= $endDateString ) {

        if( !empty( $get_target_url ) ) echo '<a href="'. $target_url .'">';

            // Image ad
            if ( $image_ad_url != '' ) {
                echo '<img src="'. $image_ad_url .'" alt="'. __('AdZonia ad ', 'wp-adzonia'), the_title_attribute('echo=0') .'" />';
            }
            // Code ad
            else if ( $ad_code != '' ) {
                echo $ad_code;
            }

        if( !empty( $get_target_url ) ) echo '</a>';

    }
    
}


/**
*   AdZonia SHORTCODE
*   @see show_adzonia()
*   @param (int) $ad_id. Would be the post ID.
*
*   Usage:
*   [wp-adzonia id="#"]
*   -----------------------------------------------------*/

function adzonia_shortcode( $atts ) {
    
    $atts = shortcode_atts( array(
                'id' => '',
            ), $atts );

    $adID = $atts['id'];

    ob_start();
    ?>
        <div class="wp-adzonia-ad <?php echo $adID != '' ? 'adzonia-'. $adID : '' ?>">
            <?php show_adzonia( $adID ); ?>
        </div>
    <?php
    return ob_get_clean();

}

add_shortcode( 'wp-adzonia', 'adzonia_shortcode' );





/**
*   AdZonia Widget
*   adding a widget to add ad to the widget areas easily
*   -----------------------------------------------------*/

class adzonia_widget extends WP_Widget {

    function __construct() {
        parent::__construct(
            'adzonia_widget', //base ID of widget
            __('AdZonia', 'wp-adzonia'), //name of the widget
            array( 'description' => __( 'AdZonia widget to call the advertisement easily.', 'wp-adzonia' ) )
        );
    }

    // Widget Backend
    public function form( $instance ) {

        if ( isset( $instance[ 'title' ] ) ) {
            $title = $instance[ 'title' ];
        }
        else {
            $title = '';
        }

        if ( isset( $instance[ 'ad_id' ] ) ) {
            $ad_id = $instance[ 'ad_id' ];
        } else {
            $ad_id = '';
        }

        // Widget admin form
        $wpadz_args = array(
            'post_type' => 'adzonia',
            'posts_per_page' => -1,
            'post_status' => 'publish'
        );

        $widget_ad_query = get_posts( $wpadz_args );
        ?>
        <p>
            <label for="<?php echo $this->get_field_id( 'title' ); ?>"><?php _e( 'Title:' ); ?></label>
            <input class="widefat" id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" type="text" value="<?php echo esc_attr( $title ); ?>" />
        </p>
        <p>
            <label for="<?php echo $this->get_field_id( 'ad_id' ); ?>"><?php _e( 'Advertisements:', 'wp-adzonia' ); ?></label>         
            <select class="widefat" id="<?php echo $this->get_field_id( 'ad_id' ); ?>" name="<?php echo $this->get_field_name( 'ad_id' ); ?>">
                <option value=""><?php _e( 'Choose one...', 'wp-adzonia' ); ?></option>
                <?php
                foreach( $widget_ad_query as $active_ad ) { ?>
                    <option value="<?php echo $active_ad->ID; ?>" <?php selected( $ad_id, $active_ad->ID ); ?>>
                        <?php
                        echo $active_ad->ID . '&nbsp;&mdash;&nbsp;' . $active_ad->post_title;
                    echo '</option>';
                }
                ?>
            </select>
        </p>
    <?php
    }

    // Updating widget replacing old instances with new
    public function update( $new_instance, $old_instance ) {
        $instance = array();
        $instance['title'] = ( ! empty( $new_instance['title'] ) ) ? strip_tags( $new_instance['title'] ) : '';
        $instance['ad_id'] = ( ! empty( $new_instance['ad_id'] ) ) ? $new_instance['ad_id'] : '';
        return $instance;
    }

    //Creating Widget Front End
    public function widget( $args, $instance ) {
        $title = apply_filters( 'widget_title', $instance['title'] );
        echo $args['before_widget'];

        if ( ! empty( $title ) )
            echo $args['before_title'] . $title . $args['after_title']; //before & after widget args are defined by themes
        // This is where you run the code and display the output
        if ( ! empty( $ad_id ) )
            $instance['ad_id'];
            show_adzonia( $instance['ad_id'] );

        echo $args['after_widget'];
    }

}

// Register and load the widget
function adzonia_load_widget() {
    register_widget( 'adzonia_widget' );
}

add_action( 'widgets_init', 'adzonia_load_widget' );