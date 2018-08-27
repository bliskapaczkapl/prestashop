<?php
/**
 * Created by PhpStorm.
 * User: pawel
 * Date: 21.08.18
 * Time: 08:56
 */

namespace Bliskapaczka\Prestashop\Core\Command;

/**
 * Class UpdateCommandHandler
 *
 * @package Bliskapaczka\Prestashop\Core\Command
 */
class UpdateCommandHandler extends CommandHandlerAbstract
{

    /**
     * @param CommandAbstract $command
     * @throws \Exception
     */
    public function handle(CommandAbstract $command)
    {
        $this->apiClient->setOrderId($command->getOrder()->number);

        $response = $this->apiClient->get();

        $decodedResponse = json_decode($response);

        $properResponse = $decodedResponse instanceof stdClass && empty($decodedResponse->errors);

        if (!$response && !$properResponse) {
            $message = ($decodedResponse ? current($decodedResponse->errors)->message : '');
            throw new \Exception(sprintf("Bliskapaczka: Error or empty API response %s", $message));
        }
        $command->getOrder()->number = strip_tags($decodedResponse->number);
        $command->getOrder()->status = strip_tags($decodedResponse->status);
        $command->getOrder()->delivery_type = strip_tags($decodedResponse->deliveryType);
        $command->getOrder()->creation_date = strip_tags($decodedResponse->creationDate);
        $command->getOrder()->advice_date = strip_tags($decodedResponse->adviceDate);
        $command->getOrder()->tracking_number = strip_tags($decodedResponse->trackingNumber);
        $command->getOrder()->save();
    }
}
