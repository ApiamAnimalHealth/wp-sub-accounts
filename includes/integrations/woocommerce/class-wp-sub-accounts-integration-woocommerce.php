<?php

defined( 'ABSPATH' ) or exit;  // Exit if accessed directly.

/**
 * Integration class for WooCommerce plugin.
 *
 * @since 1.0.0
 */
class WP_Sub_Accounts_Integration_WooCommerce {


    /**
     * The single instance of the class.
     */
    protected static $_instance = null;


	/**
	 * Constructor.
	 *
	 * @since 1.0.0
	 */
	public function __construct() {


	}

    public static function instance() {
        if ( is_null( self::$_instance ) ) {
            self::$_instance = new self();
        }
        return self::$_instance;
    }

	public function define_hooks()
    {
//        add_filter( 'woocommerce_persistent_cart_enabled', '__return_false' );
        add_action( 'wp_sub_accounts_do_switch_set_auth',  array( $this, 'destroy_woocommerce_session' )  );
//        add_action( 'woocommerce_set_cart_cookies',  array( $this, 'action_woocommerce_set_cart_cookies' )  );

    }

    function action_woocommerce_set_cart_cookies()
    {
//        logit('action_woocommerce_set_cart_cookies');

    }


	/**
	 * Clears the current WooCommerce session.
	 *
	 * @since 1.0.0
	 *
	 * @param int $user_id
	 * @param mixed $parent_id
	 */
	public function destroy_woocommerce_session( $user_id, $parent_id = false )
    {
//        logit('calling destroy cart session');
//        WC()->cart->get('session')->destroy_cart_session();
//        logit('calling destroy session');
        WC()->session->destroy_session();


	}


}
