<?php
//Set custom memory limit
ini_set('memory_limit', '512M');
ini_set('error_reporting', E_ALL);

$GLOBALS['ROOT_DIR'] = dirname(__FILE__) . '/../../..';

//Define include path for Pseudo Mocks
ini_set('include_path', ini_get('include_path') . PATH_SEPARATOR . dirname(__FILE__) . '/pseudo_mock');

// Load Pseudo Mocks
require_once 'Address.php';
require_once 'Configuration.php';
require_once 'Customer.php';
require_once 'Order.php';

require_once $GLOBALS['ROOT_DIR'] . '/modules/bliskapaczka/vendor/autoload.php';

function autoloader($class)
{
    if (preg_match('#^(Bliskapaczka\\\\Prestashop)\b#', $class)) {
        $filePath = $GLOBALS['ROOT_DIR'] . '/modules/bliskapaczka/' . str_replace('\\', '/', $class) . '.php';
        // @codingStandardsIgnoreStart
        require_once($filePath);
        // @codingStandardsIgnoreEnd
    }
}

spl_autoload_register('autoloader');
