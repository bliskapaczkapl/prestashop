<?php
/**
 * Created by PhpStorm.
 * User: pawel
 * Date: 21.08.18
 * Time: 09:06
 */

namespace Bliskapaczka\Prestashop\Core\Command;

use Bliskapaczka\ApiClient\BliskapaczkaInterface;

/**
 * Class CommandHandlerAbstract
 *
 * @package Bliskapaczka\Prestashop\Core\Command
 */
abstract class CommandHandlerAbstract
{

    /**
 * @var BliskapaczkaInterface
*/
    protected $apiClient;

    /**
     * CommandHandlerAbstract constructor.
     *
     * @param BliskapaczkaInterface $apiClient
     */
    public function __construct(BliskapaczkaInterface $apiClient)
    {
        $this->apiClient = $apiClient;
    }

    /**
     * @param CommandAbstract $command
     * @return void
     */
    abstract public function handle(CommandAbstract $command);
}
