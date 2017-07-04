<?php
/*
* 2007-2016 PrestaShop
*
* NOTICE OF LICENSE
*
* This source file is subject to the Open Software License (OSL 3.0)
* that is bundled with this package in the file LICENSE.txt.
* It is also available through the world-wide-web at this URL:
* http://opensource.org/licenses/osl-3.0.php
* If you did not receive a copy of the license and are unable to
* obtain it through the world-wide-web, please send an email
* to license@prestashop.com so we can send you a copy immediately.
*
* DISCLAIMER
*
* Do not edit or add to this file if you wish to upgrade PrestaShop to newer
* versions in the future. If you wish to customize PrestaShop for your
* needs please refer to http://www.prestashop.com for more information.
*
*  @author PrestaShop SA <contact@prestashop.com>
*  @copyright  2007-2016 PrestaShop SA
*  @license	http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
*  International Registered Trademark & Property of PrestaShop SA
*/

class Order extends OrderCore
{
    /**
     * Point code
     */
    public $pos_code;

    /**
     * Operator name
     */
    public $pos_operator;

    /**
     * @see ObjectModel::$definition
     */
    public static $definition = array(
        'table' => 'orders',
        'primary' => 'id_order',
        'fields' => array(
            'id_address_delivery' => array('type' => self::TYPE_INT, 'validate' => 'isUnsignedId', 'required' => true),
            'id_address_invoice' => array('type' => self::TYPE_INT, 'validate' => 'isUnsignedId', 'required' => true),
            'id_cart' => array('type' => self::TYPE_INT, 'validate' => 'isUnsignedId', 'required' => true),
            'id_currency' => array('type' => self::TYPE_INT, 'validate' => 'isUnsignedId', 'required' => true),
            'id_shop_group' => array('type' => self::TYPE_INT, 'validate' => 'isUnsignedId'),
            'id_shop' => array('type' => self::TYPE_INT, 'validate' => 'isUnsignedId'),
            'id_lang' => array('type' => self::TYPE_INT, 'validate' => 'isUnsignedId', 'required' => true),
            'id_customer' => array('type' => self::TYPE_INT, 'validate' => 'isUnsignedId', 'required' => true),
            'id_carrier' => array('type' => self::TYPE_INT, 'validate' => 'isUnsignedId', 'required' => true),
            'current_state' => array('type' => self::TYPE_INT, 'validate' => 'isUnsignedId'),
            'secure_key' => array('type' => self::TYPE_STRING, 'validate' => 'isMd5'),
            'payment' => array('type' => self::TYPE_STRING, 'validate' => 'isGenericName', 'required' => true),
            'module' => array('type' => self::TYPE_STRING, 'validate' => 'isModuleName', 'required' => true),
            'recyclable' => array('type' => self::TYPE_BOOL, 'validate' => 'isBool'),
            'gift' => array('type' => self::TYPE_BOOL, 'validate' => 'isBool'),
            'gift_message' => array('type' => self::TYPE_STRING, 'validate' => 'isMessage'),
            'mobile_theme' => array('type' => self::TYPE_BOOL, 'validate' => 'isBool'),
            'total_discounts' => array('type' => self::TYPE_FLOAT, 'validate' => 'isPrice'),
            'total_discounts_tax_incl' => array('type' => self::TYPE_FLOAT, 'validate' => 'isPrice'),
            'total_discounts_tax_excl' => array('type' => self::TYPE_FLOAT, 'validate' => 'isPrice'),
            'total_paid' => array('type' => self::TYPE_FLOAT, 'validate' => 'isPrice', 'required' => true),
            'total_paid_tax_incl' => array('type' => self::TYPE_FLOAT, 'validate' => 'isPrice'),
            'total_paid_tax_excl' => array('type' => self::TYPE_FLOAT, 'validate' => 'isPrice'),
            'total_paid_real' => array('type' => self::TYPE_FLOAT, 'validate' => 'isPrice', 'required' => true),
            'total_products' => array('type' => self::TYPE_FLOAT, 'validate' => 'isPrice', 'required' => true),
            'total_products_wt' => array('type' => self::TYPE_FLOAT, 'validate' => 'isPrice', 'required' => true),
            'total_shipping' => array('type' => self::TYPE_FLOAT, 'validate' => 'isPrice'),
            'total_shipping_tax_incl' =>  array('type' => self::TYPE_FLOAT, 'validate' => 'isPrice'),
            'total_shipping_tax_excl' => array('type' => self::TYPE_FLOAT, 'validate' => 'isPrice'),
            'carrier_tax_rate' => array('type' => self::TYPE_FLOAT, 'validate' => 'isFloat'),
            'total_wrapping' => array('type' => self::TYPE_FLOAT, 'validate' => 'isPrice'),
            'total_wrapping_tax_incl' => array('type' => self::TYPE_FLOAT, 'validate' => 'isPrice'),
            'total_wrapping_tax_excl' => array('type' => self::TYPE_FLOAT, 'validate' => 'isPrice'),
            'round_mode' => array('type' => self::TYPE_INT, 'validate' => 'isUnsignedId'),
            'round_type' => array('type' => self::TYPE_INT, 'validate' => 'isUnsignedId'),
            'shipping_number' => array('type' => self::TYPE_STRING, 'validate' => 'isTrackingNumber'),
            'conversion_rate' => array('type' => self::TYPE_FLOAT, 'validate' => 'isFloat', 'required' => true),
            'invoice_number' => array('type' => self::TYPE_INT),
            'delivery_number' => array('type' => self::TYPE_INT),
            'invoice_date' => array('type' => self::TYPE_DATE),
            'delivery_date' => array('type' => self::TYPE_DATE),
            'valid' => array('type' => self::TYPE_BOOL),
            'reference' => array('type' => self::TYPE_STRING),
            'date_add' => array('type' => self::TYPE_DATE, 'validate' => 'isDate'),
            'date_upd' => array('type' => self::TYPE_DATE, 'validate' => 'isDate'),
            'pos_code' => array('type' => self::TYPE_STRING),
            'pos_operator' => array('type' => self::TYPE_STRING),
        ),
    );
}
