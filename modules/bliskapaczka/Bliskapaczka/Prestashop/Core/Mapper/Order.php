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
     * @param Order $order
     * @param Address $shippingAddress
     * @param Customer $customer
     * @param Bliskapaczka\Prestashop\Core\Helper $helper
     * @return array
     */
    public function getData($order, $shippingAddress, $customer, $helper)
    {
        $data = [];

        $data['receiverFirstName'] = $shippingAddress->firstname;
        $data['receiverLastName'] = $shippingAddress->lastname;
        $data['receiverPhoneNumber'] = $helper->telephoneNumberCeaning($shippingAddress->phone_mobile);
        $data['receiverEmail'] = $customer->email;

        $data['operatorName'] = $order->pos_operator;
        $data['destinationCode'] = $order->pos_code;

        $data['parcel'] = [
            'dimensions' => $this->getParcelDimensions($helper)
        ];

        $data = $this->prepareSenderData($data, $helper);

        return $data;
    }

    /**
     * Get parcel dimensions in format accptable by Bliskapaczka API
     *
     * @param \Bliskapaczka\Prestashop\Core\$helper $helper
     * @return array
     */
    private function getParcelDimensions($helper)
    {
        return $helper->getParcelDimensions();
    }

    /**
     * Prepare sender data in fomrat accptable by Bliskapaczka API
     *
     * @param array $data
     * @param \Bliskapaczka\Prestashop\Core\$helper $helper
     * @return array
     * @SuppressWarnings(PHPMD.NPathComplexity)
     * @SuppressWarnings(PHPMD.CyclomaticComplexity)
     */
    protected function prepareSenderData($data, $helper)
    {
        if (\Configuration::get($helper::SENDER_EMAIL)) {
            $data['senderEmail'] = \Configuration::get($helper::SENDER_EMAIL);
        }

        if (\Configuration::get($helper::SENDER_FIRST_NAME)) {
            $data['senderFirstName'] = \Configuration::get($helper::SENDER_FIRST_NAME);
        }

        if (\Configuration::get($helper::SENDER_LAST_NAME)) {
            $data['senderLastName'] = \Configuration::get($helper::SENDER_LAST_NAME);
        }

        if (\Configuration::get($helper::SENDER_PHONE_NUMBER)) {
            $data['senderPhoneNumber'] = $helper->telephoneNumberCeaning(
                \Configuration::get($helper::SENDER_PHONE_NUMBER)
            );
        }

        if (\Configuration::get($helper::SENDER_STREET)) {
            $data['senderStreet'] = \Configuration::get($helper::SENDER_STREET);
        }

        if (\Configuration::get($helper::SENDER_BUILDING_NUMBER)) {
            $data['senderBuildingNumber'] = \Configuration::get($helper::SENDER_BUILDING_NUMBER);
        }

        if (\Configuration::get($helper::SENDER_FLAT_NUMBER)) {
            $data['senderFlatNumber'] = \Configuration::get($helper::SENDER_FLAT_NUMBER);
        }

        if (\Configuration::get($helper::SENDER_POST_CODE)) {
            $data['senderPostCode'] = \Configuration::get($helper::SENDER_POST_CODE);
        }

        if (\Configuration::get($helper::SENDER_CITY)) {
            $data['senderCity'] = \Configuration::get($helper::SENDER_CITY);
        }

        return $data;
    }
}
