<?php
namespace Bliskapaczka\Prestashop\Core\Mapper;

use Bliskapaczka\Prestashop\Core\Helper;

/**
 * Class to map order data to data acceptable by Sendit Bliskapaczka API
 */
class Order extends MapperAbstract
{
    /**
     * Prepare mapped data
     *
     * @param  \Order         $order
     * @param  \Address       $shippingAddress
     * @param  \Customer      $customer
     * @param  Helper         $helper
     * @param  \Configuration $configuration
     * @return array
     */
    public function getData($order, $shippingAddress, $customer, $helper, $configuration)
    {
        $data = [];

        $data['receiverFirstName'] = $shippingAddress->firstname;
        $data['receiverLastName'] = $shippingAddress->lastname;
        $data['receiverPhoneNumber'] = $helper->telephoneNumberCleaning($shippingAddress->phone_mobile);
        $data['receiverEmail'] = $customer->email;

        $data['operatorName'] = $order->pos_operator;
        $data['destinationCode'] = $order->pos_code;

        $data['parcel'] = [
            'dimensions' => $this->getParcelDimensions($helper)
        ];

        $data = $this->prepareSenderData($data, $helper, $configuration);

        return $data;
    }
}
