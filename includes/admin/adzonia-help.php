<?php
/**
 * Help Tabs
 *
 * Displaying WordPress help tab on AdZonia context pages.
 *
 * @author      nanodesigns
 * @category    Admin/Help
 * @package     AdZonia
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

/**
 * AdZonia Help Contents.
 *
 * @since  2.0.0 Introduced.
 * 
 * @return void.
 * ----------------------------------------------------
 */
function adzonia_help_contents() {
    $screen = get_current_screen();
    if( 'adzonia' === $screen->post_type && in_array($screen->base, array('post', 'edit')) ) {
    
        $help_tab_content = '<p>'. __('Advertisements added using AdZonia, can be displayed in 4 alternative ways &mdash;', 'adzonia') .'</p>';
        $help_tab_content .= '<ul>';
            $help_tab_content .= '<li>';
                // translators: AdZonia shortcode
                $help_tab_content .= sprintf( __('<strong>Shortcode</strong> &mdash; The simplest is using a shortcode. The shortcode is that simple, just put %s into the body of any post or page or shortcode enabled widget. Just replace the <em>hash</em> (<code>#</code>) with the ad ID.', 'adzonia'), '<code>[wp-adzonia id="#"]</code>' );
            $help_tab_content .= '</li>';
            $help_tab_content .= '<li>';
                $help_tab_content .= __('<strong>Widget</strong> &mdash; Using the AdZonia widget into any widget enabled area or sidebar. Just drag and drop the "AdZonia" widget into the sidebar, and choose the active (published) ad from the list.', 'adzonia');
            $help_tab_content .= '</li>';
            $help_tab_content .= '<li>';
                // translators: AdZonia PHP function
                $help_tab_content .= sprintf( __('<strong>PHP Code</strong> &mdash; If you are a developer and want to use the PHP code into your template (theme) directly, just use this: %s. Just replace the <em>hash</em> (<code>#</code>) with the ad ID.', 'adzonia'), '<code>&lt;?php if ( function_exists( "show_adzonia" ) ) show_adzonia( # ); ?&gt;</code>' );
            $help_tab_content .= '</li>';
            $help_tab_content .= '<li>';
                $help_tab_content .= __('<strong>Predefined Areas</strong> &mdash; a new beta feature is added to show advertisement in predefined areas, directly from the admin panel without any code. Just you have to set the location where you want to show the ad. (it&rsquo;s an additional feature will work beside all the 3 mentioned above)', 'adzonia');
            $help_tab_content .= '</li>';
        $help_tab_content .= '</ul>';
        
        $screen->add_help_tab( array(
            'id'       => 'adzonia-help',
            'title'    => __( 'AdZonia', 'adzonia' ),
            'content'  => $help_tab_content
        ));

        // Help sidebar
        $screen->set_help_sidebar(
            '<p><strong>'. __( 'For more detailed instructions:', 'adzonia' ) .'</strong></p>'.
            '<p><a href="https://github.com/nanodesigns/adzonia/wiki/User-Manual" target="_blank">'. __( 'AdZonia Manual', 'adzonia' ) .'</a></p>'.
            '<p>To reduce the plugin size we shifted the manual to our Github Wiki.</p>'
        );
    }
}

add_action( 'load-edit.php',        'adzonia_help_contents' );
add_action( 'load-post-new.php',    'adzonia_help_contents' );
