<?php
namespace WodorNet\MotoTripBundle\Tests\Entity;

use WodorNet\MotoTripBundle\Entity\Trip;
use WodorNet\MotoTripBundle\Entity\TripSignup;
use WodorNet\MotoTripBundle\Entity\User;

class UserTest extends \PHPUnit_Framework_TestCase
{

    public function testIsCandidateForTrip() {

        $user = new User();
        $trip = new Trip();

        $tripSignup = new TripSignup();
        $tripSignup->setTrip($trip);
        $tripSignup->setStatus(TripSignup::STATUS_NEW);

        $user->addTrip($trip);

        $this->assertTrue($user->isCandidateForTrip($trip));

        $this->assertFalse($user->isCandidateForTrip(new Trip()));

    }


}