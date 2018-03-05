<?php

namespace Bliskapaczka\ApiClient\Bliskapaczka;

use Bliskapaczka\ApiClient\BliskapaczkaInterface;
use Bliskapaczka\ApiClient\AbstractBliskapaczka;

/**
 * Bliskapaczka class
 *
 * @author  Mateusz Koszutowski (mkoszutowski@divante.pl)
 * @version 0.1.0
 */
class Order extends AbstractBliskapaczka implements BliskapaczkaInterface
{
    const REQUEST_URL = 'order';

    /**
     * Call API method create order
     *
     * @param array $data
     * @return json $response
     */
    public function create(array $data)
    {
        $this->validate($data);

        $response = $this->doCall($this->getUrl(), json_encode($data), array(), 'POST');

        return $response;
    }

    /**
     * Validate data
     *
     * @param array $data
     * @return bool
     */
    public function validate(array $data)
    {
        $validator = $this->getValidator();
        $validator->setData($data);
        $validator->validate();
    }
}
