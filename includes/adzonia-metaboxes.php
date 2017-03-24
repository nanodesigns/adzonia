<?php
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
