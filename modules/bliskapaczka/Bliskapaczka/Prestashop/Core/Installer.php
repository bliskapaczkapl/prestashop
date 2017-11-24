<?php

namespace Bliskapaczka\Prestashop\Core;

/**
 * Manage data in proccess installation and uninstallation module
 */
class Installer
{
    private $config = null;

    /**
     * Constructor
     *
     * @param array $config
     */
    public function __construct($config)
    {
        $this->config = $config;
    }

    /**
     * Insert data to db
     *
     * @param Bliskapaczka\Prestashop\Core\Helper $helper
     * @return bool
     */
    public function install($helper)
    {
        if ($this->installCarrier($helper) == false ||
            $this->updateCartAndOrder() == false
        ) {
            return false;
        }

        return true;
    }

    /**
     * Delete module data from db
     *
     * @param int $id_carrier
     * @return bool
     */
    public function uninstall($id_carrier)
    {
        $carrier = new \Carrier($id_carrier);

        if (!$carrier->delete()) {
            return false;
        }

        return true;
    }

    /**
     * Add new carrier
     *
     * @param Bliskapaczka\Prestashop\Core\Helper $helper
     * @return bool
     */
    private function installCarrier($helper)
    {
        $carrier = new \Carrier();
        $carrier->name = $this->config->name;
        $carrier->id_tax_rules_group = 0;
        $carrier->active = true;
        $carrier->deleted = false;

        foreach (\Language::getLanguages(true) as $language) {
            $carrier->delay[(int)$language['id_lang']] = $this->config->delay;
        }
        
        $carrier->is_free = false;
        $carrier->shipping_method = 1;
        $carrier->shipping_handling = true;
        $carrier->shipping_external = true;
        $carrier->is_module = true;
        $carrier->external_module_name = $this->config->name;
        $carrier->need_range = true;
        $carrier->range_behavior = false;
        $carrier->grade = 0;

        if (!$carrier->add()) {
            return false;
        }

        \Configuration::updateValue($helper::BLISKAPACZKA_CARRIER_ID, (int)$carrier->id);

        $this->rangesAndFeeForZone($carrier->id);

        return true;
    }

    /**
     * Configure shipping
     *
     * @param int $id_carrier
     */
    private function rangesAndFeeForZone($id_carrier)
    {
        $range_price = new \RangePrice();
        $range_price->id_carrier = $id_carrier;
        $range_price->delimiter1 = '0';
        $range_price->delimiter2 = '500';
        $range_price->add();

        $range_weight = new \RangeWeight();
        $range_weight->id_carrier = $id_carrier;
        $range_weight->delimiter1 = '0';
        $range_weight->delimiter2 = '100';
        $range_weight->add();

        $groups = \Group::getGroups(true);
        foreach ($groups as $group) {
            \Db::getInstance()->autoExecute(
                _DB_PREFIX_ . 'carrier_group',
                array('id_carrier' => (int)$id_carrier, 'id_group' => (int)$group['id_group']),
                'INSERT'
            );
        }

        \Db::getInstance()->autoExecute(
            _DB_PREFIX_ . 'carrier_zone',
            array('id_carrier' => (int)$id_carrier, 'id_zone' => 1),
            'INSERT'
        );

        \Db::getInstance()->autoExecuteWithNullValues(
            _DB_PREFIX_ . 'delivery',
            array(
                'id_carrier' => (int)$id_carrier,
                'id_range_price' => (int)$range_price->id,
                'id_range_weight' => null,
                'id_zone' => 1,
                'price' => '10'
            ),
            'INSERT'
        );

        \Db::getInstance()->autoExecuteWithNullValues(
            _DB_PREFIX_ . 'delivery',
            array(
                'id_carrier' => (int)$id_carrier,
                'id_range_price' => null,
                'id_range_weight' => (int)$range_weight->id,
                'id_zone' => 1,
                'price' => '10'
            ),
            'INSERT'
        );
    }

    /**
     * Add new columns to order and cart tables
     *
     * @return bool
     */
    private function updateCartAndOrder()
    {
        $tables = array(
            'orders',
            'cart'
        );

        $columns = array(
            'pos_code',
            'pos_operator'
        );

        foreach ($tables as $table) {
            foreach ($columns as $column) {
                //If column 'orders.$column' does not exist, create it
                \Db::getInstance(_PS_USE_SQL_SLAVE_)->query(
                    'SHOW COLUMNS FROM `' . _DB_PREFIX_ . $table . '` LIKE "' . $column . '"'
                );
                if (\Db::getInstance()->NumRows() == 0) {
                    \Db::getInstance()->execute(
                        'ALTER TABLE `' . _DB_PREFIX_ . $table . '` ADD `' . $column . '` VARCHAR( 128 ) DEFAULT NULL'
                    );
                }
            }
        }

        return true;
    }
}
