<?php
namespace WodorNet\MotoTripBundle\Event;
use Symfony\Component\EventDispatcher\Event;

use WodorNet\MotoTripBundle\Entity\TripSignup;

class TripSignupEvent extends  Event {

    /**
     * @var \WodorNet\MotoTripBundle\Entity\TripSignup
     */
    protected $tripSignup;

    /**
     * @param \WodorNet\MotoTripBundle\Entity\TripSignup $tripSignup
     */
    public function __construct(TripSignup $tripSignup)
    {
        $this->tripSignup = $tripSignup;
    }

    /**
     * @return \WodorNet\MotoTripBundle\Entity\TripSignup
     */
    public function getTripSignup()
    {
        return $this->tripSignup;
    }
}