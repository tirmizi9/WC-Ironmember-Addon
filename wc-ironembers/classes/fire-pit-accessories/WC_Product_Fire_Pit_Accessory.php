<?php
/**
 * Created by PhpStorm.
 * User: marc
 * Date: 2021-03-15
 * Time: 1:04 PM
 */
if (class_exists('WC_Product_Fire_Pit_Accessory')) return;
class WC_Product_Fire_Pit_Accessory extends WC_Product
{
	/**
	 * Stores product data.
	 *
	 * @var array
	 */
	protected $extra_data = array(
		'pit_ids' => []
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
		return 'fire-pit-accessory';
	}

	/**
	 * Get post IDs.
	 *
	 * @param  string $context What the value is for. Valid values are view and edit.
	 * @return array
	 */
	public function get_pit_ids($context = 'view')
	{
		return $this->get_prop('pit_ids', $context);
	}

	/**
	 * Set post IDs.
	 *
	 * @param array $post_ids IDs from the up-sell products.
	 */
	public function set_pit_ids($pit_ids)
	{
		$this->set_prop('pit_ids', array_filter((array) $pit_ids));
	}
}