<?php
/**
 * Functions and actions specific to AdZonia installation.
 *
 * @version     2.0.0
 * @package     AdZonia/Classes
 * @category    Admin
 * @author      nanodesigns
 * ----------------------------------------------------
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

/**
 * ADZ_Install class.
 */
class ADZ_Install {

	/**
	 * Initiate.
	 * ----------------------------------------------------
	 */
	public static function init() {
		add_action( 'init', array( __CLASS__, 'check_version' ), 5 );
		add_action( 'admin_init', array( __CLASS__, 'install_actions' ) );
		add_filter( 'plugin_action_links_' . ADZ_PLUGIN_BASENAME, array( __CLASS__, 'plugin_action_links' ) );
	}

	/**
	 * Init background updates
	 * ----------------------------------------------------
	 */
	/*public static function init_background_updater() {
		include_once( dirname( __FILE__ ) . '/class-adz-background-updater.php' );
		self::$background_updater = new ADZ_Background_Updater();
	}*/

	/**
	 * Check AdZonia version and run the updater is required.
	 *
	 * This check is done on all requests and runs if he versions do not match.
	 * ----------------------------------------------------
	 */
	public static function check_version() {
		if ( get_option( 'adzonia_version' ) !== ADZ()->version ) {
			self::install();
			do_action( 'adzonia_updated' );
		}
	}

	/**
	 * Install ADZ.
	 * ----------------------------------------------------
	 */
	public static function install() {
		// Register post types
		ADZ_Post_types::register_post_types();

		// Queue upgrades/setup wizard
		$current_adz_version = get_option( 'adzonia_version', null );

		self::update_adz_version();

		// Trigger action
		do_action( 'adzonia_installed' );
	}

	/**
	 * Update ADZ version to current.
	 * ----------------------------------------------------
	 */
	private static function update_adz_version() {
		delete_option( 'adzonia_version' );
		add_option( 'adzonia_version', ADZ()->version );
	}

	/**
	 * Default options.
	 *
	 * Sets up the default options used on the settings page.
	 * ----------------------------------------------------
	 */
	/*private static function create_options() {
		// Include settings so that we can run through defaults
		include_once( dirname( __FILE__ ) . '/admin/class-adz-admin-settings.php' );
	}*/

	/**
	 * Show action links on the plugin screen.
	 *
	 * @param	mixed $links Plugin Action links
	 * @return	array
	 * ----------------------------------------------------
	 */
	public static function plugin_action_links( $links ) {
		$action_links = array(
			'settings' => '<a href="' . admin_url( 'admin.php?page=adz-settings' ) . '" aria-label="' . esc_attr__( 'View AdZonia settings', 'adzonia' ) . '">' . __( 'Settings', 'adzonia' ) . '</a>',
		);

		return array_merge( $action_links, $links );
	}

}

ADZ_Install::init();
