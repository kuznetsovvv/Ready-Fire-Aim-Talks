<?php
/**
 * Template Name: Log In Page
 *
 * This is the template that is used for the login page.
 *
 * @link https://codex.wordpress.org/Template_Hierarchy
 *
 * @package AEresources
 */

if (is_user_logged_in() && !current_user_can('administrator')) {
    header("Location: https://" . $_SERVER["HTTP_HOST"] );
}
get_header();

    if(!isset($_COOKIE["wordpress_test_cookie"])){
        //setcookie("wordpress_test_cookie", 'WP_Cookie_check' );
    ?><script type="text/javascript">
                var date = new Date();
                date.setTime(date.getTime()+(3600000000));
        document.cookie = "wordpress_test_cookie=WP_Cookie_check; expires="+date.toGMTString()+"; path=/";
    </script><?php
    }
?>
<link rel="stylesheet" href="<?php
    echo get_stylesheet_directory_uri();
?>/login.css?v=<?php
    echo +filemtime(get_stylesheet_directory() . "/login.css");?>">
<style>
    
#logout, #searchbutton, #homebutton, #dropdownbutton, #changepass{
    display:none !important;
}
#greybar, #tocbooklimit, #copyrightStatement{
    display:none !important;
}
<?php 
    
if(!($_GET["action"] === null)){
    if(($_GET["action"] == "lostpassword")){
        ?>
        .login-column{
            display:none;
        }
        .register-column{
            display:none;
        }
        .recover-column{
            display:block;
        }
        .reset-column{
            display:none;
        }
        <?php
    }elseif(($_GET["action"] == "register")){
        ?>
        .login-column{
            display:none;
        }
        .register-column{
            display:block;
        }
        .recover-column{
            display:none;
        }
        .reset-column{
            display:none;
        }
        <?php
    }elseif(($_GET["action"] == "reset")){
        ?>
        .login-column{
            display:none;
        }
        .register-column{
            display:none;
        }
        .recover-column{
            display:none;
        }
        .reset-column{
            display:block;
        }
        <?php
    }else{
        ?>
        .login-column{
            display:block;
        }
        .register-column{
            display:none;
        }
        .recover-column{
            display:none;
        }
        .reset-column{
            display:none;
        }
        <?php
    }
}else{
    ?>
    .login-column{
        display:block;
    }
    .register-column{
        display:none;
    }
    .recover-column{
        display:none;
    }
    .reset-column{
        display:none;
    }
    <?php
}
?>
</style>
<div class="logincontainer">
    <div class="loginwidthlimit">
        <div id="primary" class="content-area">
            <div class="logincolumn">
                <main id="main" class="site-main" role="main">
                    <div id="padarea">
                        <?php
                        while ( have_posts() ) : the_post();

                            get_template_part( 'template-parts/content', 'login' );


                        endwhile; // End of the loop.

                        ?>
                    </div>
                </main><!-- #main -->
            </div>
        </div><!-- #primary -->
        <div class="clearfix"> &nbsp; </div>
    </div><!-- #widthlimit -->
</div><!-- #container -->
<div class="loginmodal" id="created" onclick="closemodal()">
    <div class="closeright">✕</div>
    <p style="width: 100%; text-align: center;"><strong>Success</strong></p>
    <p>Account created successfully, you should receive your activation email within five minutes.</p>
    <p>If you do not recieve the email, double check your 'spam' or 'junk' mailbox.</p>
</div>
<div class="loginmodal" id="reset" onclick="closemodal()">
    <div class="closeright">✕</div>
    <p style="width: 100%; text-align: center;"><strong>Success</strong></p>
    <p>An email with your password reset link will be sent, you should receive your link within five minutes.</p>
</div>
<div class="loginmodal" id="set" onclick="closemodal()">
    <div class="closeright">✕</div>
    <p style="width: 100%; text-align: center;"><strong>Success</strong></p>
    <p>Your password has been set, you will be automatically logged on in five seconds.</p>
    <p>If you are not automatically logged in, simply close this popup and log in manually.</p>
</div>
<div class="loginmodal" id="logfail" onclick="closemodal()">
    <div class="closeright">✕</div>
    <p><strong>Error</strong></p>
    <?php
    if(!($_GET["errr"] === null)){
        if(!($_GET["errr"] == "both_empty")){
            $errormessage = $_GET["errr"];
            $errArr = explode("/n", $errormessage);
            $errormessage = "";
            foreach($errArr as $errarrorr){
                if(strlen($errarrorr)>1){
                    $errormessage = $errormessage.htmlspecialchars(ucfirst($errarrorr)).".</p><p style='color:red;'>";
                }
            }
            $errormessage = str_replace("_", " ", $errormessage);
            $errormessage = str_replace("Form empty", "It appears the password reset form was submitted while empty", $errormessage);
            $errormessage = str_replace("Username exists", "An account with that email address already exists. </p><p>If you do not have your activation email, check you 'spam' or 'junk' mail box.</p><p> If you've lost access, you can <a href='https://agorasbigblackbook.com/log-in/?action=lostpassword'>reset your password</a>", $errormessage);
            $errormessage = str_replace("Email dne", "Password could not be reset.</p><p style='color:red;'> An account with that email does not exist. </p><p> Double check your spelling and try again. </p><p> If you do not yet have an account, please <a href='https://agorasbigblackbook.com/log-in/?action=register'>create one here</a>", $errormessage);
            $errormessage = str_replace("Incorrect password", "Incorrect Email or Password. </p><p>Please check your spelling and try again. </p><p>If this error persists, you can <a href='https://agorasbigblackbook.com/log-in/?action=lostpassword'>reset your password</a>", $errormessage);
            $errormessage = str_replace("Pw too short", "Please select a password longer than 5 characters.", $errormessage);
            $errormessage = str_replace("Not whitelisted", "This email was not found in the Agora database.</p><p style='color:red;'>Please double-check that you have entered your email correctly. </p><p>If the problem persists, or you would like to add a personal email, please contact us at <a href='mailto:help@agorasbigblackbook.com'>help@agorasbigblackbook.com</a>", $errormessage);
            $errormessage = str_replace("Test cookie", "An internal error occured, please try again.", $errormessage);
            echo "<p style='color:red;'>";
            echo $errormessage;
            echo "</p>";
        }
    }
    ?>
    <p>This error has been logged.</p>
</div>
<div class="loginmodaloverlay" onclick="closemodal()"></div>
<script type="text/javascript">
function openmodal(id){
    jQuery(".loginmodal").css("display", "none");
    jQuery(".loginmodaloverlay").css("display", "block");
    jQuery("#"+id).css("display", "block");
}
function closemodal(){
    if(<?php echo ($_GET["action"] == "reset")?"true":"false"; ?>){ 
       window.history.back();
    }
    jQuery(".loginmodal").slideUp(250);
    jQuery(".loginmodaloverlay").slideUp(250);  
}
function align(){
    if(!(jQuery(window).width() < 800)){
        jQuery("#main").parent().animate({paddingTop: ((jQuery("#loginimg").height()-jQuery("#main").height())/2)+"px"},450);
    }
}
jQuery( window ).resize(function() {
    align();
});
jQuery( window ).on('load', function(){
    align();
    jQuery("input").on('keyup', function (e) {
        if(!(jQuery(this).parents('form:first').attr("id") == "resetpassform")){
            document.cookie = "useremail="+jQuery(this).parents('form:first').find("input:first").val();
        }
        if (e.keyCode == 13) {
            if(!(jQuery(this).parents('form:first').attr("id") == "resetpassform")){
                document.cookie = "useremail="+jQuery(this).parents('form:first').find("input:first").val();
            }
            if(<?php echo ($_GET["action"] === null)?"false":"true" ?>){
            if(!(  (jQuery(this).parents('form:first').attr("id") == "resetpassform"))){
                jQuery(this).parents('form:first').submit();
            }else{
                fielda = jQuery(this).parents('form:first').find("input:first").val();
                fieldb = jQuery(this).parents('form:first').find("input:eq(1)").val();
              
                var errors = "";
                if(fielda.length < 5 || fieldb.length < 5 ){
                    errors = errors+"/nplease_fill_out_both_fields";
                }
                if(jQuery(this).parents('form:first').attr("id") == "registerform"){
                    if(!(fielda.indexOf("@") >= 0 && fieldb.indexOf("@") >= 0)){
                        errors = errors+"/nenter_a_valid_email_address";
                    }
                }else{
                    if(fielda.length < 5 || fieldb.length < 5 ){
                        errors = errors+"/npw_too_short";
                    }
                }
                if(fielda != fieldb){
                    errors = errors+"/nboth_fields_must_match";   
                }
                if(errors.length == 0){
                    jQuery(this).parents('form:first').submit();     
                }else{
                    window.location.href = "<?php echo site_url(); ?>/log-in/?action=<?php echo $_GET["action"] ?>&errr="+errors;   
                }  
            }
        }else{
            if(!(jQuery(this).parents('form:first').attr("id") == "resetpassform")){
                document.cookie = "useremail="+jQuery(this).parents('form:first').find("input:first").val();
            }else{
                var date = new Date();
                date.setTime(date.getTime()+(30*1000));
                document.cookie = "upw="+jQuery(this).parents('form:first').find("input:first").val()+"; expires="+date.toGMTString();
            }
            jQuery(this).parents('form:first').submit();                                   
         }
        }
    });
    jQuery(".loginbutton").click(function(){
        if(!(jQuery(this).parents('form:first').attr("id") == "resetpassform")){
            document.cookie = "useremail="+jQuery(this).parents('form:first').find("input:first").val();
        }else{
            var date = new Date();
            date.setTime(date.getTime()+(30*1000));
            document.cookie = "upw="+jQuery(this).parents('form:first').find("input:first").val()+"; expires="+date.toGMTString();
        }
		if((jQuery(this).parents('form:first').attr("id") == "registerform")){
			jQuery(this).parents('form:first').find("input:eq(1)").val(jQuery(this).parents('form:first').find("input:first").val());
		}
        if(<?php echo ($_GET["action"] === null)?"false":"true" ?>){
            if(!(  (jQuery(this).parents('form:first').attr("id") == "resetpassform"))){
                jQuery(this).parents('form:first').submit();
            }else{
                fielda = jQuery(this).parents('form:first').find("input:first").val();
                fieldb = jQuery(this).parents('form:first').find("input:eq(1)").val();
              
                var errors = "";
                if(fielda.length < 5 || fieldb.length < 5 ){
                    errors = errors+"/nplease_fill_out_both_fields";
                }
                if(jQuery(this).parents('form:first').attr("id") == "registerform"){
                    if(!(fielda.indexOf("@") >= 0 && fieldb.indexOf("@") >= 0)){
                        errors = errors+"/nenter_a_valid_email_address";
                    }
                }else{
                    if(fielda.length < 5 || fieldb.length < 5 ){
                        errors = errors+"/npw_too_short";
                    }
                }
                if(fielda != fieldb){
                    errors = errors+"/nboth_fields_must_match";   
                }
                if(errors.length == 0){
                    jQuery(this).parents('form:first').submit();     
                }else{
                    window.location.href = "<?php echo site_url(); ?>/log-in/?action=<?php echo $_GET["action"] ?>&errr="+errors;   
                }  
            }
        }else{
            jQuery(this).parents('form:first').submit();                                   
        }
    });
});
</script><?php
if(isset($_COOKIE['useremail'])) {
    echo "<script type='text/javascript'>jQuery('form:not(#resetpassform)').find('input:first').attr('value','".$_COOKIE['useremail']."');</script>";
}
if(isset($_COOKIE['upw'])&&($_GET["success"] == "1")&&($_GET["did"] == "reset")) {
    echo "<script type='text/javascript'>jQuery('form:not(#resetpassform)').find('input:eq(1)').attr('value','".$_COOKIE['upw']."');</script>";
    if(isset($_COOKIE['useremail'])) { ?>
    <script type="text/javascript">
        var autologintimeout = setTimeout(function(){
            jQuery('#loginform').submit();
        },5000);
    </script><?php
    }
}
if(!($_GET["action"] === null)&& !($_GET["success"] === null)){
    if(($_GET["success"] == "1")){
        if(($_GET["action"] == "register")){
            echo '<script type="text/javascript">openmodal("created");</script>';
        }elseif(($_GET["action"] == "recover")){
            echo '<script type="text/javascript">openmodal("reset");</script>';
        }elseif(($_GET["action"] == "login")){
            echo '<script type="text/javascript">openmodal("set");</script>';
        }
    }
}
if(isset($_GET["errr"])){
    if(!($_GET["errr"] == "both_empty")){
        echo '<script type="text/javascript">openmodal("logfail");</script>';
    }
}
if(isset($_GET["key"])&&isset($_GET["login"])){
    $value = rawurlencode(sprintf( '%s:%s', wp_unslash( $_GET['login'] ), wp_unslash( $_GET['key'] ) ));
	$rp_cookie = 'wp-resetpass-' . COOKIEHASH;
    //setcookie( $rp_cookie, $value);  // php set cookie being whiny about headers being already sent
    echo('<script type="text/javascript">document.cookie = "'.$rp_cookie.'='.$value.';path=/"; jQuery("#resetpassform #rp_key").attr("value","'.$_GET["key"].'");jQuery("#resetpassform #user_login").attr("value","'.$_GET["login"].'");');

	echo "</script>";
}

get_sidebar();
get_footer();




