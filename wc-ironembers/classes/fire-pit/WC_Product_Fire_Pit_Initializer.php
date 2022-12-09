<?php
/**
 * Created by PhpStorm.
 * User: marc
 * Date: 2021-03-12
 * Time: 3:09 PM
 */
if (class_exists('WC_Product_Fire_Pit_Initializer')) return;
class WC_Product_Fire_Pit_Initializer
{
	public static function wp_initialize()
	{
		$self = new static();

		add_filter('woocommerce_data_stores', array($self, 'register_wc_data_store'));
		add_action('init', array($self, 'init'));
	}

	public function init()
	{
		WC_Product_Fire_Pit_Ajax::init();

		add_filter('product_type_selector', array($this, 'register_wc_type'));
		add_filter('woocommerce_product_class', array($this, 'register_wc_class'), 10, 2);
		add_filter('woocommerce_product_data_tabs', array($this, 'register_wc_tabs'));
		add_filter('woocommerce_product_data_panels', array($this, 'register_wc_data_panels'));

		add_action('woocommerce_product_options_general_product_data', function() {
			echo '<div class="options_group show_if_fire_pit clear"></div>';
		});
		add_action('woocommerce_product_options_inventory_product_data', function() {
			echo '<div class="options_group show_if_fire_pit clear"></div>';
		});
		add_action('woocommerce_admin_process_product_object', array($this, 'register_wc_product_data'));

		add_action('admin_enqueue_scripts', array($this, 'register_js'), 99);

		add_action('woocommerce_fire-pit_add_to_cart', function() {
			wc_get_template( 'single-product/add-to-cart/fire-pit.php' );
		});

		add_action('woocommerce_after_add_to_cart_quantity', [$this, 'product_page_form']);
		add_action('woocommerce_add_to_cart', [$this, 'woocommerce_add_to_cart'], 10, 6);
	}

	public function register_wc_type($types)
	{
		$types['fire-pit'] = __('Fire Pit', 'wc-ironembers');
		return $types;
	}

	public function register_wc_class($classname, $product_type)
	{
		if ( $product_type === 'fire-pit' ) {
			$classname = 'WC_Product_Fire_Pit';
		}
		return $classname;
	}

	public function register_wc_tabs($tabs)
	{
		$tabs['linked_posts'] = array(
			'label' => __('Linked Posts', 'wc-ironembers'),
			'target' => 'linked_posts_options',
			'priority' => 41,
			'class' => 'show_if_fire_pit'
		);
		$tabs['linked_accessories'] = array(
			'label' => __('Linked Accessories', 'wc-ironembers'),
			'target' => 'linked_accessory_options',
			'priority' => 42,
			'class' => 'show_if_fire_pit'
		);
		$tabs['production_addons'] = array(
			'label' => __('Production Addons', 'wc-ironembers'),
			'target' => 'linked_addons_options',
			'priority' => 43,
			'class' => 'show_if_fire_pit'
		);
		return $tabs;
	}

	public function register_wc_data_panels() {
		global $post, $thepostid, $product_object;

		include WC_IRONEMBERS_PLUGIN_DIR . '/templates/html-product-data-linked-posts.php';
		include WC_IRONEMBERS_PLUGIN_DIR . '/templates/html-product-data-linked-fire-pit-accessories.php';
		include WC_IRONEMBERS_PLUGIN_DIR . '/templates/html-product-data-production-addons.php';
	}

	public function register_wc_product_data( $product )
	{
		if ( ! is_a( $product, 'WC_Product_Fire_Pit' ) ) {
			return;
		}

		$errors = $product->set_props(
			array(
				'post_ids' => isset( $_POST['post_ids'] ) ? array_map( 'intval', (array) wp_unslash( $_POST['post_ids'] ) ) : array(),
				'accessory_ids' => isset( $_POST['accessory_ids'] ) ? array_map( 'intval', (array) wp_unslash( $_POST['accessory_ids'] ) ) : array(),
				'production_addon_ids' => isset( $_POST['production_addon_ids'] ) ? array_map( 'intval', (array) wp_unslash( $_POST['production_addon_ids'] ) ) : array()
			)
		);

		if ( is_wp_error( $errors ) ) {
			/** @var WP_Error $errors */
			WC_Admin_Meta_Boxes::add_error( $errors->get_error_message() );
		}
	}

	public function register_wc_data_store($stores = array())
	{
		$stores['product-fire-pit'] = new WC_Product_Fire_Pit_Data_Store_CPT();
		return $stores;
	}

	public function register_js()
	{
		wp_register_script('tf-wc-product-fire-pit', plugins_url() . '/wc-ironembers/assets/js/admin-fire-pit.js', array( 'jquery', 'selectWoo' ), '0.0.1');
		wp_localize_script(
			'tf-wc-product-fire-pit',
			'tf_wc_product_fire_pit_params',
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
		wp_enqueue_script('tf-wc-product-fire-pit');
	}

	public function product_page_form()
	{
		global $product;

		if ( ! ( $product instanceof WC_Product_Fire_Pit ) ) {
			return;
		}

		$product_ids = $product->get_production_addon_ids();
		$addons = [];

		foreach ($product_ids as $id) {
			$addon = wc_get_product($id);
			if (!($addon instanceof WC_Product_Fire_Pit_Panel_Text)) {
				$addons[] = $addon;
			}
		}


		if (!count($addons)) {
			return ;
		}

		echo "<h4 class='pit-addon-heading'>Include some addons for this fire pit</h4>";
		/** @var WC_Product $addon */
		foreach ($addons as $addon) {
			echo "<div class='pit-addon-options'><label><input type='checkbox' name='pit_addons[]' value='" . $addon->get_id() . "' /><span>+" . $addon->get_price_html() . " " . get_woocommerce_currency() . "</span> - " . $addon->get_title() . "</label></div>";
		}
	}

	public function woocommerce_add_to_cart($cart_item_key, $product_id, $quantity, $variation_id, $variation, $cart_item_data)
	{
		$product = wc_get_product($product_id);

		if (!($product instanceof WC_Product_Fire_Pit)) {
			return ;
		}

		if (array_key_exists('pit_addons', $_POST)) {
			$pitAddonIds = $_POST['pit_addons'];
			unset($_POST['pit_addons']);

			foreach ($pitAddonIds as $id) {
				WC()->cart->add_to_cart($id, 1, 0, [], []);
			}
		}
	}
}