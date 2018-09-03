<?php
/**
 * Created by PhpStorm.
 * User: pawel
 * Date: 27.08.18
 * Time: 09:14
 */
namespace Bliskapaczka\Prestashop\Core\Command\Exception;

/**
 * Class CarrierNameException
 *
 * @package Bliskapaczka\Prestashop\Core\Command\Exception
 */
class CarrierNameException extends \Exception
{
    /**
     * CarrierNameException constructor.
     */
    public function __construct()
    {
        parent::__construct('Bliskapaczka: Can\'t find carrier method name', 0);
    }
}
