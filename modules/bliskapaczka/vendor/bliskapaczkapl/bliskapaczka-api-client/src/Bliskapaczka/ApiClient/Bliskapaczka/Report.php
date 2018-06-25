<?php

namespace Bliskapaczka\ApiClient\Bliskapaczka;

use Bliskapaczka\ApiClient\BliskapaczkaInterface;
use Bliskapaczka\ApiClient\AbstractBliskapaczka;
use Bliskapaczka\ApiClient\Exception;

/**
 * Bliskapaczka class
 *
 * @author  Mateusz Koszutowski (mkoszutowski@divante.pl)
 * @version 0.1.0
 */
class Report extends AbstractBliskapaczka implements BliskapaczkaInterface
{
    const REQUEST_URL = 'report/pickupconfirmation/[[operator]]';

    /**
     * Set order id
     *
     * @param string $operator
     */
    public function setOperator($operator)
    {
        $this->operator = $operator;
    }

    /**
     * Set start period date
     *
     * @param string $date
     */
    public function setStartPeriod($date)
    {
        $date = strtotime($date);
        $dateFormated = date('Y-m-d\TH:i:s', $date);

        $this->startPeriod = $dateFormated;
    }

    /**
     * Set end period date
     *
     * @param string $date
     */
    public function setEndPeriod($date)
    {
        $date = strtotime($date);
        $dateFormated = date('Y-m-d\TH:i:s', $date);

        $this->endPeriod = $dateFormated;
    }

    /**
     * Return valid URL for API call get waybill for order
     *
     * @return string
     */
    public function getUrl()
    {
        if (!isset($this->operator) || empty($this->operator)) {
            throw new  Exception('Please set valid operator name', 1);
        }

        $url = str_replace('[[operator]]', $this->operator, self::REQUEST_URL);

        if (isset($this->startPeriod)) {
            $url .= '?startPeriod=' . $this->startPeriod;

            if (isset($this->endPeriod)) {
                $url .= '&endPeriod=' . $this->endPeriod;
            }
        }

        return $url;
    }

    /**
     * Call API method create order
     *
     * @param array $data
     */
    public function get()
    {
        $response = $this->doCall($this->getUrl(), json_encode(''), array(), 'GET');

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
        // $validator = $this->getValidator();
        // $validator->setData($data);
        // $validator->validate();
    }
}
