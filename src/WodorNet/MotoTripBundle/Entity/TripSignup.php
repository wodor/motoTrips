<?php

namespace WodorNet\MotoTripBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use WodorNet\MotoTripBundle\Entity\Trip;

/**
 * WodorNet\MotoTripBundle\Entity\TripSignup
 *
 * @ORM\Table()
 * @ORM\Entity(repositoryClass="WodorNet\MotoTripBundle\Entity\TripSignupRepository")
 */
class TripSignup
{
    /**
     * @var integer $id
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var datetime $signupDate
     *
     * @ORM\Column(name="signupDate", type="datetime")
     */
    private $signupDate;

    /**
     * @var string $signupType
     *
     * @ORM\Column(name="signupType", type="string", length=255)
     */
    private $signupType;

    /**
     * @var object $trip
     *
     * @ORM\ManyToOne(targetEntity="Trip", inversedBy="tripSignups")
     * @ORM\JoinColumn(name="trip", referencedColumnName="id")
     */
    private $trip;

    /**
     * @var object $user
     *
     * @ORM\ManyToOne(targetEntity="User", inversedBy="tripSignups")
     * @ORM\JoinColumn(name="user", referencedColumnName="id")
     */
    private $user;

    /**
     * @var array $testArray
     *
     * @ORM\Column(name="testArray", type="array")
     */
    private $testArray;


    /**
     * @var string $description Message to the owner of trip
     *
     * @ORM\Column(type="text")
     *
     */
    private $description;


    /**
     * Get id
     *
     * @return integer
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set signupDate
     *
     * @param datetime $signupDate
     */
    public function setSignupDate($signupDate)
    {
        $this->signupDate = $signupDate;
    }

    /**
     * Get signupDate
     *
     * @return datetime
     */
    public function getSignupDate()
    {
        return $this->signupDate;
    }

    /**
     * Set signupType
     *
     * @param string $signupType
     */
    public function setSignupType($signupType)
    {
        $this->signupType = $signupType;
    }

    /**
     * Get signupType
     *
     * @return string
     */
    public function getSignupType()
    {
        return $this->signupType;
    }

    /**
     * Set trip
     *
     * @param Trip $trip
     */
    public function setTrip(Trip $trip)
    {
        $this->trip = $trip;
    }

    /**
     * Get trip
     *
     * @return Trip
     */
    public function getTrip()
    {
        return $this->trip;
    }

    /**
     * Set user
     *
     * @param object $user
     */
    public function setUser($user)
    {
        $this->user = $user;
    }

    /**
     * Get user
     *
     * @return object
     */
    public function getUser()
    {
        return $this->user;
    }

    /**
     * Set testArray
     *
     * @param array $testArray
     */
    public function setTestArray($testArray)
    {
        $this->testArray = $testArray;
    }

    /**
     * Get testArray
     *
     * @return array
     */
    public function getTestArray()
    {
        return $this->testArray;
    }

    /**
     * @param string $description
     */
    public function setDescription($description)
    {
        $this->description = $description;
    }

    /**
     * @return string
     */
    public function getDescription()
    {
        return $this->description;
    }
}