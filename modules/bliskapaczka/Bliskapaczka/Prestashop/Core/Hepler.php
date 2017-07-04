<?php

namespace Bliskapaczka\Prestashop\Core;

use Bliskapaczka\ApiClient;

/**
 * Bliskapaczka helper
 *
 * @author Mateusz Koszutowski (mkoszutowski@divante.pl)
 */
class Hepler
{
    const SIZE_TYPE_FIXED_SIZE_X = 'BLISKAPACZKA_PARCEL_SIZE_TYPE_FIXED_SIZE_X';
    const SIZE_TYPE_FIXED_SIZE_Y = 'BLISKAPACZKA_PARCEL_SIZE_TYPE_FIXED_SIZE_Y';
    const SIZE_TYPE_FIXED_SIZE_Z = 'BLISKAPACZKA_PARCEL_SIZE_TYPE_FIXED_SIZE_Z';
    const SIZE_TYPE_FIXED_SIZE_WEIGHT = 'BLISKAPACZKA_PARCEL_SIZE_TYPE_FIXED_SIZE_WEIGHT';
    
    const API_KEY = 'BLISKAPACZKA_API_KEY';
    const TEST_MODE = 'BLISKAPACZKA_TEST_MODE';

    /**
     * Get parcel dimensions in format accptable by Bliskapaczka API
     *
     * @return array
     */
    public function getParcelDimensions()
    {
        $height = \Configuration::get(self::SIZE_TYPE_FIXED_SIZE_X);
        $length = \Configuration::get(self::SIZE_TYPE_FIXED_SIZE_Y);
        $width = \Configuration::get(self::SIZE_TYPE_FIXED_SIZE_Z);
        $weight = \Configuration::get(self::SIZE_TYPE_FIXED_SIZE_WEIGHT);

        $dimensions = array(
            "height" => $height,
            "length" => $length,
            "width" => $width,
            "weight" => $weight
        );

        return $dimensions;
    }

    /**
     * Get lowest price from pricing list
     *
     * @param array $priceList - price list
     * @param bool taxInc - return price with tax
     * @return float
     */
    public function getLowestPrice($priceList, $taxInc = true)
    {
        $lowestPriceTaxExc = null;
        $lowestPriceTaxInc = null;

        foreach ($priceList as $carrier) {
            if ($carrier->availabilityStatus == false) {
                continue;
            }

            if ($lowestPriceTaxInc == null || $lowestPriceTaxInc > $carrier->price->gross) {
                $lowestPriceTaxExc = $carrier->price->net;
                $lowestPriceTaxInc = $carrier->price->gross;
            }
        }

        if ($taxInc) {
            $lowestPrice = $lowestPriceTaxInc;
        } else {
            $lowestPrice = $lowestPriceTaxExc;
        }

        return $lowestPrice;
    }

    /**
     * Get price for specific carrier
     *
     * @param array $priceList
     * @param string $carrierName
     * @return float
     */
    public function getPriceForCarrier($priceList, $carrierName, $taxInc = true)
    {
        $price = null;

        foreach ($priceList as $carrier) {
            if ($carrier->operatorName == $carrierName) {
                if ($taxInc) {
                    $price = $carrier->price->gross;
                } else {
                    $price = $carrier->price->net;
                }
            }
        }

        return $price;
    }

    /**
     * Get prices from pricing list
     *
     * @param array $priceList
     * @return array
     */
    public function getPrices($priceList)
    {
        $prices = array();

        foreach ($priceList as $carrier) {
            if ($carrier->price == null) {
                continue;
            }

            $prices[$carrier->operatorName] = $carrier->price->gross;
        }

        return $prices;
    }

    /**
     * Get disabled operators from pricing list
     *
     * @param array $priceList
     * @return array
     */
    public function getDisabledOperators($priceList)
    {
        $disabled = array();

        foreach ($priceList as $carrier) {
            if ($carrier->availabilityStatus == false) {
                $disabled[] = $carrier->operatorName;
            }
        }

        return $disabled;
    }

    /**
     * Get prices in format accptable by Bliskapaczka Widget
     *
     * @return string
     */
    public function getPricesForWidget()
    {
        $apiClient = $this->getApiClient();
        $priceList = $apiClient->getPricing(
            array("parcel" => array('dimensions' => $this->getParcelDimensions()))
        );

        $pricesJson = json_encode($this->getPrices(json_decode($priceList)));

        return $pricesJson;
    }

    /**
     * Get disabled operators in format accptable by Bliskapaczka Widget
     *
     * @return array
     */
    public function getDisabledOperatorsForWidget()
    {
        $apiClient = $this->getApiClient();
        $priceList = $apiClient->getPricing(
            array("parcel" => array('dimensions' => $this->getParcelDimensions()))
        );

        $disabledArray = $this->getDisabledOperators(json_decode($priceList));

        return $disabledArray;
    }

    /**
     * Get Bliskapaczka API Client
     *
     * @return \Bliskapaczka\ApiClient\Bliskapaczka
     */
    public function getApiClient()
    {
        $apiClient = new \Bliskapaczka\ApiClient\Bliskapaczka(
            \Configuration::get(self::API_KEY),
            $this->getApiMode('1')
        );

        return $apiClient;
    }

    /**
     * Get API mode
     *
     * @return string
     */
    public function getApiMode($configValue = '')
    {
        $mode = '';

        switch ($configValue) {
            case '1':
                $mode = 'test';
                break;

            default:
                $mode = 'prod';
                break;
        }

        return $mode;
    }
}
