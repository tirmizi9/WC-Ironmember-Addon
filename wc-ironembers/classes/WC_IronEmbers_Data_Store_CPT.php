<?php
/**
 * Created by PhpStorm.
 * User: marc
 * Date: 2021-03-15
 * Time: 1:11 PM
 */
if (class_exists('WC_IronEmbers_Data_Store_CPT')) return;
class WC_IronEmbers_Data_Store_CPT extends WC_Product_Data_Store_CPT
{
	/**
	 * Meta keys and how they transfer to CRUD props.
	 *
	 * @var array
	 */
	private $meta_key_to_props = [];

	/**
	 * Read product data. Can be overridden by child classes to load other props.
	 *
	 * @param WC_Product
	 */
	protected function read_product_data(&$product)
	{
		parent::read_product_data($product);
		$id = $product->get_id();
		$post_meta_values = get_post_meta($id);
		$set_props = array();
		foreach ($this->meta_key_to_props as $meta_key => $prop) {
			$meta_value = isset($post_meta_values[$meta_key][0]) ? $post_meta_values[$meta_key][0] : null;
			$set_props[$prop] = maybe_unserialize($meta_value); // get_post_meta only unserializes single values.
		}
		$product->set_props($set_props);
	}

	/**
	 * Helper method that updates all the post meta for a product based on it's settings in the WC_Product class.
	 *
	 * @param WC_Product
	 * @param bool $force Force all props to be written even if not changed. This is used during creation.
	 * @since 2.7.0
	 */
	protected function update_post_meta(&$product, $force = false)
	{
		parent::update_post_meta($product, $force);
		foreach ($this->meta_key_to_props as $key => $prop) {
			update_post_meta($product->get_id(), $key, $product->{"get_$prop"}());
		}
	}
}