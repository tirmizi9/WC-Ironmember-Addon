<?php
/**
 * Created by PhpStorm.
 * User: marc
 * Date: 2021-03-30
 * Time: 3:28 PM
 */
if (class_exists('WC_Product_Fire_Pit_Panel_Text_Form')) return;
class WC_Product_Fire_Pit_Panel_Text_Form
{
	private static $self = null;

	/**
	 * @return WC_Product_Fire_Pit_Tabs
	 */
	public static function instance()
	{
		if (static::$self === null) {
			static::$self = new static();
		}

		return static::$self;
	}

	public function initialize()
	{
		add_filter('woocommerce_add_cart_item_data', [$this, 'add_cart_item_data'], 10, 3);
		add_action('woocommerce_after_add_to_cart_quantity', [$this, 'product_page_form'], 20);
		add_action('woocommerce_add_to_cart', [$this, 'woocommerce_add_to_cart'], 10, 6);
		add_filter('woocommerce_get_item_data', [$this, 'get_item_data'], 10, 2);
		add_action('woocommerce_checkout_create_order_line_item', [$this, 'checkout_create_order_line_item'], 10, 4);

		if (is_admin()) {
			add_action('woocommerce_before_order_itemmeta', [$this, 'woocommerce_before_order_itemmeta'], 10, 3);
			add_action('woocommerce_before_save_order_item', [$this, 'woocommerce_before_save_order_item'], 99, 1);
			add_filter('woocommerce_hidden_order_itemmeta', [$this, 'woocommerce_hidden_order_itemmeta'], 10, 1);
		}
	}

	public function add_cart_item_data($cart_item_data, $product_id, $variation_id)
	{
		if (array_key_exists('custom_name', $_POST)) {

			$custom_name = $_POST['custom_name'];
			if (array_key_exists('name', $custom_name) && trim($custom_name['name'])) {
				$cart_item_data['custom_name_name'] = sanitize_text_field(trim($custom_name['name']));
			}

			if (array_key_exists('font', $custom_name) && trim($custom_name['font'])) {
				$cart_item_data['custom_name_font'] = sanitize_text_field(trim($custom_name['font']));
			}
			// remove this so we dont add this stuff when the text gets added.
			unset($_POST['custom_name']);
		}
		return $cart_item_data;
	}

	public function product_page_form()
	{
		global $product;

		if (!($product instanceof WC_Product_Fire_Pit)) {
			return;
		}

		$page = get_page_by_title('Custom Text', OBJECT, 'product');
		$customTextProduct = wc_get_product($page->ID);

		if (!$customTextProduct) {
			return ;
		}

		if ($customTextProduct->get_status() !== 'publish') {
			return ;
		}

		if (!in_array($customTextProduct->get_id(), $product->get_production_addon_ids())) {
			return ;
		}

		echo '<div class="fire-pit-custom-panel-text">' . PHP_EOL;
		echo '<h4>Have your family name, or text cutout from the side of the firepit for a personal touch</h4>' . PHP_EOL;
		echo '<div class="title-price-addon">' . $customTextProduct->get_price_html() . ' ' . get_woocommerce_currency() . '</div>' . PHP_EOL;
		{
			echo '<div class="product_meta_form_group">' . PHP_EOL;
			echo '<label class="product_meta_label" for="custom_name_name">Panel Text</label>' . PHP_EOL;
			echo '<input class="product_meta_control" id="custom_name_name" type="text" name="custom_name[name]" value="' . sanitize_text_field($_POST['custom_name']['name']) . '">';
			echo '</div>' . PHP_EOL;
		}
		{
			echo '<div class="product_meta_form_group">' . PHP_EOL;
			echo '<label class="product_meta_label" for="custom_name_font">Font</label>' . PHP_EOL;
			echo '<input class="product_meta_control" id="custom_name_font" type="text" name="custom_name[font]" value="' . sanitize_text_field($_POST['custom_name']['font']) . '">';
			echo '</div>' . PHP_EOL;
		}
		echo '</div>' . PHP_EOL;
	}

	public function get_item_data($item_data, $cart_item_data)
	{
		if (array_key_exists('_fire-pit-panel-text', $cart_item_data) && !empty($cart_item_data['_fire-pit-panel-text'])) {
			$item_data[] = [
				'key' => __('Panel Text', 'wc-ironembers'),
				'value' => $cart_item_data['_fire-pit-panel-text'],
			];
		}

		if (array_key_exists('_fire-pit-font', $cart_item_data) && !empty($cart_item_data['_fire-pit-font'])) {
			$item_data[] = [
				'key' => __('Custom Font', 'wc-ironembers'),
				'value' => $cart_item_data['_fire-pit-font'],
			];
		}

		return $item_data;
	}

	public function checkout_create_order_line_item($item, $cart_item_key, $values, $order)
	{
		if (array_key_exists('_fire-pit-panel-text', $values)) {
			$item->add_meta_data('_fire-pit-panel-text', $values['_fire-pit-panel-text'], true);
		}

		if (array_key_exists('_fire-pit-font', $values)) {
			$item->add_meta_data('_fire-pit-font', $values['_fire-pit-font'], true);
		}
	}

	public function woocommerce_add_to_cart($cart_item_key, $product_id, $quantity, $variation_id, $variation, $cart_item_data)
	{
		$product = wc_get_product($product_id);

		if (!($product instanceof WC_Product_Fire_Pit)) {
			return ;
		}

		if (array_key_exists('custom_name_name', $cart_item_data) && !empty($cart_item_data['custom_name_name'])) {

			$page = get_page_by_title('Custom Text', OBJECT, 'product');
			$customTextProduct = wc_get_product($page->ID);

			WC()->cart->add_to_cart($customTextProduct->get_id(), 1, 0, [], [
				'_parent_fire_pit' => $cart_item_key,
				'_fire-pit-panel-text' => $cart_item_data['custom_name_name'],
				'_fire-pit-font' => $cart_item_data['custom_name_font'],
			]);
		}
	}

	public function woocommerce_before_order_itemmeta($item_id, WC_Order_Item $item, $product)
	{
		if (!($product instanceof WC_Product_Fire_Pit_Panel_Text)) {
			return ;
		}

		$text = $item->get_meta('_fire-pit-panel-text');
		$font = $item->get_meta('_fire-pit-font');
		echo '<div class="edit" style="display:none">' . PHP_EOL;
		echo '<div class="custom-fire-pit-admin-fields">' . PHP_EOL;
		echo '<div class="custom-fire-pit-admin-fields-control-group"><label>Custom Panel Text</label><input type="text" name="fire_pit_panel_text['. $item_id .']" value="' . wc_clean($text) . '" /></div>' . PHP_EOL;
		echo '<div class="custom-fire-pit-admin-fields-control-group"><label>Font</label><input type="text" name="fire_pit_font['. $item_id .']" value="' . wc_clean($font) . '" /></div>' . PHP_EOL;
		echo '</div>' . PHP_EOL;
		echo '<p>Adding a value to custom panel text will automatically add an item to the order.</p>' . PHP_EOL;
		echo '</div>' . PHP_EOL;

	}

	public function woocommerce_hidden_order_itemmeta($list)
	{
		$list[] = '_fire-pit-panel-text';
		$list[] = '_fire-pit-font';

		return $list;
	}

	public function woocommerce_before_save_order_item(WC_Order_Item_Product $item)
	{
		$product = $item->get_product();

		if (!($product instanceof WC_Product_Fire_Pit_Panel_Text)) {
			return ;
		}

		$postItemsData = [];
		if (array_key_exists('items', $_POST)) {
			parse_str($_POST['items'], $postItemsData);
		}

		$text = '';
		$font = '';
		if (array_key_exists('fire_pit_panel_text', $postItemsData)) {
			$text = $postItemsData['fire_pit_panel_text'][$item->get_id()];
		}
		if (array_key_exists('fire_pit_font', $postItemsData)) {
			$font = $postItemsData['fire_pit_font'][$item->get_id()];
		}

		if (!$text) {
			$item->delete_meta_data('_fire-pit-panel-text');
			$item->delete_meta_data('_fire-pit-font');

			$item->save();

		} else {
			$item->update_meta_data('_fire-pit-panel-text', wc_clean($text));
			$item->update_meta_data('_fire-pit-font', wc_clean($font));

			$item->save();

		}
	}
}