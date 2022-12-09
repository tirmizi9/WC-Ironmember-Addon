<?php
/**
 * Created by PhpStorm.
 * User: marc
 * Date: 2021-03-15
 * Time: 12:45 PM
 */
if (class_exists('WC_Product_Fire_Pit_AccessoriesList')) return;
class WC_Product_Fire_Pit_AccessoriesList
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
		add_action('woocommerce_after_single_product_summary', function() {

			global $product, $post;

			if ($product && $product instanceof WC_Product_Fire_Pit) {
				wc_get_template( 'single-product/accessories-list.php', ['product' => $product, 'show_title' => true]);
			}
		}, 12);
	}
}