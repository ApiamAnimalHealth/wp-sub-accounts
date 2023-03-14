<?php


class Wp_Sub_Accounts_User_Switching_Model {

    /**
     * The single instance of the class.
     */
    protected static $_instance = null;

    public function __construct(  )
    {

    }

    public static function instance() {
        if ( is_null( self::$_instance ) ) {
            self::$_instance = new self();
        }
        return self::$_instance;
    }

    public static function get_parent_cookie()
    {

        if ( isset( $_COOKIE[ WP_SUB_ACCOUNTS_PARENT_COOKIE ] ) )
        {
            return wp_unslash( $_COOKIE[ WP_SUB_ACCOUNTS_PARENT_COOKIE ] ); // WPCS: sanitization ok
        }
        else
        {
            return false;
        }

    }

    public static function get_parent_user()
    {

        $cookie = self::get_parent_cookie();

        if ( ! empty( $cookie ) )
        {

            $parent_id = wp_validate_auth_cookie( $cookie, 'logged_in' );
            if ( $parent_id )
            {
                return get_userdata( $parent_id );
            }

        }

        return false;

    }

    public function is_current_user_switched()
    {

        if ( is_user_logged_in() )
        {
            $parent_user = self::get_parent_user();
            if ( ! empty( $parent_user ) )
            {
                return $parent_user->ID;
            }
        }

        return false;

    }



}
function wp_sub_accounts_user_switching_model() {
    return Wp_Sub_Accounts_User_Switching_Model::instance();
}