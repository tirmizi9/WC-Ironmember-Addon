<?php
/**
 * Created by PhpStorm.
 * User: marc
 * Date: 2021-03-30
 * Time: 3:09 PM
 */
if (class_exists('WC_Product_Fire_Pit_Panel_Text_Initializer')) return;
class WC_Product_Fire_Pit_Panel_Text_Initializer
{
	public static function wp_initialize()
	{
		$self = new static();

		add_action('init', array($self, 'init'));
	}

	public function init()
	{
		add_filter('product_type_selector', array($this, 'register_wc_type'));
		add_filter('woocommerce_product_class', array($this, 'register_wc_class'), 10, 2);

		add_action('admin_enqueue_scripts', array($this, 'register_js'), 99);
	}

	public function register_wc_type($types)
	{
		$types['fire-pit-panel-text'] = __('Fire Pit Panel Text', 'wc-ironembers');
		return $types;
	}

	public function register_wc_class($classname, $product_type)
	{
		if ( $product_type === 'fire-pit-panel-text' ) {
			$classname = 'WC_Product_Fire_Pit_Panel_Text';
		}
		return $classname;
	}

	public function register_js()
	{
		wp_register_script('wc-product-fire-pit-panel-text', plugins_url() . '/wc-ironembers/assets/js/admin-fire-pit-panel-text.js', array( 'jquery', 'selectWoo' ), '0.0.1');
		wp_enqueue_script('wc-product-fire-pit-panel-text');
	}
}