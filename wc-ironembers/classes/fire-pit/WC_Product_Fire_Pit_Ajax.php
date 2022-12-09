<?php
/**
 * Created by PhpStorm.
 * User: marc
 * Date: 2021-03-12
 * Time: 3:00 PM
 */
if (class_exists('WC_Product_Fire_Pit_Ajax')) return;
class WC_Product_Fire_Pit_Ajax extends WC_AJAX {

	/**
	 * Hook in ajax handlers.
	 */
	public static function init() {
		add_action( 'init', array( __CLASS__, 'define_ajax' ), 0 );
		add_action( 'template_redirect', array( __CLASS__, 'do_wc_ajax' ), 0 );
		self::add_ajax_events();
	}

	/**
	 * Get WC Ajax Endpoint.
	 *
	 * @param string $request Optional.
	 *
	 * @return string
	 */
	public static function get_endpoint( $request = '' ) {
		return esc_url_raw( apply_filters( 'woocommerce_ajax_get_endpoint', add_query_arg( 'wc-ajax', $request, remove_query_arg( array( 'remove_item', 'add-to-cart', 'added-to-cart', 'order_again', '_wpnonce' ), home_url( '/', 'relative' ) ) ), $request ) );
	}

	/**
	 * Set WC AJAX constant and headers.
	 */
	public static function define_ajax() {
		// phpcs:disable
		if ( ! empty( $_GET['wc-ajax'] ) ) {
			wc_maybe_define_constant( 'DOING_AJAX', true );
			wc_maybe_define_constant( 'WC_DOING_AJAX', true );
			if ( ! WP_DEBUG || ( WP_DEBUG && ! WP_DEBUG_DISPLAY ) ) {
				@ini_set( 'display_errors', 0 ); // Turn off display_errors during AJAX events to prevent malformed JSON.
			}
			$GLOBALS['wpdb']->hide_errors();
		}
		// phpcs:enable
	}

	/**
	 * Send headers for WC Ajax Requests.
	 *
	 * @since 2.5.0
	 */
	private static function wc_ajax_headers() {
		if ( ! headers_sent() ) {
			send_origin_headers();
			send_nosniff_header();
			wc_nocache_headers();
			header( 'Content-Type: text/html; charset=' . get_option( 'blog_charset' ) );
			header( 'X-Robots-Tag: noindex' );
			status_header( 200 );
		} elseif ( Constants::is_true( 'WP_DEBUG' ) ) {
			headers_sent( $file, $line );
			trigger_error( "wc_ajax_headers cannot set headers - headers already sent by {$file} on line {$line}", E_USER_NOTICE ); // @codingStandardsIgnoreLine
		}
	}

	/**
	 * Check for WC Ajax request and fire action.
	 */
	public static function do_wc_ajax() {
		global $wp_query;

		// phpcs:disable WordPress.Security.NonceVerification.Recommended
		if ( ! empty( $_GET['wc-ajax'] ) ) {
			$wp_query->set( 'wc-ajax', sanitize_text_field( wp_unslash( $_GET['wc-ajax'] ) ) );
		}

		$action = $wp_query->get( 'wc-ajax' );

		if ( $action ) {
			self::wc_ajax_headers();
			$action = sanitize_text_field( $action );
			do_action( 'wc_ajax_' . $action );
			wp_die();
		}
		// phpcs:enable
	}

	/**
	 * Hook in methods - uses WordPress ajax handlers (admin-ajax).
	 */
	public static function add_ajax_events() {

		$ajax_events_nopriv = array(
			'json_search_posts'
		);

		foreach ( $ajax_events_nopriv as $ajax_event ) {
			add_action( 'wp_ajax_woocommerce_' . $ajax_event, array( __CLASS__, $ajax_event ) );
			add_action( 'wp_ajax_nopriv_woocommerce_' . $ajax_event, array( __CLASS__, $ajax_event ) );

			// WC AJAX can be used for frontend ajax requests.
			add_action( 'wc_ajax_' . $ajax_event, array( __CLASS__, $ajax_event ) );
		}

		$ajax_events = array(
		);

		foreach ( $ajax_events as $ajax_event ) {
			add_action( 'wp_ajax_woocommerce_' . $ajax_event, array( __CLASS__, $ajax_event ) );
		}
	}

	/**
	 * Search for products and echo json.
	 *
	 * @param string $term (default: '') Term to search for.
	 * @param bool   $include_variations in search or not.
	 */
	public static function json_search_posts( $term = '', $include_variations = false )
	{
		check_ajax_referer( 'tf-wc-search-posts', 'security' );

		if ( empty( $term ) && isset( $_GET['term'] ) ) {
			$term = (string) wc_clean( wp_unslash( $_GET['term'] ) );
		}

		if ( empty( $term ) ) {
			wp_die();
		}

		if ( ! empty( $_GET['limit'] ) ) {
			$limit = absint( $_GET['limit'] );
		} else {
			$limit = absint( apply_filters( 'woocommerce_json_search_limit', 30 ) );
		}

		$include_ids = ! empty( $_GET['include'] ) ? array_map( 'absint', (array) wp_unslash( $_GET['include'] ) ) : array();
		$exclude_ids = ! empty( $_GET['exclude'] ) ? array_map( 'absint', (array) wp_unslash( $_GET['exclude'] ) ) : array();

		$args = [
			'post_type' => 'post',
			'post_status' => 'publish',
			'posts_per_page' => $limit,
			'post__not_in' => $exclude_ids
		];

		$results = new WP_Query( $args );

		$posts = array();
		while ($results->have_posts()) : $results->the_post();
			if (stripos(get_the_title(), $term) !== false) {
				$posts[get_the_ID()] = get_the_title();
			}
		endwhile;

		wp_send_json( apply_filters( 'woocommerce_json_search_found_posts', $posts ) );
	}

}