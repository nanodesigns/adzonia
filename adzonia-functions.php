<?php
/**
*   FUNCTION TO CALL WITH AJAX TO DEACTIVATE THE AD
*   Used in: adzonia-view.php
*/
function deactivate_ad() {
    global $wpdb;
    $table = $wpdb->wp_adzonia = $wpdb->prefix . "wp_adzonia";

    $id = explode('_', $_POST['id']);

    if( isset( $_POST['id'] ) ) {
      if( wp_verify_nonce( $id[2], $id[0] . '_' . $id[1] ) ) {
        $wpdb->update(
                    $table,
                    array( 'ad_status' => '0' ),
                    array( 'ID' => $id[1] ),
                    array( '%d' )
                );
      } else {
        echo 'Nonce not verified';
      }
    } else {
        echo 'Sorry, not done';
    }
    die(); // ajax call must die to avoid trailing 0 in your response
}

add_action('wp_ajax_deactivate_ad', 'deactivate_ad');
//add_action( 'wp_ajax_nopriv_deactivate_ad', 'deactivate_ad'); //not logged in users




/**
*   FUNCTION TO CALL WITH AJAX TO ACTIVATE THE AD
*   Used in: adzonia-view.php
*/
function activate_ad() {
    global $wpdb;
    $table = $wpdb->wp_adzonia = $wpdb->prefix . "wp_adzonia";

    $id = explode('_', $_POST['id']);

    if( isset( $_POST['id'] ) ) {
      if( wp_verify_nonce( $id[2], $id[0] . '_' . $id[1] ) ) {
        $wpdb->update(
                    $table,
                    array( 'ad_status' => '1' ),
                    array( 'ID' => $id[1] ),
                    array( '%d' )
                );
      } else {
        echo 'Nonce not verified';
      }
    } else {
        echo 'Sorry, not done';
    }
    die(); // ajax call must die to avoid trailing 0 in your response
}

add_action('wp_ajax_activate_ad', 'activate_ad');
//add_action( 'wp_ajax_nopriv_activate_ad', 'activate_ad'); //not logged in users



/**
*   FUNCTION TO CALL WITH AJAX TO DELETE THE AD
*   Used in: adzonia-view.php
*/
function delete_ad() {
    global $wpdb;
    $table = $wpdb->wp_adzonia = $wpdb->prefix . "wp_adzonia";

    $id = explode('_', $_POST['id']);

    if( isset( $_POST['id'] ) ) {
      if( wp_verify_nonce( $id[2], $id[0] . '_' . $id[1] ) ) {
        $wpdb->delete(
                    $table,
                    array( 'ID' => $id[1] ),
                    array( '%d' )
                );
      } else {
        echo 'Nonce not verified';
      }
    } else {
        echo 'Sorry, not done';
    }
    die(); // ajax call must die to avoid trailing 0 in your response
}

add_action('wp_ajax_delete_ad', 'delete_ad');
//add_action( 'wp_ajax_nopriv_delete_ad', 'delete_ad'); //not logged in users
