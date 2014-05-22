<?php

/*function deactivate_ad() {
    //global $wpdb;
    //$table = $wpdb->wp_adzonia = $wpdb->prefix . "wp_adzonia";

    //$id = explode('_', $_POST['id']);

    if( isset( $_POST['id'] ) ) {
      if( wp_verify_nonce( $id[2], $id[0] . '_' . $id[1] ) ) {
        $wpdb->update(
                    $table,
                    array(
                        'ad_status' => '0' //integer
                    ),
                    array( 'ID' => $id[1] ),
                    array(
                        '%d'
                    )
                );
        echo $_POST['id'];
        echo 'Updated status';
      } else {
        echo 'Nonce not verified';
      }
    } else {
        echo 'Sorry, not done';
    }
    wp_die(); // ajax call must die to avoid trailing 0 in your response
}

add_action('wp_ajax_deactivate_ad', 'deactivate_ad');
add_action( 'wp_ajax_nopriv_deactivate_ad', 'deactivate_ad'); //not logged in users */
