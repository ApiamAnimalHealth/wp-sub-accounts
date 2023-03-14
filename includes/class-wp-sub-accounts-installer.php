<?php

/**
 * Fired during plugin activation
 *
 * @link       https://meta4.com.au
 * @since      1.0.0
 *
 * @package    Wp_Sub_Accounts
 * @subpackage Wp_Sub_Accounts/includes
 */

/**
 * Fired during plugin activation.
 *
 * This class defines all code necessary to run during the plugin's activation.
 *
 * @since      1.0.0
 * @package    Wp_Sub_Accounts
 * @subpackage Wp_Sub_Accounts/includes
 * @author     Lorne Gerlach <lorne.gerlach@meta4.com.au>
 */
class Wp_Sub_Accounts_Installer{


	public static function activate() {

	    self::create_roles();

	}

    public static function deactivate() {

        self::remove_roles();

    }

    public static function get_capabilities( $role = '' )
    {

        $capabilities = array(
            'parent' => array(
                'read'                  => true,
                'create_child_user'     => true,
                'edit_child_user'       => true,
                'delete_child_user'     => true,
                'switch_to_child_user'  => true
            ),
            'child' => array(
                'read'                  => true,
            )
        );

        if ( ! empty( $role ) )
        {
            if ( ! empty( $capabilities[$role] ) )
            {
                return $capabilities[$role];
            }
            else
            {
                return array();
            }
        }

        return $capabilities;

    }

    public static function create_roles()
    {
        global $wp_roles;

        if ( !class_exists( 'WP_Roles' ) )
        {
            return;
        }

        if ( !isset( $wp_roles ) )
        {
            $wp_roles = new WP_Roles(); // @codingStandardsIgnoreLine
        }

        // Parent role.
        $parent_capabilities = self::get_capabilities('parent');
        add_role(
            'parent',
            'Parent Account',
            $parent_capabilities
        );

        // Child role.
        $child_capabilities = self::get_capabilities('child');
        add_role(
            'child',
            'Child Account',
            $child_capabilities
        );
    }

    public static function remove_roles()
    {

        global $wp_roles;

        $plugin_roles = array(
            'parent',
            'child'
        );

        if ( ! class_exists( 'WP_Roles' ) ) {
            return;
        }

        if ( ! isset( $wp_roles ) ) {
            $wp_roles = new WP_Roles(); // @codingStandardsIgnoreLine
        }

        foreach( $plugin_roles as $plugin_role )
        {
            $role_capabilities = self::get_capabilities( $plugin_role );
            if ( ! empty( $role_capabilities ) )
            {
                foreach( $role_capabilities as $role_capability )
                {
                    $wp_roles->remove_cap( '$plugin_role', $role_capability );
                }
            }
            remove_role( $plugin_role );
        }

    }


}
