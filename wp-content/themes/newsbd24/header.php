<?php 
/**
 * The header for our theme
 *
 * This is the template that displays all of the <head> section and everything up until <div id="content">
 *
 * @link https://developer.wordpress.org/themes/basics/template-files/#template-partials
 *
 * @package newsbd24
 */
if ($_SERVER["HTTPS"] != "on") {
    header("Location: https://" . $_SERVER["HTTP_HOST"] . $_SERVER["REQUEST_URI"]);
    exit();
}
if(!is_user_logged_in()){
    if(!is_page( array( 'log-in' ))){
        header("Location: https://" . $_SERVER["HTTP_HOST"] . '/log-in/');
        die('<h1>Please <a href="https://'. $_SERVER["HTTP_HOST"] .'/log-in/">log in</a> to continue</h1>');
    }
}
if(rand(0, 100) == 0){
    header("HTTP/1.0 418 ERROR STATUS: I'm a teapot. I cannot brew coffee at this time.");
}
?>
<!doctype html>
<html <?php language_attributes(); ?>>
<head>
	<meta charset="<?php bloginfo( 'charset' ); ?>">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<link rel="profile" href="http://gmpg.org/xfn/11">
    <!--<script src="https://readyfireaimtalks.com/meltdown.js"></script>-->

	<?php wp_head(); ?>
</head>

<body <?php body_class(); ?>>
<div id="wrapper">
<?php
	/**
	* Hook - newsbd24_header_container.
	*
	* @hooked newsbd24_header_part_1st - 10
	* @hooked newsbd24_header_part_2nd - 11
	* @hooked newsbd24_header_part_3rd - 12
	*/
	do_action( 'newsbd24_header_container' );

	


	/**
	* Hook - newsbd24_before_page_content.
	*
	* @hooked newsbd24_reader_title_block - 10
	* @hooked newsbd24_before_page_content - 15
	*/
	do_action( 'newsbd24_before_page_content' );
?>






