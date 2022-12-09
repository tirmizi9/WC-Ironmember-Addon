<?php
/**
 * Created by PhpStorm.
 * User: marc
 * Date: 2021-03-15
 * Time: 1:16 PM
 */
if (class_exists('WC_Product_Fire_Pit_Accessory_Initializer')) return;
class WC_Product_Fire_Pit_Accessory_Initializer
{
	public static function wp_initialize()
	{
		$self = new static();

		add_filter('woocommerce_data_stores', array($self, 'register_wc_data_store'));
		add_action('init', array($self, 'init'));
	}

	public function register_wc_data_store($stores = array())
	{
		$stores['product-fire-pit-accessory'] = new WC_Product_Fire_Pit_Accessory_Data_Store_CPT();
		return $stores;
	}

	public function init()
	{
		add_filter('product_type_selector', array($this, 'register_wc_type'));
		add_filter('woocommerce_product_class', array($this, 'register_wc_class'), 10, 2);

		add_action('admin_enqueue_scripts', array($this, 'register_js'), 99);

		add_action('woocommerce_fire-pit-accessory_add_to_cart', function() {
			// using the fire pits one
			wc_get_template( 'single-product/add-to-cart/fire-pit.php' );
		});

	}

	public function register_wc_type($types)
	{
		$types['fire-pit-accessory'] = __('Fire Pit Accessory', 'wc-ironembers');
		return $types;
	}

	public function register_wc_class($classname, $product_type)
	{
		if ( $product_type === 'fire-pit-accessory' ) {
			$classname = 'WC_Product_Fire_Pit_Accessory';
		}
		return $classname;
	}

	public function register_js()
	{
		wp_register_script('wc-product-fire-pit-accessory', plugins_url() . '/wc-ironembers/assets/js/admin-fire-pit-accessory.js', array( 'jquery', 'selectWoo' ), '0.0.1');
		wp_localize_script(
			'wc-product-fire-pit-accessory',
			'tf_wc_product_fire_pit_accessory_params',
			array(
				'i18n_no_matches'           => _x( 'No matches found', 'enhanced select', 'woocommerce' ),
				'i18n_ajax_error'           => _x( 'Loading failed', 'enhanced select', 'woocommerce' ),
				'i18n_input_too_short_1'    => _x( 'Please enter 1 or more characters', 'enhanced select', 'woocommerce' ),
				'i18n_input_too_short_n'    => _x( 'Please enter %qty% or more characters', 'enhanced select', 'woocommerce' ),
				'i18n_input_too_long_1'     => _x( 'Please delete 1 character', 'enhanced select', 'woocommerce' ),
				'i18n_input_too_long_n'     => _x( 'Please delete %qty% characters', 'enhanced select', 'woocommerce' ),
				'i18n_selection_too_long_1' => _x( 'You can only select 1 item', 'enhanced select', 'woocommerce' ),
				'i18n_selection_too_long_n' => _x( 'You can only select %qty% items', 'enhanced select', 'woocommerce' ),
				'i18n_load_more'            => _x( 'Loading more results&hellip;', 'enhanced select', 'woocommerce' ),
				'i18n_searching'            => _x( 'Searching&hellip;', 'enhanced select', 'woocommerce' ),
				'ajax_url'                  => admin_url( 'admin-ajax.php' ),
				'search_posts_nonce'        => wp_create_nonce( 'tf-wc-search-posts' ),
			)
		);
		wp_enqueue_script('wc-product-fire-pit-accessory');
	}
}