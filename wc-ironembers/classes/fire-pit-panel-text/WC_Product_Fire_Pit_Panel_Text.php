<?php
/**
 * Created by PhpStorm.
 * User: marc
 * Date: 2021-03-30
 * Time: 3:08 PM
 */
if (class_exists('WC_Product_Fire_Pit_Panel_Text')) return;
class WC_Product_Fire_Pit_Panel_Text extends WC_Product
{
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
		return 'fire-pit-panel-text';
	}


}