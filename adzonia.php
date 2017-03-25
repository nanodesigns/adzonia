<?php
/**
 * @package           AdZonia
 * @author            nanodesigns <info@nanodesignsbd.com>
 * @license           GPL-2.0+
 * @link              http://nanodesignsbd.com/
 *
 * @wordpress-plugin
 * Plugin Name:       AdZonia
 * Plugin URI:        http://adzonia.nanodesignsbd.com/
 * Description:       A simpler and easier advertisement manager plugin for WordPress sites, and most astonishingly - it's in WordPress way.
 * Version:           2.0.0
 * Author:            nanodesigns
 * Author URI:        http://nanodesignsbd.com/
 * Requires at least: 4.4.0
 * Tested up to:      4.7.3
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       adzonia
 * Domain Path:       /i18n/languages
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

/**
 * Translation-ready
 * Make the plugin translation-ready.
 *
 * Note:
 * the first-loaded translation file overrides any
 * following ones if the same translation is present.
 *
 * Locales found in:
 *      - WP_LANG_DIR/adzonia/adzonia-LOCALE.mo
 *      - WP_LANG_DIR/plugins/adzonia-LOCALE.mo
 *      
 * @since  1.0.0 Initiated.
 * @since  2.0.0 Modified for more flexibility.
 */
function adzonia_load_textdomain() {

    /**
     * -----------------------------------------------------------------------
     * WP FILTER HOOK
     * plugin_locale
     *
     * WordPress' core filter hook to filter a plugin's locale.
     *
     * @link   https://developer.wordpress.org/reference/hooks/plugin_locale/
     *
     * @param  string $locale The plugin's current locale.
     * @param  string $domain Text domain. Unique identifier for retrieving translated strings.
     * -----------------------------------------------------------------------
     */
    $locale = apply_filters( 'plugin_locale', get_locale(), 'adzonia' );
    
    load_textdomain(
        'adzonia',
        WP_LANG_DIR .'/adzonia/adzonia-'. $locale .'.mo'
    );

    load_plugin_textdomain(
        'adzonia',
        false,
        dirname( plugin_basename( __FILE__ ) ) .'/i18n/languages'
    );
}

add_action( 'init', 'adzonia_load_textdomain', 1 );


if ( ! class_exists( 'AdZonia' ) ) :

/**
 * Main AdZonia Class
 *
 * @class AdZonia
 * -----------------------------------------------------------------------
 */
final class AdZonia {

    /**
     * @var string
     */
    public $plugin = 'AdZonia';

    /**
     * @var string
     */
    public $version = '2.0.0';

    /**
     * Minimum WordPress version.
     * @var string
     */
    public $wp_version = '4.4.0';

    /**
     * @var string
     */
    public $prefix = 'adz_';

    /**
     * @var ADZ The single instance of the class
     */
    protected static $_instance = null;

    /**
     * Main AdZonia Instance.
     *
     * Ensures only one instance of AdZonia Ticket is loaded or can be loaded.
     * 
     * @static
     * @see ADZ()
     * @return AdZonia - Main instance
     */
    public static function instance() {
        if ( is_null( self::$_instance ) ) {
            self::$_instance = new self();
        }
        return self::$_instance;
    }

    /**
     * Get the plugin url.
     * @return string
     */
    public function plugin_url() {
        return untrailingslashit( plugins_url( '/', __FILE__ ) );
    }

    /**
     * Get the plugin path.
     * @return string
     */
    public function plugin_path() {
        return untrailingslashit( plugin_dir_path( __FILE__ ) );
    }

    /**
     * Get the plugin base location.
     * @return string
     */
    public function plugin_basename() {
        return plugin_basename( __FILE__ );
    }
    
}

endif;

/**
 * Returns the main instance of AdZonia.
 * @return ADZ
 * -----------------------------------------------------------------------
 */
function ADZ() {
    return AdZonia::instance();
}


/**
 * Cross Check Requirements when active
 *
 * Cross check for Current WordPress version is
 * greater than required. Cross check whether the user
 * has privilege to `activate_plugins`, so that notice
 * cannot be visible to any non-admin user.
 *
 * @link   http://10up.com/blog/2012/wordpress-plug-in-self-deactivation/
 * 
 * @since  2.0.0
 * -----------------------------------------------------------------------
 */
function adzonia_cross_check_on_activation() {
    $unmet = false;

    $install = new ADZ_Install;

    if ( current_user_can( 'activate_plugins' ) ) :
        
        if ( ! $install->adzonia_is_version_supported() ) {
            $unmet = true;
            add_action( 'admin_notices', array( 'WP_Install', 'adzonia_fail_version_admin_notice' ) );
        }

        if ( ! $install->adzonia_is_dependency_loaded() ) {
            $unmet = true;
            add_action( 'admin_notices', array( 'WP_Install', 'adzonia_fail_dependency_admin_notice' ) );
        }
        
        if( $unmet ) {

            add_action( 'admin_init', array( 'WP_Install', 'adzonia_force_deactivate' ) );
            
            if ( isset( $_GET['activate'] ) ) {
                unset( $_GET['activate'] );
            }

        }

    endif;
}

add_action( 'plugins_loaded', 'adzonia_cross_check_on_activation' );


/**
 * Add Settings link on plugin page
 *
 * Add a 'Settings' link to the Admin Plugin page after the activation
 * of the plugin. So the user can easily get to the Settings page, and
 * can setup the plugin as necessary.
 *
 * @since  2.0.0
 * 
 * @param  array $links  Links on the plugin page per plugin.
 * @return array         Modified with our link.
 * -----------------------------------------------------------------------
 */
function adzonia_plugin_settings_link( $links ) {
    // '/wp-admin/edit.php?post_type=adzonia&page=adzonia-settings';
    $settings_link = '<a href="'. esc_url( admin_url( 'edit.php?post_type=adzonia&page=adzonia-settings' ) ) .'" title="'. esc_attr__( 'Set the AdZonia settings', 'adzonia' ) .'">'. __( 'Settings', 'adzonia' ) .'</a>';

    array_unshift( $links, $settings_link ); //make the settings link be first item
    return $links;
}

add_filter( 'plugin_action_links_'. plugin_basename( __FILE__ ), 'adzonia_plugin_settings_link' );


require_once( 'includes/class-adzonia-install.php' );
require_once( 'includes/adzonia-cpt-adzonia.php' );
require_once( 'includes/adzonia-functions.php' );
require_once( 'includes/adzonia-set-environment.php' );
require_once( 'includes/shortcodes/adzonia-shortcode.php' );
require_once( 'includes/widgets/adzonia-widget.php' );

if( is_admin() ) :
    require_once( 'includes/adzonia-metaboxes.php' );
    require_once( 'admin/adzonia-admin-functions.php' );
    require_once( 'admin/adzonia-settings.php' );
endif;

register_activation_hook( __FILE__, array( 'ADZ_Install', 'adzonia_install' ) );
