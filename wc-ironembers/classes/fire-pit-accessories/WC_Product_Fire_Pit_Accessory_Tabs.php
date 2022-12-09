<?php
/**
 * Created by PhpStorm.
 * User: marc
 * Date: 2021-03-17
 * Time: 1:18 PM
 */
if (class_exists('WC_Product_Fire_Pit_Accessory_Tabs')) return;
class WC_Product_Fire_Pit_Accessory_Tabs
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
		add_filter('woocommerce_product_tabs', function($tabs = []) {
			global $product, $post;

			if ($product && $product instanceof WC_Product_Fire_Pit_Accessory) {
				unset($tabs['description'], $tabs['additional_information']);
				$tabs['specifications'] = [
					'title'    => __( 'Specifications', 'wc-ironembers' ),
					'priority' => 10,
					'callback' => [$this, 'tab_specifications'],
				];

				if (have_rows('videos', $product->get_id())) {
					$tabs['videos'] = [
						'title'    => __( 'Videos', 'wc-ironembers' ),
						'priority' => 20,
						'callback' => [$this, 'tab_videos'],
					];
				}
			}

			return $tabs;
		});
	}

	public function tab_specifications($tabs = [])
	{
		wc_get_template( 'single-product/tabs/specifications.php' );
	}

	public function tab_videos($tabs = [])
	{
		wc_get_template( 'single-product/tabs/videos.php' );
	}
}