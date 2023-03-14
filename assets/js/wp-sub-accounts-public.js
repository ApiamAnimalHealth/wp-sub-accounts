(function( $ ) {
	'use strict';

    $(document).ready(
        function(){
            var current_user_id = $('#wp-sub-accounts-current-user-id').val();
            $('#wp-sub-accounts-user-switcher').val(current_user_id);
            $('.wp-sub-accounts-user-switching-drawer').slideUp();
        }
    );

    $(document).on(
        'change',
        '#wp-sub-accounts-user-switcher',
        function(){
            var current_user_id = $('#wp-sub-accounts-current-user-id').val();
            if ( $(this).val() !== current_user_id )
            {
                var the_form = $(this).closest('form');
                the_form.submit();
            }
        }
    );

    $(document).on(
        'click',
        '.wp-sub-accounts-user-switching-drawer-toggle',
        function() {
            var parentForm = $(this).closest('form');
            parentForm.find('.wp-sub-accounts-user-switching-drawer').slideToggle(
                300,
                function(){
                    parentForm.toggleClass('drawer-active');
                }
            );
        }
    )

})( jQuery );
