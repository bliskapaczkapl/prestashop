<?php
/**
 * Created by PhpStorm.
 * User: pawel
 * Date: 21.08.18
 * Time: 08:55
 */

namespace Bliskapaczka\Prestashop\Core\Command;

use Bliskapaczka\Prestashop\Core\Command\Exception\CarrierNameException;

/**
 * Class AdviceCommand
 *
 * @package Bliskapaczka\Prestashop\Core\Command
 */
class AdviceCommand extends CommandAbstract
{
    /**
 * @var \Caririer
*/
    protected $carrier;

    /**
     * AdviceCommand constructor.
     *
     * @param  \Order $order
     * @throws CarrierNameException
     */
    public function __construct(\Order $order)
    {
        parent::__construct($order);
        $this->carrier = new \Carrier($order->id_carrier, $order->id_lang);
        if (!in_array($this->carrier->name, array('bliskapaczka', 'bliskapaczka_courier'))) {
            throw new CarrierNameException();
        }
    }

    /**
     * @return \Caririer
     */
    public function getCarrier()
    {
        return $this->carrier;
    }
}
