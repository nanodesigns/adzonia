<?php require_once('adzonia-functions.php'); ?>


<div class="wrap nano-ad nano-ad-view">
  <h2 class="page-title">AdZonia Ad Management<a href="<?php echo admin_url('/admin.php?page=add-adzonia'); ?>" class="add-new-h2">Add New</a></h2>
  <?php

  if( isset( $_GET["success"] ) ) {
      if( isset( $_GET["addnew"] ) && ($_GET["addnew"] === '1') )
          $success_message = "Your advertisement is <strong>added</strong> successfully!";
      if( isset( $_GET["edit"] ) && ($_GET["edit"] === '1') )
          $success_message = "Your advertisement is <strong>updated</strong> successfully!";
      if( isset( $_GET["activate"] ) && ($_GET["activate"] === 'true') )
          $success_message = "Your selected advertisement is <strong>activated</strong>!";
      if( isset( $_GET["activate"] ) && ($_GET["activate"] === 'false') )
          $success_message = "Your selected advertisement is <strong>deactivated</strong>!";
      if( isset( $_GET["delete"] ) && ($_GET["delete"] === 'yes') )
          $success_message = "Your selected advertisement is <strong>deleted</strong>!";
      echo '<div id="notification-message" class="notification-success">';
      echo '<p>' . ( !empty( $success_message ) ? $success_message : '' ) . '</p>';
      echo '</div>';
  }



  global $wpdb;
  $table = $wpdb->wp_adzonia = $wpdb->prefix . "wp_adzonia";

  $ad_query = $wpdb->get_results(
      "SELECT *
          FROM $table;
          ");

  $active = array_reduce($ad_query, function ($result, $item) {
      if ($item->ad_status === '1') {
          return $result + 1;
      }
      return $result;
  });

  ?>

  <ul class="subsubsub nano-ad-list">
      <li class="all">Total Ad <span class="count">(<?php echo $total = count($ad_query); ?>)</span> |</li>
      <li class="ad-active">Active Ad <span class="count">(<?php echo ( !empty($active) ? $active : '0'); ?>)</span> |</li>
      <li class="ad-inactive">Inactive Ad <span class="count">(<?php echo $inactive = $total - $active; ?>)</span></a></li>
  </ul>

  <form action="" enctype="multipart/form-data" method="post">

    <table class="wp-list-table widefat nano-ad-table">
        <thead>
        <tr>
            <th>ID</th>
            <th>Ad Type</th>
            <th>Name of the Ad</th>
            <th>Specification</th>
            <th>Start Date</th>
            <th>End Date</th>
            <th>Shortcode</th>
        </tr>
        </thead>
        <tbody>
        
        <?php
        /*
         * UPDATE
         * Activate or Deactivate on this page
         */
        if( isset( $_GET["activate"] ) ) {
            $row_id = $_GET["id"];

            // ACTIVATE
            if ($_GET["activate"] === 'true'){
                $wpdb->update(
                    $table,
                    array(
                        'ad_status' => '1' //integer
                    ),
                    array( 'ID' => $row_id ),
                    array(
                        '%d'
                    )
                );
            }

            /*// DEACTIVATE
            if ($_GET["activate"] == 'false'){
                $wpdb->update(
                    $table,
                    array(
                        'ad_status' => '0' //integer
                    ),
                    array( 'ID' => $row_id ),
                    array(
                        '%d'
                    )
                );
            }*/
        }

        /*
         * DELETE
         * delete row on click
         */

        if( isset( $_GET["delete"] ) ) {
            $item_id = $_GET["id"];

            // DELETE ROW
            if ($_GET["delete"] == 'yes'){
                $wpdb->delete(
                    $table,
                    array( 'ID' => $item_id ),
                    array(
                        '%d' //integer
                    )
                );
            }
        } //endif( isset( $_GET["delete"] ) )

        if( !empty( $ad_query ) ) {

            foreach ( $ad_query as $the_ad ) {
                $element_id = 'deactivate_' . $the_ad->id . '_' . wp_create_nonce('deactivate_' . $the_ad->id );

                echo '<tr id="ad-row-'. $the_ad->id .'" class="ad_row'. ( $the_ad->ad_status === '1' ? '' : ' ad-inactive' ) .'">';
                    echo '<td class="'. ( $the_ad->ad_status === '1' ? ' ad-active-id' : ' ad-inactive-id' ) .'">' . $the_ad->id . '</td>';
                    echo "<td>";
                    if( $the_ad->ad_type == 'imagead' ){
                        echo '<img class="wp-ad-image" src="' . $the_ad->ad_image_url . '" width="50" height="auto" alt="Image Ad - Image" />';
                    } else {
                        echo '<span class="code">' . '&lt;code&gt;<br/>
                        &nbsp;&nbsp;code ad<br/>
                        &lt;&frasl;code&gt;' . '</span>';
                    }
                    echo "</td>";
                    echo '<td>';
                        echo '<a class="row-ad-name" href="?page=add-adzonia&id='. $the_ad->id .'&action=edit">' . ( empty( $the_ad->name_of_ad ) ? 'Anonymous Ad' : $the_ad->name_of_ad ) . '<a/>';
                        echo '<div class="ad_edit_links">';
                        echo '<a href="?page=add-adzonia&id='. $the_ad->id .'&action=edit">Edit</a>';
                        echo '&nbsp;|&nbsp';
                        echo ( $the_ad->ad_status === '1' ? '<a class="deactivate-ad" href="#" id="'. $element_id .'">Deactivate</a>' : '<a href="?page=adzonia&id='. $the_ad->id .'&activate=true&success">Activate</a>' );
                        echo '&nbsp;|&nbsp';
                        echo '<a href="?page=adzonia&id='. $the_ad->id .'&delete=yes&success">Delete</a>';
                        echo '</div>';
                    echo "</td>";
                    echo "<td>";
                    if( $the_ad->ad_type === 'imagead' ){
                        if( $the_ad->url === "#" ) {
                            echo "&times; Not Linked";
                        } else {
                            echo "Linked to: " . $the_ad->url;
                        }
                    } else {
                        if( !empty( $the_ad->ad_code_title ) ) {
                            echo $the_ad->ad_code_title;
                        } else {
                            echo "Anonymous Code Ad";
                        }
                    }
                    echo "</td>";
                    echo "<td>" . mysql2date( 'j M Y', $the_ad->str_time) . "<br/>" . mysql2date( 'g:i A', $the_ad->str_time) . "</td>";
                    echo "<td>" . mysql2date( 'j M Y', $the_ad->end_time) . "<br/>" . mysql2date( 'g:i A', $the_ad->end_time) . "</td>";
                    echo '<td><span class="code">[wp-adzonia id="'. $the_ad->id .'"]</span></td>';
                echo "</tr>";
            }

        } else {
            echo "<tr>";
                echo '<td colspan="6">';
                    echo '<div class="text-center dead">No ad was posted yet. Please add some advertisement to view here.</div>';
                echo '</td>';
            echo "</tr>";
        }
        ?>
        </tbody>
        <tfoot>
        <tr>
            <th>ID</th>
            <th>Ad Type</th>
            <th>Name of the Ad</th>
            <th>Specification</th>
            <th>Start Date</th>
            <th>End Date</th>
            <th>Shortcode</th>
        </tr>
        </tfoot>
    </table>

  </form>

</div> <!-- .wrapper -->

<script type="text/javascript">
  var ajaxurl = '<?php echo admin_url('admin-ajax.php'); ?>';

  jQuery(document).on('click', '.deactivate-ad', function () {
      var id = this.id;
      alert(id);
      jQuery.ajax({
          type: 'POST',
          url: ajaxurl,
          data: {
                  action: "deactivate_ad",
                  id: id
                },
          success: function (data) {
            alert(data);
          }
      });
  });
</script>

<?php
function deactivate_ad() {
    //global $wpdb;
    //$table = $wpdb->wp_adzonia = $wpdb->prefix . "wp_adzonia";

    //$id = explode('_', $_POST['id']);

    if( isset( $_POST['id'] ) ) {
      /*if( wp_verify_nonce( $id[2], $id[0] . '_' . $id[1] ) ) {
        $wpdb->update(
                    $table,
                    array(
                        'ad_status' => '0' //integer
                    ),
                    array( 'ID' => $id[1] ),
                    array(
                        '%d'
                    )
                );*/
        echo $_POST['id'];
        echo 'Updated status';
      /*} else {
        echo 'Nonce not verified';
      }*/
    } else {
        echo 'Sorry, not done';
    }
    wp_die(); // ajax call must die to avoid trailing 0 in your response
}

add_action('wp_ajax_deactivate_ad', 'deactivate_ad');
add_action( 'wp_ajax_nopriv_deactivate_ad', 'deactivate_ad'); //not logged in users
?>
