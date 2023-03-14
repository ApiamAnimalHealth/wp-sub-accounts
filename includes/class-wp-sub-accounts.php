<?php

/**
 * The file that defines the core plugin class
 *
 * A class definition that includes attributes and functions used across both the
 * public-facing side of the site and the admin area.
 *
 * @link       https://meta4.com.au
 * @since      1.0.0
 *
 * @package    Wp_Sub_Accounts
 * @subpackage Wp_Sub_Accounts/includes
 */

/**
 * The core plugin class.
 *
 * This is used to define internationalization, admin-specific hooks, and
 * public-facing site hooks.
 *
 * Also maintains the unique identifier of this plugin as well as the current
 * version of the plugin.
 *
 * @since      1.0.0
 * @package    Wp_Sub_Accounts
 * @subpackage Wp_Sub_Accounts/includes
 * @author     Lorne Gerlach <lorne.gerlach@meta4.com.au>
 */
class Wp_Sub_Accounts {


    /**
     * The single instance of the class.
     */
    protected static $_instance = null;

    private $plugin_path;


    /**
	 * The loader that's responsible for maintaining and registering all hooks that power
	 * the plugin.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      Wp_Sub_Accounts_Loader    $loader    Maintains and registers all hooks for the plugin.
	 */
	protected $loader;


    /**
     * The class that's responsible for loading and maintaining all of the plugin's integrations
     *
     * @since    1.0.0
     * @access   protected
     * @var      WP_Sub_Accounts_Integrations    $integrations    .
     */
    protected $integrations;

    /**
	 * The unique identifier of this plugin.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      string    $plugin_name    The string used to uniquely identify this plugin.
	 */
	protected $plugin_name;

	/**
	 * The current version of the plugin.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      string    $version    The current version of the plugin.
	 */
	protected $version;

	/**
	 * Define the core functionality of the plugin.
	 *
	 * Set the plugin name and the plugin version that can be used throughout the plugin.
	 * Load the dependencies, define the locale, and set the hooks for the admin area and
	 * the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */
	public function __construct() {
		if ( defined( 'WP_SUB_ACCOUNTS_VERSION' ) ) {
			$this->version = WP_SUB_ACCOUNTS_VERSION;
		} else {
			$this->version = '1.0.0';
		}
		$this->plugin_name = 'wp-sub-accounts';

		$this->load_dependencies();
		$this->set_locale();
		$this->set_integrations();
		$this->define_admin_hooks();
		$this->define_public_hooks();

	}

    public static function instance() {
        if ( is_null( self::$_instance ) ) {
            self::$_instance = new self();
        }
        return self::$_instance;
    }


	/**
	 * Load the required dependencies for this plugin.
	 *
	 * Include the following files that make up the plugin:
	 *
	 * - Wp_Sub_Accounts_Loader. Orchestrates the hooks of the plugin.
	 * - Wp_Sub_Accounts_i18n. Defines internationalization functionality.
	 * - Wp_Sub_Accounts_Admin. Defines all hooks for the admin area.
	 * - Wp_Sub_Accounts_Public. Defines all hooks for the public side of the site.
	 *
	 * Create an instance of the loader which will be used to register the hooks
	 * with WordPress.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function load_dependencies() {

		/**
		 * The class responsible for orchestrating the actions and filters of the
		 * core plugin.
		 */
		require_once WP_SUB_ACCOUNTS_PLUGIN_DIR . 'includes/class-wp-sub-accounts-loader.php';
        $this->loader = new Wp_Sub_Accounts_Loader();

		require_once WP_SUB_ACCOUNTS_PLUGIN_DIR . 'includes/wp-sub-accounts-utility-functions.php';

		/**
		 * The class responsible for updating the plugin (if required).
		 */
		require_once WP_SUB_ACCOUNTS_PLUGIN_DIR . 'includes/class-wp-sub-accounts-update.php';

		/**
		 * The class responsible for defining all actions that occur in the admin area.
		 */

//        require_once WP_SUB_ACCOUNTS_PLUGIN_DIR . 'admin/react-wp-scripts.php';

		require_once WP_SUB_ACCOUNTS_PLUGIN_DIR . 'includes/class-wp-sub-accounts-admin.php';
		require_once WP_SUB_ACCOUNTS_PLUGIN_DIR . 'includes/class-wp-sub-accounts-multi-role-model.php';
		require_once WP_SUB_ACCOUNTS_PLUGIN_DIR . 'includes/class-wp-sub-accounts-multi-role-controller.php';
		require_once WP_SUB_ACCOUNTS_PLUGIN_DIR . 'includes/class-wp-sub-accounts-parent-model.php';
		require_once WP_SUB_ACCOUNTS_PLUGIN_DIR . 'includes/class-wp-sub-accounts-parent-controller.php';
		require_once WP_SUB_ACCOUNTS_PLUGIN_DIR . 'includes/class-wp-sub-accounts-user-switching-model.php';
		require_once WP_SUB_ACCOUNTS_PLUGIN_DIR . 'includes/class-wp-sub-accounts-user-switching-controller.php';

		/**
		 * The class responsible for defining all actions that occur in the public-facing
		 * side of the site.
		 */
		require_once WP_SUB_ACCOUNTS_PLUGIN_DIR . 'includes/class-wp-sub-accounts-public.php';



	}

	/**
	 * Define the locale for this plugin for internationalization.
	 *
	 * Uses the Wp_Sub_Accounts_i18n class in order to set the domain and to register the hook
	 * with WordPress.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function set_locale() {

		$plugin_i18n = $this->load_class(
            '/includes/class-wp-sub-accounts-i18n.php',
            'Wp_Sub_Accounts_i18n'
        );

		$this->loader->add_action( 'plugins_loaded', $plugin_i18n, 'load_plugin_textdomain' );

	}

	private function set_integrations()
    {
        $this->integrations = $this->load_class(
            '/includes/integrations/class-wp-sub-accounts-integrations.php',
            'WP_Sub_Accounts_Integrations'
        );
        $this->loader->add_action( 'init', $this->integrations, 'load_dependencies' );
    }

	/**
	 * Register all of the hooks related to the admin area functionality
	 * of the plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function define_admin_hooks() {


//		$plugin_admin = new Wp_Sub_Accounts_Admin( $this->get_plugin_name(), $this->get_version() );
//
//		$this->loader->add_action( 'admin_enqueue_scripts', $plugin_admin, 'enqueue_styles' );
//		$this->loader->add_action( 'admin_enqueue_scripts', $plugin_admin, 'enqueue_scripts' );
//		$this->loader->add_action( 'admin_enqueue_scripts', $plugin_admin, 'enqueue_assets' );
//		$this->loader->add_action( 'admin_menu', $plugin_admin, 'admin_menu' );

		if ( function_exists( 'wp_sub_accounts_update' ) ) {

			$plugin_update = wp_sub_accounts_update();
			$this->loader->add_action( 'admin_init', $plugin_update, 'check_version' );

		}

		$plugin_user_switching_controller = new Wp_Sub_Accounts_User_Switching_Controller();

        $this->loader->add_action( 'plugins_loaded',        $plugin_user_switching_controller, 'define_cookies' );
        $this->loader->add_action( 'init',                  $plugin_user_switching_controller, 'maybe_do_switch' );
        $this->loader->add_filter( 'user_has_cap',          $plugin_user_switching_controller, 'filter_user_has_cap', 10, 4 );
        $this->loader->add_action( 'wp_logout',             $plugin_user_switching_controller, 'clear_parent_cookie' );
        $this->loader->add_action( 'wp_login',              $plugin_user_switching_controller, 'clear_parent_cookie' );
        $this->loader->add_action( 'switch_to_user',        $plugin_user_switching_controller, 'clear_parent_cookie' );
        $this->loader->add_action( 'switch_back_user',      $plugin_user_switching_controller, 'clear_parent_cookie' );


        $plugin_multi_role_model = new Wp_Sub_Accounts_Multi_Role_Model();
        $plugin_multi_role_controller = new Wp_Sub_Accounts_Multi_Role_Controller( $plugin_multi_role_model );

        $this->loader->add_action( 'admin_enqueue_scripts', $plugin_multi_role_controller, 'remove_dropdown' );
        $this->loader->add_action( 'user_new_form',         $plugin_multi_role_controller, 'output_checklist' );
        $this->loader->add_action( 'show_user_profile',     $plugin_multi_role_controller, 'output_checklist' );
        $this->loader->add_action( 'edit_user_profile',     $plugin_multi_role_controller, 'output_checklist' );
        $this->loader->add_action( 'profile_update',        $plugin_multi_role_controller, 'process_checklist' );
        $this->loader->add_action( 'user_register',         $plugin_multi_role_controller, 'process_checklist' );

        $this->loader->add_action( 'set_user_role',         $plugin_multi_role_controller, 'preserve_user_roles', 10, 3 );



	}

	/**
	 * Register all of the hooks related to the public-facing functionality
	 * of the plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function define_public_hooks() {

//        $this->integrations->define_hooks();

		$plugin_public = new Wp_Sub_Accounts_Public( $this->get_plugin_name(), $this->get_version() );

        $this->loader->add_action( 'wp_enqueue_scripts', $plugin_public, 'enqueue_styles' );
        $this->loader->add_action( 'wp_enqueue_scripts', $plugin_public, 'enqueue_scripts' );

//        $plugin_admin = new Wp_Sub_Accounts_Admin( $this->get_plugin_name(), $this->get_version() );
//        $this->loader->add_action( 'wp_enqueue_scripts', $plugin_admin, 'enqueue_assets' );

	}

	/**
	 * Run the loader to execute all of the hooks with WordPress.
	 *
	 * @since    1.0.0
	 */
	public function run() {
		$this->loader->run();
	}


    public function get_plugin_path() {

        if ( ! empty( $this->plugin_path ) )
        {
            return $this->plugin_path;
        }

        return $this->plugin_path = WP_SUB_ACCOUNTS_PLUGIN_DIR;

    }


    /**
     * Require and instantiate a class
     *
     * @since 1.0.0
     * @param string $local_path path to class file in plugin, e.g. '/includes/class-wc-foo.php'
     * @param string $class_name class to instantiate
     * @return object instantiated class instance
     */
    public function load_class( $local_path, $class_name ) {

        require_once( $this->get_plugin_path() . $local_path );

        return new $class_name;
    }

	/**
	 * The name of the plugin used to uniquely identify it within the context of
	 * WordPress and to define internationalization functionality.
	 *
	 * @since     1.0.0
	 * @return    string    The name of the plugin.
	 */
	public function get_plugin_name() {
		return $this->plugin_name;
	}

	/**
	 * The reference to the class that orchestrates the hooks with the plugin.
	 *
	 * @since     1.0.0
	 * @return    Wp_Sub_Accounts_Loader    Orchestrates the hooks of the plugin.
	 */
	public function get_loader() {
		return $this->loader;
	}

	/**
	 * Retrieve the version number of the plugin.
	 *
	 * @since     1.0.0
	 * @return    string    The version number of the plugin.
	 */
	public function get_version() {
		return $this->version;
	}

}
function wp_sub_accounts() {
    return Wp_Sub_Accounts::instance();
}