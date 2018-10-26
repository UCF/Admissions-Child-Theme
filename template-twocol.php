<?php
/**
 * Template Name: Two Column
 * Template Post Type: page, post
 */
?>
<?php get_header(); the_post(); ?>

<article class="<?php echo $post->post_status; ?> post-list-item">
	<div class="container mt-4 mt-sm-5 mb-5 pb-sm-4">

		<?php if ( $post->page_lead_text ) : ?>
		<div class="lead mb-4 mb-md-5">
			<?php echo apply_filters( 'the_content', $post->page_lead_text ); ?>
		</div>
		<?php endif; ?>

		<div class="row">
			<div class="col-lg-7">
				<?php the_content(); ?>
			</div>

			<?php if ( have_rows( 'page_sidebar_contents' ) ): ?>
			<div class="col-lg-4 offset-lg-1">
				<hr class="hidden-lg-up my-4 my-md-5">

				<?php while ( have_rows( 'page_sidebar_contents' ) ) : the_row(); ?>
				<aside class="mb-4 mb-md-5">
					<?php
					switch ( get_row_layout() ) {
						case 'page_sidebar_layout_spotlight':
							$spotlight = get_sub_field( 'page_sidebar_spotlight' );
							echo admissions_twocol_display_spotlight( $spotlight );
							break;
						case 'page_sidebar_layout_section':
							$section = get_sub_field( 'page_sidebar_section' );
							echo admissions_twocol_display_section( $section );
							break;
						case 'page_sidebar_layout_custom':
							$custom = get_sub_field( 'page_sidebar_custom' );
							if ( $custom ) {
								echo $custom;
							}
							break;
						default:
							break;
					}
					?>
				</aside>
				<?php endwhile; ?>
			</div>
			<?php endif; ?>
		</div>
	</div>
</article>

<?php get_footer(); ?>
