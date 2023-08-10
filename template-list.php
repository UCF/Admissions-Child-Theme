<?php
/**
 * Template Name: List
 * Template Post Type: page
 */
?>
<?php
use Adm\Theme\Includes\Sections;

get_header(); the_post();

$list = get_field( 'list', $post );

if ( $list ):
	$list_markup = Sections\display_list( $list );
	$list_count  = Sections\get_list_count( $list );
	$list_nav    = Sections\display_list_nav( $list, $list_count );

	$content_below_header   = get_field( 'content_below_header' );
	$content_above_list_nav = get_field( 'content_above_list_nav' );
	$content_below_list_nav = get_field( 'content_below_list_nav' );
	$content_above_list     = get_field( 'content_above_list' );
	$content_below_list     = get_field( 'content_below_list' );
	$content_above_footer   = get_field( 'content_above_footer' );

	$container_class = 'container';
	if ( ! $content_below_header ) {
		$container_class .= ' mt-sm-5';
	}
	if ( ! $content_above_footer ) {
		$container_class .= ' mb-5 pb-sm-4';
	}
?>

<article class="<?php echo $post->post_status; ?> post-list-item">
	<?php echo $content_below_header; ?>
	<div class="<?php echo $container_class; ?>">
		<div class="row">
			<div class="col-lg-4 p-3 p-md-4 mb-4 mb-sm-5 mb-lg-0 bg-faded affixed-sidebar sticky-top">
				<?php echo $content_above_list_nav; ?>
				<?php echo $list_nav; ?>
				<?php echo $content_below_list_nav; ?>
			</div>
			<div class="col-lg-8 pl-lg-5">
				<?php echo $content_above_list; ?>
				<?php echo $list_markup; ?>
				<?php echo $content_below_list; ?>
			</div>
		</div>
	</div>
	<?php echo $content_above_footer; ?>
</article>

<?php endif; ?>

<?php get_footer(); ?>
