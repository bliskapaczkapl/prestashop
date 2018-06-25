<?php

namespace Bliskapaczka\ApiClient\Bliskapaczka\Todoor;

use Bliskapaczka\ApiClient\Bliskapaczka\Todoor;
use Bliskapaczka\ApiClient\BliskapaczkaInterface;
use Bliskapaczka\ApiClient\AbstractBliskapaczka;

/**
 * Bliskapaczka class
 *
 * @author  Mateusz Koszutowski (mkoszutowski@divante.pl)
 * @version 0.1.0
 */
class Advice extends Todoor implements BliskapaczkaInterface
{
    const REQUEST_URL = 'order/advice/todoor';

    /**
     * Return end of url for request
     */
    public function getUrl()
    {
        return static::REQUEST_URL;
    }
}
