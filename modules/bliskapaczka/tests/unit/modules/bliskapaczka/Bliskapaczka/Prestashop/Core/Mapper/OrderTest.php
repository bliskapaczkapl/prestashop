<?php

namespace Bliskapaczka\Prestashop\Core\Mapper;

use PHPUnit\Framework\TestCase;

class OrderTest extends TestCase
{

    protected function setUp()
    {
        $this->receiverFirstName = 'Zenek';
        $this->receiverLastName = 'Bliskopaczki';
        $this->receiverPhoneNumber = '504 445 665';
        $this->receiverEmail = 'zenek.bliskopaczki@sendit.pl';
        $this->operatorName = 'INPOST';
        $this->destinationCode = 'KRA010';

        $this->addressMock = $this->getMockBuilder(\Address::class)
                                    ->disableOriginalConstructor()
                                    ->disableOriginalClone()
                                    ->disableArgumentCloning()
                                    ->disallowMockingUnknownTypes()
                                    ->setMethods(array())
                                    ->getMock();

        $this->addressMock->firstname = $this->receiverFirstName;
        $this->addressMock->lastname = $this->receiverLastName;
        $this->addressMock->phone_mobile = $this->receiverPhoneNumber;

        $this->orderMock = $this->getMockBuilder(Order::class)
                                     ->disableOriginalConstructor()
                                     ->disableOriginalClone()
                                     ->disableArgumentCloning()
                                     ->disallowMockingUnknownTypes()
                                     ->setMethods(array())
                                     ->getMock();

        $this->orderMock->pos_operator = $this->operatorName;
        $this->orderMock->pos_code = $this->destinationCode;

        $this->customerMock = $this->getMockBuilder(\Customer::class)
                                     ->disableOriginalConstructor()
                                     ->disableOriginalClone()
                                     ->disableArgumentCloning()
                                     ->disallowMockingUnknownTypes()
                                     ->setMethods(array())
                                     ->getMock();

        $this->customerMock->email = $this->receiverEmail;
    }

    public function testClassExists()
    {
        $this->assertTrue(class_exists('\Bliskapaczka\Prestashop\Core\Mapper\Order'));
    }

    public function testTypeOfReturnedData()
    {
        $mapper = new \Bliskapaczka\Prestashop\Core\Mapper\Order();
        $data = $mapper->getData($this->orderMock, $this->addressMock, $this->customerMock);

        $this->assertTrue(is_array($data));
    }

    // public function testMapperForReceiverFirstName()
    // {
    //     $mapper = new Sendit_Bliskapaczka_Model_Mapper_Order();
    //     $data = $mapper->getData($this->orderMock);

    //     $this->assertEquals($this->receiverFirstName, $data['receiverFirstName']);
    // }

    // public function testMapperForReceiverLastName()
    // {
    //     $mapper = new Sendit_Bliskapaczka_Model_Mapper_Order();
    //     $data = $mapper->getData($this->orderMock);

    //     $this->assertEquals($this->receiverLastName, $data['receiverLastName']);
    // }

    // public function testMapperForReceiverPhoneNumber()
    // {
    //     $mapper = new Sendit_Bliskapaczka_Model_Mapper_Order();
    //     $data = $mapper->getData($this->orderMock);

    //     $this->assertEquals($this->receiverPhoneNumber, $data['receiverPhoneNumber']);
    // }

    // public function testMapperForReceiverEmail()
    // {
    //     $mapper = new Sendit_Bliskapaczka_Model_Mapper_Order();
    //     $data = $mapper->getData($this->orderMock);

    //     $this->assertEquals($this->receiverEmail, $data['receiverEmail']);
    // }

    // public function testMapperForOperatorName()
    // {
    //     $mapper = new Sendit_Bliskapaczka_Model_Mapper_Order();
    //     $data = $mapper->getData($this->orderMock);

    //     $this->assertEquals($this->operatorName, $data['operatorName']);
    // }

    // public function testMapperForDestinationCode()
    // {
    //     $mapper = new Sendit_Bliskapaczka_Model_Mapper_Order();
    //     $data = $mapper->getData($this->orderMock);

    //     $this->assertEquals($this->destinationCode, $data['destinationCode']);
    // }

    // public function testMapperForParcel()
    // {
    //     $mapper = new Sendit_Bliskapaczka_Model_Mapper_Order();
    //     $data = $mapper->getData($this->orderMock);

    //     $this->assertTrue(is_array($data['parcel']));
    // }
}
