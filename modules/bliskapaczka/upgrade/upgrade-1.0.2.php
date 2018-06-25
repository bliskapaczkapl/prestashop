<?php

// @codingStandardsIgnoreFile
if (!defined('_PS_VERSION_')) {
    exit;
}

function upgrade_module_1_0_2($module)
{
    $config = new Bliskapaczka\Prestashop\Core\Config();

    /* @var Bliskapaczka\Prestashop\Core\Installer */
    $installer = new Bliskapaczka\Prestashop\Core\Installer($config);

    if ($installer->addAdminPanel() == false) {
        return false;
    }

    return true;
}
