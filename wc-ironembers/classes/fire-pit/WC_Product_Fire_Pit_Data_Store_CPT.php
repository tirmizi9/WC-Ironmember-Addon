<?php
/**
 * Created by PhpStorm.
 * User: marc
 * Date: 2021-03-12
 * Time: 3:01 PM
 */
if (class_exists('WC_Product_Fire_Pit_Data_Store_CPT')) return;
class WC_Product_Fire_Pit_Data_Store_CPT extends WC_IronEmbers_Data_Store_CPT implements WC_Object_Data_Store_Interface
{
	/**
	 * Meta keys and how they transfer to CRUD props.
	 *
	 * @var array
	 */
	private $meta_key_to_props = array(
		'_post_ids' => 'post_ids',
		'_accessory_ids' => 'accessory_ids',
		'_production_addon_ids' => 'production_addon_ids',
	);
}
