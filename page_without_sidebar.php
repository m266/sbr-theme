<?php
/*
Template Name: Ohne Sidebar
*/
?>
<?php
/**
 * Description:  The template for displaying without sidebar
 * Author:       Hans M. Herbrand
 * Author URI:   http://www.web266.de
 * Version:      1.0
 * License:      GNU General Public License v2 or later
 * License URI:  http://www.gnu.org/licenses/gpl-2.0.html
 * @package WordPress
 * @subpackage Twenty_Sixteen
 * @since Twenty Sixteen 1.0
 */

get_header(); ?>

<div id="primary" class="content-area">
	<main id="main" class="site-main" role="main">
		<?php
		// Start the loop.
		while ( have_posts() ) : the_post();

			// Include the page content template.
			get_template_part( 'template-parts/content', 'page' );

			// If comments are open or we have at least one comment, load up the comment template.
			if ( comments_open() || get_comments_number() ) {
				comments_template();
			}

			// End of the loop.
		endwhile;
		?>
        
	</main><!-- .site-main -->

	<?php get_sidebar( 'content-bottom' ); ?>

</div><!-- .content-area -->

<?php get_sidebar(); ?>
<?php get_footer(); ?>