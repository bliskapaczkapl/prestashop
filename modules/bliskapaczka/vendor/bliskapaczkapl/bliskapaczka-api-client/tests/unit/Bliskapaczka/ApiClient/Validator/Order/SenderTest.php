<?php

namespace Bliskapaczka\ApiClient\Validator\Order;

use Bliskapaczka\ApiClient\ValidatorInterface;
use Bliskapaczka\ApiClient\Validator\Order\Sender;
use PHPUnit\Framework\TestCase;

class SenderTest extends TestCase
{
    protected function setUp()
    {
        $this->orderData = [
            "senderFirstName" => "string",
            "senderLastName" => "string",
            "senderPhoneNumber" => "606555433",
            "senderEmail" => "bob@example.com",
            "senderStreet" => "string",
            "senderBuildingNumber" => "string",
            "senderFlatNumber" => "string",
            "senderPostCode" => "54-130",
            "senderCity" => "string"
        ];
    }

    public function testClassExists()
    {
        $this->assertTrue(class_exists('Bliskapaczka\ApiClient\Validator\Order\Sender'));
    }

    public function testClassImplementInterface()
    {
        $order = new Sender();
        $this->assertTrue(is_a($order, 'Bliskapaczka\ApiClient\ValidatorInterface'));
    }

    /**
     * @expectedException Bliskapaczka\ApiClient\Exception
     * @expectedExceptionMessageRegExp /Invalid \w+/
     */
    public function testSenderPostCodeShouldntbeValidated()
    {
        $this->orderData['senderPostCode'] = 'string';

        $order = new Sender();
        $order->setData($this->orderData);
        $order->validate();
    }

    public function testSenderPostCodeIfEmptyOrNotSet()
    {
        $this->orderData['senderPostCode'] = '';

        $order = new Sender();
        $order->setData($this->orderData);
        $order->validate();

        unset($this->orderData['senderPostCode']);

        $order = new Sender();
        $order->setData($this->orderData);
        $order->validate();
    }

    public function testSenderPhoneNumberIfEmptyOrNotSet()
    {
        $this->orderData['senderPhoneNumber'] = '';

        $order = new Sender();
        $order->setData($this->orderData);
        $order->validate();

        unset($this->orderData['senderPhoneNumber']);

        $order = new Sender();
        $order->setData($this->orderData);
        $order->validate();
    }

    public function testSenderEmailIfEmptyOrNotSet()
    {
        $this->orderData['senderEmail'] = '';

        $order = new Sender();
        $order->setData($this->orderData);
        $order->validate();

        unset($this->orderData['senderEmail']);

        $order = new Sender();
        $order->setData($this->orderData);
        $order->validate();
    }

    public function testSenderCityIfEmptyOrNotSet()
    {
        $this->orderData['senderCity'] = '';

        $order = new Sender();
        $order->setData($this->orderData);
        $order->validate();

        unset($this->orderData['senderCity']);

        $order = new Sender();
        $order->setData($this->orderData);
        $order->validate();
    }


    public function testReceiverPhoneNumberShouldntbeValidated()
    {
        $this->orderData['receiverPhoneNumber'] = 'string';

        $order = new Sender();
        $order->setData($this->orderData);
        $this->assertTrue($order->validate());
    }

    public function testReceiverPostCodeShouldntbeValidated()
    {
        $this->orderData['receiverPostCode'] = 'string';

        $order = new Sender();
        $order->setData($this->orderData);
        $order->validate();
    }

    public function testReceiverFirstNameShouldntbeValidated()
    {
        $this->orderData['receiverFirstName'] = '';

        $order = new Sender();
        $order->setData($this->orderData);
        $order->validate();
    }
}
