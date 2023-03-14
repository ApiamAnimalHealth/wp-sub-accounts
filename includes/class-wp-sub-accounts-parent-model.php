<?php


class Wp_Sub_Accounts_Parent_Model {

    /**
     * The single instance of the class.
     */
    protected static $_instance = null;

    public $role = 'parent';

    public $capabilities = array(
        'read'                  => true,
        'create_child_user'     => true,
        'edit_child_user'       => true,
        'delete_child_user'     => true,
        'switch_to_child_user'  => true
    );


    public static function instance() {
        if ( is_null( self::$_instance ) ) {
            self::$_instance = new self();
        }
        return self::$_instance;
    }


    public function get_children( $user_id )
    {

        $args  = array(
            'meta_key' => 'is_child_of_' .$user_id ,
            'meta_value' => 1
        );

        $user_query = new WP_User_Query( $args );

        $results = $user_query->get_results();
        if ( ! empty( $results ) )
        {
            return $results;
        }

        return false;

    }

    public function add_child( $parent_id, $child )
    {
        if ( is_numeric( $child ) )
        {
            $user_data = get_userdata( $child );
            if ( $user_data === false )
            {
                // user doesn't exist
                return false;
            }
        }
        elseif ( $child instanceof WP_User )
        {
            $child = $child->ID;
        }
        elseif ( is_object( $child ) )
        {
            if ( empty( $child->user_login ) )
            {
                // Not a valid user object
                return false;
            }
            $child = $child->ID;
        }
        elseif ( is_array( $child ) )
        {
            $result = wp_insert_user( $child );
            if ( is_wp_error( $result ) )
            {
                return false;
            }
            $child = $result;
        }

        if ( ! user_can( $child, 'child' ) )
        {
            $child_user = get_userdata( $child );
            $child_user->add_role('child');
        }

        // $child should now be a valid user ID
        $result = update_user_meta( $child, 'parent', $parent_id );
        $result = update_user_meta( $child, 'is_child_of_'.$parent_id, true );
        if ( ! empty( $result ) ) {

            return true;

        } else {

            return false;

        }

    }

    public function is_child_of( $child, $parent_id ) {

        $result = get_user_meta( $child, 'parent', true );
        $result = get_user_meta( $child, 'is_child_of_'.$parent_id, true );

        if ( ! empty( $result ) ) return true;

        return false;
/*
        if ( ! is_array( $result ) ) $result = [$result];

        return in_array( $parent, $result );
*/
    }



}


function wp_sub_accounts_parent_model() {
    return Wp_Sub_Accounts_Parent_Model::instance();
}


