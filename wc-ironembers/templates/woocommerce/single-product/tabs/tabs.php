<?php
/**
 * Created by PhpStorm.
 * User: marc
 * Date: 2021-03-16
 * Time: 2:54 PM
 */

/**
 * Single Product tabs
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/single-product/tabs/tabs.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see     https://docs.woocommerce.com/document/template-structure/
 * @package WooCommerce/Templates
 * @version 3.8.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Filter tabs and allow third parties to add their own.
 *
 * Each tab is an array containing title, callback and priority.
 * @see woocommerce_default_product_tabs()
 */
$product_tabs = apply_filters( 'woocommerce_product_tabs', array() );

if ( ! empty( $product_tabs ) ) : ?>
	<div class="col-xs-12">

		<div class="woocommerce-tabs wc-tabs-wrapper row vc_row wc-ironembers-tabs-container" data-vc-full-width="true">
			<div class="col-xs-12">
				<ul class="tabs wc-tabs wc-ironembers-tabs" data-vc-full-width="true">

					<?php foreach ( $product_tabs as $key => $product_tab ) : ?>
						<li class="<?php echo esc_attr( $key ); ?>_tab">
							<a href="#tab-<?php echo esc_attr( $key ); ?>"><?php echo apply_filters( 'woocommerce_product_' . $key . '_tab_title', esc_html( $product_tab['title'] ), $key ); ?></a>
						</li>
					<?php endforeach; ?>

				</ul>
				<div class="vc_row-full-width vc_clearfix"></div><!-- /.vc_row-full-width vc_clearfix -->
				<?php foreach ( $product_tabs as $key => $product_tab ) : ?>
					<div class="woocommerce-Tabs-panel woocommerce-Tabs-panel--<?php echo esc_attr( $key ); ?> panel entry-content wc-tab" id="tab-<?php echo esc_attr( $key ); ?>">
						<?php
						if ( isset( $product_tab['callback'] ) ) {
							call_user_func( $product_tab['callback'], $key, $product_tab );
						}
						?>
					</div>
				<?php endforeach; ?>
			</div><!-- /.col-xs-12 -->

		</div>
		<div class="vc_row-full-width vc_clearfix"></div><!-- /.vc_row-full-width vc_clearfix -->

		<?php do_action( 'woocommerce_product_after_tabs' ); ?>
	</div><!-- /.col-xs-12 -->
<?php endif; ?>