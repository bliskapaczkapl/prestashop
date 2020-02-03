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
        $data = $this->prepareDestinationData($data, $shippingAddress, $helper, $customer);

        $data['operatorName'] = $order->pos_operator;
        $data['deliveryType'] = 'D2D';
        if ($order->pos_operator == 'POCZTA') {
            $data['deliveryType'] = 'P2D';
        }
        $data['parcel'] = [
            'dimensions' => $this->getParcelDimensions($helper)
        ];

        if ($order->is_cod == 1) {
            $data['codValue'] = $order->total_paid + $order->total_shipping;
            $data = $this->prepareInsuranceDataIfNeeded($order, $data);
        }

        $data = $this->prepareSenderData($data, $helper, $configuration);

        return $data;
    }
}
