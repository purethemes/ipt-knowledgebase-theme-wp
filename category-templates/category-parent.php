<?php
/**
 * @package iPanelThemes Knowledgebase
 */
global $term_meta, $cat, $cat_id, $sub_categories;
?>
		<?php if ( have_posts() ) : ?>

			<header class="kb-parent-category-header row">
				<div class="col-md-4 col-lg-2 kb-pcat-icon">
					<?php if ( isset( $term_meta['image_url'] ) && '' != $term_meta['image_url'] ) : ?>
					<p class="text-center">
						<img class="img-circle" src="<?php echo esc_attr( $term_meta['image_url'] ); ?>" alt="<?php echo esc_attr( $cat->name ); ?>" />
					</p>
					<?php endif; ?>
					<div class="caption">
					<?php if ( isset( $term_meta['support_forum'] ) && '' != $term_meta['support_forum'] ) : ?>
						<p class="text-center"><a class="btn btn-default btn-block" href="<?php echo esc_url( $term_meta['support_forum'] ); ?>">
							<i class="glyphicon ipt-support"></i> <?php _e( 'Support', 'ipt_kb' ); ?>
						</a></p>
					<?php endif; ?>
					</div>
				</div>
				<div class="col-md-8 col-lg-10">
					<h1 class="page-title">
						<?php
							single_cat_title();
						?>
					</h1>
					<?php
						// Show an optional term description.
						$term_description = term_description();
						if ( ! empty( $term_description ) ) :
							printf( '<div class="taxonomy-description well">%s</div>', $term_description );
						endif;
					?>
				</div>
				<div class="clearfix"></div>
			</header><!-- .page-header -->

			<div class="row kb-cat-parent-row">
				<?php /* Start the subcategory loop */ ?>
				<?php $cat_iterator = 0; foreach ( $sub_categories as $scat ) : ?>
				<?php $sterm_meta = get_option( 'ipt_kb_category_meta_' . $scat->term_id, array() ); ?>
				<?php $sterm_link = esc_url( get_category_link( $scat ) ); ?>
				<?php $scat_posts = new WP_Query( array(
					'cat' => $scat->term_id,
					'posts_per_page' => get_option( 'posts_per_page', 5 ),
				) ); ?>
				<div class="col-md-6 kb-subcat">
					<div class="col-md-3 col-sm-2 col-xs-3 kb-subcat-icon">
						<p class="text-center">
							<a href="<?php echo $sterm_link; ?>" class="thumbnail">
								<?php if ( isset( $sterm_meta['icon_class'] ) && '' != $sterm_meta['icon_class'] ) : ?>
								<i class="glyphicon <?php echo esc_attr( $sterm_meta['icon_class'] ); ?>"></i>
								<?php else : ?>
								<i class="glyphicon ipt-books"></i>
								<?php endif; ?>
							</a>
						</p>
						<p class="text-center">
							<a href="<?php echo $sterm_link; ?>" class="btn btn-default btn-block">
								<i class="glyphicon ipt-link"></i>
								<?php _e( 'Browse', 'ipt_kb' ); ?>
							</a>
						</p>
					</div>
					<div class="col-md-9 col-sm-10 col-xs-9">
						<h2 class="knowledgebase-title"><a data-popt="kb-homepage-popover-<?php echo $scat->term_id; ?>" title="<?php echo esc_attr( sprintf( __( '%s / %s', 'ipt_kb' ), $cat->name, $scat->name ) ); ?>" href="#" class="btn btn-default btn-sm text-muted ipt-kb-popover"><i class="glyphicon ipt-paragraph-justify "></i></a> <?php echo $scat->name; ?></h2>
						<div class="ipt-kb-popover-target" id="kb-homepage-popover-<?php echo $scat->term_id; ?>">
							<?php echo wpautop( $scat->description ); ?>
							<p class="text-right">
								<?php if ( isset( $term_meta['support_forum'] ) && '' != $term_meta['support_forum'] ) : ?>
								<a class="btn btn-default" href="<?php echo esc_url( $term_meta['support_forum'] ); ?>">
									<i class="glyphicon ipt-support"></i> <?php _e( 'Get support', 'ipt_kb' ); ?>
								</a>
								<?php endif; ?>
								<a href="<?php echo $sterm_link; ?>" class="btn btn-info">
									<i class="glyphicon ipt-link"></i> <?php _e( 'Browse all', 'ipt_kb' ); ?>
								</a>
							</p>
						</div>

						<div class="list-group">
							<?php if ( $scat_posts->have_posts() ) : ?>
								<?php while ( $scat_posts->have_posts() ) : $scat_posts->the_post(); ?>
								<?php get_template_part( 'category-templates/content', 'popular' ); ?>
								<?php endwhile; ?>
							<?php else : ?>
								<?php get_template_part( 'category-templates/no-result' ); ?>
							<?php endif; ?>
						</div>
					</div>
					<div class="clearfix"></div>
				</div>
				<?php wp_reset_query(); ?>
				<?php $cat_iterator++; if ( $cat_iterator %2 == 0 ) echo '<div class="clearfix"></div>'; ?>
				<?php endforeach; ?>
				<div class="clearfix"></div>
			</div>

		<?php else : ?>

			<?php get_template_part( 'no-results', 'archive' ); ?>

		<?php endif; ?>

