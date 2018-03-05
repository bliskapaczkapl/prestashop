<?php

namespace Bliskapaczka\ApiClient;

use Psr\Log\LoggerInterface;
use Bliskapaczka\ApiClient\Exception;

/**
 * Bliskapaczka class
 *
 * @author  Mateusz Koszutowski (mkoszutowski@divante.pl)
 * @version 0.1.0
 */
abstract class AbstractValidator
{
    const PHONE_NUMBER_PATTERN = '/^(5[0137]|6[069]|7[2389]|88)\d{7}$/';

    /**
     * Set data to validata
     *
     * @param array $data
     */
    public function setData(array $data)
    {
        $this->data = $data;
    }

    /**
     * Basic validation for data
     */
    protected function validationByProperty()
    {
        foreach ($this->properties as $property => $settings) {
            if (!isset($this->data[$property])) {
                continue;
            }

            $this->notBlank($property, $settings);
            $this->maxLength($property, $settings);
            $this->specificValidation($property);
        }
    }

    /**
     * Set of specific method for order validation
     *
     * @param string $property
     */
    protected function specificValidation($property)
    {
        switch ($property) {
            case 'senderEmail':
            case 'receiverEmail':
                self::email($this->data[$property]);
                break;
            case 'senderPhoneNumber':
            case 'receiverPhoneNumber':
                self::phone($this->data[$property]);
                break;
            case 'senderPostCode':
            case 'receiverPostCode':
                self::postCode($this->data[$property]);
                break;
            case 'parcel':
                self::parcel($this->data[$property]);
                break;
        }
    }

    /**
     * Validation for not blank properties
     *
     * @param string $property
     * @param array $settings
     */
    protected function notBlank($property, $settings)
    {
        if (isset($settings['notblank'])
            && isset($settings['notblank']) === true
            && (is_null($this->data[$property]) || strlen($this->data[$property]) == 0)
        ) {
            throw new Exception('Invalid ' . $property, 1);
        }
    }

    /**
     * Validation of lenght
     *
     * @param string $property
     * @param array $settings
     */
    protected function maxLength($property, $settings)
    {
        if (isset($settings['maxlength'])
            && $settings['maxlength'] > 0
            && strlen($this->data[$property]) > $settings['maxlength']
        ) {
            throw new Exception('Invalid ' . $property, 1);
        }
    }

    /**
     * Validate email address
     *
     * @param string $data
     */
    public static function email($data)
    {
        if (filter_var($data, FILTER_VALIDATE_EMAIL) == false) {
            throw new \Bliskapaczka\ApiClient\Exception('Invalid email', 1);
        }

        return true;
    }

    /**
     * Validate phone number
     *
     * @param string $data
     */
    public static function phone($data)
    {
        preg_match(self::PHONE_NUMBER_PATTERN, $data, $phoneNumberMatches);

        if (!is_array($phoneNumberMatches) || count($phoneNumberMatches) == 0) {
            throw new \Bliskapaczka\ApiClient\Exception('Invalid phone number', 1);
        }

        return true;
    }

    /**
     * Validate postcode
     *
     * @param string $data
     */
    public static function postCode($data)
    {
        preg_match('/^\d{2}\-\d{3}$/', $data, $matches);

        if (!is_array($matches) || count($matches) == 0) {
            throw new \Bliskapaczka\ApiClient\Exception('Invalid post code', 1);
        }

        return true;
    }

    /**
     * Validate parcel
     *
     * @param array $data
     */
    public static function parcel($data)
    {
        if (!is_array($data) || !array_key_exists('dimensions', $data)) {
            throw new Exception('Invalid parcel', 1);
        }

        $dimensions = ['height', 'length', 'width', 'weight'];

        # Parcel dimesnsions should be graten than 0
        foreach ($dimensions as $dimension) {
            if ($data['dimensions'][$dimension] <= 0) {
                throw new \Bliskapaczka\ApiClient\Exception('Dimesnion must be greater than 0', 1);
            }
        }
    }
}
