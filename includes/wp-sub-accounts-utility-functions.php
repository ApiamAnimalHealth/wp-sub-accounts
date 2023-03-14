<?php


if ( ! function_exists('pr') ) {

    function pr( $thing, $return = false ) {

        $output = '';
        $output .= "<pre>";
        $output .= print_r( $thing, true );
        $output .= "</pre>";

        if ( ! $return ) {
            echo $output;
        } else {
            return $output;
        }

    }

}


if ( ! function_exists('logit') ) {

    function logit( $thing ) {

        if ( true === WP_DEBUG ) {
            if ( is_array( $thing ) || is_object( $thing ) ) {
                error_log( print_r( $thing, true ) );
            } else {
                error_log( $thing );
            }
        }

    }

}


if ( ! function_exists('render_backtrace') ) {

    function render_backtrace( $backtrace, $print = false ) {

        $output = '';
        if ( ! empty( $backtrace ) ) {

            foreach( $backtrace as $item ) {
                $output .= $item['function'] . '<br>' . "\n";
            }

        }
        if ( $print  ) {
            echo $output;
        }

        return $output;

    }

}

if ( ! function_exists('send_admin_notification') ) {

	function send_admin_notification( $subject, $msg, $to = false ) {

		$subject = '['. get_option('blogname') . '] - ' . $subject;

		if ( empty( $to ) ) {
			$to = get_option('admin_email');
		}

		wp_mail($to, $subject, $msg );

	}

}




/**
 * Define a constant if it is not already defined.
 *
 * @since 3.0.0
 * @param string $name  Constant name.
 * @param mixed  $value Value.
 */
if ( ! function_exists('maybe_define_constant') ) {

    function maybe_define_constant( $name, $value ) {
        if ( ! defined( $name ) ) {
            define( $name, $value );
        }
    }

}

