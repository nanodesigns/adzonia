<?php
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
