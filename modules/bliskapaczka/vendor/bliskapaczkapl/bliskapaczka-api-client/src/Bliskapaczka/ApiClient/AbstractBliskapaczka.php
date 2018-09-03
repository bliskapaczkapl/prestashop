<?php

namespace Bliskapaczka\ApiClient;

use Psr\Log\LoggerInterface;
use Bliskapaczka\ApiClient\ApiCaller\ApiCaller;
use Bliskapaczka\ApiClient\Mappers\Order;
use Bliskapaczka\ApiClient\Exception;

/**
 * Bliskapaczka class
 *
 * @author  Mateusz Koszutowski (mkoszutowski@divante.pl)
 */
abstract class AbstractBliskapaczka
{
    /**
     * @const Bliska paczka api version
     */
    const API_VERSION = 'v1';

    /**
     * @const URL for the api
     */
    const API_URL = 'https://api.bliskapaczka.pl';

    /**
     * @const URL for the sandbox api
     */
    const SANDBOX_API_URL = 'https://api.sandbox-bliskapaczka.pl';

    /**
     * Ending of url for specific request
     */
    const REQUEST_URL = '';

    /**
     * @var ApiCaller
    */
    private $apiCaller;

    /**
     * Create Bliskapaczka instance
     *
     * @param string $bearer
     * @param string $mode
     */
    public function __construct($bearer, $mode = 'prod')
    {
        $this->bearer = (string)$bearer;
        $this->setApiUrl((string)$this->getApiUrlForMode($mode));
        $this->logger = new Logger();
    }

    /**
     * Get API Caller
     *
     * @return ApiCaller
     */
    public function getApiCaller()
    {
        if ($this->apiCaller === null) {
            $this->apiCaller = new ApiCaller($this->logger);
        }

        return $this->apiCaller;
    }

    /**
     * Get API url for mode
     *
     * @param string $mode
     * @return string
     */
    public function getApiUrlForMode($mode)
    {
        $url = '';

        switch ($mode) {
            case 'test':
                $url = self::SANDBOX_API_URL;
                break;

            case 'prod':
                $url = self::API_URL;
                break;
        }

        return $url;
    }

    /**
     * Return API url
     *
     * @return string
     */
    public function getApiUrl()
    {
        return $this->apiUrl;
    }

    /**
     * In some cases we need other API url
     *
     * @param string $url
     */
    public function setApiUrl($url)
    {
        $this->apiUrl = $url;
    }

    /**
     * Return end of url for request
     */
    public function getUrl()
    {
        return static::REQUEST_URL;
    }

    /**
     * Get validator object for this instance
     *
     * @return ValidatorInterface
     */
    public function getValidator()
    {
        // Bliskapaczka\ApiClient\Bliskapaczka\Order
        $className = get_class($this);
        $validatorName = str_replace('\Bliskapaczka', '\Validator', $className);
    
        if (!class_exists($validatorName)) {
            throw new Exception('Validator not exists', 1);
        }

        return new $validatorName;
    }

    /**
     * Create cURL configuration and call
     *
     * @param string $url
     * @param string $body
     * @param array $headers
     * @param string $method
     * @param bool $expectXML
     */
    protected function doCall($url, $body = '', $headers = array(), $method = 'GET', $expectXML = true)
    {
        // build Authorization header
        $headers[] = 'Authorization: Bearer ' . $this->bearer;
        $headers[] = 'Content-Type: application/json';
        
        // set options
        $options[CURLOPT_URL] = $this->apiUrl . '/v1/' . $url;
        $options[CURLOPT_TIMEOUT] = 2;
        $options[CURLOPT_HTTP_VERSION] = CURL_HTTP_VERSION_1_1;
        $options[CURLOPT_HTTPHEADER] = $headers;
        
        if ($method == 'POST') {
            $options[CURLOPT_POST] = true;
            $options[CURLOPT_POSTFIELDS] = $body;
        }

        $response = $this->getApiCaller()->doCall($options);

        return $response;
    }
}
