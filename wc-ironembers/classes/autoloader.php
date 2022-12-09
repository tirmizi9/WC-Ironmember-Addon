<?php
/**
 * Created by PhpStorm.
 * User: marc
 * Date: 2021-03-12
 * Time: 2:21 PM
 */

require_once __DIR__ . '/WC_IronEmbers_Data_Store_CPT.php';

require_once __DIR__ . '/fire-pit/WC_Product_Fire_Pit.php';
require_once __DIR__ . '/fire-pit/WC_Product_Fire_Pit_Ajax.php';
require_once __DIR__ . '/fire-pit/WC_Product_Fire_Pit_Data_Store_CPT.php';
require_once __DIR__ . '/fire-pit/WC_Product_Fire_Pit_Initializer.php';
require_once __DIR__ . '/fire-pit/WC_Product_Fire_Pit_Tabs.php';
require_once __DIR__ . '/fire-pit/WC_Product_Fire_Pit_AccessoriesList.php';
require_once __DIR__ . '/fire-pit/WC_Product_Fire_Pit_Meta_CustomName.php';

require_once __DIR__ . '/fire-pit-accessories/WC_Product_Fire_Pit_Accessory.php';
require_once __DIR__ . '/fire-pit-accessories/WC_Product_Fire_Pit_Accessory_Data_Store_CPT.php';
require_once __DIR__ . '/fire-pit-accessories/WC_Product_Fire_Pit_Accessory_Initializer.php';
require_once __DIR__ . '/fire-pit-accessories/WC_Product_Fire_Pit_Accessory_Tabs.php';

require_once __DIR__ . '/fire-pit-panel-text/WC_Product_Fire_Pit_Panel_Text.php';
require_once __DIR__ . '/fire-pit-panel-text/WC_Product_Fire_Pit_Panel_Text_Initializer.php';
require_once __DIR__ . '/fire-pit-panel-text/WC_Product_Fire_Pit_Panel_Text_Form.php';

require_once __DIR__ . '/currency/WC_IronEmbers_Currency_Exchange.php';
require_once __DIR__ . '/currency/providers/WC_IronEmbers_Currency_Exchange_CurrConv.php';
require_once __DIR__ . '/currency/providers/WC_IronEmbers_Currency_Exchange_ExchangeRatesAPI.php';

require_once __DIR__ . '/WC_Payment_Order_Inquiry.php';