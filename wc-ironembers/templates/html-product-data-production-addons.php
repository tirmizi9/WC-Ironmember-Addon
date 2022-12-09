<?php
defined( 'ABSPATH' ) || exit;

if (!is_a($product_object, 'WC_Product_Fire_Pit')) {
	return;
}

$args = array(
	'category' => array('production-addons'),
	'limit' => -1,
	'meta_key' => '_sku',
	'orderby' => 'meta_value',
	'order' => 'ASC'
);
$products = wc_get_products( $args );
$accessory_ids = $product_object->get_production_addon_ids('edit');
if (!is_array($accessory_ids)) {
    $accessory_ids = [];
}
?>
<div id="linked_addons_options" class="panel woocommerce_options_panel hidden">
	<h4>This fire pit allows for the following addons:</h4>
	<div class="linked-addons-options-list">
		<?php foreach ($products as $accessory): ?>
			<label class="linked-addons-options-list-item"><input type="checkbox" class="checkbox" style="" name="production_addon_ids[]" id="accessory_<?php echo $accessory->get_id(); ?>" value="<?php echo $accessory->get_id(); ?>" <?php echo in_array($accessory->get_id(), $accessory_ids) ? 'checked="checked"' : ''; ?>>
				<?php echo $accessory->get_name(); ?>
				[<?php echo $accessory->get_sku(); ?>]
			</label>
		<?php endforeach; ?>
	</div>
</div>


