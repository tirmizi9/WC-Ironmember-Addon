
<div class="reviews-tab-container">
	<?php while(have_rows('reviews')): the_row() ?>
	<div class="review-container">
		<div class="review-content"><?php the_sub_field('review'); ?></div>
		<div class="review-submitter"><?php echo get_sub_field('submitter_name'); ?></div>
	</div>
	<?php endwhile; ?>
</div>