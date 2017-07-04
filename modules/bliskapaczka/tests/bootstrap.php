<?php
//Set custom memory limit
ini_set('memory_limit', '512M');
ini_set('error_reporting', E_ALL);

$GLOBALS['ROOT_DIR'] = dirname(__FILE__) . '/../../..';

require_once $GLOBALS['ROOT_DIR'] . '/vendor/autoload.php';

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
