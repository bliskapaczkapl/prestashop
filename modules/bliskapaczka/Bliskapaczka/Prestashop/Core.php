<?php

namespace Bliskapaczka\Prestashop;

/**
 * Bliskapaczka Core class
 */
class Core
{
    /**
     * Autoloader for Bliskapaczka namespace
     *
     * @param string $class
     */
    public static function autoloader($class)
    {
        if (preg_match('#^(Bliskapaczka\\\\ApiClient)\b#', $class)) {
            $libDir = _PS_MODULE_DIR_ . '/bliskapaczka/vendor/bliskapaczkapl/bliskapaczka-api-client/src/';
            $filePath = $libDir . str_replace('\\', '/', $class) . '.php';
        }

        if (preg_match('#^(Bliskapaczka\\\\Prestashop)\b#', $class)) {
            $filePath = _PS_MODULE_DIR_ . 'bliskapaczka/' . str_replace('\\', '/', $class) . '.php';
        }

        if (isset($filePath) && is_file($filePath)) {
            require_once($filePath);
        }
    }
}

spl_autoload_register('Bliskapaczka\Prestashop\Core::autoloader', true, true);
