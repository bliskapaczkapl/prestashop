<?php
namespace Bliskapaczka\Prestashop\Core\Mapper;

/**
 * Class to map order data to data acceptable by Sendit Bliskapaczka API
 */
class Order
{
    /**
     * Prepare mapped data
     *
     * @param Mage_Sales_Model_Order $order
     */
    public function getData(\Order $order)
    {
        $data = [];

        $shippingAddress = new \Address((int)$order->id_address_delivery);
        $customer = new \Customer((int)$order->id_customer);

        $data['receiverFirstName'] = $shippingAddress->firstname;
        $data['receiverLastName'] = $shippingAddress->lastname;
        $data['receiverPhoneNumber'] = $shippingAddress->phone_mobile;
        $data['receiverEmail'] = $customer->email;

        $data['operatorName'] = $order->pos_operator;
        $data['destinationCode'] = $order->pos_code;

        $data['parcel'] = [
            'dimensions' => $this->getParcelDimensions()
        ];

        return $data;
    }

    /**
     * Get parcel dimensions in format accptable by Bliskapaczka API
     *
     * @return array
     */
    private function getParcelDimensions()
    {
        $helper = new \Bliskapaczka\Prestashop\Core\Hepler();
        return $helper->getParcelDimensions();
    }
}
