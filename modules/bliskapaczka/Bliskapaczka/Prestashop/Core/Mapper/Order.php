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
        $data['deliveryType'] = 'P2P';
        if ($data['operatorName'] === 'FEDEX') {
            $data['deliveryType'] = 'D2P';
        }
        $data['parcel'] = [
            'dimensions' => $this->getParcelDimensions($helper)
        ];

        if ($order->is_cod == 1) {
            $data['codValue'] = $order->total_paid + $order->total_shipping;
        }
        if ($data['operatorName'] === 'FEDEX') {
            $data['deliveryType'] = 'D2P';
            $data = $this->prepareDestinationData($data, $shippingAddress, $helper, $customer);
        }

        $data = $this->prepareSenderData($data, $helper, $configuration);

        return $data;
    }
}
