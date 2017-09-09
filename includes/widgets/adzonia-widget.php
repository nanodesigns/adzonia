<?php
/**
 * AdZonia Widget.
 *
 * Adding a widget to add advertisement to the widget-enabled areas easily.
 *
 * @since  1.2.0 Initiated.
 * @since  2.0.0 Updated with the new version, and ensured proper sanitization.
 *
 * @author      nanodesigns
 * @category    Widget
 * @package     AdZonia
 * ----------------------------------------------------
 */
class AdZonia_Widget extends WP_Widget {
    function __construct() {

        parent::__construct(
            'adzonia',                          // ID of widget
            esc_html__('AdZonia', 'adzonia'),   // name of the widget
            array(
                'description' => esc_html__( 'AdZonia widget to call the advertisement easily.', 'adzonia' )
            )
        );

    }

    // Widget Backend
    public function form( $instance ) {

        $title = ( isset($instance['title']) && ! empty($instance['title']) ) ? $instance['title'] : '';
        $ad_id = ( isset($instance['ad_id']) && ! empty($instance['ad_id']) ) ? $instance['ad_id'] : '';

        // Widget admin form
        $published_ads = get_posts( array(
            'post_type'      => 'adzonia',
            'posts_per_page' => -1,
            'post_status'    => 'publish'
        ) );
        ?>
        <p>
            <label for="<?php echo esc_attr( $this->get_field_id('title') ); ?>">
                <?php esc_html_e( 'Title:', 'adzonia' ); ?>
            </label>
            <input class="widefat" id="<?php echo esc_attr( $this->get_field_id('title') ); ?>" name="<?php echo esc_attr( $this->get_field_name('title') ); ?>" type="text" value="<?php echo esc_attr( $title ); ?>" />
        </p>
        <p>
            <label for="<?php echo esc_attr( $this->get_field_id('ad_id') ); ?>">
                <?php esc_html_e( 'Advertisements:', 'adzonia' ); ?>
            </label>
            <select class="widefat" id="<?php echo intval( $this->get_field_id('ad_id') ); ?>" name="<?php echo esc_attr( $this->get_field_name('ad_id') ); ?>">
                <option value=""><?php esc_html_e( 'Choose one...', 'adzonia' ); ?></option>
                <?php
                foreach( $published_ads as $published_ad ) { ?>
                    <option value="<?php echo intval( $published_ad->ID ); ?>" <?php selected( $ad_id, $published_ad->ID ); ?>>
                        <?php echo intval($published_ad->ID) .' &ndash; '. esc_html($published_ad->post_title); ?>
                    </option>
                <?php
                }
                ?>
            </select>
        </p>

    <?php
    }

    // Updating widget replacing old instances with new
    public function update( $new_instance, $old_instance ) {

        $instance          = $old_instance;
        $instance['title'] = ! empty($new_instance['title']) ? strip_tags($new_instance['title'])   : '';
        $instance['ad_id'] = ! empty($new_instance['ad_id']) ? intval($new_instance['ad_id'])       : '';

        return $instance;

    }

    // Creating Widget Front End
    public function widget( $args, $instance ) {

        $title = apply_filters( 'widget_title', $instance['title'] );
        
        echo $args['before_widget'];

            if ( ! empty( $title ) ) {
                // Before & after widget args are defined by themes
                echo $args['before_title'];
                    echo $title;
                echo $args['after_title'];
            }

            // This is where you run the code and display the output
            if ( ! empty( $instance['ad_id'] ) ) {
                show_adzonia( $instance['ad_id'] );
            }

        echo $args['after_widget'];

    }
}

// Register and load the widget
function adzonia_load_widget() {
    register_widget( 'AdZonia_Widget' );
}

add_action( 'widgets_init', 'adzonia_load_widget' );
