<?php
/**
 * The Template for displaying all single posts.
 *
 * @package iPanelThemes Knowledgebase
 */

get_header(); ?>
	<?php get_sidebar(); ?>
	<div id="primary" class="content-area col-md-8 col-md-pull-4">
		<main id="main" class="site-main" role="main">

		<?php while ( have_posts() ) : the_post(); ?>

			<?php get_template_part( 'content', 'single' ); ?>

			<?php ipt_kb_like_article(); ?>

			<?php ipt_kb_content_nav( 'nav-below' ); ?>

			<?php
				// If comments are open or we have at least one comment, load up the comment template
				if ( comments_open() || '0' != get_comments_number() )
					comments_template();
			?>

		<?php endwhile; // end of the loop. ?>

		</main><!-- #main -->
	</div><!-- #primary -->


<?php get_footer(); ?>