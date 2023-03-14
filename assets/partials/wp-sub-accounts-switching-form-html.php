<?php
/**
 * Output the switching dropdown form.
 *
 * @var $parent WP_User
 * @var $current_url string
 * @var $children array
 */
if ( ( user_can( $user, 'parent' )  || ! empty( $parent ) ) && ! empty( $children ) ) {
    ?>
    <form action="<?php echo wp_login_url(); ?>" method="post" class="wp-sub-accounts-user-switching-form">
        <span class="wp-sub-accounts-user-switching-drawer-toggle"></span>
        <?php wp_nonce_field( 'wp_sub_accounts_user_switching' ); ?>
        <input type="hidden" name="action" value="wp_sub_accounts_user_switching">
        <input type="hidden" name="current_url" value="<?php echo $current_url; ?>>">
        <input type="hidden" name="current_user_id" value="<?php echo $user->ID; ?>"
               id="wp-sub-accounts-current-user-id">
        <label for="wp-sub-accounts-user-switcher" class="wp-sub-accounts-user-switching-label" class="wp-sub-accounts-user-label">
            <?php echo apply_filters( 'wp_sub_accounts_user_switcher_label', __( 'Currently logged in as: ', 'wp-sub-accounts' ) ); ?>
        </label>
        <select name="user_id" id="wp-sub-accounts-user-switcher">
            <option value="<?php echo $parent->ID; ?>"><?php echo $parent->user_login; ?></option>
            <?php
            foreach ( $children as $child )
            {
                ?>
                <option value="<?php echo $child->ID; ?>">&nbsp;&nbsp;&nbsp;<?php echo $child->user_login; ?></option>
                <?php
            }
            ?>
        </select>
        <div class="wp-sub-accounts-user-switching-drawer">
            <?php if (! user_can( $user, 'parent' ) ) { ?>
                <div class="wp-sub-accounts-user-switching-original-user">
                    <?php echo __( 'Originally logged in as: ', 'wp-sub-accounts' ) . $parent->user_login;?>
                </div>
            <?php } ?>

            <a href="<?php echo wp_logout_url( $current_url ); ?>" class="wp-sub-accounts-user-switching-logout-link">
                <?php echo apply_filters( 'wp_sub_accounts_user_switching_logout_text', __( 'Logout', 'wp-sub-accounts' ) ); ?>
            </a>
        </div>
    </form>
    <?php
}
else
{
    ?>
    <div class="user-id wp-sub-accounts-user-label">
        Currently logged in as: <?php echo $user->user_login; ?>
    </div>
    <?php
}
?>
