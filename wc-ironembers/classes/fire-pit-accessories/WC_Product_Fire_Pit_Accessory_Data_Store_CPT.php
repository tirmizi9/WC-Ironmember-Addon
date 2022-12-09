<?php
/**
 * Created by PhpStorm.
 * User: marc
 * Date: 2021-03-15
 * Time: 1:08 PM
 */
if (class_exists('WC_Product_Fire_Pit_Accessory_Data_Store_CPT')) return;
class WC_Product_Fire_Pit_Accessory_Data_Store_CPT extends WC_IronEmbers_Data_Store_CPT implements WC_Object_Data_Store_Interface
{
	/**
	 * Meta keys and how they transfer to CRUD props.
	 *
	 * @var array
	 */
	private $meta_key_to_props = array(
		'_pit_ids' => 'pit_ids',
	);
}