<?php
defined( 'ABSPATH' ) || exit;

if (!is_a($product_object, 'WC_Product_Fire_Pit')) {
	return;
}

$args = array(
	'type' => 'fire-pit-accessory',
    'limit' => -1,
	'meta_key' => '_sku',
	'orderby' => 'meta_value',
	'order' => 'ASC'
);
$products = wc_get_products( $args );
$accessory_ids = $product_object->get_accessory_ids('edit');
if (!is_array($accessory_ids)) {
	$accessory_ids = [];
}
?>
<div id="linked_accessory_options" class="panel woocommerce_options_panel hidden">
	<h4>This fire pit uses the following accessories:</h4>
	<div class="linked-accessory-options-list">
		<?php foreach ($products as $accessory): ?>
			<label class="linked-accessory-options-list-item"><input type="checkbox" class="checkbox" style="" name="accessory_ids[]" id="accessory_<?php echo $accessory->get_id(); ?>" value="<?php echo $accessory->get_id(); ?>" <?php echo in_array($accessory->get_id(), $accessory_ids) ? 'checked="checked"' : ''; ?>>
                <?php echo $accessory->get_name(); ?>
                [<?php echo $accessory->get_sku(); ?>]
            </label>
		<?php endforeach; ?>
	</div>
</div>


