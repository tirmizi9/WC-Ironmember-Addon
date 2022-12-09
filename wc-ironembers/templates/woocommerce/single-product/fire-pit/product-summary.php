<?php
/**
 * Created by PhpStorm.
 * User: marc
 * Date: 2021-03-16
 * Time: 4:18 PM
 */
?>


<h1 class="product_title entry-title"><?php echo esc_html( get_the_title() ); ?></h1>
<div class="product_sku">SKU: <?php echo $product->get_sku(); ?></div>
<div class="product_price_container">
    <div class="<?php echo esc_attr( apply_filters( 'woocommerce_product_price_class', 'price' ) ); ?>"><?php echo $product->get_price_html(); ?> <?php echo get_woocommerce_currency(); ?></div>
</div>
<?php do_action( 'woocommerce_' . $product->get_type() . '_add_to_cart' ); ?>
<div><?php do_action( 'woocommerce_template_single_sharing' ); ?></div>
