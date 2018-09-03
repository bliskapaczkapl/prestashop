<?php
/**
 * Created by PhpStorm.
 * User: pawel
 * Date: 21.08.18
 * Time: 08:13
 */
namespace Bliskapaczka\Prestashop\Core\Command\Exception;

/**
 * Class CommandIsNotObjectException
 *
 * @package Bliskapaczka\Prestashop\Core\Command\Exception
 */
class CommandIsNotObjectException extends \Exception
{

    /**
     * CommandIsNotObjectException constructor.
     */
    public function __construct()
    {
        parent::__construct("Command is not object", 0);
    }
}
