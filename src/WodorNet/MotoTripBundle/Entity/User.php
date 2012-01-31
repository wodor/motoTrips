<?php
namespace WodorNet\MotoTripBundle\Entity;

use FOS\UserBundle\Entity\User as BaseUser;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * @ORM\Entity
 * @ORM\Table(name="fos_user")
 */
class User extends BaseUser
{
    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @ORM\OneToMany(targetEntity="TripSignup", mappedBy="user")
     */
    protected $tripSignups;


    /**
     * @ORM\OneToMany(targetEntity="Trip", mappedBy="creator")
     */
    protected $trips;


    public function __construct()
    {
        parent::__construct();
        $this->tripSignups = new ArrayCollection();
        $this->trips = new ArrayCollection();
    }


    public function __toString()
    {
        return $this->username;
    }

    public function addTripSignup($tripSignups)
    {
        $this->tripSignups[] = $tripSignups;
    }

    public function getTripSignups()
    {
        return $this->tripSignups;
    }

    public function addTrip($trip)
    {
        $this->trips->add($trip);
        $trip->setCreator($this);
    }

    public function getTrips()
    {
        return $this->trips;
    }

    /**
     * Move it to service layer ?
     * @param Trip $trip
     * @return bool
     */
    public function isCandidateForTrip(Trip $trip)
    {
        foreach ($this->getTripSignups() as $tripsignup) {
            /** @var $tripsignup \WodorNet\MotoTripBundle\Entity\TripSignup */
            if ($tripsignup->getStatus() == TripSignup::STATUS_NEW) {
                if ($tripsignup->getTrip() === $trip) {
                    return true;
                }
            }
        }
        return false;
    }

}