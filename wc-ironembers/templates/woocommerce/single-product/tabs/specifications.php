<?php
defined( 'ABSPATH' ) || exit;

global $post;

$specs = get_field('specs');

?>

<div>
	<?php echo $specs; ?>
</div>
<div>
	<?php the_content(); ?>
</div>
