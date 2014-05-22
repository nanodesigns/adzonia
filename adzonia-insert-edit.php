<?php
global $plugin_name;
if( !isset( $_GET["action"] ) ) {
    if( isset($_POST) && !empty($_POST) ) {
        global $wpdb;
        $table = $wpdb->wp_adzonia = $wpdb->prefix . "wp_adzonia";

        //getting my form data into variables
        $ad_type = $_POST['nano_ad_type'];
        $ad_title = $_POST['nano_ad_title'];
        $ad_code_title = $_POST['ad_code_title'];
        $ad_code = $_POST['nano_ad_code'];
        $ad_image_url = $_POST['ad_image'];
        $ad_url = $_POST['nano_ad_url'];
        $ad_str_time = $_POST['nano_ad_str_date'];
        $ad_end_time = $_POST['nano_ad_end_date'];
        $ad_status = ( isset( $_POST['nano_ad_active'] ) ? '1' : '0' );

        $data = array(
            'ad_type'           => $ad_type,
            'name_of_ad'        => $ad_title,
            'ad_code_title'     => $ad_code_title,
            'ad_code'           => $ad_code,
            'ad_image_url'      => $ad_image_url,
            'url'               => $ad_url,
            'str_time'          => $ad_str_time,
            'end_time'          => $ad_end_time,
            'ad_status'         => $ad_status
        );

        $format = array(
                    '%s',   //string
                    '%s',
                    '%s',
                    '%s',
                    '%s',
                    '%s',
                    '%s',
                    '%s',
                    '%d'    //integer
                    );

        // validation
        if( ( ( $ad_str_time && $ad_end_time ) != '' )
            && (
                   ($_POST['nano_ad_type'] == 'imagead' && ($ad_image_url && $ad_url) != '')
                || ($_POST['nano_ad_type'] == 'codead' && $ad_code != '')
            ) )
                {

                    $success = $wpdb->insert( $table, $data, $format );

                    // Redirect to view ad page
                    header( "location:" . admin_url('/admin.php?page=adzonia&addnew=1&success') );
                }

        if(isset($success)) {
            $success_message = "Your advertisement is <strong>added</strong> successfully!";
        } else {
            $error_message = "<strong>Oops!</strong> Seems some mandatory fields are empty.";
        }
    }
} //endif( !isset( $_GET["action"] ) )



/*
* If the form is in Edit Mode
*/

if( isset( $_GET["action"] ) && $_GET["action"] === 'edit' ) {

    global $wpdb;
    $table = $wpdb->wp_adzonia = $wpdb->prefix . "wp_adzonia";
    $got_id = ( isset( $_GET["id"] ) ? $_GET["id"] : '' );

    if( isset($_POST['nano_ad_update']) ) {

        //getting my form data into variables
        $ad_type = $_POST['nano_ad_type'];
        $ad_title = $_POST['nano_ad_title'];
        $ad_code_title = $_POST['ad_code_title'];
        $ad_code = $_POST['nano_ad_code'];
        $ad_image_url = $_POST['ad_image'];
        $ad_url = $_POST['nano_ad_url'];
        $ad_str_time = $_POST['nano_ad_str_date'];
        $ad_end_time = $_POST['nano_ad_end_date'];
        $ad_status = ( isset( $_POST['nano_ad_active'] ) ? '1' : '0' );

        $data = array(
                    'ad_type'           => $ad_type,
                    'name_of_ad'        => $ad_title,
                    'ad_code_title'     => $ad_code_title,
                    'ad_code'           => $ad_code,
                    'ad_image_url'      => $ad_image_url,
                    'url'               => $ad_url,
                    'str_time'          => $ad_str_time,
                    'end_time'          => $ad_end_time,
                    'ad_status'         => $ad_status
                    );

        $where = array( 'ID' => $got_id );

        // validation
        if( ( ( $ad_str_time && $ad_end_time ) != '' )
            && (
                ($_POST['nano_ad_type'] === 'imagead' && ($ad_image_url && $ad_url) != '')
                || ($_POST['nano_ad_type'] === 'codead' && $ad_code != '')
            ) )
            {
                $success = $wpdb->update( $table, $data, $where );

                // Redirect to view ad page
                header( "location:" . admin_url('/admin.php?page=adzonia&edit=1&success') );
            }

        if(isset($success)) {
            $success_message = "Your advertisement is <strong>updated</strong> successfully!";
        } else {
            $error_message = "<strong>Oops!</strong> Seems some mandatory fields are empty.";
        }
    }

    $ad_query_with_id = $wpdb->get_results(
    "SELECT *
    FROM $wpdb->wp_adzonia
    WHERE id = ". $got_id .";
    ");
}
?>
<div class="wrap nano-ad nano-ad-add">
    <?php if( isset( $_GET["action"] ) && $_GET["action"] == 'edit' ) { ?>
        <h2 class="page-title">Edit Advertisement - <?php echo $plugin_name; ?></h2>
    <?php } else { ?>
        <h2 class="page-title">Add new Advertisement - <?php echo $plugin_name; ?></h2>
    <?php }


    if( !empty($success_message) || !empty($error_message)) {
        $class = ( !empty( $success_message ) ? "notification-success" : "notification-error" );
        echo '<div id="notification-message" class="' . $class. '">';
        echo '<p>' . ( !empty( $success_message ) ? $success_message : $error_message ) . '</p>';
        echo '</div>';
    }
    ?>
    <form name="nano_wordpress_ad" id="nano_wordpress_ad" action="" enctype="multipart/form-data" method="post">

        <div class="nano-ad-body">

            <div class="nano-ad-left">
            <?php
            /*
             * In the input fields' value, I passed the queried data, because
             * I'm using the same form for inserting and editing data as well.
             * So, I checked the data in both way:
             *  1st: Whether isset the query for the data?
             *     AND
             *  2nd: Whether the field is not empty
             * if the both the check success the data will be shown, otherwise
             * NULL ('') will be passed as default
             */
            ?>
            <div id="titlediv" class="row form-elements">
                <label class="ad-label" for="nano_ad_title">Name the ad</label>
                <p class="direction">Write down a name for the ad, so that you can understand anytime</p>
                <input type="text" name="nano_ad_title" size="30" value="<?php
                if( isset( $ad_query_with_id ) && !empty($ad_query_with_id[0]->name_of_ad) ) {
                    echo $ad_query_with_id[0]->name_of_ad;
                } elseif( isset($_POST['nano_ad_title']) && $_POST['nano_ad_title'] != '' ) {
                    echo $_POST['nano_ad_title'];
                } else {
                    echo '';
                }
                ?>" id="nano-ad-title"/>
            </div> <!-- #titlediv -->

            <div id="selectdiv" class="row mt10">
                <strong style="font-size: 1.1em;"><?php _e( 'Type of Ad:','ad-zonia' ); ?></strong>
                <select name="nano_ad_type" id="nano_ad_type">
                    <option value="">Select One</option>
                    <option id="id1" value="imagead"
                        <?php
                        if ( isset($_POST['nano_ad_type']) && $_POST['nano_ad_type'] != '' ) {
                            echo ( $_POST['nano_ad_type'] === 'imagead' ? 'selected="selected"' : 'disabled="disabled"' );
                        } elseif( isset( $ad_query_with_id ) && !empty($ad_query_with_id[0]->ad_type) ) {
                            echo ( $ad_query_with_id[0]->ad_type === 'imagead' ? 'selected="selected"' : 'disabled="disabled"' );
                        } else {
                            echo '';
                        }
                        ?>
                    >Image Ad</option>
                    <option id="id3" value="codead"
                        <?php
                        if ( isset($_POST['nano_ad_type']) && $_POST['nano_ad_type'] != '' ) {
                            echo ( $_POST['nano_ad_type'] === 'codead' ? 'selected="selected"' : 'disabled="disabled"' );
                        } elseif( isset( $ad_query_with_id ) && !empty($ad_query_with_id[0]->ad_type) ) {
                            echo ( $ad_query_with_id[0]->ad_type === 'codead' ? 'selected="selected"' : 'disabled="disabled"' );
                        } else {
                            echo '';
                        }
                        ?>
                        >Code Ad</option>
                    <option value="flashad" disabled="disabled">Flash Ad (upcoming)</option>
                </select>
            </div>

            <div id="imagead" class="togglediv imagediv row mt10 form-elements hide-me">
                <label class="ad-label" for="nano_ad_image">Ad image<span class="required">*</span></label>
                <p class="direction">Upload an image or write up the path. Don't forget <tt>http://</tt> at the beginning.</p>
                <input type="text" size="36" name="ad_image" id="nano-ad-image-url" autocomplete="off" value="<?php
                if( isset( $ad_query_with_id ) && !empty($ad_query_with_id[0]->ad_image_url) ) {
                    echo $ad_query_with_id[0]->ad_image_url;
                } elseif( isset($_POST['ad_image']) && $_POST['ad_image'] != '' ) {
                    echo $_POST['ad_image'];
                } else {
                    echo '';
                }
                ?>" placeholder="http://" />
                <input type="button" name="nano_ad_image" class="button" id="nano-ad-image" value="Upload Image"/>

                <label class="ad-label" for="nano_ad_url">Link<span class="required">*</span></label>
                <p class="direction">Write down the URL to link the ad. Don't forget <tt>http://</tt> at the beginning. If you don't want to link the image, put a hash (<tt>#</tt>)</p>
                <input type="text" name="nano_ad_url" placeholder="http://example.com" size="30" value="<?php
                if( isset( $ad_query_with_id ) && !empty($ad_query_with_id[0]->url) ) {
                    echo $ad_query_with_id[0]->url;
                } elseif( isset($_POST['nano_ad_url']) && $_POST['nano_ad_url'] != '' ) {
                    echo $_POST['nano_ad_url'];
                } else {
                    echo '';
                }
                ?>" id="nano-ad-url">
            </div> <!-- #imagediv -->

            <div id="codead" class="togglediv codediv row mt10 form-elements hide-me">

                <label class="ad-label" for="nano_ad_code">Ad Code<span class="required">*</span></label>
                <p class="direction">If you have any custom code for your ad, you can write them here</p>
                <textarea rows="5" cols="40" name="nano_ad_code" id="nano-ad-code"><?php
                    if( isset( $ad_query_with_id ) && !empty($ad_query_with_id[0]->ad_code) ) {
                        echo stripslashes($ad_query_with_id[0]->ad_code);
                    } elseif( isset($_POST['nano_ad_code']) && $_POST['nano_ad_code'] != '' ) {
                        echo stripslashes($_POST['nano_ad_code']);
                    } else {
                        echo '';
                    }
                    ?></textarea>

                <label class="ad-label" for="ad_code_title">Ad Code Title</label>
                <p class="direction">Just if you like to name the code you've just written</p>
                <input type="text" size="30" name="ad_code_title" id="ad_code_title" value="<?php
                if( isset( $ad_query_with_id ) && !empty($ad_query_with_id[0]->ad_code_title) ) {
                    echo $ad_query_with_id[0]->ad_code_title;
                } elseif( isset($_POST['ad_code_title']) && $_POST['ad_code_title'] != '' ) {
                    echo $_POST['ad_code_title'];
                } else {
                    echo '';
                }
                ?>" />

            </div> <!-- #codediv -->

            <script type="text/javascript">
                // First to load the DOM, then the jQuery
                $(function() {
                    $('#nano_ad_type').change(function(){
                        $('.togglediv').hide();
                        $('#' + $(this).val()).show();
                    }).focus(function(){
                            $('.togglediv').hide();
                            $('#' + $(this).val()).show();
                        });
                });

                $(document).ready(function() {
                    $('#datetimepickerstart').datetimepicker();
                    $('#datetimepickerend').datetimepicker();
                });
            </script>

            <div id="datediv" class="row mt10">
                <div class="nano-ad-block-left form-elements">
                    <label class="ad-label" for="nano_ad_str_date">Start Date<span class="required">*</span></label>
                    <p class="direction">Choose a date, from when the ad will start showing</p>
                    <input type="text" id="datetimepickerstart" name="nano_ad_str_date" size="30" autocomplete="off" value="<?php
                    if( isset( $ad_query_with_id ) && !empty($ad_query_with_id[0]->str_time) ) {
                        echo $ad_query_with_id[0]->str_time;
                    } elseif( isset($_POST['nano_ad_str_date']) && $_POST['nano_ad_str_date'] != '' ) {
                        echo $_POST['nano_ad_str_date'];
                    } else {
                        echo '';
                    }
                    ?>">
                </div> <!-- .nano-ad-date-left -->
                <div class="nano-ad-block-right form-elements">
                    <label class="ad-label" for="nano_ad_end_date">End Date<span class="required">*</span></label>
                    <p class="direction">Choose a date, until when the ad will be visible</p>
                    <input type="text" id="datetimepickerend" name="nano_ad_end_date" size="30" autocomplete="off" value="<?php
                    if( isset( $ad_query_with_id ) && !empty($ad_query_with_id[0]->end_time) ) {
                        echo $ad_query_with_id[0]->end_time;
                    } elseif( isset($_POST['nano_ad_end_date']) && $_POST['nano_ad_end_date'] != '' ) {
                        echo $_POST['nano_ad_end_date'];
                    } else {
                        echo '';
                    }
                    ?>">
                </div> <!-- .nano-ad-date-right -->
            </div> <!-- #datediv -->

            </div> <!-- .nano-ad-left -->
            <?php

            /*
             * The hidden input field will occur only on Edit mode
             * Otherwise not
             */
            if( isset( $_GET["action"] ) && $_GET["action"] == 'edit' ) ?>
            <input type="hidden" name="ad_id" value="<?php echo ( isset( $_GET["id"] ) ? $_GET["id"] : '' ); ?>" />

            <div class="nano-ad-right">
                <label class="ad-label" style="border-bottom: 1px solid #ccc;" for="nano_ad_publish">Publish</label>
                <div class="nano-ad-submit-zone">

                    <div class="row">
                        <p><?php _e('After filling the necessary fields, to show the advertisement on the site, remember to <u>make the ad <strong>Active</strong></u>, and then Save the data.',  'ad-zonia'); ?></p>
                    </div>

                    <div class="nano-ad-submit row">
                        <label><input type="checkbox" name="nano_ad_active" id="nano_ad_active" value="1" <?php
                            if( isset( $ad_query_with_id ) && $ad_query_with_id[0]->ad_status === '1' ) {
                                echo 'checked="checked"';
                            } elseif( isset($_POST['nano_ad_active']) && $_POST['nano_ad_active'] != '' ) {
                                echo 'checked="checked"';
                            } else {
                                echo '';
                            }
                            ?>><span class="ad-active"><strong><?php
                                    if( isset( $ad_query_with_id ) && $ad_query_with_id[0]->ad_status == 1 ) {
                                        echo 'Active';
                                    } elseif( isset($_POST['nano_ad_active']) && $_POST['nano_ad_active'] != '' ) {
                                        echo 'Active';
                                    } else {
                                        echo 'Make Active';
                                    }
                                    ?></strong></span></label>
                        <?php if( isset( $_GET["action"] ) && $_GET["action"] === 'edit' ) { ?>
                            <input type="submit" name="nano_ad_update" id="nano-ad-publish" class="button button-primary button-large" value="Update">
                        <?php } else { ?>
                            <input type="submit" name="nano_ad_publish" id="nano-ad-publish" class="button button-primary button-large" value="Publish">
                        <?php } ?>
                    </div> <!-- .nano-ad-submit -->

                </div> <!-- .nano-ad-submit-zone -->

            </div> <!-- .nano-ad-right -->

        </div> <!-- .nano-ad-body -->

    </form>

</div> <!-- .wrapper -->
