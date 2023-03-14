<?php

/**
 * A set of action functions which handle the behavior of the roles checklist
 * on user edit screens.
 */
class Wp_Sub_Accounts_Multi_Role_Controller {

    /**
     * The model object.
     *
     * @var object
     */
    var $model;

    /**
     * Constructor. Define properties.
     *
     * @param object $model The model object.
     */
    public function __construct( $model ) {
        $this->model = $model;
    }

    /**
     * Remove the default WordPress role dropdown from the DOM.
     *
     * @param string $hook The current admin screen.
     */
    public function remove_dropdown( $hook ) {

        if ( $hook == 'user-edit.php' || $hook == 'user-new.php')
        {
            wp_enqueue_script( 'wp-sub-accounts-multi-roles', WP_SUB_ACCOUNTS_PLUGIN_URL . 'assets/js/wp-sub-accounts-multi-roles.js', array( 'jquery' ), '1.0' );
        }


    }

    /**
     * Output the checklist view. If the user is not allowed to edit roles,
     * nothing will appear.
     *
     * @param WP_User $user The current user object.
     */
    public function output_checklist( $user ) {

        if ( ! $this->model->can_update_roles() ) {
            return;
        }

        wp_nonce_field( 'update-wp-sub-accounts-multi-roles', 'wp_sub_accounts_multi_roles_nonce' );

        $roles      = $this->model->get_roles();
        $user_roles = is_object( $user ) ? $user->roles : array();
        $user_id    = is_object( $user ) ? $user->ID : 0;

        include( apply_filters( 'wp_sub_accounts_multi_roles_checklist_template', WP_SUB_ACCOUNTS_PLUGIN_DIR . 'assets/partials/wp-sub-accounts-roles-checklist-html.php' ) );

    }

    /**
     * Update the given user's roles as long as we've passed the nonce
     * and permissions checks.
     *
     * @param int $user_id The user ID whose roles might get updated.
     */
    public function process_checklist( $user_id ) {

        if ( empty( $_POST['_wp_http_referer'] ) ) {
            return;
        }

        if ( isset( $_POST['wp_sub_accounts_multi_roles_nonce'] ) && ! wp_verify_nonce( $_POST['wp_sub_accounts_multi_roles_nonce'], 'update-wp-sub-accounts-multi-roles' ) ) {
            return;
        }

        $new_roles = isset( $_POST['wp_sub_accounts_multi_roles'] ) ? $_POST['wp_sub_accounts_multi_roles'] : array();
        if ( empty( $new_roles ) ) {
            return;
        }

        if ( ! $this->model->can_update_roles() ) {
            return;
        }

        $this->model->update_roles( $user_id, $new_roles );

    }


    /**
     * @param int       $user_id    The user ID.
     * @param string    $new_role   The new role.
     * @param array     $old_roles  An array of the user's previous roles.
     */
    public function preserve_user_roles( $user_id, $new_role, $old_roles ) {

        if ( ! empty( $new_role ) && $new_role !== 'parent' )
        {
            // We're not trying to reset all roles.
            if ( ! empty( $old_roles ) )
            {
                if ( in_array( 'parent', $old_roles ) )
                {
                    $user = new WP_User($user_id);
                    $user->add_role( 'parent' );
                }
            }
        }

    }

}
