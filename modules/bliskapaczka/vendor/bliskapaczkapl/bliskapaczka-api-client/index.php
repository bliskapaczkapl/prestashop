<?php

require __DIR__ . '/vendor/autoload.php';

$apiKey = 'e5814ba2-2dc6-4f31-bfa9-2afd4bc171a9';
$apiClient = new Bliskapaczka\ApiClient\Bliskapaczka\Order($apiKey, 'test');

$orderData = [
    "senderFirstName" => "string",
    "senderLastName" => "string",
    "senderPhoneNumber" => "606555433",
    "senderEmail" => "bob@example.com",
    "senderStreet" => "string",
    "senderBuildingNumber" => "string",
    "senderFlatNumber" => "string",
    "senderPostCode" => "54-130",
    "senderCity" => "string",
    "receiverFirstName" => "string",
    "receiverLastName" => "string",
    "receiverPhoneNumber" => "600555432",
    "receiverEmail" => "eva@example.com",
    "operatorName" => "INPOST",
    "destinationCode" => "KRA010",
    "postingCode" => "KOS01L",
    "codValue" => null,
    "insuranceValue" => null,
    "additionalInformation" => "string",
    "parcel" => [
        "dimensions" => [
            "height" => 20,
            "length" => 20,
            "width" => 20,
            "weight" => 2
        ]
    ]
];
var_dump($apiClient->create($orderData));

$apiClient = new Bliskapaczka\ApiClient\Bliskapaczka\Pricing($apiKey, 'test');

$pricingData = [
    "parcel" => [
        "dimensions" => [
            "height" => 20,
            "length" => 20,
            "width" => 20,
            "weight" => 2
        ]
    ]
];
var_dump($apiClient->get($pricingData));

$apiClient = new Bliskapaczka\ApiClient\Bliskapaczka\Pricing\Todoor($apiKey, 'test');

$pricingData = [
    "parcel" => [
        "dimensions" => [
            "height" => 20,
            "length" => 20,
            "width" => 20,
            "weight" => 2
        ]
    ]
];
var_dump($apiClient->get($pricingData));

$apiClient = new Bliskapaczka\ApiClient\Bliskapaczka\Order\Waybill($apiKey, 'test');
$apiClient->setOrderId('000000636P-000000108');

var_dump($apiClient->get());

$apiClient = new Bliskapaczka\ApiClient\Bliskapaczka\Report($apiKey, 'test');
$apiClient->setOperator('ruch');

file_put_contents('zupa.pdf', $apiClient->get());
