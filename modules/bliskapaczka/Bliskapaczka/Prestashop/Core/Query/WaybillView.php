<?php
/**
 * Created by PhpStorm.
 * User: pawel
 * Date: 21.08.18
 * Time: 15:42
 */
namespace Bliskapaczka\Prestashop\Core\Query;

/**
 * Class WaybillView
 *
 * @package Bliskapaczka\Prestashop\Core\Query
 */
class WaybillView
{
    /**
 * @var string
*/
    protected $url;

    /**
     * WaybillView constructor.
     *
     * @param string $url
     */
    public function __construct($url)
    {
        $this->url = $url;
    }

    /**
     * @return string
     */
    public function getUrl()
    {
        return $this->url;
    }
}
