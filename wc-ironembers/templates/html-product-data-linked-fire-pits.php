<?php
defined( 'ABSPATH' ) || exit;

if (!is_a($product_object, 'WC_Product_Fire_Pit_Accessory')) {
	return;
}

$args = array(
	'type' => 'fire-pit'
);
$products = wc_get_products( $args );
$pit_ids = $product_object->get_pit_ids( 'edit' );
?>
<div id="linked_fire_pits_options" class="panel woocommerce_options_panel hidden">
	<h4>This accessory works with the following fire pits:</h4>
	<div class="options_group">
		<?php foreach ($products as $pit): ?>
		<p class="form-field">
			<label for="pit_<?php echo $pit->get_id(); ?>"><?php echo $pit->get_name(); ?></label>
			<input type="checkbox" class="checkbox" style="" name="pit_ids[]" id="pit_<?php echo $pit->get_id(); ?>" value="<?php echo $pit->get_id(); ?>" <?php echo in_array($pit->get_id(), $pit_ids) ? 'checked="checked"' : ''; ?>>
		</p>
		<?php endforeach; ?>
	</div>
</div>


