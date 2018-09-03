<?php
/**
 * Created by PhpStorm.
 * User: pawel
 * Date: 20.08.18
 * Time: 16:29
 */
namespace Bliskapaczka\Prestashop\Core\Command\Exception;

/**
 * Class HandlersDoesNotHaveHandleMethod
 *
 * @package Bliskapaczka\Prestashop\Core\Command\Exception
 */
class HandlersDoesNotHaveHandleMethod extends \Exception
{

    /**
     * HandlersDoesNotHaveHandleMethod constructor.
     */
    public function __construct()
    {
        $message = 'Handlers does not have handle method';
        parent::__construct($message, 0);
    }
}
