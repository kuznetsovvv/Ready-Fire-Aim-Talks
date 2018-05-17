<?php
/**
 * Template Name: Home Page
 *
 * This is the template that is used for the home page.
 *
 * @link https://codex.wordpress.org/Template_Hierarchy
 *
 * @package AEresources
 */

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
?>/home.css?v=<?php
    echo +filemtime(get_stylesheet_directory() . "/home.css");?>">
<style>
</style>
<!--<div class="container">
    <div class="homewidthlimit">
        <div id="primary" class="content-area">
            <div class="homecolumn">
                <main id="main" class="site-main" role="main">
                    <div id="padarea"> -->
                        <?php $query = new WP_Query( 'p=122' ); ?>  
                        <?php if ( $query->have_posts() ) : while ( $query->have_posts() ) : $query->the_post(); 

                            get_template_part( 'template-parts/content', 'vidja' );
                            

                                endwhile; // End of the loop.
                                endif;//end conditional
                        ?>
                    <!--/div>
                </main> #main 
            </div>
        <!--</div><!-- #primary 
        <div class="clearfix"> &nbsp; </div>
    </div><!-- #widthlimit 
</div><!-- #container --><?php

get_sidebar();
get_footer();




