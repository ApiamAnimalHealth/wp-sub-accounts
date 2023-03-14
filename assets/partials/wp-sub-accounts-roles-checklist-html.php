<?php
/**
 * Output the roles checklist.
 *
 * @var $roles      array All WordPress roles in name => label pairs.
 * @var $user_roles array An array of role names belonging to the current user.
 */
?><h3>Permissions</h3>
<table class="form-table">
	<tr>
		<th>Roles</th>
		<td>
			<?php foreach ( $roles as $name => $label ) : ?>
				<label for="wp-sub-accounts-multiple-roles-<?php echo esc_attr( $name ); ?>">
					<input
						id="wp-sub-accounts-multiple-roles-<?php echo esc_attr( $name ); ?>"
						type="checkbox"
						name="wp_sub_accounts_multi_roles[]"
						value="<?php echo esc_attr( $name ); ?>"
						<?php checked( in_array( $name, $user_roles ) ); ?>
					/>
					<?php echo esc_html( $label ); ?>
				</label>
				<br/>
			<?php endforeach; ?>
		</td>
	</tr>
</table>
<?php
//if ( ! empty( $user_id ) )
//{
//    $user_data = get_userdata( $user_id );
//    if ( is_object( $user_data) )
//    {
//        $current_user_caps = $user_data->allcaps;
//        pr( $current_user_caps );
//
//    }
//}
?>
