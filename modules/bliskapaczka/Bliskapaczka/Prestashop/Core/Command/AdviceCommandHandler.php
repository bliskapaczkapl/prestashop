<?php
/**
 * Created by PhpStorm.
 * User: pawel
 * Date: 21.08.18
 * Time: 13:11
 */

namespace Bliskapaczka\Prestashop\Core\Command;

/**
 * Class AdviceCommandHandler
 *
 * @package Bliskapaczka\Prestashop\Core\Command
 */
class AdviceCommandHandler
{
    /**
     * @param AdviceCommand $command
     * @throws \Exception
     */
    public function handle(AdviceCommand $command)
    {
        /* @var \Bliskapaczka\Prestashop\Core\Helper $bliskapaczkaHelper */
        $bliskapaczkaHelper = new \Bliskapaczka\Prestashop\Core\Helper();

        $mapper = $this->getMapper($command->getCarrier()->name);

        $shippingAddress = new \Address((int)$command->getOrder()->id_address_delivery);
        $customer = new \Customer((int)$command->getOrder()->id_customer);
        $configuration = new \Configuration();

        $data = $mapper->getData(
            $command->getOrder(),
            $shippingAddress,
            $customer,
            $bliskapaczkaHelper,
            $configuration
        );
        $apiClient = $bliskapaczkaHelper->getApiClientForAdvice($command->getCarrier()->name);
        $apiClient->setOrderId($command->getOrder()->number);
        $response = $apiClient->create($data);

        $decodedResponse = json_decode($response);

        $properResponse = $decodedResponse instanceof stdClass && empty($decodedResponse->errors);
        if (!$response && !$properResponse) {
            $message = ($decodedResponse ? current($decodedResponse->errors)->message : '');
            throw new \Exception(sprintf("Bliskapaczka: Error or empty API response %s", $message));
        }

        $command->getOrder()->status = strip_tags($decodedResponse->status);
        $command->getOrder()->advice_date = strip_tags($decodedResponse->adviceDate);
        $command->getOrder()->save();
    }


    /**
     * Get Mapper instance for shipping method
     *
     * @param  string $method
     * @return \Bliskapaczka\Prestashop\Core\Mapper\Order|\Bliskapaczka\Prestashop\Core\Mapper\Todoor
     */
    protected function getMapper($method)
    {
        if ($method == 'bliskapaczka') {
            /* @var \Bliskapaczka\Prestashop\Core\Mapper\Order $mapper */
            $mapper = new \Bliskapaczka\Prestashop\Core\Mapper\Order();
        }

        if ($method == 'bliskapaczka_courier') {
            /* @var \Bliskapaczka\Prestashop\Core\Mapper\Todoor $mapper */
            $mapper = new \Bliskapaczka\Prestashop\Core\Mapper\Todoor();
        }

        return $mapper;
    }
}
