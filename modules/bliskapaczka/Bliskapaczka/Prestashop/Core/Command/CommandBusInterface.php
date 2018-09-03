<?php
/**
 * Created by PhpStorm.
 * User: pawel
 * Date: 21.08.18
 * Time: 08:36
 */

namespace Bliskapaczka\Prestashop\Core\Command;

/**
 * Interface CommandBusInterface
 *
 * @package Bliskapaczka\Prestashop\Core\Command
 */
interface CommandBusInterface
{
    /**
     * @param mixed   $commandClass
     * @param $handler
     * @return void
     */
    public function registerHandler($commandClass, $handler);

    /**
     * @param $command
     * @return void
     */
    public function handle($command);
}
