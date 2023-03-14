<?php


class Wp_Sub_Accounts_User_Switching_Controller {


    /**
     * The single instance of the class.
     */
    protected static $_instance = null;

    public $model;

    public function __construct(  )
    {
        $this->model = wp_sub_accounts_user_switching_model();
    }

    public static function instance() {
        if ( is_null( self::$_instance ) ) {
            self::$_instance = new self();
        }
        return self::$_instance;
    }

    public function filter_user_has_cap( array $user_caps, array $required_caps, array $args, WP_User $user ) {


        if ( 'switch_to_child_user' === $args[0] ) {

        	// Must have "parent" capacity to switch to any child.
            $can_switch = isset( $user_caps['parent']  );

            // Lets check if we're looking at a specific user to switch to.
            if ( $can_switch && ! empty( $args[2] ) ) {

            	// Yes. This is their ID.
                $user_id_to_switch_to = $args[2];

                // If the user we're switching to is not the user whose capacities we are checking,
	            // we need to confirm a relationship between the two.
                if ( $user->ID != $user_id_to_switch_to ) {

	                if( wp_sub_accounts_parent_model()->is_child_of( $user_id_to_switch_to, $user->ID ) ) {

		                $can_switch = true;

	                }

                }

            }

            $user_caps['switch_to_child_user'] = $can_switch;

        }

        return $user_caps;
    }

    public function define_cookies()
    {

        // Our auth_cookie
        if ( ! defined( 'WP_SUB_ACCOUNTS_USER_COOKIE' ) ) {
            define( 'WP_SUB_ACCOUNTS_USER_COOKIE', 'wp_sub_accounts_user_' . COOKIEHASH );
        }

        // Our secure_auth_cookie
        if ( ! defined( 'WP_SUB_ACCOUNTS_USER_SECURE_COOKIE' ) ) {
            define( 'WP_SUB_ACCOUNTS_USER_SECURE_COOKIE', 'wp_sub_accounts_user_secure_' . COOKIEHASH );
        }

        // The parent account's cookie
        if ( ! defined( 'WP_SUB_ACCOUNTS_PARENT_COOKIE' ) ) {
            define( 'WP_SUB_ACCOUNTS_PARENT_COOKIE', 'wp_sub_accounts_parent_' . COOKIEHASH );
        }

    }

    /**
     * Returns whether or not User Switching's equivalent of the 'logged_in' cookie should be secure.
     *
     * This is used to set the 'secure' flag on the old user cookie, for enhanced security.
     *
     * @link https://core.trac.wordpress.org/ticket/15330
     *
     * @return bool Should the old user cookie be secure?
     */
    public static function secure_parent_cookie() {
        return ( is_ssl() && ( 'https' === parse_url( home_url(), PHP_URL_SCHEME ) ) );
    }

    /**
     * Returns whether or not User Switching's equivalent of the 'auth' cookie should be secure.
     *
     * This is used to determine whether to set a secure auth cookie or not.
     *
     * @return bool Should the auth cookie be secure?
     */
    public static function secure_auth_cookie() {
        return ( is_ssl() && ( 'https' === parse_url( wp_login_url(), PHP_URL_SCHEME ) ) );
    }


    public static function get_auth_cookie()
    {
        if ( self::secure_auth_cookie() )
        {
            $auth_cookie_name = WP_SUB_ACCOUNTS_USER_SECURE_COOKIE;
        }
        else
        {
            $auth_cookie_name = WP_SUB_ACCOUNTS_USER_COOKIE;
        }

        if ( isset( $_COOKIE[ $auth_cookie_name ] ) )
        {
            $cookie = json_decode( wp_unslash( $_COOKIE[ $auth_cookie_name ] ) ); // WPCS: sanitization ok
        }
        if ( ! isset( $cookie ) || ! is_array( $cookie ) )
        {
            $cookie = array();
        }

        return $cookie;

    }


    public function set_parent_cookie( $parent_id )
    {

        $expiration             = time() + 172800; // 48 hours
        $parent_cookie          = wp_generate_auth_cookie( $parent_id, $expiration, 'logged_in' );
        $secure_parent_cookie   = self::secure_parent_cookie();
/*
        $secure_auth_cookie     = self::secure_auth_cookie();
        $secure_parent_cookie   = self::secure_parent_cookie();
        $auth_cookie            = self::get_auth_cookie();
        $parent_cookie          = wp_generate_auth_cookie( $parent_id, $expiration, 'logged_in' );

        if ( $secure_auth_cookie ) {
            $auth_cookie_name = WP_SUB_ACCOUNTS_USER_SECURE_COOKIE;
            $scheme = 'secure_auth';
        } else {
            $auth_cookie_name = WP_SUB_ACCOUNTS_USER_COOKIE;
            $scheme = 'auth';
        }

        array_push( $auth_cookie, wp_generate_auth_cookie( $parent_id, $expiration, $scheme ) );

        setcookie( $auth_cookie_name, json_encode( $auth_cookie ), $expiration, SITECOOKIEPATH, COOKIE_DOMAIN, $secure_auth_cookie, true );
*/
        setcookie( WP_SUB_ACCOUNTS_PARENT_COOKIE, $parent_cookie, $expiration, COOKIEPATH, COOKIE_DOMAIN, $secure_parent_cookie, true );

    }

    public function clear_parent_cookie()
    {
/*
        $auth_cookie = self::get_auth_cookie();

        if ( ! empty( $auth_cookie ) ) {
            array_pop( $auth_cookie );
        }
*/
        if ( ! empty( $_COOKIE ) )
        {
            foreach( $_COOKIE as $index => $cookie )
            {
                if ( strpos( $index, 'wp_sub_accounts' ) !== false )
                {
                    unset($_COOKIE[$index]);
                    $res = setcookie($index, '', time() - 3600);
                }
            }
        }
/*

        if ( empty( $auth_cookie ) ) {
            $expire = time() - 31536000;
            setcookie( WP_SUB_ACCOUNTS_USER_COOKIE,         ' ', $expire, SITECOOKIEPATH, COOKIE_DOMAIN );
            setcookie( WP_SUB_ACCOUNTS_USER_SECURE_COOKIE,  ' ', $expire, SITECOOKIEPATH, COOKIE_DOMAIN );
            setcookie( WP_SUB_ACCOUNTS_PARENT_COOKIE,       ' ', $expire, COOKIEPATH, COOKIE_DOMAIN );
        }
*/

    }


    public function maybe_do_switch()
    {

    	// Need an action and a user ID to proceed.
        if ( empty( $_REQUEST['action'] ) || empty( $_REQUEST['user_id'] ) ) return;

        switch ( $_REQUEST['action'] ) {

            case 'wp_sub_accounts_user_switching':

                // Check intent:
                check_admin_referer( "wp_sub_accounts_user_switching" );

	            // Need to be logged in to switch to another user.
	            $current_user = ( is_user_logged_in() ) ? wp_get_current_user() : null;
	            if ( empty( $current_user ) ) return;

	            $user_id   = $_REQUEST['user_id'];
	            $parent_id = null;
	            $can_switch = false;

	            if( user_can( $current_user, 'switch_to_child_user', $user_id ) ) {

		            $can_switch = true;
		            $parent_id  = $current_user->ID;

	            } else {

		            $parent_user = $this->model->get_parent_user();
		            if ( ! empty( $parent_user ) ) {

			            if( user_can( $parent_user, 'switch_to_child_user', $user_id ) ) {

				            $can_switch = true;
				            $parent_id  = $parent_user->ID;

			            }

		            }

	            }

	            if ( $can_switch && ! empty( $parent_id ) ) {

		            $user = $this->do_switch( $user_id, $parent_id );
		            if ( $user ) {

			            if ( ! empty( $_REQUEST['current_url'] ) ) {

				            $redirect = $_REQUEST['current_url'];

			            } else {

				            $redirect = home_url();

			            }

			            $redirect = apply_filters( 'wp_sub_accounts_after_switch_redirect_url', $redirect );

                        wp_redirect($redirect);
                        exit;

		            } else {

			            logit('switch no good.');

		            }

	            }

                break;
        }

    }

    public function do_switch( $user_id, $parent_id, $remember = true )
    {

        $user = get_userdata( $user_id );

        if ( ! $user ) {
            return false;
        }

/*
        if ( user_can($user, 'parent') )
        {
            $parent_id = get_current_user_id();
        }
        else
        {
            $parent_id = get_user_meta( $user_id, 'parent', true );
        }
*/
        do_action( 'wp_sub_accounts_do_switch_clear_auth', $user_id, $parent_id );
        wp_clear_auth_cookie();

        if ( ! empty( $parent_id ) )
        {
            $this->set_parent_cookie( $parent_id );
        }

        wp_set_auth_cookie( $user_id, $remember );
        wp_set_current_user( $user_id );

        do_action( 'wp_sub_accounts_do_switch_set_auth', $user_id, $parent_id );

        return $user;
    }


    public static function current_url() {
        return ( is_ssl() ? 'https://' : 'http://' ) . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI']; // @codingStandardsIgnoreLine
    }


    public function output_switching_form( $return = false ) {

	    $output = '';

        $user = wp_get_current_user();
        if ( ! empty( $user->ID ) ) {

            if ( current_user_can('switch_to_child_user') ) {

                $parent_id = get_current_user_id();

            } else {

                $parent_id = $this->model->is_current_user_switched();

            }

	        $children = wp_sub_accounts_parent_model()->get_children( $parent_id );
	        // Sanity checks. Let's make sure everything is as it should be.
	        // If we're not the parent, we need to make sure we're one of the children
	        if ( ! empty( $children ) && $user->ID !== $parent_id ) {

		        $children_ids = wp_list_pluck( $children, 'ID' );
		        if ( ! in_array( $user->ID, $children_ids ) ) {
		        	// Something's wrong here.. let's just show the current login information
			        $parent_id = null;
			        $parent    = null;
			        $children  = null;
		        }

	        }
	        if ( ! empty( $parent_id ) ) {
		        $parent = get_userdata( $parent_id );
	        }

	        $current_url = self::current_url();


	        ob_start();

            include( apply_filters( 'wp_sub_accounts_switching_form_template', WP_SUB_ACCOUNTS_PLUGIN_DIR . 'assets/partials/wp-sub-accounts-switching-form-html.php' ) );

            $output .= ob_get_clean();

	        if ( ! empty( $return ) ) {

		        return $output;

	        } else {

		        echo $output;

	        }


        }

    }

}

function wp_sub_accounts_user_switching_controller() {
    return Wp_Sub_Accounts_User_Switching_Controller::instance();
}