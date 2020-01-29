<?php
/**
 * Created by PhpStorm.
 * User: pawel
 * Date: 20.08.18
 * Time: 16:15
 */

namespace Bliskapaczka\Prestashop\Core\Command;

use Bliskapaczka\Prestashop\Core\Command\Exception\CommandIsNotExistInHandlersException;
use Bliskapaczka\Prestashop\Core\Command\Exception\CommandIsNotObjectException;
use Bliskapaczka\Prestashop\Core\Command\Exception\HandlerIsNoObjectException;
use Bliskapaczka\Prestashop\Core\Command\Exception\HandlersDoesNotHaveHandleMethod;

/**
 * Class CommandBus
 *
 * @package Bliskapaczka\Prestashop\Core\Command
 */
class CommandBus implements CommandBusInterface
{
    /**
 * @var array
*/
    protected $handlers = array();

    /**
     * @param string $commandClass
     * @param object $handler
     * @throws HandlerIsNoObjectException
     * @throws HandlersDoesNotHaveHandleMethod
     */
    public function registerHandler($commandClass, $handler)
    {
        if (!is_object($handler)) {
            throw new HandlerIsNoObjectException();
        }

        if (!method_exists($handler, 'handle')) {
            throw new HandlersDoesNotHaveHandleMethod();
        }

        $this->handlers[$commandClass] = $handler;
    }

    /**
     * @param object $command
     * @throws CommandIsNotExistInHandlersException
     * @throws CommandIsNotObjectException
     */
    public function handle($command)
    {
        if (!is_object($command)) {
            throw new CommandIsNotObjectException();
        }
        if (!array_key_exists(get_class($command), $this->handlers)) {
            throw new CommandIsNotExistInHandlersException();
        }

        try {
            $this->handlers[get_class($command)]->handle($command);
        } catch (\Exception $exception) {
            PrestaShopLogger::addLog($exception);
        }

    }
}
