<?php
/**
 * Plugin Name:     AdZonia
 * Plugin URI:      http://nanodesignsbd.com/
 * Description:     A simpler and easier advertisement manager plugin for WordPress sites, and most astonishingly - it's in WordPress way. Read the instructions (AdZonia &raquo; Settings-Instructions).
 * Version:         2.0.0
 * Author:          Mayeenul Islam (@mayeenulislam)
 * Author URI:      http://nanodesignsbd.com/mayeenulislam/
 * License:         GNU General Public License v2.0
 * License URI:     http://www.gnu.org/licenses/gpl-2.0.html
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
if( !defined( 'ABSPATH' ) )
    exit;

// CONSTANTS
define( 'PLUGINVERSION', '2.0.0' );


/**
 * Translation-ready
 * Make the plugin translation-ready.
 *
 * @since  1.0.0
 * ----------------------------------------------------
 */
function adzonia_load_textdomain() {
    load_plugin_textdomain( 'adzonia', FALSE, dirname( plugin_basename( __FILE__ ) ) . '/i18n/languages/' );
}
add_action( 'init', 'adzonia_load_textdomain', 1 );



/**
 * Add Settings Sub Menu
 * Thankfully with the assistance of Mark Otto.
 * 
 * @link http://ottopress.com/2009/wordpress-settings-api-tutorial/
 *
 * @since 1.2.0
 * ----------------------------------------------------
 */
function adzonia_settings_page() {
    add_submenu_page(
        'edit.php?post_type=adzonia',                   //$parent_slug
        __('Settings & Instructions', 'adzonia' ),      //$page_title
        __('Settings-Instructions', 'adzonia' ),        //$menu_title
        'manage_options',                               //$capability
        'adzonia-settings',                             //$menu_slug
        'adzonia_settings_page_callback'                //callback function
    );
}

add_action('admin_menu', 'adzonia_settings_page');

function adzonia_settings_page_callback() { ?>

    <div class="wrap">
        <h2><?php _e('Settings & Instructions', 'adzonia' ); ?></h2>
        <?php settings_errors(); ?>
        <div class="adzonia-left-column">
            <form action="options.php" method="post">
                <?php settings_fields('adzonia_options'); ?>
                <?php do_settings_sections('adzonia_settings'); ?>
                <?php submit_button(); ?>
            </form>            
        </div>
        <!-- /.adzonia-left-column -->
        <div class="adzonia-right-column">
            
        </div>
        <!-- /.adzonia-right-column -->
        <div class="clearfix"></div>
        <hr>
        <?php
        //get the manual from external file
        require_once( 'manual/inner-manual.php' );
        ?>
    </div> <!-- .wrap -->

<?php
}

function adzonia_options_init(){
    register_setting(
        'adzonia_options',                          // Option group*
        'adzonia_options',                          // Option Name*
        'adzonia_options_validate'                  // Sanitize Callback Function
    );
    add_settings_section(
        'adzonia_general',                          // ID/Slug*
        sprintf( __( '%s Settings', 'adzonia' ), '<span class="dashicons dashicons-admin-generic"></span>'),                                       // Name*
        'adzonia_gen_section_callback',             // Callback*
        'adzonia_settings'                          // Page on which to add this section of options*
    );
    add_settings_field(
        'adzonia_css',                              // ID*
        __( 'AdZonia CSS', 'adzonia' ),          // Title*
        'adzonia_setting_css_field',                // Callback Function*
        'adzonia_settings',                         // Page (Plugin)*
        'adzonia_general'                           // Section
    );
    add_settings_section(
        'adzonia_troubleshoot',                     // ID/Slug*
        sprintf( __( '%s Troubleshoot', 'adzonia' ), '<span class="dashicons dashicons-admin-tools"></span>'),                                       // Name*
        'adzonia_tr_section_callback',              // Callback*
        'adzonia_settings'                          // Page on which to add this section of options*
    );
    add_settings_field(
        'adzonia_jquery',                           // ID*
        __( 'AdZonia jQuery', 'adzonia' ),       // Title*
        'adzonia_setting_jquery_field',             // Callback Function*
        'adzonia_settings',                         // Page (Plugin)*
        'adzonia_troubleshoot'                      // Section
    );
}
add_action( 'admin_init', 'adzonia_options_init' );

// General Section
function adzonia_gen_section_callback() {
    _e('<p>Set these optional settings if you want. It&rsquo;s not mandatory, and even without the settings your advertisements will work just fine.</p>', 'adzonia');
}

function adzonia_setting_css_field() {
    $options = get_option('adzonia_options');
    echo "<input name='adzonia_options[adzonia_css_check]' id='adzonia_css' type='checkbox' value='1' ".checked( 1, $options['adzonia_css_check'], false ) . " /> <label for='adzonia_css'>". __( 'check the box to show Ads as inline element rather block element. It will load AdZonia CSS into the site&rsquo;s front-end', 'adzonia' ) ."</label>";
}

// Troubleshoot Section
function adzonia_tr_section_callback() {
    _e('<p>Getting trouble using the plugin? If the date picker and/or image uploader is not working, try enabling the jQuery from the plugin resources.</p>', 'adzonia');
}

function adzonia_setting_jquery_field() {
    $options = get_option('adzonia_options');
    echo "<input name='adzonia_options[adzonia_jquery_check]' id='adzonia_jquery' type='checkbox' value='1' ".checked( 1, $options['adzonia_jquery_check'], false ) . " /> <label for='adzonia_jquery'>". __( 'Load jQuery from plugin', 'adzonia' ) ."</label>";
}

// validate our options
function adzonia_options_validate( $input ) {
    $options = get_option('adzonia_options');

    //CSS Checkbox
    $css_check_val = (int) $input['adzonia_css_check'] === 1 ? (int) $input['adzonia_css_check'] : '';
    $options['adzonia_css_check'] = is_int( $css_check_val );

    //jQuery Checkbox
    $jquery_check_val = (int) $input['adzonia_jquery_check'] === 1 ? (int) $input['adzonia_jquery_check'] : '';
    $options['adzonia_jquery_check'] = is_int( $jquery_check_val );

    return $options;
}


// Get the back end options
$get_options = get_option('adzonia_options');


/**
 * AdZonia FrontEnd stylesheet.
 * @see  option is checked or not.
 * ----------------------------------------------------
 */
if( $get_options['adzonia_css_check'] === true ) {
    function adzonia_output_css() {
        wp_enqueue_style( 'adzonia', plugins_url('assets/css/adzonia.css', __FILE__) );
    }
    add_action( 'wp_enqueue_scripts', 'adzonia_output_css' );
}



/**
 * Enqueue necessary admin scripts.
 * Load scripts only where necessary using get_currecnt_screen().
 * ----------------------------------------------------
 */
function adzonia_admin_scripts() {

    $screen = get_current_screen();
    if( 'adzonia' === $screen->post_type && 'post' === $screen->base ) {
        
        /**
         * jQuery DateTimePicker
         */
        wp_register_style( 'jquery-datetimepicker', plugins_url('libs/jquery-datetimepicker/jquery.datetimepicker.css', __FILE__) );
        wp_register_script( 'jquery-datetimepicker', plugins_url('/libs/jquery-datetimepicker/jquery.datetimepicker.min.js', __FILE__), array('jquery'), PLUGINVERSION, true );

        if( function_exists('wp_enqueue_media') ) {
            wp_enqueue_media();
        }
        else {
            wp_enqueue_script('media-upload');
            wp_enqueue_script('thickbox');
            wp_enqueue_style('thickbox');
        }

        wp_enqueue_style( 'adzonia-admin', plugins_url('assets/css/adzonia-admin.css', __FILE__), array('jquery-datetimepicker'), PLUGINVERSION );
        wp_enqueue_script( 'adzonia-admin', plugins_url( '/assets/js/adzonia-admin.min.js', __FILE__ ), array('jquery', 'jquery-ui-tabs', 'jquery-datetimepicker'), PLUGINVERSION, true );

        //load only on edit page (not in add-new page)
        //if( $screen->action == '' ) {
            global $post;
            $image_ad   = '';
            $code_ad    = '';
            $image_ad   = get_post_meta( $post->ID, 'wpadz_ad_image', true );
            $code_ad    = get_post_meta( $post->ID, 'wpadz_ad_code', true );

            wp_localize_script(
                'adzonia-admin',
                'adzonia',     //the var key in JS
                array(
                    'is_img_ad'    => $image_ad,
                    'is_code_ad'   => $code_ad,
                    'img_lib_head'  => esc_html__( 'Choose Ad Image', 'adzonia' ),
                    'img_btn_text'  => esc_html__( 'Choose Image', 'adzonia' )
                )
            );
        //}//endif( $screen->action == '' )

    } //endif
}

add_action('admin_enqueue_scripts', 'adzonia_admin_scripts');


/**
 * Register AdZonia Custom Post Type
 *
 * Custom post type to get the advertisement information
 * in WordPress' way.
 *
 * @since  1.0.0
 * ----------------------------------------------------
 */
function register_cpt_adzonia() {
    $labels = array(
        'name'                  => _x( 'AdZonia', 'AdZonia', 'adzonia' ),
        'singular_name'         => _x( 'AdZonia', 'AdZonia', 'adzonia' ),
        'add_new'               => _x( 'New AdZonia', 'AdZonia', 'adzonia' ),
        'add_new_item'          => _x( 'Add New AdZonia', 'AdZonia', 'adzonia' ),
        'edit_item'             => _x( 'Edit AdZonia', 'AdZonia', 'adzonia' ),
        'new_item'              => _x( 'New AdZonia', 'AdZonia', 'adzonia' ),
        'view_item'             => _x( 'View AdZonia', 'AdZonia', 'adzonia' ),
        'search_items'          => _x( 'Search AdZonia', 'AdZonia', 'adzonia' ),
        'not_found'             => _x( 'No AdZonia is created yet. Try making one first', 'AdZonia', 'adzonia' ),
        'not_found_in_trash'    => _x( 'No AdZonia found in Trash', 'AdZonia', 'adzonia' ),
        'parent_item_colon'     => _x( 'Parent AdZonia:', 'AdZonia', 'adzonia' ),
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
        'menu_icon'             => plugins_url('/assets/images/adzonia-icon.png', __FILE__),
        'show_in_nav_menus'     => false,
        'publicly_queryable'    => false,
        'exclude_from_search'   => true,
        'has_archive'           => false,
        'query_var'             => true,
        'can_export'            => true,
        'rewrite'               => true,
        'capability_type'       => 'post'
    );

    register_post_type( 'adzonia', $args );    
}
add_action( 'init', 'register_cpt_adzonia' );


/**
 * AdZonia Metabox
 * To get additional information about an advertisement.
 *
 * @since  1.0.0 initiated
 * @since  1.2.0 modified
 * ----------------------------------------------------
 */
function adzonia_specifications_meta_box() {
    add_meta_box(
        'adzonia-info',                                 // metabox ID
        __('AdZonia Specification', 'adzonia'),      // metabox title
        'adzonia_specifications_specifics',             // callback function
        'adzonia',                                      // post type (+ CPT)
        'normal',                                       // 'normal', 'advanced', or 'side'
        'high'                                          // 'high', 'core', 'default' or 'low'
    );
}

add_action( 'add_meta_boxes', 'adzonia_specifications_meta_box' );


// Field Array
$adzonia_meta_fields = array(
    array(
        'label' => __('Ad Image', 'adzonia'),
        'desc'  => __('Add an image if you wish to show an image ad', 'adzonia'),
        'id'    => 'wpadz_ad_image',
        'type'  => 'ad_image'
    ),
    array(
        'label' => __('Ad Code', 'adzonia'),
        'desc'  => __('If your ad is a Code-ad, then write down the code here', 'adzonia'),
        'id'    => 'wpadz_ad_code',
        'type'  => 'ad_code'
    ),
    array(
        'label' => __('End Date', 'adzonia'),
        'desc'  => __('Choose a date until when the ad will be visible', 'adzonia'),
        'id'    => 'wpadz_end_date',
        'type'  => 'end_date'
    ),
    array(
        'label' => __('Target URL', 'adzonia'),
        'desc'  => __('Enter the URL, to where the ad will direct the viewer after clicking', 'adzonia'),
        'id'    => 'wpadz_target_url',
        'type'  => 'target_url'
    ),
    array(
        'label' => __('Location', 'adzonia'),
        'desc'  => __('Choose a location to show the ad in predefined areas', 'adzonia'),
        'id'    => 'wpadz_location',
        'type'  => 'location',
        'options' => array (
            'before_content' => array (
                'label' => 'Before all the Post/Page Content',
                'value' => 'before_content'
            ),
            'after_content' => array (
                'label' => 'After all the Post/Page Content',
                'value' => 'after_content'
            )
        )
    )
);


// The Callback
function adzonia_specifications_specifics() {
global $adzonia_meta_fields, $post;
// Use nonce for verification
echo '<input type="hidden" name="adzonia_nonce" value="'.wp_create_nonce(basename(__FILE__)).'" />';
?>
    <div class="row adz-meta-div">

        <p class="non-js-directions"><?php _e( 'Either create an <strong>Image ad</strong> (<span class="dashicons dashicons-format-image p-icon"></span>), or a <strong>Code ad</strong> (<span class="dashicons dashicons-editor-code p-icon"></span>). Mixure won&rsquo;t be counted, sorry.', 'adzonia' ) ?></p>

        <?php
        $image_ad = '';
        $code_ad = '';
        $image_ad = get_post_meta( $post->ID, 'wpadz_ad_image', true );
        $code_ad = get_post_meta( $post->ID, 'wpadz_ad_code', true );
        ?>

        <div id="adzonia-tabs">
            <?php if( $image_ad === '' && $code_ad === '' ) { ?>
            <ul class="tab-switches">
                <li><a href="#image-ad-tab-content">Image Ad</a></li>
                <li><a href="#code-ad-tab-content">Code Ad</a></li>
            </ul>
            <?php } ?>
            <div class="clearfix"></div>
            <div id="image-ad-tab-content" class="adzonia-tab-contents">
                <table class="adz-meta-table adz-img-table">
                    <?php
                    foreach ($adzonia_meta_fields as $field) {
                        // get value of this field if it exists for this post
                        $meta = get_post_meta( $post->ID, $field['id'], true );
                        // begin a table row with
                            switch($field['type']) {
                                // case items will go here
                                
                                case 'ad_image':
                                    echo '<tr>';
                                        echo '<td><div class="dashicons dashicons-format-image"></div></td>';
                                        echo '<td class="adz-label-td"><label for="'.$field['id'].'">'.$field['label'].'<span class="orange">*</span></label></td>';
                                        echo '<td class="adz-info-td upload-td">';
                                            echo '<input type="text" name="'.$field['id'].'" id="'.$field['id'].'" value="'.$meta.'" />';
                                            echo '<input type="button" name="nano_ad_image" class="button" id="nano-ad-image" value="Upload"/>';
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
            </div>
            <!-- /#image-ad-tab-content -->
            <div id="code-ad-tab-content" class="adzonia-tab-contents">
                <table class="adz-meta-table">
                    <?php
                    foreach ($adzonia_meta_fields as $field) {
                        // get value of this field if it exists for this post
                        $meta = get_post_meta($post->ID, $field['id'], true);
                        // begin a table row with
                            switch($field['type']) {
                                case 'ad_code':
                                    echo '<tr>';
                                        echo '<td><div class="dashicons dashicons-editor-code"></div></td>';
                                        echo '<td class="adz-label-td"><label for="'.$field['id'].'">'.$field['label'].'<span class="orange">*</span></label></td>';
                                        echo '<td class="adz-info-td">';
                                            echo '<textarea name="'.$field['id'].'" id="'.$field['id'].'" cols="50" rows="5">'.stripslashes( $meta ).'</textarea>';
                                        echo '</td>';
                                        echo '<td><span class="dashicons dashicons-editor-help adz-tooltip-icon" data-tooltip="'. $field['desc'] .'"></span></td>';
                                    echo '</tr>';
                                break;
                            } //end switch
                    } // end foreach
                    ?>
                </table>
            </div>
            <!-- /#code-ad-tab-content -->
        </div>

        <table class="adz-meta-table">
            <?php
            foreach ($adzonia_meta_fields as $field) {
                // get value of this field if it exists for this post
                $meta = get_post_meta($post->ID, $field['id'], true);
                // begin a table row with
                    switch($field['type']) {
                        // case items will go here

                        case 'end_date':
                            echo '<tr>';
                                echo '<td><div class="dashicons dashicons-calendar"></div></td>';
                                echo '<td class="adz-label-td"><label for="'.$field['id'].'">'.$field['label'].'<span class="required">*</span></label></td>';
                                echo '<td class="adz-info-td">';
                                    echo '<input type="text" name="'.$field['id'].'" id="'.$field['id'].'" value="'.$meta.'" autocomplete="off" placeholder="YYYY/MM/DD 24:00" required />';
                                echo '</td>';
                                echo '<td><span class="dashicons dashicons-editor-help adz-tooltip-icon" data-tooltip="'. $field['desc'] .'"></span></td>';
                            echo '</tr>';
                        break;

                        case 'location':
                            echo '<tr>';
                                echo '<td><div class="dashicons dashicons-align-right"></div></td>';
                                echo '<td class="adz-label-td"><label for="'.$field['id'].'">'.$field['label'].'</label></td>';
                                echo '<td class="adz-info-td">';
                                    echo '<select name="'.$field['id'].'" id="'.$field['id'].'">';
                                        echo '<option>Select a predefined place (optional)</option>';
                                        foreach ($field['options'] as $option) {
                                            echo '<option', $meta == $option['value'] ? ' selected="selected"' : '', ' value="'.$option['value'].'">'.$option['label'].'</option>';
                                        }
                                    echo '</select>';
                                echo '</td>';
                                echo '<td><span class="dashicons dashicons-editor-help adz-tooltip-icon" data-tooltip="'. $field['desc'] .'"></span> <em style="color: red">*Beta Feature</em></td>';
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
    if ( !isset($_POST['adzonia_nonce']) || !wp_verify_nonce( $_POST['adzonia_nonce'], basename(__FILE__) ) ) 
        return $post_id;

    // check autosave
    if ( defined('DOING_AUTOSAVE') && DOING_AUTOSAVE )
        return $post_id;

    // check permissions
    if ( 'adzonia' === $_POST['post_type'] ) {
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
            //if it's the code field, sanitize the data
            if( 'wpadz_ad_code' === $field['id'] ) {
                $filtered_code = addslashes( $_POST[$field['id']] );
                update_post_meta($post_id, $field['id'], $filtered_code);
            }
            //otherwise simply insert 'em
            else {
                update_post_meta($post_id, $field['id'], $new);
            }
        } elseif ( '' == $new && $old ) {
            delete_post_meta($post_id, $field['id'], $old);
        }
    } // end foreach
}

add_action( 'save_post',        'save_adzonia_meta' );
add_action( 'new_to_publish',   'save_adzonia_meta' );


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
    $index = array_search( "title", array_keys( $columns ) );
    if( $index !== false ){
        $before = array_slice( $columns, 0, $index + 1 );
        $after = array_splice( $columns, $index + 1, count( $columns ) );
        $columns = $before + array(
            'ad_id' => __( 'ID', 'adzonia' ),
            'ad_image' => __( 'Preview', 'adzonia' )
            ) + $after + array(
                'until' => __( 'Until', 'adzonia' ),
                'adz_shortcode' => __( 'Shortcode', 'adzonia' )            
            );
    }
    return $columns;

}

add_filter( 'manage_edit-adzonia_columns', 'adzonia_set_custom_columns', 50 );


/**
 * Fill the columns with their data
 * 
 * @param  array $column  specific post type column.
 * @param  integer $post_id adzonia post id.
 * ----------------------------------------------------
 */
function adzonia_custom_column( $column, $post_id ) {
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
            echo mysql2date( 'Y/m/d', $to_date) . '<br/>' . mysql2date( 'g:i A', $to_date);
            break;

        case 'adz_shortcode' :
            echo '<code>[adzonia id="'. $post_id .'"]</code>';
            break;
    }
}

add_action( 'manage_adzonia_posts_custom_column' , 'adzonia_custom_column', 10, 2 );



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


/**
 * AdZonia Widget
 *
 * Adding a widget to add ad to the widget-enabled areas easily.
 *
 * @since  1.2.0
 * ----------------------------------------------------
 */
class adzonia_widget extends WP_Widget {
    function __construct() {
        parent::__construct(
            'adzonia_widget', //base ID of widget
            __('AdZonia', 'adzonia'), //name of the widget
            array( 'description' => __( 'AdZonia widget to call the advertisement easily.', 'adzonia' ) )
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
            <label for="<?php echo $this->get_field_id( 'title' ); ?>"><?php _e( 'Title:', 'adzonia' ); ?></label>
            <input class="widefat" id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" type="text" value="<?php echo esc_attr( $title ); ?>" />
        </p>
        <p>
            <label for="<?php echo $this->get_field_id( 'ad_id' ); ?>"><?php _e( 'Advertisements:', 'adzonia' ); ?></label>         
            <select class="widefat" id="<?php echo $this->get_field_id( 'ad_id' ); ?>" name="<?php echo $this->get_field_name( 'ad_id' ); ?>">
                <option value=""><?php _e( 'Choose one...', 'adzonia' ); ?></option>
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


/**
 * Assistance from Giuseppe (aka Gmazzap, G.M.) (@gmazzap) - Italy.
 * @param  \WP_Query $query
 * @since  1.2.1
 * ----------------------------------------------------
 *
 * Show the advertisement into the starting of the posts or
 * into the ending of the posts.
 */
function adzonia_ad_position_executioner( \WP_Query $query ) {
    if ( ! $query->is_main_query() ) {
        return;
    }
    $key =  'wpadz_location';
    $ads_args = array(
            'post_type' => 'adzonia',
            'meta_query' => array(
              array(
                'key' => $key,
                'value' => array( 'after_content', 'before_content' ),
                'compare' => 'IN'
              )
            ),
                'nopaging' => true,
                'post_status' => 'publish'
            );
    $ads = get_posts( $ads_args );
    if ( empty( $ads ) ) {
        return;
    }
    $before = '';
    $after = '';
    foreach( $ads as $ad ) {
        $ad_content = get_adzonia( $ad->ID );
        if ( get_post_meta( $ad->ID, $key, true ) === 'before_content' ) {
            $before .= $ad_content;
        } else {
            $after .= $ad_content;
        }
    }

    $query->adzonia_before = $before;
    $query->adzonia_after = $after;

    add_filter( 'the_content', 'adzonia_ad_position_func', PHP_INT_MAX  );
    add_filter( 'the_excerpt', 'adzonia_ad_position_func', PHP_INT_MAX  );

    // at the very end remove the filter
    add_action( 'loop_end', 'adzonia_remove_hooks' );
}
add_action( 'loop_start', 'adzonia_ad_position_executioner' );

function adzonia_ad_position_func( $content ) {
    global $wp_query;
    if ( isset($wp_query->adzonia_before) && ! empty($wp_query->adzonia_before) ) {
        $content = $wp_query->adzonia_before . $content;
    }
    if ( isset($wp_query->adzonia_after) && ! empty($wp_query->adzonia_after) ) {
        $content = $content . $wp_query->adzonia_after;
    }
    // reset $wp_query vars
    //$wp_query->adzonia_before = $wp_query->adzonia_after = '';

    return $content;
}

function adzonia_remove_hooks() {
    remove_action( 'loop_start', 'adzonia_ad_position_executioner' );
    remove_filter( 'the_content', 'adzonia_ad_position_func', PHP_INT_MAX  );
    remove_filter( 'the_excerpt', 'adzonia_ad_position_func', PHP_INT_MAX  );
    remove_action( current_filter(), __FUNCTION__  );
}


/**
 * Additional Functions
 *
 * To make the main page clean, additional functions
 * are taken away from this page.
 * 
 * @since  1.2.1
 * ----------------------------------------------------
 */
require_once( 'inc/functions-additionals.php' );
