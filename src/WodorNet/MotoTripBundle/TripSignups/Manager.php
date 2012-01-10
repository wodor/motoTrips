<?php

namespace WodorNet\MotoTripBundle\TripSignups;

use \WodorNet\MotoTripBundle\Entity\TripSignup;
use \WodorNet\MotoTripBundle\Event\TripSignupEvent;
use \WodorNet\MotoTripBundle\MotoTripEvents;

use \Symfony\Component\EventDispatcher\EventDispatcher;

class Manager
{
    /**
     * @var \Doctrine\ORM\EntityManager
     */
    private $em;

    /**
     * @var \Symfony\Component\EventDispatcher\EventDispatcher
     */
    private  $dispatcher;

    public function __construct(\Doctrine\ORM\EntityManager $em, EventDispatcher $dispatcher){
        $this->em = $em;
        $this->dispatcher = $dispatcher;
    }

    public function approve(TripSignup $tripSignup) {

        $tripSignup->setStatus('approved');
        $this->em->persist($tripSignup);
        $this->em->flush();

        $event  = new TripSignupEvent($tripSignup);
        $this->dispatcher->dispatch(MotoTripEvents::onTripSignupApprove, $event);
    }

}
