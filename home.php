<?php
/**
 * Dynamic Homepage
 *
 * This is the most generic template file in a WordPress theme
 * and one of the two required files for a theme (the other being style.css).
 * It is used to display a page when nothing more specific matches a query.
 * E.g., it puts together the home page when no home.php file exists.
 * Learn more: http://codex.wordpress.org/Template_Hierarchy
 *
 * @package iPanelThemes Knowledgebase
 */

get_header();

$main_categories = get_categories( array(
	'taxonomy' => 'category',
	'parent' => 0,
	'hide_empty' => 0,
) );
?>
	<div id="primary" class="content-area">
		<main id="main" class="site-main" role="main">
			<?php get_search_form(); ?>
			<div class="row kb-home-cat-row">
				<?php $cat_iterator = 0; foreach ( $main_categories as $cat ) : ?>
				<?php $term_meta = get_option( 'ipt_kb_category_meta_' . $cat->term_id, array() ); ?>
				<?php $term_link = esc_url( get_term_link( $cat ) ); ?>
				<div class="col-md-6">
					<div class="col-md-4">
						<?php if ( isset( $term_meta['image_url'] ) && '' != $term_meta['image_url'] ) : ?>
						<p class="text-center">
							<a href="<?php echo $term_link; ?>">
							<img class="img-circle" src="<?php echo esc_attr( $term_meta['image_url'] ); ?>" alt="<?php echo esc_attr( $cat->name ); ?>" />
							</a>
						</p>
						<?php endif; ?>
						<div class="caption">
						<?php if ( isset( $term_meta['support_forum'] ) && '' != $term_meta['support_forum'] ) : ?>
							<p class="text-center"><a class="btn btn-default btn-block" href="<?php echo esc_url( $term_meta['support_forum'] ); ?>">
								<i class="glyphicon ipt-support"></i> <?php _e( 'Get support', 'ipt_kb' ); ?>
							</a></p>
						<?php endif; ?>
						</div>
					</div>
					<div class="col-md-8">
						<h2 class="knowledgebase-title"><a data-popt="kb-homepage-popover-<?php echo $cat->term_id; ?>" title="<?php echo esc_attr( $cat->name ); ?>" href="#" class="btn btn-default btn-sm text-muted ipt-kb-popover"><i class="glyphicon ipt-paragraph-justify "></i></a> <?php echo $cat->name; ?></h2>
						<div class="ipt-kb-popover-target" id="kb-homepage-popover-<?php echo $cat->term_id; ?>">
							<?php echo wpautop( $cat->description ); ?>
							<p class="text-right">
								<?php if ( isset( $term_meta['support_forum'] ) && '' != $term_meta['support_forum'] ) : ?>
								<a class="btn btn-default" href="<?php echo esc_url( $term_meta['support_forum'] ); ?>">
									<i class="glyphicon ipt-support"></i> <?php _e( 'Get support', 'ipt_kb' ); ?>
								</a>
								<?php endif; ?>
								<a href="<?php echo $term_link; ?>" class="btn btn-info">
									<i class="glyphicon ipt-link"></i> <?php _e( 'Browse all', 'ipt_kb' ); ?>
								</a>
							</p>
						</div>
						<?php $sub_categories = get_categories( array(
							'taxonomy' => 'category',
							'parent' => $cat->term_id,
							'hide_empty' => 0,
							'number' => '5',
						) ); ?>
						<div class="list-group">
							<?php if ( ! empty( $sub_categories ) ) : ?>
							<?php foreach ( $sub_categories as $scat ) : ?>
							<?php $sterm_meta = get_option( 'ipt_kb_category_meta_' . $scat->term_id, array() ); ?>
							<a href="<?php echo esc_url( get_term_link( $scat, 'category' ) ); ?>" class="list-group-item">
								<span class="badge"><?php echo $scat->count; ?></span>
								<?php if ( isset( $sterm_meta['icon_class'] ) && '' != $sterm_meta['icon_class'] ) : ?>
								<i class="glyphicon <?php echo esc_attr( $sterm_meta['icon_class'] ); ?>"></i>
								<?php else : ?>
								<i class="glyphicon ipt-books"></i>
								<?php endif; ?>
								<?php echo $scat->name; ?>

							</a>
							<?php endforeach; ?>
							<?php else : ?>
							<?php $cat_posts = new WP_Query( array(
								'posts_per_page' => get_option( 'posts_per_page', 5 ),
								'cat' => $cat->term_id,
							) ); ?>
							<?php if ( $cat_posts->have_posts() ) : ?>
							<?php while ( $cat_posts->have_posts() ) : $cat_posts->the_post(); ?>
								<?php get_template_part( 'category-templates/content', 'popular' ); ?>
							<?php endwhile; ?>
							<?php else : ?>
								<?php get_template_part( 'category-templates/no-result' ); ?>
							<?php endif; ?>
							<?php wp_reset_query(); ?>
							<?php endif; ?>
						</div>
						<p class="text-right">
							<a href="<?php echo $term_link; ?>" class="btn btn-default">
								<i class="glyphicon ipt-link"></i> <?php _e( 'Browse all', 'ipt_kb' ); ?>
							</a>
						</p>
					</div>
				</div>
				<?php $cat_iterator++; if ( $cat_iterator % 2 == 0 ) echo '<div class="clearfix"></div>'; ?>
				<?php endforeach; ?>
				<div class="clearfix"></div>
			</div>


			<div class="row kb-home-panels">
				<?php // Recent posts ?>
				<div class="col-md-6">
					<div class="panel panel-info">
						<div class="panel-heading">
							<h3 class="panel-title"><i class="glyphicon glyphicon-calendar"></i>&nbsp;&nbsp;<?php _e( 'Recent articles', 'ipt_kb' ); ?></h3>
						</div>
						<div class="panel-body">
							<div class="list-group">
								<?php if ( have_posts() ) : ?>
									<?php while ( have_posts() ) : the_post(); ?>
									<?php get_template_part( 'category-templates/content', 'date' ); ?>
									<?php endwhile; ?>
								<?php else : ?>
									<?php get_template_part( 'category-templates/no-result' ); ?>
								<?php endif; ?>
							</div>
						</div>
					</div>
				</div>
				<?php // Popular posts ?>
				<?php
				$args = array(
					'posts_per_page' => get_option( 'posts_per_page', 5 ),
					'orderby' => 'meta_value_num',
					'order' => 'DESC',
					'meta_key' => 'ipt_kb_like_article',
				);
				$popular_query = new WP_Query( $args );
				?>
				<div class="col-md-6">
					<div class="panel panel-success">
						<div class="panel-heading">
							<h3 class="panel-title"><i class="glyphicon glyphicon-fire"></i>&nbsp;&nbsp;<?php _e( 'Popular articles', 'ipt_kb' ); ?></h3>
						</div>
						<div class="panel-body">
							<div class="list-group">
								<?php if ( $popular_query->have_posts() ) : ?>
									<?php while ( $popular_query->have_posts() ) : $popular_query->the_post(); ?>
									<?php get_template_part( 'category-templates/content', 'popular' ); ?>
									<?php endwhile; ?>
								<?php else : ?>
									<?php get_template_part( 'category-templates/no-result' ); ?>
								<?php endif; ?>
							</div>
						</div>
					</div>
				</div>
			</div>

			<?php wp_reset_query(); ?>

		</main><!-- #main -->
	</div><!-- #primary -->
<?php get_footer(); ?>
