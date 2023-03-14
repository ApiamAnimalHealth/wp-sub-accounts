<?php

/**
 * The plugin bootstrap file
 *
 * This file is read by WordPress to generate the plugin information in the plugin
 * admin area. This file also includes all of the dependencies used by the plugin,
 * registers the activation and deactivation functions, and defines a function
 * that starts the plugin.
 *
 * @link              https://meta4.com.au
 * @since             1.0.0
 * @package           Wp_Sub_Accounts
 *
 * @wordpress-plugin
 * Plugin Name:       Wordpress Sub Accounts
 * Plugin URI:        https://meta4.com.au
 * Description:       This is a short description of what the plugin does. It's displayed in the WordPress admin area.
 * Version:           1.0.0
 * Author:            Lorne Gerlach
 * Author URI:        https://meta4.com.au
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       wp-sub-accounts
 * Domain Path:       /languages
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

/**
 * Currently plugin version.
 * Start at version 1.0.0 and use SemVer - https://semver.org
 * Rename this for your plugin and update it as you release new versions.
 */
define( 'WP_SUB_ACCOUNTS_VERSION', '1.1.0' );
define( 'WP_SUB_ACCOUNTS_PLUGIN_DIR', plugin_dir_path( __FILE__) );
define( 'WP_SUB_ACCOUNTS_PLUGIN_URL', plugin_dir_url( __FILE__) );

/**
 * The code that runs during plugin activation.
 * This action is documented in includes/class-wp-sub-accounts-activator.php
 */
function activate_wp_sub_accounts() {
	require_once WP_SUB_ACCOUNTS_PLUGIN_DIR . 'includes/class-wp-sub-accounts-installer.php';
	Wp_Sub_Accounts_Installer::activate();
}

/**
 * The code that runs during plugin deactivation.
 * This action is documented in includes/class-wp-sub-accounts-deactivator.php
 */
function deactivate_wp_sub_accounts() {
	require_once WP_SUB_ACCOUNTS_PLUGIN_DIR . 'includes/class-wp-sub-accounts-installer.php';
    Wp_Sub_Accounts_Installer::deactivate();
}

register_activation_hook( __FILE__, 'activate_wp_sub_accounts' );
register_deactivation_hook( __FILE__, 'deactivate_wp_sub_accounts' );

/**
 * The core plugin class that is used to define internationalization,
 * admin-specific hooks, and public-facing site hooks.
 */
require WP_SUB_ACCOUNTS_PLUGIN_DIR . 'includes/class-wp-sub-accounts.php';

/**
 * Begins execution of the plugin.
 *
 * Since everything within the plugin is registered via hooks,
 * then kicking off the plugin from this point in the file does
 * not affect the page life cycle.
 *
 * @since    1.0.0
 */
function run_wp_sub_accounts() {

    wp_sub_accounts()->run();

}
run_wp_sub_accounts();
