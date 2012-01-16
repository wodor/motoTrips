<?php
namespace WodorNet\MotoTripBundle\Tests\Entity;

use WodorNet\MotoTripBundle\Entity\Trip;

class TripTest extends \PHPUnit_Framework_TestCase
{


    public function GetDurationProvider() {

        $trip = new Trip();
        $trip->setStartDate(new \DateTime("2012-01-01 12:10"));
        $trip->setEndDate(new \DateTime("2012-01-01 18:10"));

        $exp[] = array($trip, new \DateInterval("PT6H"));

        return $exp;
    }

    /**
     * @dataProvider GetDurationProvider
     */
    public function  testGetDuration($trip, \DateInterval $interval) {

        $this->assertEquals($trip->getDuration()->format('%R%h'), $interval->format('%R%h'));

    }
}

/**
 * do przetestowania
 *
 * wywołanie zdarzen przy zapisie
 * wykonanie zdarzen
 *
 */