<?php
/**
 * Assuming the check on this page was done so we do have blog post ids on the product
 */
global $product;

$postIds = $product->get_post_ids();
$posts = [];

foreach ($postIds as $postId) {
	$posts[] = get_post($postId);
}
$originalPost = $GLOBALS['post'];
wp_enqueue_style('rella-sc-latest-posts');
function blog_tab_brief_excerpt_filter($text) {
	return wp_trim_words($text, 32, '');
}
add_filter('the_excerpt', 'blog_tab_brief_excerpt_filter');
?>

<div class="related-blog-carousel">
<?php foreach ($posts as $post): $GLOBALS['post'] = $post; ?>
	<div class="related-blog-carousel-item">
		<div class="latest-posts latest-default latest-bg-grey latest-meta meta-sm6">
			<figure>
				<a href="<?php the_permalink(); ?>" title="<?php the_title(); ?>">
					<?php if( has_post_thumbnail() ) {
						rella_the_post_thumbnail( 'rella-default-blog', null, false );
					} else{
						echo '<div class="latest-post-thumbnail-placeholder"></div>';
					}
					?>
				</a>
			</figure>

			<div class="latest-content">

				<header>
					<h3 class="entry-title"><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h3>
					<div class="meta text-uppercase">

						<time datetime="<?php the_date('Y-m-d'); ?>"><?php the_time( get_option( 'date_format' ) ); ?></time>
						<?php
						$category      = get_the_category();
						$firstCategory = $category[0]->cat_name;
						$category_id   = $category[0]->term_id;
						if ( ! empty( $firstCategory ) ) {
							?>
							<span class="tags"><a href="<?php echo get_category_link( $category[0]->term_id ); ?>"><?php echo $firstCategory; ?></a></span>
						<?php } ?>

					</div>
				</header>

				<div class="excerpt">
					<?php the_excerpt(); ?>
				</div><!-- /.excerpt -->

			</div><!-- /.latest-content -->

		</div><!-- /.latest-posts -->
	</div>
<?php endforeach; ?>
</div>

<?php
$GLOBALS['post'] = $originalPost;
remove_filter('the_excerpt', 'blog_tab_brief_excerpt_filter');