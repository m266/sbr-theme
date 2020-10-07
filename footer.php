<?php
/**
 * The template for displaying the footer
 *
 * Contains the closing of the #content div and all content after
 *
 * @package WordPress
 * @subpackage Twenty_Sixteen
 * @since Twenty Sixteen 1.0
 */
?>

		</div><!-- .site-content -->

		<footer id="colophon" class="site-footer" role="contentinfo">

                <!-- RSS-Icon nur anzeigen, wenn Beitraege vorhanden sind -->
                <?php $count_posts = wp_count_posts()->publish;
                if (!$count_posts == 0) {
                ?>
			<!-- rss-icon -->
            <div class="rss-icon">
                <a class="rss-link" href="<?php echo esc_url( home_url( '/' ) ); ?>/feed/" title="RSS-Feed"><img src="<?php echo get_stylesheet_directory_uri(); ?>/images/rss-icon.png" alt="RSS-Icon" /></a>
            </div><!-- .rss-icon -->
<?php
}
?>

            <!-- SBR-Footer -->
            <div class="sbr-footer">

	            <?php echo do_shortcode('[h_modified]');  ?>
                <br>
	            <?php /* echo bloginfo('name');?>&nbsp;<?php bloginfo('description');*/ ?>
	            <span class="center"><?php echo do_shortcode("[h_copyright]"); ?></span>


	            <!-- BeW-Logo nur mobile -->
                <div class="bew-logo">
                	<a href="http://www.betreuungswerk.de/" title="Betreuungswerk Post Postbank Telekom" target="_blank"><img src="<?php echo get_stylesheet_directory_uri(); ?>/images/bew-logo.png" alt="Betreuungswerk Post Postbank Telekom" width="238" height="82" /></a>
                </div><!-- .bew-logo -->

            </div>
            <!-- .SBR-Footer -->

            <!-- rss-Symetrieausgleich -->
            <!-- Kein Symetrieausgleich, wenn kein RSS-Icon vorhanden ist -->
            <?php
            if (!$count_posts == 0) {
            ?>
	        <div class="rss-symetrieausgleich"></div><!-- .rss-symetrieausgleich -->
            <?php
            }
            ?>
		</footer><!-- .site-footer -->
	</div><!-- .site-inner -->
</div><!-- .site -->

<?php wp_footer(); ?>

<script type="text/javascript">
	jQuery( document ).ready(function($) {
	$('a[href*="#"]:not([href="#"])').click(function() {
	  if (location.pathname.replace(/^\//, '') == this.pathname.replace(/^\//, '') && location.hostname == this.hostname) {
	    var target = $(this.hash);
	    target = target.length ? target : $('[name=' + this.hash.slice(1) + ']');
	    if (target.length) {
	      $('html, body').animate({
	        scrollTop: target.offset().top
	      }, 1000);
	      return false;
	    }
	  }
	});
});
</script>
</body>
</html>