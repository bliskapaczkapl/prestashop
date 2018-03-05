<?php

namespace Bliskapaczka\ApiClient\Bliskapaczka;

use Bliskapaczka\ApiClient\Bliskapaczka\Report;
use PHPUnit\Framework\TestCase;

class ReportTest extends TestCase
{
    public function testClassExists()
    {
        $this->assertTrue(class_exists('Bliskapaczka\ApiClient\Bliskapaczka\Report'));
    }

    public function testGetUrl()
    {
        $apiKey = '6061914b-47d3-42de-96bf-0004a57f1dba';
        $apiUrl = 'http://localhost:1234';
        $operator = 'ruch';
        
        $apiClientReport = new Report($apiKey);
        $apiClientReport->setApiUrl($apiUrl);
        $apiClientReport->setOperator($operator);

        $this->assertEquals('report/pickupconfirmation/' . $operator, $apiClientReport->getUrl());
    }

    public function testGetUrlWithStartPeriod()
    {
        $apiKey = '6061914b-47d3-42de-96bf-0004a57f1dba';
        $apiUrl = 'http://localhost:1234';
        $operator = 'ruch';
        $date = '2017-10-23T12:00:00';
        
        $apiClientReport = new Report($apiKey);
        $apiClientReport->setApiUrl($apiUrl);
        $apiClientReport->setOperator($operator);
        $apiClientReport->setStartPeriod($date);

        $this->assertEquals(
            'report/pickupconfirmation/' . $operator . '?startPeriod=2017-10-23T12:00:00',
            $apiClientReport->getUrl()
        );

        $date = '2017-10-23';
        $apiClientReport->setStartPeriod($date);

        $this->assertEquals(
            'report/pickupconfirmation/' . $operator . '?startPeriod=2017-10-23T00:00:00',
            $apiClientReport->getUrl()
        );
    }

    public function testGet()
    {
        $apiKey = '6061914b-47d3-42de-96bf-0004a57f1dba';
        $apiUrl = 'http://localhost:1234';
        $operator = 'ruch';
        
        $apiClientReport = new Report($apiKey);
        $apiClientReport->setApiUrl($apiUrl);
        $apiClientReport->setOperator($operator);

        $apiClientReport->get();
    }
}
