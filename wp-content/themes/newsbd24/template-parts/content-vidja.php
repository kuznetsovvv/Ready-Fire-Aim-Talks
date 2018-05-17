<?php
/**
 * Template part for displaying video content in page.php
 *
 * @link https://codex.wordpress.org/Template_Hierarchy
 *
 * @package newsbd24
 */

?>

	<?php
    /**
    * Hook - newsbd24_posts_formats_thumbnail.
    *
    * @hooked newsbd24_posts_formats_thumbnail - 10
    */
    do_action('newsbd24_posts_formats_thumbnail');
        $cont = apply_filters( 'the_content', get_the_content() );
            $cont = str_replace( ']]>', ']]&gt;', $cont );
            //$pp = strpos($cont, "wistia_responsive_padding");
            //var_dump($pp);
        $doc = new DOMDocument();
        $doc->loadHTML($cont);
        // find wistia div
        $xp = new DOMXPath($doc);
        $wvid = $xp->query('//div[@class="wistia_responsive_padding"]');
        $wvid = $wvid->item(0);
        // create new tags
       /* $diva = $doc->createElement("br", ''); 
        $divb = $doc->createElement("h1", get_the_title()); 
        $divc = $doc->createElement("h3", get_the_author()); 
        // insert created element after $wvid
        $wvid->parentNode->insertBefore($divc, $wvid->nextSibling); 
        $wvid->parentNode->insertBefore($divb, $wvid->nextSibling); 
        $wvid->parentNode->insertBefore($diva, $wvid->nextSibling); //*/
        $wistiabox = $doc->saveHTML($wvid);
        $wscript = $xp->query('//p[ ./script]');
        $wscript = $wscript->item(0);
        $wistiascript = $doc->saveHTML($wscript);
        $pnode = $wvid->parentNode;
        $pnode->removeChild($wvid);
        $pnode = $wscript->parentNode;
        $pnode->removeChild($wscript);
       
        $desc = $doc->saveHTML();//*/
        
    ?>
	
    	<div class="contcol col-sm-12 col-md-4">
            <?php do_action( 'newsbd24_site_branding' ); ?>
            <h1><?php echo get_the_title()?></h1>
            <h4><?php echo get_the_author()?></h4>
            <div class="bio"><strong>Video Description</strong><br><br><?php echo($desc); ?>
            </div>
            <div class="bio"><strong>About <?php echo get_the_author();?></strong><br><br><?php
                echo(get_avatar(get_the_author_meta('ID')));
                echo("<br>");
                echo(get_the_author_meta('description'));
                // only display views if there's enough of them
                $uviews = get_post_meta(get_the_ID())[unique_viewcount][0];
                if($uviews>50){
                    echo('<p>Views: '.$uviews.'</p>');
                }//*/
            ?></div>
        </div>
    	<div class=" col-sm-12 col-md-8">
            <br><br><br>
            <br><br><br>
		<?php
            
        //var_dump($doc);
        //var_dump($cont);
			//the_content();
            echo $wistiabox;
            echo $wistiascript;
        ?></div>
        <br><hr><br>
                        
        
                        <?php

			wp_link_pages( array(
				'before' => '<div class="page-links">' . esc_html__( 'Pages:', 'newsbd24' ),
				'after'  => '</div>',
			) );
		?>
<hr>
	<?php if ( get_edit_post_link() ) : ?>
		<footer class="entry-footer">
			<?php
				edit_post_link(
					sprintf(
						wp_kses(
							/* translators: %s: Name of current post. Only visible to screen readers */
							__( 'Edit <span class="screen-reader-text">%s</span>', 'newsbd24' ),
							array(
								'span' => array(
									'class' => array(),
								),
							)
						),
						get_the_title()
					),
					'<span class="edit-link">',
					'</span>'
				);
			?>
		</footer><!-- .entry-footer -->
	<?php endif; ?>
