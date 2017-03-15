<?php
/**
 * @package           AdZonia
 * @author            nanodesigns <info@nanodesignsbd.com>
 * @license           GPL-2.0+
 * @link              http://nanodesignsbd.com/
 *
 * @wordpress-plugin
 * Plugin Name:       AdZonia
 * Plugin URI:        http://github.com/nanodesigns/adzonia
 * Description:       A simpler and easier advertisement manager plugin for WordPress sites, and most astonishingly - it's in WordPress way.
 * Version:           2.0.0
 * Author:            nanodesigns
 * Author URI:        http://nanodesignsbd.com/
 * Requires at least: 4.4.0
 * Tested up to:      4.7.2
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       adzonia
 * Domain Path:       /i18n/languages
 */

// If this file is called directly, abort.
if ( ! defined( 'ABSPATH' ) ) {
    die;
}

if ( ! class_exists( 'AdZonia' ) ) :

/**
 * Main AdZonia Class.
 *
 * @class   AdZonia
 * @version 2.0.0
 * ----------------------------------------------------
 */
final class AdZonia {

    /**
     * AdZonia version.
     *
     * @var string
     * ----------------------------------------------------
     */
    public $version = '2.0.0';

    /**
     * The single instance of the class.
     *
     * @var AdZonia
     * ----------------------------------------------------
     */
    protected static $_instance = null;

    /**
     * Main AdZonia Instance.
     *
     * Ensures only one instance of AdZonia is loaded or can be loaded.
     *
     * @static
     * @see    ADZ()
     * @return AdZonia - Main instance.
     * ----------------------------------------------------
     */
    public static function instance() {
        if ( is_null( self::$_instance ) ) {
            self::$_instance = new self();
        }
        return self::$_instance;
    }

    /**
     * Cloning is forbidden.
     * ----------------------------------------------------
     */
    public function __clone() {
        wp_die(
            '<h1>' . __( 'Cheatin&#8217; uh?', 'adzonia' ) . '</h1>' .
            '<p>' . __( 'Sorry, you are not allowed to customize this site.', 'adzonia' ) . '</p>',
            403
        );
    }

    /**
     * AdZonia Constructor.
     * ----------------------------------------------------
     */
    public function __construct() {
        $this->define_constants();
        $this->includes();
        $this->init_hooks();

        do_action( 'adzonia_loaded' );
    }

    /**
     * Hook into actions and filters.
     * ----------------------------------------------------
     */
    private function init_hooks() {
        register_activation_hook( __FILE__, array( 'ADZ_Install', 'install' ) );
        add_action( 'init', array( $this, 'init' ), 0 );
        add_action( 'init', array( 'AdZonia_Shortcodes', 'init' ) );
    }

    /**
     * Define AdZonia Constants.
     * ----------------------------------------------------
     */
    private function define_constants() {
        $this->define( 'ADZ_PLUGIN_FILE', __FILE__ );
        $this->define( 'ADZ_ABSPATH', dirname( __FILE__ ) . '/' );
        $this->define( 'ADZ_PLUGIN_BASENAME', plugin_basename( __FILE__ ) );
        $this->define( 'ADZ_VERSION', $this->version );
        $this->define( 'ADZONIA_VERSION', $this->version );
    }

    /**
     * Define constant if not already set.
     *
     * @param  string       $name
     * @param  string|bool  $value
     * ----------------------------------------------------
     */
    private function define( $name, $value ) {
        if ( ! defined( $name ) ) {
            define( $name, $value );
        }
    }

    /**
     * Include required core files used in admin and on the frontend.
     * ----------------------------------------------------
     */
    public function includes() {
        include_once( ADZ_ABSPATH . 'includes/class-adz-autoloader.php' );
        include_once( ADZ_ABSPATH . 'includes/adz-core-functions.php' );
        include_once( ADZ_ABSPATH . 'includes/class-adz-shortcodes.php' );

        if ( is_admin() ) {
            include_once( ADZ_ABSPATH . 'includes/admin/class-adz-admin.php' );
        }
    }

    /**
     * Init AdZonia when WordPress Initialises.
     * ----------------------------------------------------
     */
    public function init() {
        // Before init action.
        do_action( 'before_adzonia_init' );

        // Set up localization.
        $this->load_plugin_textdomain();

        // Init action.
        do_action( 'adzonia_init' );
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
     * @since  1.0.0 initiated.
     * @since  2.0.0 updated for more flexibility.
     * ----------------------------------------------------
     */
    public function load_plugin_textdomain() {

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

    /**
     * Get the plugin url.
     * @return string
     * ----------------------------------------------------
     */
    public function plugin_url() {
        return untrailingslashit( plugins_url( '/', __FILE__ ) );
    }

    /**
     * Get the plugin path.
     * @return string
     * ----------------------------------------------------
     */
    public function plugin_path() {
        return untrailingslashit( plugin_dir_path( __FILE__ ) );
    }

}

endif;

/**
 * Main instance of AdZonia.
 *
 * Returns the main instance of ADZ to prevent the need to use globals.
 *
 * @since  2.0.0
 * @return AdZonia
 * ----------------------------------------------------
 */
function ADZ() {
    return AdZonia::instance();
}
