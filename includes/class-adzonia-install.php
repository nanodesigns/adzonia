<?php
/**
 * Installation related functions and actions.
 *
 * @author   	nanodesigns
 * @category 	Core
 * @package  	AdZonia/Classes
 * @version  	2.0.0
 */


if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * ADZ_Install Class.
 */
Class ADZ_Install {

    /**
     * Initiate the plugin
     * 
     * Register all the necessary things when the plugin get activated.
     *
     * @since   2.0.0
     * -----------------------------------------------------------------------
     */
    public static function adzonia_install() {
        
        /**
         * Update db version to current
         * @since  1.0.0
         * ...
         */
        delete_option( 'adzonia_version' );
    	add_option( 'adzonia_version', ADZ()->version );

        /**
         * Flush the rewrite rules, soft
         * 
         * To activate custom post types' single templates, and
         * taxonomies, we are flushing the rewrite rules, once.
         *
         * @since  2.0.0
         * ...
         */
        adzonia_register_cpt_adzonia();
        flush_rewrite_rules( false );

        /**
         * -----------------------------------------------------------------------
         * HOOK : ACTION HOOK
         * adzonia_installed
         * 
         * Hook fired just after the completion of installing AdZonia
         *
         * @since  2.0.0
         * -----------------------------------------------------------------------
         */
        do_action( 'adzonia_installed' );
        
    }


    /**
     * Check if plugin dependencies are ready.
     *
     * @since  2.0.0
     * @return boolean True of dependencies are here, false otherwise.
     * --------------------------------------------------------------------------
     */
    public function adzonia_is_dependency_loaded() {
        if( ! file_exists( ADZ()->plugin_path() .'/assets/css/adzonia.css' ) ) {
            return false;
        } else if( ! file_exists( ADZ()->plugin_path() .'/assets/css/adzonia-admin.css' ) ) {
            return false;
        } else if( ! file_exists( ADZ()->plugin_path() .'/assets/js/adzonia.min.js' ) ) {
            return false;
        } else if( ! file_exists( ADZ()->plugin_path() .'/assets/js/adzonia-admin.min.js' ) ) {
            return false;
        }

        return true;
    }


    /**
     * Check whether the plugin is compatible to WordPress version
     *
     * @since  2.0.0
     * @return boolean True of WordPress version supported, false otherwise.
     * --------------------------------------------------------------------------
     */
    public function adzonia_is_version_supported() {
        if ( version_compare( get_bloginfo( 'version' ), ADZ()->wp_version, '<=' ) ) {
            return false;
        }

        return true;
    }


    /**
     * Admin notices: Failed version dependency
     *
     * @since  2.0.0
     * --------------------------------------------------------------------------
     */
    public function adzonia_fail_version_admin_notice() {
        echo '<div class="updated"><p>';
            printf(
                /* translators: 1. minimum WordPress core version 2. WordPress update page URL */
                wp_kses( __('AdZonia requires WordPress core version <strong>%1$s</strong> or greater. The plugin has been deactivated. Consider <a href="%2$s">upgrading WordPress</a>.', 'adzonia' ),
                    array( 'a' => array('href' => true), 'strong' => array() )
                ),
                ADZ()->wp_version,
                admin_url('update-core.php')
            );
        echo '</p></div>';                
    }

    /**
     * Admin notices: Failed resouces dependency
     *
     * @since  2.0.0
     * --------------------------------------------------------------------------
     */
    public function adzonia_fail_dependency_admin_notice() {
        echo '<div class="updated"><p>';
            printf(
                /* translators: 1. first command 2. second command 3. plugin installation link with popup thickbox (modal) */
                wp_kses( __( 'AdZonia&rsquo;s required dependencies are not loaded - plugin cannot function properly. Open the command console and run %1$s and then %2$s before anything else. If you are unaware what this is, please <a href="%3$s" class="thickbox">install the production version</a> instead.', 'adzonia' ),
                    array( 'a' => array('href' => true, 'class' => true) )
                ),
                '<code>npm install</code>',
                '<code>grunt</code>',
                esc_url( add_query_arg( array(
                    'tab'           => 'plugin-information',
                    'plugin'        => 'adzonia',
                    'TB_iframe'     => 'true',
                    'width'         => '600',
                    'height'        => '800'
                ), admin_url('plugin-install.php') ) )
            );
        echo '</p></div>';
    }


    /**
     * Deactivate the plugin
     * Deactivate the plugin forcefully on unmet dependencies.
     *
     * @since  2.0.0
     * --------------------------------------------------------------------------
     */
    public function adzonia_force_deactivate() {
        deactivate_plugins( ADZ()->plugin_basename() );
    }

}
