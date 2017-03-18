<?php

/**
 * Define the internationalization functionality
 *
 * Loads and defines the internationalization files for this plugin
 * so that it is ready for translation.
 *
 * @link       http://nanodesignsbd.com
 * @since      2.0.0
 *
 * @package    AdZonia
 * @subpackage AdZonia/includes
 */

/**
 * Define the internationalization functionality.
 *
 * Loads and defines the internationalization files for this plugin
 * so that it is ready for translation.
 *
 * @since      2.0.0
 * @package    AdZonia
 * @subpackage AdZonia/includes
 * @author     nanodesigns <info@nanodesignsbd.com>
 */
class AdZonia_i18n {


	/**
	 * Load the plugin text domain for translation.
	 *
	 * @since    2.0.0
	 */
	public function load_plugin_textdomain() {

		load_plugin_textdomain(
			'adzonia',
			false,
			dirname( dirname( plugin_basename( __FILE__ ) ) ) . '/i18n/languages/'
		);

	}



}
