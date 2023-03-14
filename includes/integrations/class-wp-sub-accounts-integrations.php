<?php

defined( 'ABSPATH' ) or exit;  // Exit if accessed directly.

/**
 * Class handling integrations and compatibility issues with other plugins:
 *
 * - WooCommerce Subscriptions: https://woocommerce.com/
 * - User Switching: https://wordpress.org/plugins/user-switching/
 *
 * @since 1.0.0
 */
class WP_Sub_Accounts_Integrations {



    /** @var null|\WP_Sub_Accounts_Integration_WooCommerce instance */
    private $woocommerce;

    /** @var null|\WP_Sub_Accounts_Integration_User_Switching instance */
    private $user_switching;


    /**
     * Loads integrations.
     *
     * @since 1.6.0
     */
    public function __construct() {

    }

    public function load_dependencies()
    {

        // WooCommerce
        if ( $this->is_woocommerce_active() )
        {
            $this->woocommerce = wp_sub_accounts()->load_class( '/includes/integrations/woocommerce/class-wp-sub-accounts-integration-woocommerce.php', 'WP_Sub_Accounts_Integration_WooCommerce' );
        }

        // User Switching
        if ( $this->is_user_switching_active() )
        {
            $this->user_switching = wp_sub_accounts()->load_class( '/includes/integrations/user-switching/class-wp-sub-accounts-integration-user-switching.php', 'WP_Sub_Accounts_Integration_User_Switching' );
        }

        $this->define_hooks();

    }

    public function define_hooks()
    {

        if ( $this->get_woocommerce_instance() )
        {
            $this->get_woocommerce_instance()->define_hooks();
        }

        if ( $this->get_user_switching_instance() )
        {
            $this->get_user_switching_instance()->define_hooks();
        }


    }


    /**
     * Returns the WooCommerce integration instance.
     *
     * @since 1.0.0
     *
     * @return null|\WP_Sub_Accounts_Integration_WooCommerce
     */
    public function get_woocommerce_instance() {
        return $this->woocommerce;
    }


    /**
     * Returns the User Switching integration instance.
     *
     * @since 1.0.0
     *
     * @return null|\WP_Sub_Accounts_Integration_User_Switching
     */
    public function get_user_switching_instance() {
        return $this->user_switching;
    }


    /**
     * Checks if WooCommerce is active
     *
     * @since 1.0.0
     *
     * @return bool
     */
    public function is_woocommerce_active() {

        return is_plugin_active( 'woocommerce/woocommerce.php' );
    }


    /**
     * Checks if User Switching is active.
     *
     * @since 1.0.0
     *
     * @return bool
     */
    public function is_user_switching_active() {
        return is_plugin_active( 'user-switching/user-switching.php' );
    }



}
