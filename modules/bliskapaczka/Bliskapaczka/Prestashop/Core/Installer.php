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
     * @param  Bliskapaczka\Prestashop\Core\Helper $helper
     * @return bool
     */
    public function install($helper)
    {
        if ($this->installCarrier($helper) == false
            || $this->updateCartAndOrder() == false
            || $this->addToOrderInfoAboutBliskapaczkaOrder() == false
            || $this->addAdminPanel($helper) == false
            || $this->addCourier($helper) == false
        ) {
            return false;
        }

        return true;
    }

    /**
     * Delete module data from db
     *
     * @param  int $id_carrier
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
     * @param  Bliskapaczka\Prestashop\Core\Helper $helper
     * @return bool
     */
    private function installCarrier($helper)
    {
        $carrier = new \Carrier();
        $carrier->name = $this->config->name;
        $carrier = $this->prepareCarrierObject($carrier);

        if (!$carrier->add()) {
            return false;
        }

        \Configuration::updateValue($helper::BLISKAPACZKA_CARRIER_ID, (int)$carrier->id);

        // Fix testes
        $carrier->setTaxRulesGroup((int)$carrier->id_tax_rules_group);

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

    /**
     * Add new columns to order and cart tables
     *
     * @return bool
     */
    public function addToOrderInfoAboutBliskapaczkaOrder()
    {
        \Db::getInstance()->execute(
            'ALTER TABLE `' . _DB_PREFIX_ . 'orders`
            ADD COLUMN `number` TEXT NULL DEFAULT NULL AFTER `pos_operator`,
            ADD COLUMN `status` TEXT NULL DEFAULT NULL AFTER `number`,
            ADD COLUMN `delivery_type` TEXT NULL DEFAULT NULL AFTER `status`,
            ADD COLUMN `creation_date` DATETIME NULL DEFAULT NULL AFTER `delivery_type`,
            ADD COLUMN `advice_date` DATETIME NULL DEFAULT NULL AFTER `creation_date`,
            ADD COLUMN `tracking_number` TEXT NULL DEFAULT NULL AFTER `advice_date`;'
        );

        return true;
    }

    /**
     * Add new columns to order and cart tables
     *
     * @param  Bliskapaczka\Prestashop\Core\Helper $helper
     * @return bool
     */
    public function addAdminPanel($helper)
    {
        $tab = new \Tab();

        foreach (\Language::getLanguages() as $language) {
            $tab->name[$language['id_lang']] = 'Bliskapaczka Orders';
        }

        $tab->class_name = 'AdminOrders';
        $tab->module = $this->config->name;

        $idParent = (int)\Tab::getIdFromClassName('AdminParentOrders');
        $tab->id_parent = $idParent;
        $tab->position = \Tab::getNbTabs($idParent);

        if (!$tab->save()) {
            return false;
        }

        \Configuration::updateValue($helper::BLISKAPACZKA_TAB_ID, $tab->id);

        return true;
    }

    /**
     * Add new columns to order and cart tables
     *
     * @param  Bliskapaczka\Prestashop\Core\Helper $helper
     * @return bool
     */
    public function addCourier($helper)
    {
        $carrier = new \Carrier();
        $carrier->name = $this->config->courier_name;
        $carrier = $this->prepareCarrierObject($carrier);

        $idCarrier = \Db::getInstance()->getValue(
            'SELECT id_carrier FROM `ps_carrier` WHERE name = \'' . $this->config->courier_name . '\';'
        );

        if (!$idCarrier) {
            if (!$carrier->add()) {
                return false;
            }

            \Configuration::updateValue($helper::BLISKAPACZKA_COURIER_CARRIER_ID, (int)$carrier->id);

            // Fix testes
            $carrier->setTaxRulesGroup((int)$carrier->id_tax_rules_group);

            $this->rangesAndFeeForZone($carrier->id);
        }

        return true;
    }

    /**
     * @param \Carrier $carrier
     * @return \Carrier
     */
    protected function prepareCarrierObject(\Carrier $carrier)
    {
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
        return $carrier;
    }
    /**
     * Delete columns from order
     *
     * @return bool
     */
    public function deleteToOrderInfoAboutBliskapaczkaOrder()
    {
        \Db::getInstance()->execute(
            'ALTER TABLE `' . _DB_PREFIX_ . 'orders`
            DROP COLUMN `number`,
            DROP COLUMN `status`,
            DROP COLUMN `delivery_type`,
            DROP COLUMN `creation_date`,
            DROP COLUMN `advice_date`,
            DROP COLUMN `tracking_number`;'
        );

        return true;
    }

    /**
     * Delete records from tab and tab_lang
     *
     * @return bool
     */
    public function deleteAdminPanel()
    {
        $teb = new \Carrier($id_carrier);

        if (!$carrier->delete()) {
            return false;
        }

        return true;
    }

    /**
     * Delete courier carrier from db
     *
     * @param  int $id_tab
     * @return bool
     */
    public function deleteCourier($id_tab)
    {
        $tab = new \Tab($id_tab);

        if (!$tab->delete()) {
            return false;
        }

        return true;
    }
}
