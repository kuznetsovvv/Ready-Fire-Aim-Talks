<?php
/**
 * The template for displaying all single posts
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/#single-post
 *
 * @package newsbd24
 */

get_header(); ?>

<?php
/**
* Hook - newsbd24_blog_content_wrapper_before.
*
* @hooked newsbd24_left_sidebar - 11
* @hooked newsbd24_blog_content_wrapper_before - 12
*/
do_action( 'newsbd24_blog_content_wrapper_before' );
$theuser = wp_get_current_user();
?>

		<?php
		while ( have_posts() ) : the_post();
            $pid = get_the_ID();
            $viewedby = get_post_meta($pid, "viewed_by")[0];
            $uniqueviews = get_post_meta($pid, "unique_viewcount")[0];
			get_template_part( 'template-parts/single/content', get_post_type() );

            //Track Unique Pageviews
            //add fields to do so
            add_post_meta($pid, "viewed_by", ",", true);
            add_post_meta($pid, "unique_viewcount", 0, true);
            //if user hasn't viewed it before. Commas prevent ID subsrings matching.
            if (strpos($viewedby, ','.$theuser->ID) === false) {
                $viewedby = $viewedby.$theuser->ID.",";
                //log userid
                update_post_meta($pid, "viewed_by", $viewedby);
                //only then count and update unique_viewcount
                update_post_meta($pid, "unique_viewcount", count(explode(",", $viewedby))-2);
            }

			do_action('newsbd24_single_post_navigation');

			// If comments are open or we have at least one comment, load up the comment template.
			if ( comments_open() || get_comments_number() ) :
				comments_template();
			endif;

		endwhile; // End of the loop.
		?>

<?php
/**
* Hook - newsbd24_blog_content_wrapper_after.
*
* @hooked newsbd24_blog_content_wrapper_after - 10
* @hooked newsbd24_sidebar - 11
*/
do_action( 'newsbd24_blog_content_wrapper_after' );
?>
<?php

get_footer();
