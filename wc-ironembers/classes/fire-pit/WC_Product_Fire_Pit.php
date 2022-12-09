<?php
/**
 * Created by PhpStorm.
 * User: marc
 * Date: 2021-03-12
 * Time: 2:26 PM
 */
if (class_exists('WC_Product_Fire_Pit')) return;

class WC_Product_Fire_Pit extends WC_Product
{
	/**
	 * Stores product data.
	 *
	 * @var array
	 */
	protected $extra_data = array(
		'post_ids' => [],
		'accessory_ids' => [],
		'production_addon_ids' => [],
	);

	/**
	 * Build the instance
	 */
	public function __construct($product)
	{
		parent::__construct($product);
	}

	/**
	 * Return the product type
	 * @return string
	 */
	public function get_type()
	{
		return 'fire-pit';
	}

	/**
	 * Get post IDs.
	 *
	 * @param  string $context What the value is for. Valid values are view and edit.
	 * @return array
	 */
	public function get_post_ids($context = 'view')
	{
		return $this->get_prop('post_ids', $context);
	}

	/**
	 * Set post IDs.
	 *
	 * @param array $post_ids IDs from the up-sell products.
	 */
	public function set_post_ids($post_ids)
	{
		$this->set_prop('post_ids', array_filter((array) $post_ids));
	}

	public function get_accessory_ids($context = 'view')
	{
		return $this->get_prop('accessory_ids', $context);
	}

	public function set_accessory_ids($accessory_ids)
	{
		$this->set_prop('accessory_ids', array_filter((array) $accessory_ids));
	}

	public function set_production_addon_ids($product_ids)
	{
		$this->set_prop('production_addon_ids', array_filter((array) $product_ids));
	}

	public function get_production_addon_ids($context = 'view')
	{
		return $this->get_prop('production_addon_ids', $context);
	}
}