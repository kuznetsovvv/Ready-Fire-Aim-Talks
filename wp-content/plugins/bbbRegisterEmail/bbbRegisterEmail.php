<?php
/**
 * Plugin Name: bbbRegisterEmail
 * Description: Overwrites the pluggable 'wp_new_user_notification()' plugin to allow the sending of a custom email
 * Author: Vladimir Kuznetsov
 * Version: 1.337
 */

if ( !function_exists('wp_new_user_notification') ) :
/**
 * Pluggable - Email login credentials to a newly-registered user
 *
 * A new user registration notification is also sent to admin email.
 *
 * @since 2.0.0
 *
 * @param int    $user_id        User ID.
 * @param string $plaintext_pass Optional. The user's plaintext password. Default empty.
 */
function wp_new_user_notification( $user_id, $deprecated = null, $notify = '' ) {
    if ( $deprecated !== null ) {
        _deprecated_argument( __FUNCTION__, '4.3.1' );
    }
 
    global $wpdb, $wp_hasher;
    $user = get_userdata( $user_id );
 
    // The blogname option is escaped with esc_html on the way into the database in sanitize_option
    // we want to reverse this for the plain text arena of emails.
    $blogname = wp_specialchars_decode(get_option('blogname'), ENT_QUOTES);
 
    if ( 'user' !== $notify ) {
        $switched_locale = switch_to_locale( get_locale() );
        $message = '<html><table border="0" cellpadding="0" cellspacing="0" height="100%" width="100%" id="bodyTable">
            <tr>
                <td align="center" valign="top">
                    <table border="0" cellpadding="20" cellspacing="0" width="600" id="emailContainer">
                        <tr>
                            <td align="center" valign="top">
                                <table border="0" cellpadding="20" cellspacing="0" width="100%" id="emailHeader">
                                    <tr>
                                        <td align="center" valign="top">'.$blogname.' has had a new user register.</td>
                                    </tr>
                                </table>
                            </td>
                        </tr>
                        <tr>
                            <td align="center" valign="top">
                                <table border="0" cellpadding="20" cellspacing="0" width="100%" id="emailBody">
                                    <tr>
                                        <td align="center" valign="top">'.sprintf( __( 'Email: %s' ), $user->user_email ) .'</td>
                                    </tr>
                                </table>
                            </td>
                        </tr>
                        <tr>
                            <td align="center" valign="top">
                                <table border="0" cellpadding="20" cellspacing="0" width="100%" id="emailFooter">
                                    <tr>
                                        <td align="center" valign="top">
                                            <a href="https://agorasbigblackbook.com/wp-admin/user-edit.php?user_id='.$user->ID.'">Click here to edit their settings.</a>
                                        </td>
                                    </tr>
                                </table>
                            </td>
                        </tr>
                    </table>
                </td>
            </tr>
        </table></html>
        ';
         
        @wp_mail( get_option( 'admin_email' ), sprintf( __( '[%s] New User Registration' ), $blogname ), $message, "Content-type: text/html" );
 
        if ( $switched_locale ) {
            restore_previous_locale();
        }
    }
 
    // `$deprecated was pre-4.3 `$plaintext_pass`. An empty `$plaintext_pass` didn't sent a user notification.
    if ( 'admin' === $notify || ( empty( $deprecated ) && empty( $notify ) ) ) {
        return;
    }
 
    // Generate something random for a password reset key.
    $key = wp_generate_password( 20, false );
 
    /** This action is documented in wp-login.php */
    do_action( 'retrieve_password_key', $user->user_login, $key );
 
    // Now insert the key, hashed, into the DB.
    if ( empty( $wp_hasher ) ) {
        $wp_hasher = new PasswordHash( 8, true );
    }
    $hashed = time() . ':' . $wp_hasher->HashPassword( $key );
    $wpdb->update( $wpdb->users, array( 'user_activation_key' => $hashed ), array( 'user_login' => $user->user_login ) );
 
    $switched_locale = switch_to_locale( get_user_locale( $user ) );
 
    $message = __('To set your password, visit the following address:') . "\r\n\r\n";
    $message .= '<' . network_site_url("wp-login.php?action=rp&key=$key&login=" . rawurlencode($user->user_login), 'login') . ">\r\n\r\n";
  
           $message = '<html><table style="background:#cccccc; background-color:#cccccc;" border="0" cellpadding="0" cellspacing="0" height="100%" width="100%" id="bodyTable">
            <tr>
                <td align="center" valign="top">
                    <table border="0" cellpadding="20" cellspacing="0" width="600" id="emailContainer" style="font-family: Arial, Helvetica, sans-serif;">
                        <tr>
                            <td align="center" valign="top">
                                <table border="0" cellpadding="20" cellspacing="0" width="100%" id="emailHeader">
                                    <tr>
                                        <td align="center" valign="top"><br>&nbsp;<br>&nbsp;<br></td>
                                    </tr>
                                </table>
                            </td>
                        </tr>
                        <tr style="background: #000000; background-color: #000000; color:#ffffff; font-family: Goudy Old Style, Garamond, Big Caslon, Times New Roman, serif;">
                            <td align="center" valign="top">
                                <table border="0" cellpadding="20" cellspacing="0" width="100%" id="emailHeader">
                                    <tr>
                                        <td align="center" valign="top"><h1 style="font-family: Goudy Old Style, Garamond, Big Caslon, Times New Roman, serif;">'.$blogname.'</h1></td>
                                    </tr>
                                </table>
                            </td>
                        </tr>
                        <tr style="background: #ffffff; background-color: #ffffff">
                            <td align="center" valign="top">
                                <table border="0" cellpadding="20" cellspacing="0" width="100%" id="emailBody">
                                    <tr style="width: 420px;">
                                        <td align="center" valign="top">
                                        <h2 style="font-family: Arial, Helvetica, sans-serif;">Almost done.</h2>
                                        <p style="font-family: Arial, Helvetica, sans-serif;">Click the link below<br>to set your password and<br>finish setting up your account.</p>
                                        <a width="422" href="'.network_site_url("log-in/?action=reset&key=$key&login=" . rawurlencode($user->user_login), 'login').'" bgcolor="#fe7030" width="420" style=" width: 420px; background:#fe7030; background-color:#fe7030; text-decoration:none; color:#ffffff; font-size: 20px; border: none;"><span bgcolor="#fe7030" style="background:#fe7030; background-color:#fe7030; padding: 0; margin: 0; font-family: Arial, Helvetica, sans-serif; text-decoration:none; display: block; width: 420px;"> &nbsp; Click to Set Your Password &nbsp; </span></a>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td align="center" valign="top">
                                            <p>'."<small style='font-family: Arial, Helvetica, sans-serif;'>If you received this email by mistake, simply delete it. Nothing further will happen if you don't click the link above.".'</small></p>
                                            <p><small style="font-family: Arial, Helvetica, sans-serif;">For any questions, please contact <a href="mailto:help@agorasbigblackbook.com">help@agorasbigblackbook.com</a></small></p>
                                        </td>
                                    </tr>
                                </table>
                            </td>
                        </tr>
                        <tr>
                            <td align="center" valign="top">
                                <table border="0" cellpadding="20" cellspacing="0" width="100%" id="emailHeader">
                                    <tr>
                                        <td align="center" valign="top"><br>&nbsp;<br>&nbsp;<br>&nbsp;<br>&nbsp;<br></td>
                                    </tr>
                                </table>
                            </td>
                        </tr>
                    </table>
                </td>
            </tr>
        </table></html>
        ';
         
    wp_mail($user->user_email, sprintf(__('[%s] Finalize Your Account'), $blogname), $message);
    
    if ( $switched_locale ) {
        restore_previous_locale();
    }
}
endif;

?>