<?php

/**
 * Define the internationalization functionality
 *
 * Loads and defines the internationalization files for this plugin
 * so that it is ready for translation.
 *
 * @link       https://meta4.com.au
 * @since      1.0.0
 *
 * @package    Wp_Sub_Accounts
 * @subpackage Wp_Sub_Accounts/includes
 */

/**
 * Define the internationalization functionality.
 *
 * Loads and defines the internationalization files for this plugin
 * so that it is ready for translation.
 *
 * @since      1.0.0
 * @package    Wp_Sub_Accounts
 * @subpackage Wp_Sub_Accounts/includes
 * @author     Lorne Gerlach <lorne.gerlach@meta4.com.au>
 */
class Wp_Sub_Accounts_i18n {


	/**
	 * Load the plugin text domain for translation.
	 *
	 * @since    1.0.0
	 */
	public function load_plugin_textdomain() {

		load_plugin_textdomain(
			'wp-sub-accounts',
			false,
			dirname( dirname( plugin_basename( __FILE__ ) ) ) . '/languages/'
		);

	}



}
