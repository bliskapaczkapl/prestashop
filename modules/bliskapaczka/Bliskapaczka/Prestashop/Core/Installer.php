<?php

namespace Bliskapaczka\Prestashop\Core;

/**
 * Manage data in proccess installation and uninstallation module
 */
class Installer
{
    private $config = null;

    public function __construct($config)
    {
        $this->config = $config;
    }

    /**
     * Insert data to db
     *
     * @return bool
     */
    public function install()
    {
        if ($this->installCarrier() == false ||
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
     * @return bool
     */
    private function installCarrier()
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
        $carrier->range_behavior = true;
        $carrier->grade = 0;

        if (!$carrier->add()) {
            return false;
        }

        return true;
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
