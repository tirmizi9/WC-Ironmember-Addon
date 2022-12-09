<?php
/**
 * Created by PhpStorm.
 * User: marc
 * Date: 2021-03-12
 * Time: 2:18 PM
 * Plugin Name: WooCommerce Iron Embers Integration
 * Description: Customizations for Woocommerce
 * Version: 0.0.1
 * Author: Treefrog Inc.
 * Author URI: https://www.treefrog.ca
 * Copyright: © 2021 Treefrog Inc.
 * License: GPLv2 or later
 * License URI: http://www.gnu.org/licenses/gpl-2.0.html
 * Text Domain: woocommerce-fire-pit-product-type
 * WC requires at least: 3.0.0
 * WC tested up to: 4.9.1
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

define('WC_IRONEMBERS_PLUGIN_DIR', __DIR__);
define('WC_IRONEMBERS_PLUGIN_FILE', __FILE__);

//add_action('init', function() {
//	exit('init');
//});
//add_action('woocommerce_loaded', function() {
//	exit('woocommerce_loaded');
//});
class WC_IronEmbers
{

	public function __construct()
	{
		register_activation_hook(__FILE__, function() {
			// If there is no fire pit product type taxonomy, add it.
			if (!get_term_by('slug', 'fire-pit', 'product_type')) {
				wp_insert_term('fire-pit', 'product_type');
			}

			if (!get_term_by('slug', 'fire-pit-accessory', 'product_type')) {
				wp_insert_term('fire-pit-accessory', 'product_type');
			}
		});

		/**
		 * Adds this plugin to the woocommerce template include.
		 */
		add_filter('wc_get_template', function($template, $template_name, $args, $template_path, $default_path) {
			$path = WC_IRONEMBERS_PLUGIN_DIR . '/templates/woocommerce/' . $template_name;
			if (is_file($path)) {
				return $path;
			}

			return $template;
		}, 10, 5);

		/**
		 * adds this plugin to the wpml template include
		 */
		add_filter( 'wcml_cs_directories_to_scan', function($dirs) {
			$folder_name = basename( dirname( __FILE__ ) );
			$dirs[] = trailingslashit( WP_PLUGIN_DIR ) . $folder_name . '/templates/';
			return $dirs;
		});

		add_filter('woocommerce_package_rates', function($rates) {
			global $woocommerce_wpml;

			$currency = $woocommerce_wpml->multi_currency->get_client_currency();

			$exchange = 1;

			if ($currency === 'USD') {
				$exchanger = new WC_IronEmbers_Currency_Exchange();
				$exchanger->add_provider(new WC_IronEmbers_Currency_Exchange_ExchangeRatesAPI());
				$exchanger->add_provider(new WC_IronEmbers_Currency_Exchange_CurrConv());

				$exchange = $exchanger->get_rate('CAD', 'USD');
			}
			foreach ($rates as $key => $rate) {
				$rates[$key]->cost = $rates[$key]->cost * $exchange;
			}

			return $rates;
		});

		add_action('init', function() {
			if(!session_id()) {
				session_start();
			}
		}, 1);

		/**
		 * try to get the currency to stay at the currency and not switch after it's been determined.
		 */
		add_filter('wcml_client_currency', function($currency) {
			$current = isset($_SESSION['wc-ironembers']['currency']) ? $_SESSION['wc-ironembers']['currency'] : null;

			if ($current === null) {
				$_SESSION['wc-ironembers']['currency'] = $currency;
			}

			$currency = $_SESSION['wc-ironembers']['currency'];

			return $currency;
		});

		add_action('woocommerce_loaded', function() {
			require_once __DIR__ . '/classes/autoloader.php';
			WC_Product_Fire_Pit_Initializer::wp_initialize();
			WC_Product_Fire_Pit_Accessory_Initializer::wp_initialize();
			WC_Product_Fire_Pit_Panel_Text_Initializer::wp_initialize();

			WC_Product_Fire_Pit_Tabs::instance()->initialize();
			WC_Product_Fire_Pit_AccessoriesList::instance()->initialize();
			WC_Product_Fire_Pit_Accessory_Tabs::instance()->initialize();
			WC_Product_Fire_Pit_Panel_Text_Form::instance()->initialize();

			// removes the comments from the posts
			update_option('woocommerce_enable_reviews', 'no');
			remove_post_type_support('post', 'comments');
			remove_post_type_support('page', 'comments');

			register_post_status( 'wc-order-inquiry', array(
				'label'                     => 'Order Inquiry',
				'public'                    => true,
				'exclude_from_search'       => false,
				'show_in_admin_all_list'    => true,
				'show_in_admin_status_list' => true,
				'label_count'               => _n_noop( 'Order Inquiry (%s)', 'Order Inquiry (%s)' )
			) );

			add_filter('wc_order_statuses', function($order_statuses) {
				$new_order_statuses = array(
					'wc-order-inquiry' => 'Order Inquiry'
				);

				return array_merge($new_order_statuses, $order_statuses);
			});

			add_filter('woocommerce_payment_gateways', function($methods) {
				$methods[] = 'WC_Payment_Order_Inquiry';
				return $methods;
			});
		});

		if (is_admin()) {

			wp_register_style('wc-ironembers-admin-css', $this->plugin_url() . '/assets/css/admin.css', ['woocommerce_admin_styles'], '1.0', 'all');
			wp_enqueue_style('wc-ironembers-admin-css');
		}

		wp_register_style('wc-ironembers-frontend-css', $this->plugin_url() . '/assets/css/frontend.css', ['theme-shop'], '1.0', 'all');
		add_action('wp_enqueue_scripts', function() {
			wp_enqueue_style('wc-ironembers-frontend-css');
		}, 99);

		add_action( 'pre_get_posts', function ($query ){
			global $wp;

			if ( !is_admin() && $query->is_main_query() ) {
				if ($wp->request === 'orderform-order') {
					global $woocommerce;
					$woocommerce->cart->empty_cart();

					$pitId = (int)$_GET['pit'];
					$accessoryIds = explode(',', $_GET['accessories']);
					$name = (string)$_GET['name'];
					$email = (string)$_GET['email'];
					$phone = (string)$_GET['phone'];
					$postal = (string)$_GET['postal'];
					$comment = (string)$_GET['comment'];
					$text = (string)$_GET['design_text'];
					$font = (string)$_GET['design_font'];
					$message = (string)$_GET['design_message'];

					$_SESSION['wc-ironembers']['checkout'] = [
						'name' => $name,
						'email' => $email,
						'phone' => $phone,
						'postal' => $postal,
						'comment' => $comment,
						'text' => $text,
						'font' => $font,
						'message' => $message,
					];

					$woocommerce->cart->add_to_cart($pitId, 1);
					foreach ($accessoryIds as $id) {
						$woocommerce->cart->add_to_cart($id, 1);
					}

					header('Location: /cart');
					exit;
				}
			}
		});

		add_filter('woocommerce_checkout_fields', function($fields) {
			
			if (isset($_SESSION['wc-ironembers']['checkout'])) {
				$fields['order']['order_comments']['default'] = $_SESSION['wc-ironembers']['checkout']['comment'] . "\n" . $_SESSION['wc-ironembers']['checkout']['message'];
				$fields['billing']['billing_email']['default'] = $_SESSION['wc-ironembers']['checkout']['email'];
				$fields['billing']['billing_phone']['default'] = $_SESSION['wc-ironembers']['checkout']['phone'];

				unset($_SESSION['wc-ironembers']['checkout']);
			}

			return $fields;
		});

		add_shortcode('wc-ironembers-product-accessories', function($atts) {
			$a = shortcode_atts([
				'product_id' => null,
			], $atts);

			if ($a['product_id'] === null) {
				return '';
			}

			$product = wc_get_product($a['product_id']);

			if (!$product) {
				return '';
			}

			if (!($product instanceof WC_Product_Fire_Pit)) {
				return '';
			}

			ob_start();
			wc_get_template( 'single-product/accessories-list.php', ['product' => $product, 'show_title' => false]);
			return ob_get_clean();
		});
	}

	public function plugin_url()
	{
		return untrailingslashit( plugins_url( '/', WC_IRONEMBERS_PLUGIN_FILE ) );
	}
}

new WC_IronEmbers();

