<?php

if (!defined('_PS_VERSION_'))
	exit;

function upgrade_module_1_0_3($module)
{
    $config = new Bliskapaczka\Prestashop\Core\Config();

    $carrier = new \Carrier();
    $carrier->name = $config->courier_name;
    $carrier->id_tax_rules_group = 0;
    $carrier->active = true;
    $carrier->deleted = false;

    foreach (\Language::getLanguages(true) as $language) {
        $carrier->delay[(int)$language['id_lang']] = $config->delay;
    }

    $carrier->is_free = false;
    $carrier->shipping_method = 1;
    $carrier->shipping_handling = true;
    $carrier->shipping_external = true;
    $carrier->is_module = true;
    $carrier->external_module_name = $config->name;
    $carrier->need_range = true;
    $carrier->range_behavior = false;
    $carrier->grade = 0;

    $idCarrier = Db::getInstance()->getValue(
        'SELECT id_carrier FROM `ps_carrier` WHERE name = \'' . $config->courier_name . '\';'
    );

    if (!$idCarrier) {
        if (!$carrier->add()) {
            return false;
        }

        \Configuration::updateValue(\Bliskapaczka\Prestashop\Core\Helper::BLISKAPACZKA_COURIER_CARRIER_ID, (int)$carrier->id);

        // Fix testes
        $carrier->setTaxRulesGroup((int)$carrier->id_tax_rules_group);

        rangesAndFeeForZone($carrier->id);
    }

    return true;
}

/**
 * Configure shipping
 *
 * @param int $id_carrier
 */
function rangesAndFeeForZone($id_carrier)
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
