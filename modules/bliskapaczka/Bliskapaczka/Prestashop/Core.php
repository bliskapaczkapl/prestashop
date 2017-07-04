<?php
namespace Bliskapaczka\Prestashop;

/**
 * Bliskapaczka Core class
 */
class Core
{
    public static $NAMESPACE = 'Bliskapaczka';
    public static $BASEDIR = '';

    public static function autoloader($class)
    {
        if (preg_match('#^(Bliskapaczka\\\\ApiClient)\b#', $class)) {
            $libDir = _PS_CORE_DIR_ . '/vendor/bliskapaczka/bliskapaczka-api-client/src/';
            $filePath = $libDir . str_replace('\\', '/', $class) . '.php';
        }

        if (preg_match('#^(Bliskapaczka\\\\Prestashop)\b#', $class)) {
            $filePath = _PS_MODULE_DIR_ . 'bliskapaczka/' . str_replace('\\', '/', $class) . '.php';
        }

        if (isset($filePath) && is_file($filePath)) {
            // @codingStandardsIgnoreStart
            require_once($filePath);
            // @codingStandardsIgnoreEnd
        }
    }
}

spl_autoload_register('Bliskapaczka\Prestashop\Core::autoloader', true, true);
