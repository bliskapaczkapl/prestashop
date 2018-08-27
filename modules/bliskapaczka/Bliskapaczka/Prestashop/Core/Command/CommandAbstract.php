<?php
/**
 * Created by PhpStorm.
 * User: pawel
 * Date: 21.08.18
 * Time: 09:13
 */

namespace Bliskapaczka\Prestashop\Core\Command;

/**
 * Class CommandAbstract
 *
 * @package Bliskapaczka\Prestashop\Core\Command
 */
abstract class CommandAbstract
{
    /**
 * @var \Order
*/
    protected $order;

    /**
     * CommandAbstract constructor.
     *
     * @param \Order $order
     */
    public function __construct(\Order $order)
    {
        $this->order = $order;
    }

    /**
     * @return Order
     */
    public function getOrder()
    {
        return $this->order;
    }
}
