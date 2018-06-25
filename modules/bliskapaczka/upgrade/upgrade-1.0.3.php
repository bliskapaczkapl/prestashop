<?php

// @codingStandardsIgnoreFile
if (!defined('_PS_VERSION_')) {
    exit;
}

function upgrade_module_1_0_3($module)
{
    $config = new Bliskapaczka\Prestashop\Core\Config();

    /* @var Bliskapaczka\Prestashop\Core\Helper $bliskapaczkaHelper */
    $bliskapaczkaHelper = new Bliskapaczka\Prestashop\Core\Helper();

    /* @var Bliskapaczka\Prestashop\Core\Installer */
    $installer = new Bliskapaczka\Prestashop\Core\Installer($config);

    if ($installer->addCourier($bliskapaczkaHelper) == false) {
        return false;
    }

    return true;
}
