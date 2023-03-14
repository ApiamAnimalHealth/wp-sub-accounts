<?php

defined( 'ABSPATH' ) or exit;  // Exit if accessed directly.

/**
 * Integration class for User Switching plugin.
 *
 * @since 1.0.0
 */
class WP_Sub_Accounts_Integration_User_Switching {


	/**
	 * Constructor.
	 *
	 * @since 1.0.0
	 */
    public function __construct()
    {

    }

    public function define_hooks()
    {

        wp_sub_accounts()->get_loader()->add_action( 'wp_sub_accounts_do_switch_clear_auth',  $this, 'maybe_do_something', 10, 2 );

    }

	public function maybe_do_something( $user_id, $parent_id )
    {

	}


}
