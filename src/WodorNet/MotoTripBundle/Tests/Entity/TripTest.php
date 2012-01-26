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

    public function StartDateBeforeEndDateProvider() {
        $ret[] = array(new \DateTime("2012-01-01 12:10"), new \DateTime("2012-01-01 18:10"), true);
        $ret[] = array(new \DateTime("2012-01-01 12:10"), new \DateTime("2012-01-01 12:10"), false);
        $ret[] = array(new \DateTime("2012-01-01 12:10"),new \DateTime("2012-01-01 10:10") , false);
        return $ret;
    }

    /**
     * @dataProvider StartDateBeforeEndDateProvider
     * @param \WodorNet\MotoTripBundle\Entity\Trip $trip
     * @param $expectedResult
     */
    public function testStartDateBeforeEndDate(\DateTime $startDate, \DateTime $endDate, $expectedResult) {
        $trip = new Trip();
        $trip->setStartDate($startDate);
        $trip->setEndDate($endDate);
        $this->assertEquals($expectedResult, $trip->isStartDateBeforeEndDate());
    }
}

/**
 * do przetestowania
 *
 * wywołanie zdarzen przy zapisie
 * wykonanie zdarzen
 *
 */