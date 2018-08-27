<?php
/**
 * Created by PhpStorm.
 * User: pawel
 * Date: 27.08.18
 * Time: 15:32
 */
namespace Bliskapaczka\Prestashop\Core\Mapper;

/**
 * Class MapperAbstract
 * @package Bliskapaczka\Prestashop\Core\Mapper
 */
abstract class MapperAbstract
{

    /**
     * Get parcel dimensions in format accptable by Bliskapaczka API
     *
     * @param  \Bliskapaczka\Prestashop\Core\ $helper $helper
     * @return array
     */
    protected function getParcelDimensions($helper)
    {
        return $helper->getParcelDimensions();
    }

    /**
     * Prepare sender data in fomrat accptable by Bliskapaczka API
     *
     * @param                                        array                          $data
     * @param                                        \Bliskapaczka\Prestashop\Core\ $helper        $helper
     * @param                                        \Configuration                 $configuration
     * @return                                       array
     * @SuppressWarnings(PHPMD.NPathComplexity)
     * @SuppressWarnings(PHPMD.CyclomaticComplexity)
     */
    public function prepareSenderData($data, $helper, $configuration)
    {
        if ($configuration::get($helper::SENDER_EMAIL)) {
            $data['senderEmail'] = $configuration::get($helper::SENDER_EMAIL);
        }

        if ($configuration::get($helper::SENDER_FIRST_NAME)) {
            $data['senderFirstName'] = $configuration::get($helper::SENDER_FIRST_NAME);
        }

        if ($configuration::get($helper::SENDER_LAST_NAME)) {
            $data['senderLastName'] = $configuration::get($helper::SENDER_LAST_NAME);
        }

        if ($configuration::get($helper::SENDER_PHONE_NUMBER)) {
            $data['senderPhoneNumber'] = $helper->telephoneNumberCleaning(
                $configuration::get($helper::SENDER_PHONE_NUMBER)
            );
        }

        if ($configuration::get($helper::SENDER_STREET)) {
            $data['senderStreet'] = $configuration::get($helper::SENDER_STREET);
        }

        if ($configuration::get($helper::SENDER_BUILDING_NUMBER)) {
            $data['senderBuildingNumber'] = $configuration::get($helper::SENDER_BUILDING_NUMBER);
        }

        if ($configuration::get($helper::SENDER_FLAT_NUMBER)) {
            $data['senderFlatNumber'] = $configuration::get($helper::SENDER_FLAT_NUMBER);
        }

        if ($configuration::get($helper::SENDER_POST_CODE)) {
            $data['senderPostCode'] = $configuration::get($helper::SENDER_POST_CODE);
        }

        if ($configuration::get($helper::SENDER_CITY)) {
            $data['senderCity'] = $configuration::get($helper::SENDER_CITY);
        }

        return $data;
    }
}
