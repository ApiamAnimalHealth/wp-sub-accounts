<?php

/**
 * The public-facing functionality of the plugin.
 *
 * @link       https://meta4.com.au
 * @since      1.0.0
 *
 * @package    Wp_Sub_Accounts
 * @subpackage Wp_Sub_Accounts/public
 */

/**
 * The public-facing functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the public-facing stylesheet and JavaScript.
 *
 * @package    Wp_Sub_Accounts
 * @subpackage Wp_Sub_Accounts/public
 * @author     Lorne Gerlach <lorne.gerlach@meta4.com.au>
 */
class Wp_Sub_Accounts_Public {

	/**
	 * The ID of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $plugin_name    The ID of this plugin.
	 */
	private $plugin_name;

	/**
	 * The version of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $version    The current version of this plugin.
	 */
	private $version;

	/**
	 * Initialize the class and set its properties.
	 *
	 * @since    1.0.0
	 * @param      string    $plugin_name       The name of the plugin.
	 * @param      string    $version    The version of this plugin.
	 */
	public function __construct( $plugin_name, $version ) {

		$this->plugin_name = $plugin_name;
		$this->version = $version;

	}

	/**
	 * Register the stylesheets for the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_styles() {

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in Wp_Sub_Accounts_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Wp_Sub_Accounts_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		wp_enqueue_style( $this->plugin_name, WP_SUB_ACCOUNTS_PLUGIN_URL . 'assets/css/wp-sub-accounts-public.css', array(), $this->version, 'all' );

	}

	/**
	 * Register the JavaScript for the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_scripts() {

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in Wp_Sub_Accounts_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Wp_Sub_Accounts_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		wp_enqueue_script( $this->plugin_name, WP_SUB_ACCOUNTS_PLUGIN_URL . 'assets/js/wp-sub-accounts-public.js', array( 'jquery' ), filemtime( WP_SUB_ACCOUNTS_PLUGIN_DIR . 'assets/js/wp-sub-accounts-public.js' ), true );

	}

}
