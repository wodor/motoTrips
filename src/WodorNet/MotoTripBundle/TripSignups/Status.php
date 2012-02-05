<?php

namespace WodorNet\MotoTripBundle\TripSignups;

use \WodorNet\MotoTripBundle\Entity\TripSignup;
use \WodorNet\MotoTripBundle\Event\TripSignupEvent;
use \WodorNet\MotoTripBundle\MotoTripEvents;
use \WodorNet\MotoTripBundle\Entity\User;

use \Symfony\Component\EventDispatcher\EventDispatcher;

class Status
{
    private $sc;

    /**
     * @var \WodorNet\MotoTripBundle\Entity\TripSignupRepository
     */
    private $tripSignupRepository;


    /**
     * @param \WodorNet\MotoTripBundle\Entity\TripSignupRepository $em
     * @param \Symfony\Component\EventDispatcher\EventDispatcher $dispatcher
     */
    public function __construct(\WodorNet\MotoTripBundle\Entity\TripSignupRepository $tripSignupRepository, \Symfony\Component\Security\Core\SecurityContext $securityContext = null)
    {
        // make this dependency optional someday
        $this->sc = $securityContext;

        $this->tripSignupRepository = $tripSignupRepository;
    }

    public function getRelationInfo(\WodorNet\MotoTripBundle\Entity\Trip $trip, User $user = null) {

        if(is_null($user)) {
            $user = $this->sc->getToken()->getUser();
        }

        if(!$user instanceof User) {
            return 'trip.userrelation.unrelated';
        }

        if($trip->getCreator() === $user) {
            return 'trip.userrelation.owner';
        }

        $signup = $this->tripSignupRepository->getByTripAndUser($trip, $user);

        if(!$signup instanceof TripSignup) {
            return 'trip.userrelation.unrelated';
        }

        if($signup->getStatus() === TripSignup::STATUS_NEW) {
            return 'trip.userrelation.candidate';
        }

        if($signup->getStatus() === TripSignup::STATUS_APPROVED) {
            return 'trip.userrelation.attendee';
        }

        if($signup->getStatus() === TripSignup::STATUS_REJECTED) {
            return 'trip.userrelation.rejected';
        }

        return 'trip.userrelation.unrelated';
    }



}
