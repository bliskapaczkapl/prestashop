<?php
namespace Bliskapaczka\Prestashop\Core\Mapper;

use \Bliskapaczka\Prestashop\Core\Hepler;

/**
 * Class to map order data to data acceptable by Sendit Bliskapaczka API
 */
class Order
{
    /**
     * Prepare mapped data
     *
     * @param Order $order
     */
    public function getData($order, $shippingAddress, $customer)
    {
        $data = [];

        $data['receiverFirstName'] = $shippingAddress->firstname;
        $data['receiverLastName'] = $shippingAddress->lastname;
        $data['receiverPhoneNumber'] = $shippingAddress->phone_mobile;
        $data['receiverEmail'] = $customer->email;

        $data['operatorName'] = $order->pos_operator;
        $data['destinationCode'] = $order->pos_code;

        $data['parcel'] = [
            'dimensions' => $this->getParcelDimensions()
        ];

        $data = $this->prepareSenderData($data);

        return $data;
    }

    /**
     * Get parcel dimensions in format accptable by Bliskapaczka API
     *
     * @return array
     */
    private function getParcelDimensions()
    {
        $helper = new Hepler();
        return $helper->getParcelDimensions();
    }

    /**
     * Prepare sender data in fomrat accptable by Bliskapaczka API
     *
     * @param array $data
     * @return array
     * @SuppressWarnings(PHPMD.NPathComplexity)
     * @SuppressWarnings(PHPMD.CyclomaticComplexity)
     */
    protected function prepareSenderData($data)
    {
        if (\Configuration::get(Hepler::SENDER_EMAIL)) {
            $data['senderEmail'] = \Configuration::get(Hepler::SENDER_EMAIL);
        }

        if (\Configuration::get(Hepler::SENDER_FIRST_NAME)) {
            $data['senderFirstName'] = \Configuration::get(Hepler::SENDER_FIRST_NAME);
        }

        if (\Configuration::get(Hepler::SENDER_LAST_NAME)) {
            $data['senderLastName'] = \Configuration::get(Hepler::SENDER_LAST_NAME);
        }

        if (\Configuration::get(Hepler::SENDER_PHONE_NUMBER)) {
            $data['senderPhoneNumber'] = \Configuration::get(Hepler::SENDER_PHONE_NUMBER);
        }

        if (\Configuration::get(Hepler::SENDER_STREET)) {
            $data['senderStreet'] = \Configuration::get(Hepler::SENDER_STREET);
        }

        if (\Configuration::get(Hepler::SENDER_BUILDING_NUMBER)) {
            $data['senderBuildingNumber'] = \Configuration::get(Hepler::SENDER_BUILDING_NUMBER);
        }

        if (\Configuration::get(Hepler::SENDER_FLAT_NUMBER)) {
            $data['senderFlatNumber'] = \Configuration::get(Hepler::SENDER_FLAT_NUMBER);
        }

        if (\Configuration::get(Hepler::SENDER_POST_CODE)) {
            $data['senderPostCode'] = \Configuration::get(Hepler::SENDER_POST_CODE);
        }

        if (\Configuration::get(Hepler::SENDER_CITY)) {
            $data['senderCity'] = \Configuration::get(Hepler::SENDER_CITY);
        }

        return $data;
    }
}
