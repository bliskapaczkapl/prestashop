<?php
/**
 * Created by PhpStorm.
 * User: pawel
 * Date: 21.08.18
 * Time: 15:38
 */
namespace Bliskapaczka\Prestashop\Core\Query;

/**
 * Interface WaybillQueryInterface
 *
 * @package Bliskapaczka\Prestashop\Core\Query
 */
interface WaybillQueryInterface
{
    /**
     * @param int $orderId
     * @return WaybillView
     */
    public function getByOrderId($orderId);
}
