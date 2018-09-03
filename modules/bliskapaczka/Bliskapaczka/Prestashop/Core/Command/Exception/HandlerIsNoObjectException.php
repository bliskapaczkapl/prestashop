<?php
/**
 * Created by PhpStorm.
 * User: pawel
 * Date: 20.08.18
 * Time: 16:21
 */
namespace Bliskapaczka\Prestashop\Core\Command\Exception;

/**
 * Class HandlerIsNoObjectException
 *
 * @package Bliskapaczka\Prestashop\Core\Command\Exception
 */
class HandlerIsNoObjectException extends \Exception
{
    /**
     * HandlerIsNoObjectException constructor.
     */
    public function __construct()
    {
        $message = 'Handler is not object';
        parent::__construct($message, 0);
    }
}
