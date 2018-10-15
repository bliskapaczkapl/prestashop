<?php

namespace Bliskapaczka\Prestashop\Core;

/**
 * Bliskapaczka helper
 *
 * @author Mateusz Koszutowski (mkoszutowski@divante.pl)
 */
class Logger
{
    /**
     * Log debug info to file
     *
     * @param string $message
     */
    public static function debug($message)
    {
        $logger = new \FileLogger(0);
        $logger->setFilename(_PS_ROOT_DIR_ . '/log/debug.log');
        $logger->logDebug($message);
    }
}
