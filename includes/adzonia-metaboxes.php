<?php
/**
 * Metabox
 *
 * To get additional information about an advertisement.
 *
 * @author      nanodesigns
 * @category    Metaboxes
 * @package     AdZonia
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

/**
 * AdZonia Metabox
 *
 * @since  1.0.0 initiated
 * @since  1.2.0 modified
 * ----------------------------------------------------
 */
function adzonia_specifications_meta_box() {
    add_meta_box(
        'adzonia-info',                                 // metabox ID
        esc_html__('AdZonia Specification', 'adzonia'), // metabox title
        'adzonia_specifications_specifics',             // callback function
        'adzonia',                                      // post type (+ CPT)
        'normal',                                       // 'normal', 'advanced', or 'side'
        'high'                                          // 'high', 'core', 'default' or 'low'
    );
}

add_action( 'add_meta_boxes', 'adzonia_specifications_meta_box' );

// The Callback
function adzonia_specifications_specifics() {

    global $post;

    $meta_data     = get_post_meta( $post->ID, '_adzonia_specs', true );
    $meta_location = get_post_meta( $post->ID, '_adzonia_location', true );
    $meta_data     = empty($meta_data) ? array() : $meta_data;
    $meta          = adzonia_parse_defaults($meta_data);
    ?>

    <div class="adz-container">

        <div class="adz-row">
            <div class="adz-btn-group adz-form-group" data-toggle="buttons">
                <label class="adz-btn adz-btn-sm adz-btn-primary active">
                    <input type="radio" name="_adz_ad_type" value="image_ad" <?php checked( $meta['ad_type'], 'image_ad', true ); ?>><i class="dashicons dashicons-format-image"></i> <?php _e('Image Ad', 'adzonia'); ?>
                </label>
                <label class="adz-btn adz-btn-sm adz-btn-primary">
                    <input type="radio" name="_adz_ad_type" value="code_ad" <?php checked( $meta['ad_type'], 'code_ad', true ); ?>> <?php _e('Code Ad', 'adzonia'); ?> <i class="dashicons dashicons-editor-code"></i>
                </label>
            </div>
        </div>

        <table class="form-table">
            <tbody>
                <tr class="adz-conditional-to-image-ad">
                    <th scope="row">
                        <?php _e('Ad Image', 'adzonia'); ?> <small class="adz-text-danger">*</small>
                        <?php echo adzonia_tooltip( 'adz-image-tooltip', __( 'Upload/Add an image using Media Library to display on the ad place.', 'adzonia' ), 'right' ); ?>
                    </th>
                    <td>
                        <?php
                        if( empty($meta['image_id']) ) {
                            $logo_preview = ADZ()->plugin_url() .'/assets/images/default.jpg';
                            $class = '';
                        } else {
                            $logo_preview = wp_get_attachment_url($meta['image_id']);
                            $class = 'has-image';
                        }
                        ?>
                        <div class="adz-preview-image ad-image-preview <?php echo esc_attr($class); ?>">
                            <div class="close-btn" id="close-ad-image"><i class="dashicons dashicons-dismiss"></i></div>
                            <img id="ad-image-preview" src="<?php echo esc_url($logo_preview); ?>" alt="<?php esc_attr_e('Preview of Ad Image', 'adzonia' ); ?>" style="max-width: 100px; max-height: 100px; border: none;" />
                        </div>
                        <input type="text" class="grm-field-item grm-file screen-reader-text" name="_adz_ad_image" id="adz-ad-image" value="<?php echo $meta['image_id']; ?>" required="required">
                        <div id="adz-image-btn" class="adz-btn adz-btn-primary img-input-btn <?php echo esc_attr($class); ?>">
                            <i class="dashicons dashicons-upload"></i> <?php _e('Upload', 'adzonia'); ?>
                        </div>
                    </td>
                </tr>
                <tr class="adz-conditional-to-image-ad">
                    <th scope="row">
                        <?php _e('Target URL', 'adzonia'); ?>
                        <?php echo adzonia_tooltip( 'adz-target-url-tooltip', __( 'If you want to direct the user to a specific URL by clicking on the image, type it here.', 'adzonia' ), 'right' ); ?>
                    </th>
                    <td>
                        <input type="text" id="adz-target-url" class="adz-form-control" name="_adz_target_url" placeholder="http://example.com" value="<?php echo esc_url($meta['target_url']); ?>">
                    </td>
                </tr>
                <tr class="adz-conditional-to-code-ad adz-conditional-hide-first">
                    <th scope="row">
                        <?php _e('Code', 'adzonia'); ?>
                        <?php echo adzonia_tooltip( 'adz-ad-code-tooltip', __( 'Paste the code of your advertisement. Make sure it&rsquo;s not harmful and not any type of SQL injection.', 'adzonia' ), 'right' ); ?>
                    </th>
                    <td>
                        <textarea id="adz-ad-code" class="adz-form-control" name="_adz_ad_code" cols="50" rows="4"><?php echo stripslashes($meta['code']); ?></textarea>
                    </td>
                </tr>
                <tr>
                    <th scope="row">
                        <?php _e('End Date', 'adzonia'); ?>
                        <?php echo adzonia_tooltip( 'adz-end-date-tooltip', __( 'Mention a date to end the display of the advertisement automatically.', 'adzonia' ), 'right' ); ?>
                    </th>
                    <td>
                        <div class="adz-input-group">
                            <?php $date_value = !empty($meta['end_date']) ? date('F d, Y', strtotime($meta['end_date'])) : ''; ?>
                            <input type="text" id="adz-end-date" class="adz-form-control" name="_adz_end_date" autocomplete="off" placeholder="February 21, 1952" value="<?php echo $date_value; ?>">
                            <div id="call-adz-end-date" class="adz-input-group-addon"><i class="dashicons dashicons-calendar"></i></div>
                        </div>
                    </td>
                </tr>
                <tr>
                    <th scope="row">
                        <?php _e('Location', 'adzonia'); ?> <em style="color: red">(*beta)</em>
                        <?php echo adzonia_tooltip( 'adz-location-tooltip', __( 'Beta Feature: you can display the advertisement on any of the predefined places.', 'adzonia' ), 'right' ); ?>
                    </th>
                    <td>
                        <?php $ad_places = __adzonia_ad_places(); ?>
                        <select name="_adz_location" id="adz-location" class="adz-form-control">
                            
                            <option value=""><?php _e('Select a place (optional)', 'adzonia'); ?></option>
                            
                            <?php
                            echo '<optgroup label="'. __('Predefined places', 'adzonia') .'">';
                                if( empty($ad_places['default']) ) {
                                    echo '<option value="">-- no defaults --</option>';
                                } else {
                                    foreach( $ad_places['default'] as $ad_place => $ad_place_label ) {
                                        echo '<option value="'. $ad_place .'" '. selected( $meta_location, $ad_place, false ) .'>'. $ad_place_label .'</option>';
                                    }
                                }
                            echo '</optgroup>';
                            ?>
                            
                        </select>
                    </td>
                </tr>
            </tbody>
        </table>

        <?php
        // Use nonce for verification
        echo '<input type="hidden" name="adzonia_nonce" value="'. wp_create_nonce(basename(__FILE__)) .'" />';
        ?>

    </div>

<?php
}

// Save the Data
function adzonia_save_meta( $post_id ) {     
    // verify nonce
    if ( !isset($_POST['adzonia_nonce']) || !wp_verify_nonce( $_POST['adzonia_nonce'], basename(__FILE__) ) ) {
        return $post_id;
    }

    // check autosave
    if ( defined('DOING_AUTOSAVE') && DOING_AUTOSAVE ) {
        return $post_id;
    }

    // check permissions
    if ( 'adzonia' === $_POST['post_type'] ) {
        if ( !current_user_can('edit_page', $post_id) ) {
            return $post_id;
        } elseif ( !current_user_can('edit_post', $post_id) ) {
            return $post_id;
        }
    }

    $existing_data = get_post_meta( $post_id, '_adzonia_specs', true );
    $meta_data     = array();

    if( 'code_ad' === $_POST['_adz_ad_type'] ) {
        if( ! empty($_POST['_adz_ad_code']) ) {
            $filtered_code     = addslashes($_POST['_adz_ad_code']);
            $meta_data['code'] = $filtered_code;
        }
    }
    else if( 'image_ad' === $_POST['_adz_ad_type'] ) {
        if( ! empty($_POST['_adz_ad_image']) || ! empty($_POST['_adz_target_url']) ) {
            $meta_data['image_id']   = $_POST['_adz_ad_image'];
            $meta_data['target_url'] = $_POST['_adz_target_url'];
        }
    }

    if( ! empty($_POST['_adz_end_date']) ) {
        $meta_data['end_date'] = date('Y-m-d 23:59:59', strtotime($_POST['_adz_end_date']));
    }

    if( ! empty($meta_data) ) {
        $meta_data['ad_type'] = $_POST['_adz_ad_type'];
    }

    if( empty($meta_data) ) {
        delete_post_meta( $post_id, '_adzonia_specs', $existing_data );
    } else if( !empty($meta_data) && $meta_data !== $existing_data ) {
        update_post_meta( $post_id, '_adzonia_specs', $meta_data );
    }

    // Ad Location
    $existing_location = get_post_meta( $post_id, '_adzonia_location', true );
    $meta_location     = $_POST['_adz_location'];
    if( !empty($meta_location) && $meta_location !== $existing_location ) {
        update_post_meta( $post_id, '_adzonia_location', $meta_location );
    } else {
        delete_post_meta( $post_id, '_adzonia_location', $existing_location );
    }
}

add_action( 'save_post',        'adzonia_save_meta' );
add_action( 'new_to_publish',   'adzonia_save_meta' );


/**
 * Shortcode on Post Submitbox.
 * 
 * @return void
 * ----------------------------------------------------
 */
function adzonia_shortcode_specifics() {
    global $post;
    
    if( 'adzonia' === $post->post_type && 'publish' === $post->post_status ) {
        ?>
        <div class="misc-pub-section">
            <span class="dashicons dashicons-editor-code"></span> <?php esc_html_e('Shortcode', 'adzonia'); ?>:
            <strong><code class="selectable">[adzonia id="<?php echo $post->ID; ?>"]</code></strong>
        </div>
        <?php
    }
}

add_action('post_submitbox_misc_actions', 'adzonia_shortcode_specifics');
