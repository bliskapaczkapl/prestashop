<?php

// @codingStandardsIgnoreFile
if (!defined('_PS_VERSION_')) {
    exit;
}

function upgrade_module_1_0_1($module)
{
    $config = new Bliskapaczka\Prestashop\Core\Config();

    /* @var Bliskapaczka\Prestashop\Core\Installer */
    $installer = new Bliskapaczka\Prestashop\Core\Installer($config);

    if ($installer->addToOrderInfoAboutBliskapaczkaOrder() == false) {
        return false;
    }

    return true;
}
