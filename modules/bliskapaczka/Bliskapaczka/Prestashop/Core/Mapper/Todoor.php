<?php
namespace Bliskapaczka\Prestashop\Core\Mapper;

/**
 * Class to map order data to data acceptable by Sendit Bliskapaczka API
 */
class Todoor extends MapperAbstract
{
    /**
     * Prepare mapped data
     *
     * @param  Order                               $order
     * @param  Address                             $shippingAddress
     * @param  Customer                            $customer
     * @param  Bliskapaczka\Prestashop\Core\Helper $helper
     * @param  \Configuration                      $configuration
     * @return array
     */
    public function getData($order, $shippingAddress, $customer, $helper, $configuration)
    {
        $data = [];

        $data['receiverFirstName'] = $shippingAddress->firstname;
        $data['receiverLastName'] = $shippingAddress->lastname;
        $data['receiverPhoneNumber'] = $helper->telephoneNumberCleaning($shippingAddress->phone_mobile);
        $data['receiverEmail'] = $customer->email;

        $street = preg_split("/\s+(?=\S*+$)/", $shippingAddress->address1);

        $data['receiverStreet'] = $street[0];
        $data['receiverBuildingNumber'] = isset($street[1]) ? $street[1] : '';
        $data['receiverFlatNumber'] = isset($shippingAddress->address2) ? $shippingAddress->address2 : '';
        $data['receiverPostCode'] = $shippingAddress->postcode;
        $data['receiverCity'] = $shippingAddress->city;

        $data['operatorName'] = $order->pos_operator;
        $data['destinationCode'] = $order->pos_code;

        $data['parcel'] = [
            'dimensions' => $this->getParcelDimensions($helper)
        ];

        $data = $this->prepareSenderData($data, $helper, $configuration);

        return $data;
    }
}
