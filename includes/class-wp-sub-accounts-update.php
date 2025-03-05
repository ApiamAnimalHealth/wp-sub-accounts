<?php

/**
 * Used to manage plugin updates
 *
 * @link       https://meta4.com.au
 * @since      1.1.0
 *
 * @package    Wp_Sub_Accounts
 * @subpackage Wp_Sub_Accounts/includes
 */

/**
 * Used to manage plugin updates
 *
 * This class defines all code necessary to run when the plugin is updated.
 *
 * @since      1.1.0
 * @package    Wp_Sub_Accounts
 * @subpackage Wp_Sub_Accounts/includes
 * @author     Lorne Gerlach <lorne.gerlach@meta4.com.au>
 */
class Wp_Sub_Accounts_Update {

	public $prefix = 'wp_sub_accounts_';
	public $version;
	public $log_file;

	public static $_instance;

	public function __construct() {

	}

	public static function instance() {
		if ( is_null( self::$_instance ) ) {
			self::$_instance = new self();
		}
		return self::$_instance;
	}


	/**
	 * @param $version_in_code array The version we're updating to in the following format: [major, minor, patch]
	 *
	 */
	public function init_log_file( $version_in_code ) {

		$upload_dir     = wp_upload_dir( null, false );
		$plugin_log_dir = $upload_dir['basedir'] . '/' . wp_sub_accounts()->get_plugin_name() . '/logs/updates' ;

		if ( ! is_dir( $plugin_log_dir ) ) {
			mkdir( $plugin_log_dir, 0755, true );
		}

		$filename = 'update-' . implode( '-', $version_in_code ) . '.log';

		$this->log_file = $plugin_log_dir . '/' . $filename;

	}

	/**
	 * @param string $msg The message to log.
	 * @param string $log_file The absolute path of the logfile
	 *
	 * @return bool
	 */
	public function log( $msg ) {

		$msg = date('Y-m-d h:i:s') . " :: " . $msg . PHP_EOL;
		return error_log( $msg, 3, $this->log_file );

	}


	public function check_version() {

	    $version_in_use  = get_option( 'wp_sub_accounts_version', '1.1.0' );
		$version_in_code = wp_sub_accounts()->get_version();

		if ( $version_in_use != $version_in_code ) {

			$version_in_use            = explode('.', $version_in_use );
			$version_in_code           = explode( '.', $version_in_code );
			$update_function_file_path = WP_SUB_ACCOUNTS_PLUGIN_DIR . 'includes/updates/v' . $version_in_code[0] . '.php';
			$update_result             = false;

			if ( file_exists( $update_function_file_path ) ) {

				require_once $update_function_file_path;

				$this->init_log_file( $version_in_code );

				$is_major_update = $version_in_code[0] > $version_in_use[0];
				if ( $is_major_update ) {

					$function_name = $this->prefix . 'major_update_v' . $version_in_code[0];
					if (  function_exists( $function_name ) ) {

						$update_result = $function_name();

					} else {

						// No update functions found for this major version..
						// return true to update the wp_sub_accounts_version option
						$update_result = true;

					}

				} else {

					// Minor Update?
					if ( $version_in_code[0] == $version_in_use[0] && $version_in_code[1] > $version_in_use[1] ) {

						// Yup.
						$function_name = $this->prefix . 'minor_update_v' . $version_in_code[0] . '_' . $version_in_code[1];
						if (  function_exists( $function_name ) ) {

							$update_result = $function_name();

						} else {

							// No update functions found for this minor version..
							// return true to update the wp_sub_accounts_version option
							$update_result = true;

						}

					} else {

						// We don't account for patch updates..
						// return true to update the wp_sub_accounts_version option
						$update_result = true;

					}


				}

			} else {

				// No update function file found for this major version..
				// return true to update the wp_sub_accounts_version option
				$update_result = true;

			}

			if ( ! empty( $update_result ) && ! is_wp_error( $update_result ) ) {

				$version_in_code = implode( '.', $version_in_code );
				update_option( 'wp_sub_accounts_version', $version_in_code );

			} else {

				if ( is_wp_error( $update_result ) ) {

					$subject = __('WP Sub Accounts update problem.');

					$msg = 'Hey there,' . PHP_EOL;
					$msg .= PHP_EOL;
					$msg .= 'There was a problem updating the WP Sub Accounts plugin.' . PHP_EOL;
					$msg .= PHP_EOL;
					$msg .= 'The version currently installed is: '.implode( '.', $version_in_use ). PHP_EOL;
					$msg .= 'The version new version is: '.implode( '.', $version_in_code ). PHP_EOL;
					$msg .= PHP_EOL;
					$msg .= 'The error returned was: '. PHP_EOL;
					$msg .= PHP_EOL;
					$msg .= $update_result->get_error_message(). PHP_EOL;
					$msg .= PHP_EOL;
					$msg .= 'The logfile can be found at: '. PHP_EOL;
					$msg .= PHP_EOL;
					$msg .= $this->log_file. PHP_EOL;

					send_admin_notification( $subject, $msg );

				}

			}

	    }

	}

}

function wp_sub_accounts_update() {
	return Wp_Sub_Accounts_Update::instance();
}
