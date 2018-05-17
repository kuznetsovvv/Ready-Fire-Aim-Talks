<?php
/**
 * Implement the theme Core function
 */
//redirect users to my login page
add_filter('login_url', 'your_login_url', 10, 2 );
//a few possibilities of login form actions  'postpass', 'logout', 'lostpassword', 'retrievepassword', 'resetpass', 'rp', 'register', 'login' 
add_action('login_form_login', 'your_login_page');
add_action('login_form_register', 'your_register_page');
add_action('login_form_logout', 'your_login_page');
add_action('wp_login', 'your_login_redirect', 10, 2);
//add_action('lost_password', 'your_reset_page');                                             //UNCOMMENT THIS ONE ITS FOR DEBUG
// assuming that your new front end login url is "/login", use these:
function your_login_url($login_url, $redirect) {
    return home_url('');
}
function your_login_page() {
    wp_redirect(home_url('/log-in/'), 302);
}
function your_register_page() {
    wp_redirect(home_url('/log-in/?action=register'), 302);
}
function your_reset_page() {
    wp_redirect(home_url('/log-in/?action=lostpassword'), 302);
}
//catch login redirects, pass along errors
add_filter('login_redirect', 'my_login_redirect', 10, 3);
function my_login_redirect($redirect_to, $requested_redirect_to, $user) {
    if (is_wp_error($user)) {
        //Login failed, find out why...
        $error_types = array_keys($user->errors);
        //Error type seems to be empty if none of the fields are filled out
        $error_type = 'both_empty';
        //Otherwise just get the first error (as far as I know there
        //will only ever be one)
        if (is_array($error_types) && !empty($error_types)) {
            $error_type = $error_types[0];
        }
        wp_redirect( home_url('/log-in/') . "?errr=" . $error_type ."&failed=1"); 
        exit;
    } else {
        //Login OK - redirect to another page?
        if  (current_user_can('activate_plugins')) {
            wp_redirect('/wp-admin/index.php', 302);
        }else{
            wp_redirect(home_url(''), 302);
        }
    }
}
// if admin send them to the dashboard, otherwise leave them on the frontend
function your_login_redirect($user_login, $user) {
    if  (current_user_can('activate_plugins')) {
        wp_redirect('/wp-admin/index.php', 302);
    }else{
        wp_redirect(home_url(''), 302);
    }
}

//Pass reset errors
add_action('lostpassword_post', 'validate_reset', 99, 3);
function validate_reset(){
    if(isset($_POST['user_login']) && !empty($_POST['user_login'])){
        $email_address = $_POST['user_login'];
        if(filter_var( $email_address, FILTER_VALIDATE_EMAIL )){
            if(!email_exists( $email_address )){
                wp_redirect( 'log-in/?errr=email_dne' );
                exit;
            }
        }else{
                $username = $_POST['user_login'];
                if ( !username_exists( $username ) ){
                wp_redirect( 'log-in/?errr=email_dne' );
                    exit;
                }
            } 

    }else{
        wp_redirect( 'log-in/?errr=form_empty' );
        exit;   
    }
}
//clean up html emails going out
add_filter( 'wp_mail', 'my_wp_mail_filter' );
function my_wp_mail_filter( $args ) {
	
	$new_wp_mail = array(
		'to'          => $args['to'],
		'subject'     => $args['subject'],
		'message'     => $args['message'],
		'headers'     => $args['headers'],
		'attachments' => $args['attachments'],
	);
	if((strpos($new_wp_mail['message'], '</table>') !== false) && (strpos($new_wp_mail['headers'], 'text/html') === false)){//check for '<table style="' and 'Content-type: text/html'...
        $new_wp_mail['headers'] = "Content-type: text/html".$new_wp_mail['headers'];
    }
	return $new_wp_mail;
}
//clean up password reset message
add_filter('retrieve_password_message','custom_retrieve_password_message',10,2);
function custom_retrieve_password_message($message, $key){
    $userstartpos = strpos($message, "&login=") + 7;
    $userendpos = strrpos($message, ">");
    $userloginforreset = substr($message, $userstartpos, $userendpos - $userstartpos);
    return '<html><table style="background:#cccccc; background-color:#cccccc;" border="0" cellpadding="0" cellspacing="0" height="100%" width="100%" id="bodyTable">
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
                                        <td align="center" valign="top"><h1 style="font-family: Goudy Old Style, Garamond, Big Caslon, Times New Roman, serif;">'."Agora's Ready Fire Aim Talks".'</h1></td>
                                    </tr>
                                </table>
                            </td>
                        </tr>
                        <tr style="background: #ffffff; background-color: #ffffff">
                            <td align="center" valign="top">
                                <table border="0" cellpadding="20" cellspacing="0" width="100%" id="emailBody">
                                    <tr style="width: 420px;">
                                        <td align="center" valign="top">
                                        <h2 style="font-family: Arial, Helvetica, sans-serif;">Reset Your Password.</h2>
                                        <p style="font-family: Arial, Helvetica, sans-serif;">Click the link below<br>to set your new password.</p>
                                        <a width="422" href="'.network_site_url("log-in/?action=reset&key=$key&login=". $userloginforreset, 'login').'" bgcolor="#fe7030" width="420" style=" width: 420px; background:#fe7030; background-color:#fe7030; text-decoration:none; color:#ffffff; font-size: 20px; border: none;"><span bgcolor="#fe7030" style="background:#fe7030; background-color:#fe7030; padding: 0; margin: 0; font-family: Arial, Helvetica, sans-serif; text-decoration:none; display: block; width: 420px;"> &nbsp; Click to Reset Your Password &nbsp; </span></a>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td align="center" valign="top">
                                            <p>'."<small style='font-family: Arial, Helvetica, sans-serif;'>If you received this email by mistake, simply delete it. Nothing further will happen if you don't click the link above.".'</small></p>
                                            <p><small style="font-family: Arial, Helvetica, sans-serif;">For any questions, please contact <a href="mailto:help@readyfireaimtalks.com">help@readyfireaimtalks.com</a></small></p>
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
}//*/
// Returns only posts for Searches
function SearchFilter($query) {
if ($query->is_search) {
$query->set('post_type', 'post');
}
return $query;
}
add_filter('pre_get_posts','SearchFilter');

// only allow user registration in wordpress from specific domain, we'll modify this to whitelist only.
function wpcs_disable_email_domain ( $errors, $sanitized_user_login, $user_email ) {
    if((strlen($sanitized_user_login)<1)&&(strlen($user_email)<6)){
        $errors->add( 'both_fields_empty', __( 'You must input username and email to register', 'my_domain' ) );
    }    
    list( $email_user, $email_domain ) = explode( '@', $user_email );
    $allowedDomains = openlist("domains");
    if ( !in_array(strtoupper($email_domain), $allowedDomains ) ) {//if(!in_array($_SERVER['REMOTE_ADDR'], $allowed)){
        $emailWhitelist = openlist("emails");
        if ( !in_array( strtoupper($user_email), $emailWhitelist ) ) {
            $errors->add( 'not_whitelisted', __( 'Email and domain not found in whitelist, please contact us directly to receive an account.', 'my_domain' ) );
        }
    }
    if (is_wp_error($errors)) {
        //Login failed, find out why...
        $error_types = array_keys($errors->errors);
        //Error type seems to be empty if none of the fields are filled out
        $error_type = "";
        //Otherwise just get the first error (as far as I know there
        //will only ever be one)
        if (is_array($error_types) && !empty($error_types)) {
            $error_type =  $error_type."/n".$error_types[count($error_types) -1];
        }
        wp_redirect( home_url('/log-in/') . "?action=register&errr=" . str_replace(" ", "_",$error_type)."&failed=1" ); 
        return $errors;   
    }
    return $errors;
}
add_filter( 'registration_errors', 'wpcs_disable_email_domain', 10, 3 );
//Function to get lists for above operations
function openlist($name){
    if (($handle = fopen(ABSPATH."/htnoaccess/".$name.".csv", "r")) !== FALSE) {
        $outArray = [];
        while (($data = fgetcsv($handle, 420000, ",")) !== FALSE) {
            $num = count($data);
            for ($c=0; $c < $num; $c++) {
                array_push($outArray, strtoupper($data[$c]));
            }
        }
        fclose($handle);
        return($outArray);
    }
    return false;
}
//PASS NEW PASSWORD ERRORS
function new_pass_errs ( $errors, $user ) {
    if (is_wp_error($errors)) {
        //Login failed, find out why...
        $error_types = array_keys($errors->errors);
        //Error type seems to be empty if none of the fields are filled out
        $error_type = "";
        //Otherwise just get the first error (as far as I know there
        //will only ever be one)
        if (is_array($error_types) && !empty($error_types)) {
            $error_type =  $error_type."/n".$error_types[count($error_types) -1];
        }
        if (!empty($error_type) && strlen($errortype) > 1) {
            wp_redirect( home_url('/log-in/') . "?action=reset&errr=" . str_replace(" ", "_",$error_type)."&failed=1" ); 
        }else{
            wp_redirect( home_url('/log-in/') . "?action=login&success=1&did=reset" ); 
        }
        return $errors;   
    }
    wp_redirect( home_url('/404/') ); 
    return $errors;
}
add_filter( 'validate_password_reset', 'new_pass_errs', 10, 3 );

require get_template_directory() . '/inc/core.php';

/**
 * Implement News Bd 24 Custom HOOK
 */
require get_template_directory() . '/inc/theme-hooks.php';


/**
 * Custom template tags for this theme.
 */
require get_template_directory() . '/inc/template-tags.php';

/**
 * Custom template tags for this theme.
 */
require get_template_directory() . '/inc/wp_bootstrap_navwalker.php';

/**
* Custom posts hooks
*/
require get_template_directory() . '/inc/post_hooks.php';

/**
* Custom Theme Function
*/
require get_template_directory() . '/inc/template-functions.php';

/**
* Custom Theme Function
*/
require get_template_directory() . '/inc/common_hook.php';


/**
* Custom Theme Function
*/
require get_template_directory() . '/inc/customizer/customizer.php';

/**
* Load All Filter Hook
*/
require get_template_directory() . '/inc/filter_hook.php';

/**
* Load All Filter Hook
*/
require get_template_directory() . '/inc/pro/newsbd24-admin-page.php';

/**
* Load All Filter Hook
*/
require get_template_directory() . '/inc/tgm/plugins.php';




