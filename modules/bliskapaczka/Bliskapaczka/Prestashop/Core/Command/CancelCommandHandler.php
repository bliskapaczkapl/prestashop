<?php
/**
 * Created by PhpStorm.
 * User: pawel
 * Date: 21.08.18
 * Time: 08:55
 */

namespace Bliskapaczka\Prestashop\Core\Command;

/**
 * Class CancelCommandHandler
 *
 * @package Bliskapaczka\Prestashop\Core\Command
 */
class CancelCommandHandler extends CommandHandlerAbstract
{

    /**
     * @param CommandAbstract $command
     * @throws \Exception
     */
    public function handle(CommandAbstract $command)
    {
        $this->apiClient->setOrderId($command->getOrder()->number);

        $response = $this->apiClient->cancel();

        $decodedResponse = json_decode($response);

        $properResponse = $decodedResponse instanceof stdClass && empty($decodedResponse->errors);
        if (!$response && !$properResponse) {
            $message = ($decodedResponse ? current($decodedResponse->errors)->message : '');
            throw new \Exception(sprintf("Bliskapaczka: Error or empty API response %s", $message));
        }
        $command->getOrder()->status = strip_tags($decodedResponse->status);
        $command->getOrder()->save();
    }
}
