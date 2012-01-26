<?php
namespace WodorNet\MotoTripBundle\Tests\Entity;

use Doctrine\Common\Collections\ArrayCollection;

use WodorNet\MotoTripBundle\Entity\Trip;
use WodorNet\MotoTripBundle\Entity\TripSignup;
use WodorNet\MotoTripBundle\Entity\User;

class UserTest extends \PHPUnit_Framework_TestCase
{

    /**
     * @var  \WodorNet\MotoTripBundle\Entity\User
     */
    private $user;

    public function setUp() {
        $this->user = new User();
        parent::setUp();
    }

    public function testGetTripSignups() {

        $trip = new Trip();
        $tripSignup = new TripSignup();
        $tripSignup->setTrip($trip);
        $tripSignup->setStatus(TripSignup::STATUS_NEW);

        $this->user->addTripSignup($tripSignup);

        $this->assertEquals(new ArrayCollection(array($tripSignup)), $this->user->getTripSignups());

        return $tripSignup;
    }

    /**
     * @depends  testGetTripSignups
     */
    public function testIsCandidateForTrip(TripSignup $tripSignup) {

        $this->user->addTripSignup($tripSignup);

        $this->assertTrue($this->user->isCandidateForTrip($tripSignup->getTrip()));
        $this->assertFalse($this->user->isCandidateForTrip(new Trip()));

    }


}