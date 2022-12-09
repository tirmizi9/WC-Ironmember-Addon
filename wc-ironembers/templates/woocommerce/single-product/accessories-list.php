<?php
$accessories = $product->get_accessory_ids('view');

?>

<?php if (is_array($accessories) && count($accessories)): ?>

<div class="col-xs-12 fire-pit-accessories-container">
	<div id="fire-pit-accessories">
        <?php if (isset($show_title) && $show_title === true): ?>
		<h2><?php echo $product->get_name() . ' ' . __('Accessories', 'wc-ironembers'); ?></h2>
        <?php endif; ?>
		<div class="fire-pit-accessories-list">
            <?php foreach ($accessories as $acc_id): ?>
                <?php $acc_product = wc_get_product($acc_id); ?>
	            <?php $image = wp_get_attachment_image_src( get_post_thumbnail_id( $acc_id ), [270,199] );?>
                <div class="fire-pit-accessory-list-item">
                    <div class="accessory-image">
                        <?php if ($image): ?>
                        <img src="<?php  echo $image[0]; ?>" data-id="<?php echo $acc_id; ?>">
                        <?php endif; ?>
                    </div>
                    <div class="accessory-title">
                        <a href="<?php the_permalink($acc_id); ?>"><?php echo get_the_title($acc_id); ?></a>
                    </div>
                    <div class="accessory-price">
	                    <?php echo $acc_product->get_price_html(); ?> <?php echo get_woocommerce_currency(); ?>
                    </div>
                    <div class="accessory-cart">
                        <form class="cart" action="<?php echo esc_url( apply_filters( 'woocommerce_add_to_cart_form_action', $acc_product->get_permalink() ) ); ?>" method="post" enctype='multipart/form-data'>

	                        <?php

	                        woocommerce_quantity_input( array(
		                        'min_value'   => 1,
		                        'max_value'   => 1,
		                        'input_value' => 1, // WPCS: CSRF ok, input var ok.
	                        ) );

	                        ?>

                            <button type="submit" name="add-to-cart" value="<?php echo esc_attr( $acc_product->get_id() ); ?>" class=""><span><?php echo esc_html( $acc_product->single_add_to_cart_text() ); ?></span></button>

                        </form>
                    </div>
                </div>
            <?php endforeach; ?>
		</div>
	</div>
</div>

<?php endif; ?>