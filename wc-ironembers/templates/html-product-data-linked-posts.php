<?php
defined( 'ABSPATH' ) || exit;

if (!is_a($product_object, 'WC_Product_Fire_Pit')) {
    return;
}
?>
<div id="linked_posts_options" class="panel woocommerce_options_panel hidden">
    <div class="options_group">
        <p class="form-field">
            <label for="post_ids"><?php esc_html_e( 'Posts', 'woocommerce-fire-pit-product-type' ); ?></label>
            <select class="wc-post-search" multiple="multiple" style="width: 50%;" id="post_ids" name="post_ids[]" data-placeholder="<?php esc_attr_e( 'Search for a post&hellip;', 'woocommerce-fire-pit-product-type' ); ?>" data-action="woocommerce_json_search_posts" data-exclude="<?php echo intval( $post->ID ); ?>" ?>">
				<?php
				$post_ids = $product_object->get_post_ids( 'edit' );
				foreach ($post_ids as $post_id) {
					$post = get_post($post_id);
					if (is_object($post)) {
						echo '<option value="' . esc_attr( $post_id ) . '"' . selected( true, true, false ) . '>' . htmlspecialchars( wp_kses_post( $post->post_title ) ) . '</option>';
					}
				}
				?>
            </select>
        </p>
    </div>
</div>
