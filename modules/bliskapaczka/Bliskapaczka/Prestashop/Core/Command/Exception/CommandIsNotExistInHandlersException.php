<?php
/**
 * Created by PhpStorm.
 * User: pawel
 * Date: 21.08.18
 * Time: 08:29
 */
namespace Bliskapaczka\Prestashop\Core\Command\Exception;

/**
 * Class CommandIsNotExistInHandlersException
 *
 * @package Bliskapaczka\Prestashop\Core\Command\Exception
 */
class CommandIsNotExistInHandlersException extends \Exception
{

    /**
     * CommandIsNotExistInHandlersException constructor.
     */
    public function __construct()
    {
        parent::__construct("Command is not exist in handlers", 0);
    }
}
